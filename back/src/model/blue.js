'use strict';

module.exports = class extends think.Model{
  async nodeGetList(){
    let List = await this.model("location").where({'build': 0}).select();
    //let nodelist = new Array();
    return List;
  }
}