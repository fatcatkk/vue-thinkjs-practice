<template>
  <div id="cesiumContainer" style="height:750px">
    <Menu mode="horizontal" :theme="theme1" active-name="1" @on-select="onMenuSelect">
      <MenuItem name="1">
        <Icon type="ios-paper"/>
        回到原点
      </MenuItem>
      <MenuItem name="2">
        <Icon type="ios-people"/>
        管理
      </MenuItem>
      <Submenu name="3">
        <template slot="title">
          <Icon type="ios-stats" />
          分层管理
        </template>
        <MenuGroup title="层次开关">
          <MenuItem name="3-1"><i-switch v-model="switchValue1" @on-change="show_hiden(switchValue1,model2)"> </i-switch>建筑物</MenuItem>
          <MenuItem name="3-2"><i-switch v-model="switchValue2" @on-change="show_hiden(switchValue2,modelEntity)"> </i-switch>内支撑</MenuItem>
          <MenuItem name="3-3"><i-switch v-model="switchValue3" @on-change="show_hiden(switchValue3,watershow)"> </i-switch>桩</MenuItem>
          <MenuItem name="3-4"><i-switch v-model="switchValue4" > </i-switch>地表</MenuItem>
          <MenuItem name="3-5"><i-switch v-model="switchValue5" > </i-switch>侧向土压</MenuItem>
        </MenuGroup>
      </Submenu>
      <MenuItem name="4">
        <Icon type="ios-construct" />
        综合设置
      </MenuItem>
    </Menu>
    <div id="toolbar" class="dropdown-menu">
				<input type="range" min="0" max="50" step="0.1" data-bind="value: height, valueUpdate: 'input'">
				<input type="text" size="2" data-bind="value: height">
				<p>您输入的信息是：{{ value1 }}</p>
		</div>
  </div>
</template>
<script>
// import Cesium from "cesium/Cesium";
// import widget from "cesium/Widgets/widgets.css";
export default {
  name:'cesiueViewer',
  data(){
    return{
      value1:25,
      theme1:'dark',
      message:{},
      height1:[],
      timer:null,
      switchValue1:true,
      switchValue2:true,
      switchValue3:true,
      switchValue4:true,
      switchValue5:true
    }
  },
  created:function(){
    this.timer = setInterval(this.getwater,20000);
  },
  mounted(){
    //Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwMjc2MjUwNy05NzM4LTRmYWYtOTZjZS1hMmM2OGU0ZmI0NDgiLCJpZCI6NzU1NSwic2NvcGVzIjpbImFzciIsImdjIl0sImlhdCI6MTU0OTc4NDk1M30.sYGiciHRo4OweptaLDxaJnHarptZ8BRP03zUXy76LpM';
    var url = "http://mt1.google.cn/vt/lyrs=s&hl=zh-CN&x={x}&y={y}&z={z}&s=Gali";//谷歌在线底图url
    var self = this;
    var viewer = new Cesium.Viewer('cesiumContainer',{
      animation:false,
      shouldAnimate:false,
      homeButton:false,
      fullscreenButton:false,
      baseLayerPicker:false,
      geocoder:false,
      timeline:false,
      sceneModePicker:true,
      navigationHelpButton:false,
      infoBox:false,
      scene3DOnly:false,
      sceneMode:3,
      fullscreenElement:document.body,
      imageryProvider:new Cesium.UrlTemplateImageryProvider({url:url})
    });
    self.viewers = viewer;
    var viewModel = {
      height:0
    };
    Cesium.knockout.track(viewModel);
    var toolbar = document.getElementById('toolbar');
    Cesium.knockout.applyBindings(viewModel,toolbar);

    viewer.scene.globe.depthTestAgainstTerrain = true;
    var cesiumScene = viewer.scene;
    var terrainProvider = new Cesium.CesiumTerrainProvider({
      url: '/model/dixing'
    });

    cesiumScene.terrainProvider = terrainProvider;

    var tree_model = new Cesium.Model.fromGltf({
      url : ' /model/moxing1.gltf',
      modelMatrix : Cesium.Transforms.eastNorthUpToFixedFrame(Cesium.Cartesian3.fromDegrees(114.37351, 30.46791, 0.0)),
      scale : 1
	  });
    self.model2 = cesiumScene.primitives.add(tree_model); 

    var build_model = new Cesium.Model.fromGltf({
		  url : ' /model/moxing2.gltf',
		  modelMatrix : Cesium.Transforms.eastNorthUpToFixedFrame(Cesium.Cartesian3.fromDegrees(114.37351, 30.46791, 0.0)),
		  scale : 1,
		  id:'0',
		  name:'街道口'
	  });
	  self.modelEntity = cesiumScene.primitives.add(build_model); 
    
    viewer.camera.setView({
      destination : Cesium.Cartesian3.fromDegrees(114.3854, 30.47735, 600.0)
    });

    
  },
  methods:{
    show_hiden(key,status) {
			var _this=this;
			if(key){
				status.show=true;
			}else{
				status.show=false;
				//_this.viewers.scene.primitives.remove(this.model2);
			}
    },
    onMenuSelect(){
      
    }
  }
}
</script>