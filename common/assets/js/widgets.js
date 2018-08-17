console.log("widgets");
var GridControl = function(node){
  this.node = node;
  this.node.GridControl = this;
  this.GridWindows = new GridWindows(this);
  this.addEventListeners();
}
GridControl.prototype.addEventListeners = function(){
  this.node.addEventListener("click", function(e){
    if(e.target.matches(".grid-window-open") || e.target.closest(".grid-window-open")){
      e.preventDefault();
      e.stopPropagation();
      let gridItem = e.target.matches(".grid-item") ? e.target : e.target.closest(".grid-item");
      let href = gridItem.getAttribute("href");
      let identifier = "grid-window-" + href.replace("/", "-").replace(".","-");
      if(this.GridWindows.windowExists(identifier)){
        this.GridWindows.focusWindow(identifier);
      } else {
        this.GridWindows.openWindow(href, identifier);
      }
    }
  }.bind(this))
  // this.node.addEventListener("contextmenu", function(e){
  //   if(e.target.matches(".grid-item") || e.target.closest(".grid-item")){
  //     e.preventDefault();
  //     e.stopPropagation();
  //     let gridItem = e.target.matches(".grid-item") ? e.target : e.target.closest(".grid-item");
  //   }
  // })
}
GridControl.prototype.openWindow = function(href, identifier){
  return this.GridWindows.openWindow(this, href, identifier);
}

var GridWindows = function(GridControl){
  this.windows = new Map();
  this.GridControl = GridControl;
}
GridWindows.prototype.windowExists = function(identifier){
  return (typeof this.windows.get(identifier) == "undefined") ? false : true;
}
let map;
GridWindows.prototype.focusWindow = function(identifier){
  let window = this.windows.get(identifier);
  map = this.windows;
  if(typeof window == "undefined"){

  } else {
    this.windows.forEach(function(entry){
      entry.focusout();
    }.bind(this));
    window.focusin();
  }
}
GridWindows.prototype.openWindow = function(href, identifier){
  let window = new GridWindow();
  window.setIdentifier(identifier);
  window.setContentFromURL(href);
  this.windows.set(identifier, window);
  this.GridControl.node.appendChild(window.node);
}

var GridWindow = function(){
  this.node = document.createElement("div");
  this.node.className = "grid-window";
  this.node.GridWindow = this;
}
GridWindow.prototype.focusout = function(){
  this.node.classList.remove("focus");
}
GridWindow.prototype.focusin = function(){
  this.node.classList.add("focus");
}
GridWindow.prototype.setIdentifier = function(identifier){
  this.identifier = identifier;
  this.node.setAttribute("id", identifier);
}
GridWindow.prototype.setContentFromURL = function(href){
  fetch(href)
    .then(function(res){
      return res.text()
    })
    .then(function(text){
      this.node.innerHTML = text;
    }.bind(this))
}
