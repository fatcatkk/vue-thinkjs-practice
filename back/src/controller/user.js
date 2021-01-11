
const Base = require('./base.js');

module.exports = class extends Base {
  async indexAction() {
    return this.display();
  };
  async loginAction() {
    const param = this.post();
    const data = await this.model('user').login(param);

    if (think.isEmpty(data)) {
      return this.fail(9996);
    } else {
      return this.success(data);
    }
  }
};
