import Main from "../views/Main.vue";
import util from "@/api/api.js";
import login from "../views/login/login.vue"
import home from "../views/sys/home.vue";
import Cookies from "js-cookie";

export const loginRouter = {
  path:'/login',
  name:'login',
  meta: {
    title: '后台管理系统 - 登录'
  },
  component:login
};

export const otherRouter = {
  path:'/',
  name:'otherRouter',
  redirect:'/home',
  component:Main,
  children:[
    {path:'home',title:'首页',name:'home_index',component: home}
  ]
};

export let appRouter = [

];

//如果
if(localStorage.getItem("user")){
  if (localStorage.getItem("menuList")){
    let list = JSON.parse(localStorage.getItem("menuList"));
    console.log(1);
    appRouter = util.reloadMenu(list);
    console.log(appRouter)
  }
}

const routers = [
  loginRouter,
  otherRouter,
  ...appRouter
];
export default routers;