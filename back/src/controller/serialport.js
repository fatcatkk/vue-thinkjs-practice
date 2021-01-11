const Base = require('./base.js');
var serialPort = require('serialport');

var serialport = [];
module.exports = class extends Base {
  indexAction() {
    return this.display();
  };

  async serialPortScanAction(){
    let portExist = [];
    await serialPort.list().then(ports => {
      ports.forEach(function(port,index) {
        console.log(port.path);
        portExist.push(port);
        serialport[index] = new serialPort(port.path,{
          baudRate : 115200,
          autoOpen : false
        })
        // console.log(port.pnpId);
        // console.log(port.manufacturer);
      });
    });
    return this.success(portExist);
  };
  async serialPortOnAction(){
    let port = this.post();
    let self = this;
    console.log(port);
    for( let i=0; i < serialport.length; i++){
      if (serialport[i].path == port.portSelect){
        serialport[i].open(function(error){
          console.log('Port Open Status:',serialport[i].isOpen);
        });
        await think.timeout(200);
        self.success(serialport[i].isOpen);
        
      }
    }
    
  }
  async serialPortWriteAction() {
    var message = this.post();
    for( let i=0; i < serialport.length; i++){
      if (serialport[i].path == message.portSelect){
        await serialport[i].open(function(error){
          console.log('Port Open Status:',serialport[i].isOpen);
        });
        serialport[i].write(message.sendMessage);
      }
    };
    return this.success(1);
  };
  async serialPortCloseAction(){
    let self = this;
    let port = this.post();
    for( let i=0; i < serialport.length; i++){
      if (serialport[i].path == port.portSelect){
        serialport[i].close();
      };
      await think.timeout(200);
      self.success(serialport[i].isOpen);
    };
  }
}; 