'use strict';

module.exports = class extends think.Model {
  async login(param) {
    return this.where({login_name: param.login_name, password: think.md5(param.password),status:'1'}).find();
  }

  async pageData(param){
    let sql = this.field("id,login_name,rolename,name,email,phone,status").where({'id':['!=',1]}).page(param.current);
    if(!think.isEmpty(param.name)){
      sql = sql.where({'name':['like','%'+param.name+'%']});
    }
    let data = await sql.countSelect();
    return data;
  }

  async updateData(param){
    param.update_date = think.datetime();
    delete param.id;
    delete param.create_data;
    delete param.password;
    if(param.role_id == 1){
      param.rolename = '管理员';
    }else if(param.role_id == 2){
      param.rolename = '测试员';
    }
    await this.where({'login_name':param.login_name}).update(param);
  }

  async addData(param){
    let count = await this.count('id');
    param.id = count + 1;
    param.create_data = think.datetime();
    param.password = think.md5('111111');
    param.del_flag = 0;
    param.status = 1;
    if(param.role_id == 1){
      param.rolename = '管理员';
    }else if(param.role_id == 2){
      param.rolename = '测试员';
    }
    think.logger.info(param);
    await this.add(param);
  }
};
