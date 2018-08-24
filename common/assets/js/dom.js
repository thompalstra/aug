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

HTMLFormElement.prototype.serialize = function(){
  let output = [];
  for(let i = 0; i < this.elements.length; i++){
    let element = this.elements[i];
    if(["button"].indexOf(element.tagName.toLowerCase()) == -1){
      console.log(encodeURI(element.name) + "=" + encodeURI(element.value));
      output.push(encodeURI(element.name) + "=" + encodeURI(element.value));
    }
  }
  return output.join("&");
}
