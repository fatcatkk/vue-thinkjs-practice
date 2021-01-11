<template>
  <div>
    <Row>
      <Col span="24">
        <Card>
          <p slot="title">
            <Icon type="ios-list"></Icon>
            用户列表
          </p>
          <Row>
            <Input v-model="searchForm.name" placeholder="请输入名称" style="width: 200px" />
            <span @click="handleSearch" style="margin: 0 10px;"><Button type="primary" icon="search">搜索</Button></span>
          </Row>
          <Row type="flex" justify="center" align="middle" class="advanced-router">
            <Table border stripe :columns="columns" :data="data" :loading="loading" style="width: 100%;margin-top:10px"></Table>
            <Page :total="count" :current="searchForm.current" show-total style="margin-top:10px;" @on-change="pageChange"></Page>
          </Row>
        </Card>
      </Col>
    </Row>
    <Modal title="操作框" :mask-closable="false" :closable="false" v-model="modalAdd">
      <Form ref="formRef" :model="formValidate" :rules="ruleValidate" :label-width="80">
        <FormItem label="用户名" prop="name">
          <Input v-model="formValidate.name" :disabled="loginNameDisabled"></Input>
        </FormItem>
        <FormItem label="菜单" prop="name">
          <Tree ref="Tree" show-checkbox :data="menuList"></Tree>
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="text" @click="addCanFun" v-show="modalCanBut">取消</Button>
        <Button type="primary" @click="addOkFun" :loading="modalLoading">确定</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
import util from "../../api/api.js"
export default {
  data(){
    return {
      modalAdd:false,
      loading:false,
      modalLoading:false,
      modalCanBut:true,
      loginNameDisabled:true,
      searchForm:{
        type:'',
        current:1
      },
      menuList:[],
      count:0,
      columns:[
        {
          title:'用户名',
          key:'login_name'
        },
        {
          title:'姓名',
          key:'name'
        },
        {
          title:'操作',
          key:'action',
          width:210,
          align:'center',
          render:(h, params) => {
            return h('div',[
              h('Button',{
                props:{
                  type:'success',
                  size:'small'
                },
                style:{
                  marginRight:'5px'
                },
                on:{
                  click:() => {
                    this.edit(params);
                  }
                }
              },'编辑可用菜单')
            ]);
          }
        }
      ],
      data:[],
      formValidate:{
        sort:1
      },
      ruleValidate:{
        name:[
          {required:true,message:'必填项',trigger:'blur'}
        ]
      }
    }
  },
  methods:{
    init(){
      let self = this;
      this.loading = true;
      util.post(this,"sys_user/pageData",this.searchForm,function(datas){
        self.data = datas.data.data;
        self.count = datas.data.count;
        self.loading = false;
      })
    },
    handleSearch(){
      this.searchForm.current = 1;
      this.init();
    },
    pageChange(){
      this.searchForm.current = current;
      this.init();
    },
    edit(params){
      let treedata = [];
      let self = this;
      self.loading = true;
      util.post(this,"sys_user_menu/getMenuData",{login_name : params.row.login_name},function(datas){
        for(var i = 0; i < datas.data.length; i ++){
          treedata.push(datas.data[i]);
        }
        self.formValidate.name = params.row.login_name;
        self.menuList = treedata;
        self.modalAdd = true;
        self.loading = false;
      })
    },
    addOkFun(){
      let self = this;
      let menuData = this.$refs['Tree'].getCheckedNodes()
      self.loading = true;
      util.post(this,"sys_user_menu/updateUserMenu",{login_name:this.formValidate.name,menuData:menuData},function(datas){
        self.$Message.info("修改成功");
        self.addCanFun();
      })
    },
    addCanFun(){
      this.modalAdd = false;
    }
  },
  mounted(){
    this.init();
  }
}
</script>