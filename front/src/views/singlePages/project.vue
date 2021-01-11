<template>
  <div>
    <Row :gutter="16">	 
      <Col  :md="18" :xs="24">
        <Row>
          <div class="panel panel-default">
            <div class="panel-body">
              <Row >
                <Col :md="6">
                  <div class="jumbotron text-center">
                    <i class="glyphicon glyphicon-ok-sign"></i>
                    <h5>信标数目</h5>
                    <h2>{{online}}</h2>
                    <button type="button" class="btn btn-default btn-sm" @click='node_page'>管理信标</button>
                  </div>
                </Col>
                <Col :md="6">
                  <div class="jumbotron text-center">
                    <i class="glyphicon glyphicon-remove-sign"></i>
                    <h5>离线信标</h5>
                    <h2>{{dropline}}</h2>
                    <button type="button" class="btn btn-default btn-sm" @click='node_page'>管理信标</button>
                  </div>
                </Col>
                <Col :md="6">
                  <div class="jumbotron text-center">
                    <i class="glyphicon glyphicon-hdd"></i>
                    <h5>网关数目</h5>
                    <h2>2</h2>
                    <button type="button" class="btn btn-default btn-sm" @click='node_page'>管理网关</button>
                  </div>
                </Col>
                <Col :md="6">
                
                  <div class="jumbotron text-center">
                    <i class="glyphicon glyphicon-user"></i>
                    <h5>在线用户</h5>
                    <h2>3</h2>
                    <button type="button" class="btn btn-default btn-sm">管理用户</button>
                  </div>
                
                </Col>
              </Row>
            </div>
          </div>
        </Row>
        <Row style="margin-top:30px"> 
          <div class="box111">		
            <l-map
              ref="map"
              :zoom="zoom" :center="center"
              :min-zoom="minZoom"
              :max-zoom="maxZoom"
              :maxBounds="bounds"
              :crs="crs"
              style="height: 500px; width: 100%;"
            >
            <l-image-overlay :url="url" :bounds="bounds" />
            <l-marker v-for="star in stars" :lat-lng="star" :key="star.name">
              <l-popup :content="star.name" />
              </l-marker>
            </l-map>
          </div>
        </Row>
      </Col>
      <Col  :md="6" :xs="24" class="pl-2">
        <Row>
          <div class="panel panel-default">
            <div class="panel-heading">新闻通知</div>
            <div class="panel-body">
              <ul class="list-group">
                <li class="list-group-item">超低成本4G工业路由器，开售！</li>
                <li class="list-group-item"> PLC云网关，助推工控行业云端制造</li>
                <li class="list-group-item">蓝牙5.1使得蓝牙技术适得其所</li>
                <li class="list-group-item">地铁司机为何每次都能把车停的那么准</li>
                <li class="list-group-item">IBM建立基于低功耗蓝牙信标的室内定位系统</li>
              </ul>
            </div>
          </div>
        </Row>
        <Row>
          <div class="panel panel-default">
            <div class="panel-heading">登录信息</div>
            <div class="panel-body">
              <div class="media">
                <div class="media-left">
                  <i class="glyphicon glyphicon-time"></i>
                </div>
                <div class="media-body">
                <h5 class="media-heading">2019-04-17 23:39:15</h5>
                <h5 class="media-heading">上次登录时间</h5>
                </div>
              </div>
              <hr style="filter: alpha(opacity=100,finishopacity=0,style=3)" width="100%" color="#6f5499" size="3" />
              <div class="media">
                <div class="media-left">
                <a href="#">
                  <i class="glyphicon glyphicon-upload"></i>
                </a>
                </div>
                <div class="media-body">
                <h5 class="media-heading">47</h5>
                <h5 class="media-heading">登录次数</h5>
                </div>
              </div>
            </div>	 
          </div>
        </Row>
        <Row>
          <div class="panel panel-default">
            <div class="panel-heading">关注公众平台</div>
            <div class="panel-body">
              <Col  :md="8" ><img alt="Brand" src="/img/2.png" height="100" width="100" /></Col>
              <Col  :md="16" ><h5>接收报警推送，使用微信小程序控制</h5></Col>
            </div>
          </div>
        </Row>
      </Col>
    </Row>
    <!-- <remote-js src="./static/leaflet.marker.highlight/leaflet.marker.highlight-src.js"></remote-js>
    <remote-js src="./static/Leaflet.SmoothMarkerBouncing/leaflet.smoothmarkerbouncing.js"></remote-js>	    -->
  </div>
