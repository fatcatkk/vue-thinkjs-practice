const Base = require('./base.js');


module.exports = class extends Base {
  async indexAction(){
    return this.display();
  };

  async getMenuDataAction(){
    let param = this.post();
    let menuList = [];
    let allMenuData = await this.model("sys_menu").select();
    for(var i = 0; i < allMenuData.length; i++){
      menuList[i] = {};
      menuList[i].title = allMenuData[i].title;
      menuList[i].checked = false;
    }
    let userId = await this.model("sys_user").field("id").where({'login_name':param.login_name}).find();
    think.logger.info(userId);
    let menuId = await this.model("sys_user_menu").where({'user_id': userId.id}).select();
    for(var i = 0; i < menuId.length; i ++){
      menuList[menuId[i].menu_id - 1].checked = true;
    }
    return this.success(menuList);
  }

  async updateUserMenuAction(){
    let param = this.post();
    let userId = await this.model("sys_user").field("id").where({'login_name':param.login_name}).find();
    let menuData = param.menuData;
    think.logger.info(menuData);
    for(var i = 0; i < menuData.length; i++){
      await this.model("sys_user_menu").where({'user_id':userId.id,'menu_id':(menuData[i].nodeKey)+1}).thenAdd({'user_id':userId.id,'menu_id':(menuData[i].nodeKey)+1});
    }
    let afterEditMenu = await this.model("sys_user_menu").where({'user_id':userId.id}).select();
    return this.success(afterEditMenu);
  }
}