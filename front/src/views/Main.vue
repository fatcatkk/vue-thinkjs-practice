<style lang="less">
    @import "./main.less";
</style>
<template>
  <div class="main" :class="{'main-hide-text': shrink}">
    <div class="sidebar-menu-con" :style="{width: shrink? '60px':'200px', overflow:shrink? 'visible':'auto'}">
      <shrinkable-menu :shrink="shrink"  :theme="menuTheme" :menu-list="menuList">
        <div slot="top" class="logo-con">
          <img v-show="!shrink" src="../assets/images/logo.jpg" key="max-logo" />
          <img v-show="shrink" src="../assets/images/logo-min.jpg" key="min-logo" />
          
        </div>
      </shrinkable-menu>
    </div>
    <div class="main-header-con" :style="{paddingLeft: shrink?'60px':'200px'}">
      <div class="main-header">
        <div class="navicon-con">
          <Button :style="{transform: 'rotateZ(' + (this.shrink ? '-90' : '0') + 'deg)'}" type="text" @click="toggleClick">
            <Icon type="ios-arrow-back" size="32"></Icon>
          </Button>
        </div>
        <div class="header-middle-con">
          <div class="main-breadcrumb">
            <breadcrumb-nav :currentPath="currentPath"></breadcrumb-nav>
          </div>
          </div>
        <div class="header-avator-con">
          <div class="user-dropdown-menu-con">
            <Row type="flex" justify="end" align="middle" class="user-dropdown-innercon">
              <Dropdown transfer trigger="click" @on-click="handleClickUserDropdown">
                <a href="javascript:void(0)">
                  <span class="main-user-name">{{ userName }}</span>
                  <Icon type="ios-contact" size=40></Icon>
                </a>
                <DropdownMenu slot="list">
                  <DropdownItem name="ownSpace">个人中心</DropdownItem>
                  <DropdownItem name="loginout" divided>退出登录</DropdownItem>
                </DropdownMenu>
              </Dropdown>
            </Row>
          </div>
          </div>
      </div>
    </div>
    <div class="single-page-con" :style="{left: shrink?'60px':'200px'}">
      <div class="single-page">
        <keep-alive :include="cachePage">
          <router-view></router-view>
        </keep-alive>
      </div>
    </div>
  </div>
</template>
<script>
import shrinkableMenu from './main-components/shrinkable-menu/shrinkable-menu.vue';
import breadcrumbNav from './main-components/breadcrumb-nav.vue'
import util from '../api/api.js';
import Cookies from "js-cookie";
export default {
  components:{
    shrinkableMenu,
    breadcrumbNav
  },
  data(){
    return {
      shrink:false,
      userName: ''
    }
  },
  computed:{
    currentPath () {
      return this.$store.state.app.currentPath; // 从store中调用当前面包屑数组
    },
    menuList(){
      return this.$store.state.app.menuList;    // 从store中调用当前菜单数组
    },
    menuTheme () {
      return this.$store.state.app.menuTheme;  // 从store中调用当前菜单样式 ，其实目前没啥用
    },
    cachePage () {
      return this.$store.state.app.cachePage;  // 说实话这个是抄的代码，我还不懂他的用处
    }
  },
  methods:{
    init () {
      // this.$store.commit('mountMyMenulist',this); // 这句话其实也没啥用，之后再补上他的用处
      // 更新界面菜单
      let list = JSON.parse(localStorage.getItem("menuList"));
      this.$store.commit('updateMyMenulist',util.reloadMenu(list));

      this.userName = localStorage.getItem('user'); // 获取用户名称
      util.setCurrentPath(this, this.$route.name); //将此刻的路由返回给setCurrentPath函数，用于面包屑
      this.loading = false;
      
    },
    toggleClick () {
      this.shrink = !this.shrink; //缩放工具栏
    },
    handleClickUserDropdown (name) {
      //let self = this;
      if (name === 'ownSpace') {
        util.openNewPage(this, 'ownspace_index');
        this.$router.push({
          name: 'ownspace_index'
        });
      } else if (name === 'loginout') {
        // 退出登录
          this.$store.commit('logout', this); //激活store中logout函数
          this.$router.push({
              name: 'login'
          });
      }
    }
  },
  watch: {
    '$route' (to) { //监测路由动向，一旦有变化，要改变面包屑
        //this.$store.commit('setCurrentPageName', to.name);
        let pathArr = util.setCurrentPath(this, to.name);//把路由to的name传给util的函数，改变面包屑
        if (pathArr.length > 2) {
            this.$store.commit('addOpenSubmenu', pathArr[1].name);
        }
        localStorage.currentPageName = to.name;
    }
  },
  mounted(){
    this.init();
  }
}

</script>