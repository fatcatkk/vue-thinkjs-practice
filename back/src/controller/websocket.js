module.exports = class extends think.Controller {
  constructor(...arg) {
    super(...arg);

    this.io = this.ctx.req.io;
    this.socket = this.ctx.req.websocket; 
  };
  
  
  openAction(){
    // setInterval(() => {
    //   this.emit('open', '客户端连接成功！');
    // }, 5000);
    console.log('连接成功');
  };

  adduserAction() {
    console.log(this.wsData);
    this.emit("open",11111);
  };

  closeAction(){
    console.log('连接关闭');
    this.socket.disconnect(true);
  }
}