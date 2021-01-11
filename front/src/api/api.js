import axios from "axios";
import Main from "../views/Main.vue";
import Cookies from "js-cookie";
let jwt = require("jsonwebtoken");


//import dgram from "dgram";
let util = {

};



//窗口名字
util.title = function (title) {
  title = title || "后台管理系统";
  window.document.title = title;
};

//定义后端请求的端口
const ajaxUrl = 'http://localhost:1001/';

util.ajax = axios.create({
  baseURL: ajaxUrl,
  timeout: 30000,
});

//post请求
util.post = function(vm, url, param, cb) {
  let axiosIns = axios.create({ //创建post请求
    baseURL: ajaxUrl,
    timeout: 30000,
    headers: {'Content-Type': 'application/json',
      'x-token': localStorage.getItem('token') //在请求中包含token
    }
  });
  axiosIns.post(url, param).then(res => {
    //console.log(res.data);
    vm.loading = false;
    if(res&&res.status == 200 && res.data&&res.data.errno == 0){
      //console.log(res)
      cb(res.data); //请求正常，执行callback函数
    }else{ //否则的话输出错误信息框
      console.log(res);
      vm.$Message.destroy();
      vm.$Message.error(res.data.errmsg);
    }
  })
};

// 判断token是否有效
util.verifytoken = function(token){
  if(token){
    try{
      let result = jwt.verify(token,'secret');
      return result;
    }catch(err){
      return false;
    }
  }
  return false;
}

// 把一个对象解析后又json化，说实话我也不太清楚有啥用处，之后再做个测试，如果没用就删了
util.copy = function(datas){
  let obj = {};
  console.log(datas);
  obj = JSON.parse(JSON.stringify(datas));
  console.log(obj);
  return obj;
};

//
util.getRouterObjByName = function (routers, name) {
  if (!name || !routers || !routers.length) {
    return null;
  }
  // debugger;
  let routerObj = null;
  for (let item of routers) {
    if (item.name === name) {
      return item;
    }
    routerObj = util.getRouterObjByName(item.children, name);
    if (routerObj) {
      return routerObj;
    }
  }
  return null;
};

util.handleTitle = function (vm, item) { //页面头标题
  if (typeof item.title === 'object') {
    return vm.$t(item.title.i18n);
  } else {
    return item.title;
  }
};

util.setCurrentPath = function (vm, name) { //参数为vue实体、将要去向的路由名称
  let title = '';
  let isOtherRouter = false;
  vm.$store.state.app.routers.forEach(item => {
    if (item.children.length === 1) {
      if (item.children[0].name === name) { // 在路由里找到和此时界面路由名字相同的，将值赋给title
        title = util.handleTitle(vm, item);
        console.log(title);
        if (item.name === 'otherRouter') {
          isOtherRouter = true;
        }
      }
    } else {
      item.children.forEach(child => {
        if (child.name === name) {
          title = util.handleTitle(vm, child);
            if (item.name === 'otherRouter') {
              isOtherRouter = true;
            }
          }
      });
    }
  });
  let currentPathArr = [];
  if (name === 'home_index') { // 如果name是home，面包屑title就是首页
    currentPathArr = [
      {
        title: '首页',
        path: '',
        name: 'home_index'
      }
    ];
  } else if ((name.indexOf('_index') >= 0 || isOtherRouter) && name !== 'home_index') { // 否则的话，面包屑数组为首页+此时的路由名称
      currentPathArr = [
        {
          title: '首页',
          path: '/home',
          name: 'home_index'
        },
        {
          title: title,
          path: '',
          name: name
        }
      ];
  } else { // 暂时用不到，这里定义的是由子路由时的面包屑
      let currentPathObj = vm.$store.state.app.routers.filter(item => {
        if (item.children.length <= 1) {
          return item.children[0].name === name;
        } else {
            let i = 0;
            let childArr = item.children;
            let len = childArr.length;
            while (i < len) {
              if (childArr[i].name === name) {
                  return true;
                }
              i++;
            }
            return false;
          }
      })[0];

      if (currentPathObj.children.length <= 1 && currentPathObj.name === 'home') {
        currentPathArr = [
          {
            title: '首页',
            path: '',
            name: 'home_index'
          }
        ];
      } else if (currentPathObj.children.length <= 1 && !currentPathObj.children[0].icon && currentPathObj.name !== 'home') {
        
          currentPathArr = [
            {
              title: '首页',
              path: '/home',
              name: 'home_index'
            },
            {
              title: currentPathObj.title,
              path: '',
              name: name
            }
          ];
      } else {
          let childObj = currentPathObj.children.filter((child) => {
            return child.name === name;
          })[0];
          currentPathArr = [
            {
              title: '首页',
              path: '/home',
              name: 'home_index'
            },
            {
              title: currentPathObj.title,
              path: '',
              name: currentPathObj.name
            },
            {
              title: childObj.title,
              path: currentPathObj.path + '/' + childObj.path,
              name: name
            }
          ];
        }
        
   }
  vm.$store.commit('setCurrentPath', currentPathArr); //将处理好的面包屑数组返回给store中的面包屑数组，用于界面调用

  return currentPathArr;
};

util.reloadMenu = function (list) {//将菜单列表转换为路由的格式，主组件都为Main，再定义子组件
  let _menuList = [];
  list.forEach((item) => {
    if(!item.children || item.children.length == 0){
      let _item = {};
      _item.path = '/' + item.name;
      _item.icon = item.icon;
      _item.name = item.name;
      _item.title = item.title;
      _item.component = Main;
      _item.children = [{
        path:'index',
        name:item.name + "_index",
        icon:'',
        title:item.title,
        component:resolve => {require(['@/views/' + item.href + '.vue'],resolve);}
      }];
      _menuList.push(_item);
    }else{
      let _item = {};
      _item.path = '/' + item.name;
      _item.icon = item.icon;
      _item.name = item.name;
      _item.title = item.title;
      _item.component = Main;
      _item.children = [];
      item.children.forEach((item2) => {
        _item.children.push({
          path:item2.name,
          icon:item2.icon,
          name:item2.name,
          title:item2.title,
          component:resolve => {require(['@/views/' + item2.href + '.vue'], resolve);}
        });
      });
      _menuList.push(_item);
    }
  });
  return _menuList;
}

export default util;