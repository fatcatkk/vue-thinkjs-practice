const Base = require('./base.js');


module.exports = class extends Base {
  async indexAction(){
    return this.display();
  }

  async nodeOnlineAction(){
    let onlineCount = await this.model('node_online').count('state');
    let droplineCount = await this.model('node_dropline').count('state');
    let data = {online : onlineCount, dropline: droplineCount};
    return this.success(data);
  }

  async nodeGetListAction(){
    const data = await this.cache('nodeList', () => {
      let list =  this.model("blue").nodeGetList();
      return list;
    },'redis');
    return this.success(data);
  }
}