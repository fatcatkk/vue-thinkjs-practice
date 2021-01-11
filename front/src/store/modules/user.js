import Cookies from 'js-cookie';

const user = {
  state:{

  },
  mutations:{
    logout(){
      Cookies.remove("user");
      Cookies.remove("password");
      Cookies.remove("token");
      Cookies.remove("menuList");
      localStorage.clear();
    }
  }
};
export default user;