
<template>
  <div class="login-container" style="background-color:#000000;margin:0px;overflow:hidden;">
    <div class="homepage-hero-module">
      <div class="name-container">
        <div class="login-header">
          <div class="box"> <span>不知道是什么东西的东西</span></div>
          <!-- <img src="static/Video/timg.jpg"> -->
        </div>
        <Form class="login-form" ref="formInline" :model="formInline" :rules="ruleInline">
          <FormItem prop="username">
            <i-Input type="text" v-model="formInline.username" placeholder="Username">
            <Icon type="ios-person-outline" size="16" slot="prepend"></Icon>
            </i-Input>
          </FormItem>
          <FormItem prop="password">
            <i-Input type="password" v-model="formInline.password" placeholder="Password" @keyup.enter.native="handleSubmit">
              <Icon size="14" type="ios-lock-outline" slot="prepend"></Icon>
            </i-Input>
          </FormItem>
          <FormItem>
            <Button type="primary" @click="handleSubmit" >登录</Button>
          </FormItem>
        </Form>
        
      </div>	

      <div id="canvascontainer" ref='can' ></div>
      
      <div class="video-container">
        <div :style="fixStyle" class="filter"></div>
        <video :style="fixStyle" autoplay loop class="fillWidth" v-on:canplay="canplay">
          <source src="../../assets/Video/33.mp4" type="video/mp4"/>
          浏览器不支持 video 标签，建议升级浏览器。
          <!-- <source src="static/Video/2.mp4" type="video/webm"/> -->
          浏览器不支持 video 标签，建议升级浏览器。
        </video>
        <div class="poster hidden" v-if="!vedioCanPlay">
          <img :style="fixStyle" src="../../assets/Video/timg.jpg" alt="">
        </div>
      </div>
    </div>
    <div class="box1"> <span>不知道是什么东西</span></div>
  </div>
</template>

<script>
/* eslint-disable */
import util from "../../api/api.js";
import Cookies from 'js-cookie';
export default {
  /* components: {
    LoginForm
  }, */
  data() {
    return {
      formInline: {
        user: "",
        password: ""
      },
      ruleInline: {
        user: [
          {
            required: true,
            message: "Please fill in the user name",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "Please fill in the password.",
            trigger: "blur"
          },
          {
            type: "string",
            min: 6,
            message: "The password length cannot be less than 6 bits",
            trigger: "blur"
          }
        ]
      },
      vedioCanPlay: false,
      fixStyle:'',
      loading:false,
      showDialog:false
    };
  },
  methods: {
    handleSubmit(){
      var self = this;
      this.$refs.formInline.validate((valid) => {
        if(valid){
          this.loading = true;
          var param = {login_name:self.formInline.username,password:self.formInline.password};
          
          util.post(this,'sys_user/login',param,function(datas){ //像后端发送登录请求，执行登录操作，将用户名和密码发送过去
            //登陆后，利用localStorage存储用户信息
            localStorage.setItem('user',datas.data.login_name);
            localStorage.setItem('token',datas.data.token);
            localStorage.setItem('menuList',JSON.stringify(datas.data.menu));//
            let menuList=util.reloadMenu(datas.data.menu);
            
            //self.$store.commit('updateMyMenulist',menuList);
            // console.log(self.$store);
            // console.log(self.$store.state.app);
            
            self.$router.addRoutes(menuList);
            self.$Message.info("登录成功");
            self.$router.push({
              name:'home_index'
            })
          });
        }
      })
    },
    canplay(){
      this.vedioCanPlay = true;
    },
    onresize(){
      const windowWidth = document.body.clientWidth;
      const windowHeight = document.body.clientHeight;
      const windowAspectRatio = windowHeight/windowWidth;
      let videoWidth,videoHeight;
      if(windowAspectRatio < 0.5625){
        videoWidth = windowWidth;
        videoHeight = videoWidth * 0.5625;
        this.fixStyle = {
          height: windowWidth * 0.5625 + 'px',
          width: windowWidth + 'px',
          'margin-bottom': (windowHeight - videoHeight) / 2 + 'px',
          'margin-left': 'initial'
        }
      }else{
        videoHeight = windowHeight;
        videoWidth = videoHeight / 0.5625;
        this.fixStyle = {
          height: windowHeight + 'px',
          width: windowHeight / 0.5625 + 'px',
          'margin-left': (windowWidth - videoWidth) / 2 + 'px',
          'margin-bottom': 'initial'
        }
      }
    }
  },
  mounted(){
    this.onresize();
  }
}
</script>
<style lang="less" scoped>

.login-container a{color:#0078de;}
#canvascontainer{
  position: absolute;
  top: 0px;
}
.wz-input-group-prepend{
  background-color: #141a48;
   border: 1px solid #2d8cf0;
   border-right: none;
   color:  #2d8cf0;
}
.homepage-hero-module
{
	margin: 5;
}
  .video-container {
    position: relative;
    height: 100vh;
    overflow: hidden;
  }

  .video-container .poster img,
  .video-container video {
    z-index: 0;
    position: absolute;
  }
  .name-container{
	margin: auto;
	position: relative;
    z-index: 1;
	background:#fff;
	
  }
  .logo-container{
	margin: auto;
	position: relative;
    
  }

  .video-container .filter {
    z-index: 0;
    position: absolute;
    background: rgba(0, 0, 0, 0.4);
  }
  
	
  .login-header{
	top: 200px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    margin: auto;
    height: 48px;
    
    border-bottom: 1px solid #dcdcdc;
}
  .login-bottom{
	top: 50px;
	bottom:20px;
    margin: auto;
    height: 18px;

}

.box{
    width: 100%;
	text-align:center;
	line-height: 150px;/*让黄色div中的文字内容垂直居中*/
	z-index: 2;
	top: 50px;
	left: 0;
	right: 0;
	color:#ffffff;
	background:#000;
	opacity: 0.7;
	font-size:54px;
	margin: auto;
	position: absolute;
	height: 180px;
	display: block;
}
.box1{
    width: 100%;
	text-align:center;
	line-height: 30px;/*让黄色div中的文字内容垂直居中*/
	z-index: 2;
	color:#ffffff;
	bottom: 5px;
	left: 0;
	right: 0;
	background:#000;
	opacity: 0.7;
	font-size:14px;
	margin: auto;
	position: absolute;
	height: 30px;
	display: block;
}
</style>

<style rel="stylesheet/scss" lang="scss">
     .tips{
      font-size: 14px;
      color: #fff;
      margin-bottom: 5px;
    } 
    .login-container {
        height: 100vh;
        background-color: #2d3a4b;

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #293444 inset !important;
            -webkit-text-fill-color: #fff !important;
        }
        input {
            background: transparent;
            border: 1px solid #2d8cf0;
            -webkit-appearance: none;
            border-radius: 3px;
            padding: 12px 5px 12px 15px;
            color: #eeeeee;
            height: 47px;
        }
        .el-input {
            display: inline-block;
            height: 47px;
            width: 85%;
        }
        .svg-container {
            padding: 6px 5px 6px 15px;
            color: #889aa4;
        }

        .title {
            font-size: 26px;
            font-weight: 400;
            color: #eeeeee;
            margin: 0px auto 40px auto;
            text-align: center;
            font-weight: bold;
        }

        .login-form {
            position: absolute;
            left: 0;
            right: 0;
            width: 400px;
            padding: 35px 35px 15px 35px;
            margin: 250px auto;
        }

        .el-form-item {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            color: #454545;
        }

        .forget-pwd {
            color: #fff;
        }
    }

</style>