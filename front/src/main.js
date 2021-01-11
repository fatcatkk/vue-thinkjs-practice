import Vue from "vue";
import App from "./App.vue";
import router from "./router/index.js";
import store from "./store";
import iView from "iview";//引入iview
import "iview/dist/styles/iview.css";//引入iview样式
import echarts from "echarts";//引入echarts组件
import L from 'leaflet';//引入leaflet地图库
import 'leaflet/dist/leaflet.css';
import VueSocketIO from 'vue-socket.io';//做websocket


//引入cesium相关文件
var cesium = require('cesium/Cesium');
var widgets= require('cesium/Widgets/widgets.css');
 
Vue.prototype.cesium = cesium
Vue.prototype.widgets = widgets

Vue.use(iView);
Vue.prototype.$echarts = echarts;

Vue.config.productionTip = false;

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({ // 配置leaflet
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

Vue.use(new VueSocketIO({ //配置websocket
  debug: true,
  connection: 'http://127.0.0.1:1001',
  vuex:{
    
  }
}))

new Vue({
  router,
  store,
  render: h => h(App),
  /* mounted () {
    if(localStorage.getItem("user")){
      this.$store.commit('updateMyMenulist');
      
    }
  } */
}).$mount("#app");
