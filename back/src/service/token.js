'use strict'

let jwt = require('jsonwebtoken');

//let secret = think.config("jwtsecret");
var cert="I am a goog man!";
module.exports = class extends think.Service{
  
  createToken(userinfo){
    let result = jwt.sign({userinfo:userinfo}, 'secret', { expiresIn: 60*60 });
    return result;
  }

  verifyToken(token){
    if(token){
      try{
        let result = jwt.verify(token,'secret');
        return result;
      }catch(err){
        return false;
      }
    }
    return false;
  }
}