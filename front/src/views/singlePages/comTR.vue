<template>
<div>
  <Row>
      <div class="center">
        <span style="margin-right:10px;"><Button type="primary" @click="COMScan">扫描串口</Button></span>
        <br>
        <Select v-model="portSelect" placeholder="请选择串口" style="width: 200px">
          <Option v-for="item in portExist" :value="item.path" :key="item.path">{{item.path}}</Option>
        </Select>
        <span style="margin-left:10px;" v-if="!(serialportIsOpen)"><Button type="primary" @click="portOn">打开串口</Button></span>
        <span style="margin-left:10px;" v-else="!(serialportIsOpen)"><Button type="primary" @click="portClose">关闭串口</Button></span>
      </div>
  </Row>
  <Row>
    <Input class="sendarea" type="textarea" v-model="sendMessage" placeholder="输入要发送的信息" :autosize="{minRows: 4,maxRows: 8}" ></Input>
    <br>
    <span @click="portSend" style="float:100px"><Button type="primary" class="rightButton">发送</Button></span>
  </Row>
</div>
</template>
<script>
import util from '../../api/api.js'
  export default {
    data(){
      return{
        portSelect:'',
        portExist:[],
        sendMessage:'',
        serialportIsOpen:false
      }
    },
    methods:{
      COMScan(){
        let self = this;
        util.post(this,'serialport/serialPortScan',1,function(datas){
          console.log(datas.data);
          self.portExist = datas.data;
        })
      },
      portOn(){
        let self = this;
        let portN = {'portSelect':this.portSelect};
        util.post(this,'serialport/serialPortOn',portN,function(datas){
          self.serialportIsOpen = datas.data;
          if(self.serialportIsOpen){
            self.$Message.info('串口'+self.portSelect+'打开成功');
          }else{
            self.$Message.info('串口'+self.portSelect+'打开失败');
          }
          console.log(datas);
        })
      },
      portClose(){
        let self = this;
        let portN = {'portSelect':this.portSelect};
        util.post(this,'serialport/serialPortClose',portN,function(datas){
          self.serialportIsOpen = datas.data;
          if(!(self.serialportIsOpen)){
            self.$Message.info('串口'+self.portSelect+'关闭成功');
          }else{
            self.$Message.info('串口'+self.portSelect+'关闭失败');
          }
          console.log(datas);
        })
      },
      portSend(){
        let message = {'portSelect':this.portSelect , 'sendMessage':'1'}
        util.post(this,'serialport/serialPortWrite',message,function(datas){
          console.log(datas.data);
        })
      }
    },
    mounted(){
      this.COMScan();
    },
    destroyed:function() {
      this.portClose();
    }
  }
</script>
<style scoped>
.center{
  line-height: 30px;
  font-size: 15px;
  margin-bottom: 30px;
}
.sendarea{
  width:800px;
  margin-bottom: 10px;
}
.rightButton{
  align-items: right;
}
</style>