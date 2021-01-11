'use strict';

module.exports = class extends think.Model {
  async login(param) {
    return this.where({login_name: param.login_name, password: param.password}).find();
  }
};
