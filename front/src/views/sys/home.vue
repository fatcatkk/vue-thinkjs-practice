<template>
  <div :model="testdata">
    {{testdata}}
  </div>
</template>
<script>
import util from "../../api/api.js"
import dgram from "dgram";
export default {
  data(){
    return {
      testdata:'这是首页'
    }
  },
  sockets:{
    open(data){
      console.log(data,1);
    },
    close(){

    }
  },
  methods:{
    test(){
      this.$socket.emit('adduser','kkk');
    }
  },
  mounted(){
    this.test();
  }
}
</script>