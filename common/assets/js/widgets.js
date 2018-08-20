var Desktop = function(node){
  this.node = node;
  this.node.Desktop = this;
  this.DesktopItems = new DesktopItems(this, this.node.querySelector(".desktop-items"));
  this.DesktopTaskbar = new DesktopTaskbar(this, this.node.querySelector(".desktop-taskbar"));
  this.addEventListeners();
}
Desktop.prototype.addEventListeners = function(){
  this.node.on("click", ".desktop-window-open", function(event){
    event.preventDefault();
    event.stopPropagation();
    let gridItem = event.target.matches(".desktop-window-open") ? event.target : event.target.closest(".desktop-window-open");
    let href = gridItem.getAttribute("href");
    let identifier = "desktop-window-" + href.replace(/\//g, "-").replace(/\./g,"-");
    if(this.DesktopItems.winExists(identifier)){
      this.DesktopItems.focusWindow(identifier);
      this.DesktopTaskbar.focusTask(identifier);
    } else {
      this.DesktopItems.openWindow(href, identifier);
      this.DesktopTaskbar.openTask(identifier);
    }
  }.bind(this));
  this.node.addEventListener("mousedown", function(e){
    if(e.target.matches(".window-actions") || e.target.closest(".window-actions")){
      return;
    }
    if(e.target.matches(".window-bar") || e.target.closest(".window-bar")){
      e.preventDefault();
      e.stopImmediatePropagation();
      this.DesktopItems.setDragWindow(event, e.target.closest(".desktop-window").DesktopWindow);
      this.DesktopItems.focusWindow(e.target.closest(".desktop-window").DesktopWindow.identifier);
      this.DesktopTaskbar.focusTask(e.target.closest(".desktop-window").DesktopWindow.identifier);
    }
  }.bind(this));
  this.node.addEventListener("mousemove", function(e){
      this.DesktopItems.moveDragWindow(e);
  }.bind(this));
  this.node.addEventListener("mouseup", function(e){
    if(e.target.matches(".window-actions") || e.target.closest(".window-actions")){
      return;
    }
    if(e.target.matches(".window-bar") || e.target.closest(".window-bar")){
      e.preventDefault();
      e.stopPropagation();
      this.DesktopItems.unsetDragWindow();
    }
  }.bind(this));
}
Desktop.prototype.openWindow = function(href, identifier){
  return this.DesktopItems.openWindow(this, href, identifier);
}

var DesktopTaskbar = function(Desktop, node){
  this.node = node;
  this.node.DesktopTaskbar = this;
  this.Desktop = Desktop;

  this.items = new Map();
}
DesktopTaskbar.prototype.openTask = function(identifier){
  let DesktopWindow = this.Desktop.DesktopItems.windows.get(identifier);
  console.log(DesktopWindow);
  let task = new DesktopTask(this, DesktopWindow);
  task.setIdentifier(identifier);
  this.items.set(identifier, task);
  this.node.appendChild(task.node);
}
DesktopTaskbar.prototype.removeTask = function(identifier){
  let task = this.items.get(identifier);
  task.node.remove();
  this.items.delete(identifier);
}
DesktopTaskbar.prototype.focusTask = function(identifier){

}
var DesktopTask = function(DesktopTaskbar, DesktopWindow){
  this.node = document.createElement("li");
  this.node.innerHTML = "my window";
  this.DesktopTaskbar = DesktopTaskbar;
  this.DesktopWindow = DesktopWindow;
  this.addEventListeners();
}
DesktopTask.prototype.setIdentifier = function(identifier){
  this.identifier = identifier;
  this.node.setAttribute("id", identifier);
}
DesktopTask.prototype.addEventListeners = function(){
  this.node.addEventListener("click", function(event){
    this.DesktopWindow.minimize();
  }.bind(this));
}
var DesktopItems = function(Desktop, node){
  this.node = node;
  this.node.DesktopItems = this;
  this.Desktop = Desktop;

  this.windows = new Map();
}
DesktopItems.prototype.winExists = function(identifier){
  return (typeof this.windows.get(identifier) == "undefined") ? false : true;
}
let map;
DesktopItems.prototype.focusWindow = function(identifier){
  let win = this.windows.get(identifier);
  let node = win.node;
  map = this.windows;
  if(typeof win == "undefined"){

  } else {
    this.node.appendChild(node);
    this.windows.forEach(function(entry){
      entry.focusout();
    }.bind(this));
    win.focusin();
  }
}
DesktopItems.prototype.openWindow = function(href, identifier){
  let win = new DesktopWindow(this);
  win.setIdentifier(identifier);
  win.setContentFromURL(href);
  this.windows.set(identifier, win);
  this.node.appendChild(win.node);
}
DesktopItems.prototype.removeWindow = function(identifier){
  let win = this.windows.get(identifier);
  win.node.remove();
  this.windows.delete(identifier);
}
DesktopItems.prototype.moveDragWindow = function(event){
  if(this.dragWindow){
    event.preventDefault();
    event.stopPropagation();
    let node = this.dragWindow.node;
    let x = event.clientX - this.dragWindowOffset.x;
    let y = event.clientY - this.dragWindowOffset.y;
    node.style.left = x + "px";
    node.style.top = y + "px";
  }
}
DesktopItems.prototype.setDragWindow = function(event, DesktopWindow){
  this.dragWindow = DesktopWindow;
  this.dragWindowOffset = {
    x: event.offsetX,
    y: event.offsetY,
  };
}
DesktopItems.prototype.unsetDragWindow = function(DesktopWindow){
  this.dragWindow = null;
}
var DesktopWindow = function(DesktopItems){
  this.node = document.createElement("div");
  this.node.className = "desktop-window";
  this.node.DesktopWindow = this;
  this.DesktopItems = DesktopItems;
  this.addEventListeners();

  this.isFullScreen = false;
  this.isHidden = false;
  this.coords = {
    previous : {
      x: null,
      y: null,
      width: null,
      height: null
    }
  };
}
DesktopWindow.prototype.close = function(){
  let identifier = this.identifier;
  let Desktop = this.DesktopItems.Desktop;
  Desktop.DesktopTaskbar.removeTask(identifier);
  Desktop.DesktopItems.removeWindow(identifier);
  this.node.remove();
}
DesktopWindow.prototype.maximize = function(){
  if(this.isFullScreen == false){
    computed = getComputedStyle(this.node);
    this.coords.previous = {
      x: this.node.style.left,
      y: this.node.style.top,
      width: computed.getPropertyValue("width"),
      height: computed.getPropertyValue("height")
    };
    this.node.style.left = 0;
    this.node.style.top = 0;
    this.node.style.width = window.innerWidth;
    this.node.style.height = window.innerHeight;
    this.isFullScreen = true;
  } else {
    this.node.style.left = this.coords.previous.x;
    this.node.style.top = this.coords.previous.y;
    this.node.style.width = this.coords.previous.width;
    this.node.style.height = this.coords.previous.height;
    this.isFullScreen = false;
  }
}
DesktopWindow.prototype.minimize = function(){
  if(this.isHidden == false){
    this.node.classList.remove("minimize-in");
    this.node.classList.add("minimize-out");
    this.isHidden = true;
  } else {
    this.node.classList.add("minimize-in");
    this.node.classList.remove("minimize-out");
    this.isHidden = false;
  }
}
DesktopWindow.prototype.addEventListeners = function(){
  this.node.addEventListener("click", function(e){
    this.DesktopItems.focusWindow(this.identifier);
  }.bind(this));
  this.node.on("submit click", "form,button", function(event){
    event.preventDefault();
    event.stopPropagation();
    let form = event.target.matches("form") ? event.target : event.target.closest("form");
    let elements = form.elements;
    let formData = new FormData();
    for(let i = 0; i < elements.length; i++){
      let element = elements[i];
      if(["button"].indexOf(element.tagName.toLowerCase()) !== -1){

      } else {
        formData.append(element.name, element.value);
      }
    }

    if(event.type == "click"){
      let button = event.target.matches("button") ? event.target : event.target.closest("button");
      formData.append(button.name, button.value);
    }

    let params = {
      method: form.method
    };
    if(form.method.toLowerCase() == "post"){
      params.body = formData;
    } else {
      let parameters = [...formData.entries()] // expand the elements from the .entries() iterator into an actual array
                     .map(e => encodeURIComponent(e[0]) + "=" + encodeURIComponent(e[1]))  // transform the elements into encoded key-value-pairs

      let url = form.action;
      if(url.indexOf("?") !== -1){
        url = url.substring(0, url.indexOf("?"))
      }
      if(parameters){
        url = url + "?" + parameters.join("&");
      }
      form.action = url;
    }
    fetch(form.action, params)
      .then(res => res.text())
      .then((text)=>{
        this.node.innerHTML = text;
        this.addEventListeners();
      });
  }.bind(this));

  let minimize = this.node.querySelector(".window-action.minimize");
  if(minimize){
    minimize.addEventListener("click", function(event){
      event.preventDefault();
      event.stopPropagation();
      this.minimize();
    }.bind(this));
  }
  let maximize = this.node.querySelector(".window-action.maximize");
  if(maximize){
    maximize.addEventListener("click", function(event){
      event.preventDefault();
      event.stopPropagation();
      this.maximize();
    }.bind(this));
  }
  let close = this.node.querySelector(".window-action.close");
  if(close){
    close.addEventListener("click", function(event){
      event.preventDefault();
      event.stopPropagation();
      this.close();
    }.bind(this));
  }
}
DesktopWindow.prototype.focusout = function(){
  this.node.classList.remove("focus");
}
DesktopWindow.prototype.focusin = function(){
  this.node.classList.add("focus");
}
DesktopWindow.prototype.setIdentifier = function(identifier){
  this.identifier = identifier;
  this.node.setAttribute("id", identifier);
}
DesktopWindow.prototype.setContentFromURL = function(href){
  fetch(href)
    .then(function(res){
      return res.text()
    })
    .then(function(text){
      this.node.innerHTML = text;
      this.addEventListeners();

    }.bind(this))
}