</template>
<script>
import { icon, marker, circleMarker } from "leaflet";
import { LMap, LTileLayer, LMarker,LImageOverlay,LPolyline, LPopup } from 'vue2-leaflet';
import util from "../../api/api.js"
export default {
  name: 'dashboard',
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
    LImageOverlay,
    LPolyline,
    // 'remote-js': {
    //   render(createElement){
    //     return createElement('script',{attrs:{type:'text/javascript', src: this.src}});
    //   },
    //   props:{
    //     src:{type: String, required: true}
    //   }
    // }
  },
  data(){
    return {
      dropline:'0',
      online:'0',
      url:"/img/3.png",
      zoom:1,
      center:[600, 1000],
      bounds: [ [0,472.75],[118.25,0]],
      minZoom:0,
      maxZoom:4,
      crs: L.CRS.Simple,
      southWest:[],
      northEast:[],
      stars:[],
      marker_array1:[]
    };
  },
  methods:{
    blue_node_online(){
      let self = this;
      let param = {}
      util.post(this,"blue/nodeOnline",param,function(datas){
        var data = datas.data;
        //console.log(data);
        self.dropline = data.dropline;
        self.online = data.online;
      });
    },
    node_page(){
      
    },
    addmarker(state,x,y,z,mac,i){
      var beaconicon = icon({
        iconUrl:"/img/beacon00.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      var d_beaconicon = icon({
        iconUrl:"/img/beacon0.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      var BLEicon = icon({
        iconUrl:"img/beacon11.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      var d_BLEicon = icon({
        iconUrl:"img/beacon1.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      var onlineicon = icon({
        iconUrl:"img/207.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      var droplineicon = icon({
        iconUrl:"img/208.png",
        iconSize:[36,36],
        iconAnchor:[18,36],
        popupAnchor:[0,-28],
        shadowAnchor:[15,64],
        shadowSize:[50,64]
      });
      
      var xx = (x*this.southWest.lat/100);
      var yy = (y*this.northEast.lng/100);

      if(state == "1"){
        this.marker_array1[i] = L.marker([xx,yy],{icon:onlineicon});
      }
      else{
        this.marker_array1[i] = L.marker([xx,yy],{icon:droplineicon});
      }
      this.$refs.map.mapObject.addLayer(this.marker_array1[i]);
      this.marker_array1[i].bindTooltip(mac).openPopup();
    },
    node_mapshow(){
      let self = this;
      let param = {};
      util.post(this,"blue/nodeGetList",param,function(datas){
        var data = datas.data;
        console.log(data);
        data.forEach((item) => {
          self.addmarker(item.state,item.x,item.y,item.z,item.mac,item.i);
        });
      });
    },
    initmap(){
      let self = this;
      var w = 1500,
          h = 1500,
          url = "/img/3.png";
      this.southWest = this.$refs.map.mapObject.unproject([0, h], this.$refs.map.mapObject.getMaxZoom()-1);
			this.northEast = this.$refs.map.mapObject.unproject([w, 0], this.$refs.map.mapObject.getMaxZoom()-1);
			this.bounds = new L.LatLngBounds(this.southWest, this.northEast);
			this.maxBounds = this.bounds;
			this.zoom = 2;
			this.node_mapshow();	  
    }
  },
  mounted(){
    this.blue_node_online();
    // const link = document.createElement('link')
		// 		link.type = 'text/css'
		// 		link.rel = 'stylesheet'
		// 		link.href = 'http://cdn.bootcss.com/leaflet/1.0.0-rc.3/leaflet.css'
		// 		document.head.appendChild(link)
		this.initmap();
		this.$refs.map.mapObject.setView([70, 120], 1);
  }
}
</script>
<style type="text/css">
.glyphicon {
	color:#999999;
	font-size:50px;
}
.box111 {
	border: 1px solid #696;padding: 2px 2px;
	text-align: center; 
	width: 100%;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	filter: progid:DXImageTransform.Microsoft.Shadow(color='#969696',Direction=135, Strength=5);/*for ie6,7,8*/
	background-color: #999999;
	-moz-box-shadow:2px 2px 5px #969696;/*firefox*/
	-webkit-box-shadow:2px 2px 5px #969696;/*webkit*/
	box-shadow:2px 2px 5px #969696;/*opera或ie9*/
} 

.my-div-icon{
            font-size:15px;
            /*background:red;*/
            /*width:5px;*/
            color:red;
        }
.shadow_wrap{
margin-top: 5px; margin-bottom: 10px;
}

</style>