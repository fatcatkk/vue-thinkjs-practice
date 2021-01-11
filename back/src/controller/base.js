module.exports = class extends think.Controller {
  async __before() {
    this.header("Access-Control-Allow-Origin", this.header("origin") || "*");
    this.header("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,x-requested-with,x-token");
    this.header("Access-Control-Allow-Methods", "GET,POST,OPTIONS,PUT,DELETE");
    this.header('Access-Control-Allow-Credentials',true);

    // think.logger.info(1);
    if(this.ctx.method != 'POST'){
      return this.fail(9993);
    }
    if(this.ctx.action === 'login' || this.ctx.action === 'register'){
      return;
    }

    let self = this;
    let userToken = this.header("x-token");
    // think.logger.info(self.header('x-token'));
    if(think.isEmpty(userToken)){
      return this.fail(9998,'验证失败，请重新登录');
    } 

    let tokenServiceInstance = this.service('token');

    let verifyTokenResult = await tokenServiceInstance.verifyToken(userToken);
    // think.logger.info(verifyTokenResult);
    if (!verifyTokenResult) {
      think.logger.info("token不对")
      return this.fail(9997,'登录过期，请重新登录');
    }else{
      think.logger.info("验证成功");
      return;
    }
  };
};
