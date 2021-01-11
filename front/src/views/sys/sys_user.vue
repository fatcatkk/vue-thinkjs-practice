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
            <Input v-model="searchForm.name" placeholder="请输入姓名" style="width: 200px" />
            <span @click="handleSearch" style="margin: 0 10px;"><Button type="primary" icon="search">搜索</Button></span>
          </Row>
          <Row style="margin-top: 10px;">
            <Button type="info" @click="add">添加</Button>
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
        <FormItem label="用户名" prop="login_name">
          <Input v-model="formValidate.login_name" :disabled="loginNameDisabled"></Input>
        </FormItem>
        <FormItem label="姓名" prop="name">
          <Input v-model="formValidate.name"></Input>
        </FormItem>
        <FormItem label="角色" prop="role_id">
          <Select v-model="formValidate.role_id">
            <Option value="1" >管理员</Option>
            <Option value="2" >测试员</Option>
          </Select>
        </FormItem>
        <FormItem label="邮箱" prop="email">
          <Input v-model="formValidate.email"></Input>
        </FormItem>
        <FormItem label="手机号" prop="phone">
          <Input v-model="formValidate.phone"></Input>
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
      modalEdit:false,
      loading:false,
      modalLoading:false,
      modalCanBut:true,
      data:[],
      roleList:[],
      formValidate:{
      },
      searchForm:{
        type:'',
        current:1
      },
      count:0,
      loginNameDisabled:false,
      columns:[
        {
          title:'用户名',
          key:'login_name',
          fixed:'left',
          className:'table-min-width'
        },
        {
          title:'角色',
          key:'rolename',
          className:'table-min-width'
        },
        {
          title:'姓名',
          key:'name',
          className:'table-min-width'
        },
        {
          title:'邮箱',
          key:'email',
          className:'table-min-width'
        },
        {
          title:'手机号',
          key:'phone',
          className:'table-min-width'
        },
        {
          title:'登录IP',
          key:'login_ip',
          className:'table-min-width'
        },
        {
          title:'登录时间',
          key:'login_date',
          className:'table-min-width'
        },
        {
          title:'状态',
          key:'status',
          align:'center',
          className:'table-min-width',
          render:(h,params) =>{
            return h('div',[
              h('i-switch',{
                props:{
                  value:params.row.status==1,
                  size:'large'
                },
                on:{
                  'on-change':(value) => {
                    this.changeStatus(value,params)
                  }
                }
              },[
                h('span',{
                  slot:'open'
                },'正常'),
                h('span',{
                  slot:'close'
                },'冻结')
              ])
            ]);
          }
        },
        {
          title:'操作',
          key:'action',
          width:200,
          align:'center',
          fixed:'right',
          render: (h,params) => {
            return h('div', [
              h('Button', {
                props:{
                  type:'info',
                  size:'small'
                },
                sytle:{
                  marginRight:'5px'
                },
                on:{
                  click:() => {
                    this.edit(params);
                  }
                }
              },'编辑'),
              h('Button', {
                props:{
                  type:'success',
                  size:'small'
                },
                sytle:{
                  marginRight:'5px'
                },
                on:{
                  click:() => {
                    this.resetPwd(params);
                  }
                }
              },'重置密码'),
              h('Button', {
                props:{
                  size:'small'
                },
                on:{
                  click:() => {
                    this.remove(params);
                  }
                }
              },'删除')
            ]);
          }
        }
      ],
      ruleValidate:{
        login_name:[
          {required:true,message:'必填项',trigger:'blur'}
        ],
        name:[
          {required:true,message:'必填项',trigger:'blur'}
        ],
        role_id:[
          {required:true,message:'必填项',trigger:'blur'}
        ]
      }
    };
  },
  methods:{
    init(){
      let self = this;
      self.loading = true;
      util.post(this,'sys_user/pageData',this.searchForm,function(datas){
        console.log(datas);
        self.data = datas.data.data;
        self.count = datas.data.count;
        self.loading = false;
      });
    },
    handleSearch(){
      this.searchForm.current = 1;
      this.init();
    },
    pageChange(current){
      this.searchForm.current = 1;
      this.init();
    },
    add(){
      this.loginNameDisabled = false;
      this.formValidate={};
      this.modalAdd = true;
    },
    edit(params){
      this.formValidate = util.copy(params.row);
      this.loginNameDisabled = true;
      this.modalAdd = true;
    },
    remove(params){
      let self = this;
      console.log(params);
      this.$Modal.confirm({
        title:'删除',
        content:'删除后数据无法恢复，是否继续操作？',
        onOk: () => {
          this.loading = true;
          
          util.post(this,'sys_user/delData',{login_name:params.row.login_name}, function(datas){
            self.data.splice(params.index,1);
            self.loading = false;
            self.$Message.success('删除成功！');
          })
        }
      })
    },
    resetPwd(params){
      let self = this;
      this.$Modal.confirm({
        title:'重置密码',
        content:'密码将被重置为:111111,是否继续？',
        onOk:() => {
          this.loading = true;
          util.post(this,'sys_user/resetPwd',{login_name:params.row.login_name},function(datas){
            self.loading = false;
            self.$Message.success('重置密码成功！');
          })
        }
      })
    },
    changeStatus(value,params){
      let self = this;
      this.loading = true;
      let status = 0;
      if(value){
        status = 1;
      }
      util.post(this,'sys_user/changeStatus',{login_name:params.row.login_name,status:status},function(datas){
        self.data[params.index].status = value;
        self.loading = false;
        self.$Message.info('修改成功！');
      })
    },
    addOkFun(){
      let self = this;
      this.loading = true;
      this.$refs['formRef'].validate((valid) => {
        if(valid){
          if(this.loginNameDisabled){
            util.post(this,"sys_user/updateData",this.formValidate,function(datas){
              self.$Message.info("编辑成功！");
              self.addCanFun();
              self.init();
            })
          }else{
            util.post(this,"sys_user/adduser",this.formValidate,function(datas){
              self.$Message.info("新增成功！");
              self.addCanFun();
              self.init();
            })
          }
        }else{

        }
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