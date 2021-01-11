import {otherRouter,appRouter} from "@/router/router.js";
//import util from "@/api/api.js";
//import Cookies from "js-cookie";
//import Vue from "vue";

const app = {
  state:{
    cachePage: [],
    menuList: [],
    routers: [
      otherRouter,
      ...appRouter
    ],
    currentPath: [ //面包屑数组
      {
        title:'首页',
        path:'',
        name:'home_index'
      }
    ],
    tagsList: [...otherRouter.children]
  },
  mutations:{
    setTagsList (state, list) {
      state.tagsList.push(...list);
    },
    updateMyMenulist(state,list){
      if(list){
        //state.routers.push(...list);
        state.menuList = list;
      }else{
        state.menuList  = appRouter;
      };
    },
    setCurrentPath (state, pathArr) {
      state.currentPath = pathArr;
    },
    mountMyMenulist (state, vm) {  
      let tagsList=[];
      state.menuList.map((item) => {
          if (item.children.length <= 1&&!item.children[0].icon) {
              tagsList.push(item.children[0]);
          } else {
              tagsList.push(...item.children);
          }
      });
      vm.$store.commit('setTagsList', tagsList);
  },
  }
};
export default app;