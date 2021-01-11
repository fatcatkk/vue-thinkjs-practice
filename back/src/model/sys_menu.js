'use strict';

module.exports = class extends think.Model{
  async getMyMenuData(user_id,role_id){
    let menu=[];
    if(role_id == 1){//1即为管理员，展示所有菜单，不为1则为用户
      let pMenu=await this.model('sys_menu').where({pid:0,del_flag:0,is_show:1}).order('sort').select();
      for(var i=0;i<pMenu.length;i++){
        let item = pMenu[i];
        let len = menu.push(item);
        let childMenu=await this.where({pid:item.id,del_flag:0,is_show:1}).order('sort').select();
        menu[len-1].children = childMenu;
      }
    }else{
      let rolemenu=await this.model('sys_user_menu').where({user_id:user_id}).select();
      let menuids=[];
      for(var i=0;i<rolemenu.length;i++){
        let item=rolemenu[i];
        menuids.push(item.menu_id);
      }
      let pMenu=await this.where({pid:0,del_flag:0,is_show:1,id:['IN',menuids]}).order('sort').select();
      for(var i=0;i<pMenu.length;i++){
        let item=pMenu[i];
        let len = menu.push(item);
        let childMenu=await this.where({pid:item.id,del_flag:0,is_show:1,id:['IN',menuids]}).order('sort').select();
        menu[len-1].children = childMenu;
      }
    }
    return menu;
  }
}