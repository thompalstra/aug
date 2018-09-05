console.log("dom");
Node.prototype.find = function(q){
  return this.querySelectorAll(q);
}
Node.prototype.one = function(q){
  return this.querySelector(q);
}
Node.prototype.on = function(eventTypes, a, b, c){
  let target = this;
  eventTypes.split(" ").forEach(function(eventType){
    if(typeof a === "function"){
      this.addEventListener(eventType, a);
    } else if(typeof a === "string" && typeof b === "function"){
      this.addEventListener(eventType, function(originalEvent){
        target = originalEvent.target.matches(a) ? originalEvent.target : originalEvent.target.closest(a);
        if(target){
          b.call(target, originalEvent);
        }
        if(originalEvent.defaultPrevented){
          return;
        }
      }.bind(this));
    }
  }.bind(this));
  return target;
}
Node.prototype.do = function(eventType, options){
  options = (typeof options === "undefined") ? true : options;
  if(typeof options === "boolean"){
    options = {
      cancelable: options,
      bubbles: options
    };
  }
  var ce = new CustomEvent(eventType, options);
  this.dispatchEvent(ce);
  return ce;
}
HTMLCollection.prototype.delegate = NodeList.prototype.delegate = function(fn, arg){
  this.forEach(function(node){
    if(typeof node[fn] === "function"){
      node[fn].apply(node, arg);
    } else {
      throw Error("Function '" + fn + "' does not exist");
    }
  }.bind(this));
}
HTMLCollection.prototype.on = NodeList.prototype.on = function(){
  this.delegate("on", arguments);
}

HTMLFormElement.prototype.serialize = function(){
  var fd = new FormData(this);
  var output = [];
  for(var entry of fd.entries()){
  	output.push(encodeURI(entry[0]) + "=" + encodeURI(entry[1]));
  }
  return output.join("&");
}
