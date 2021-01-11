var serialport = require('serialport');
//打开串口
var serialport = new serialport('COM4',{
    baudRate : 115200,
    autoOpen : false
});
serialport.open(function(error){
    console.log('IsOpen:',serialport.isOpen)
    serialport.write('1');
    setTimeout(() => {
        serialport.close()
    }, 5000);

})

console.log('IsOpen:',serialport.isOpen)
serialport.on('data',function(data){
    console.log(data.toString());
    if(data[0] == 1)
    {
        serialport.write('1');
    }
    console.log(1);
})