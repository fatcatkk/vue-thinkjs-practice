
const Base = require('./base.js');
const dgram = require('dgram');


module.exports = class extends Base{
  async indexAction(){
    return this.display();
  };

  async loginAction(){
    let self = this;
    let param = this.post();
    think.logger.info(self.ctx.request);
    let data = await this.model("sys_user").login(param);
    if(think.isEmpty(data)){
      return this.fail(9996,'用户名或密码错误');
    }
    else{
      data.token = await this.service("token").createToken(data);
      data.menu = await this.model('sys_menu').getMyMenuData(data.id,data.role_id);
      return this.success(data);
    }
  };

  async pageDataAction(){
    let param = this.post();
    let data = await this.model("sys_user").pageData(param);
    return this.success(data);
  };
  
  async delDataAction(){
    let param = this.post();
    let affectdRows = await this.model("sys_user").where({'login_name' : param.login_name}).delete();
    if(affectdRows == 1){
      return this.success(affectdRows);
    }else{
      return this.fail(9996,'删除失败');
    }
  };
  async resetPwdAction(){
    let param = this.post();
    let affectdRows = await this.model("sys_user").where({'login_name' : param.login_name}).update({'password':think.md5('111111')});
    think.logger.info(affectdRows);
    if(affectdRows == 1){
      return this.success(affectdRows);
    }else{
      return this.fail(9996,'重置失败');
    }
  }

  async changeStatusAction(){
    let param = this.post();
    let status = 0;
    if(param.status == 1){
      status = 1;
    }
    let affectdRows = await this.model("sys_user").where({'login_name' : param.login_name}).update({'status':status});
    if(affectdRows == 1){
      return this.success(affectdRows);
    }else{
      return this.fail(9996,'切换状态失败');
    }
  }

  async updateDataAction(){
    let param = this.post();
    await this.model("sys_user").updateData(param);
    return this.success();
  }

  async adduserAction(){
    let param = this.post();
    let data = await this.model("sys_user").where({'login_name' : param.login_name}).find();
    if(!think.isEmpty(data)){
      return this.fail(9995,'用户已存在');
    }
    await this.model("sys_user").addData(param);
    return this.success();
  }
};