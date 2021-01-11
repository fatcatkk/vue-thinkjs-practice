import Vue from 'vue';
import iView from 'iview';
import util from '../api/api.js';
import VueRouter from 'vue-router';
import Cookies from 'js-cookie';
import routes from './router.js';


Vue.use(VueRouter);

// const RouterConfig = {
//   routes: routers
// };
// export const router = new VueRouter(RouterConfig);

const router = new VueRouter({
  routes
});

export default router;

//在进入系统之前通过token进行判断
router.beforeEach((to,from,next) => {
  let token = localStorage.getItem('token');
  let result = util.verifytoken(token);
  // console.log(result);
  iView.LoadingBar.start();
  util.title(to.meta.title);
  
  if(Cookies.get('locking') === '1' && to.name !== 'locking'){
    next({
      replace:true,
      name:'locking'
    });
  }else if (Cookies.get('locking') === '0' && to.name === 'locking'){
    next(false);
  }else {
    if(to.name !== "login" && result == false){
      next({
        name:"login"
      });
    }else if(to.name === "login" && result ) {
      util.title();
      next({
        name:'home_index'
      });
    }else {
      next();
    }
  };
});


