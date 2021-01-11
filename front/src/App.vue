<template>
  <div id="app">
    <router-view />
  </div>
</template>
<script>
//import util from "./api/api.js"
export default {
  name:'App',
  /* created() {
    if(localStorage.getItem('user')){
      let menuList = util.reloadMenu(JSON.parse(localStorage.getItem('menuList')));
      this.$router.addRoutes(menuList);
    }
  } */
}
</script>

<style lang="less">
.size{
  width: 100%;
  height: 100%;
}
html,body{
  .size;
  overflow: hidden;
  margin: 0;
  padding: 0;
}
#app{
  .size;
}
</style>
