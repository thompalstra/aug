var Desktop = function(node){
  this.node = node;
  this.node.Desktop = this;
  this.DesktopItems = new DesktopItems(this, this.node.querySelector(".desktop-items"));
  this.DesktopTaskbar = new DesktopTaskbar(this, this.node.querySelector(".desktop-taskbar"));
  this.addEventListeners();
}
Desktop.prototype.addEventListeners = function(){
  this.node.addEventListener("click", function(e){
    if(e.target.matches(".desktop-window-open") || e.target.closest(".desktop-window-open")){
      e.preventDefault();
      e.stopPropagation();
      let gridItem = e.target.matches(".desktop-window-open") ? e.target : e.target.closest(".desktop-window-open");
      let href = gridItem.getAttribute("href");
      let identifier = "desktop-window-" + href.replace(/\//g, "-").replace(/\./g,"-");
      if(this.DesktopItems.winExists(identifier)){
        this.DesktopItems.focusWindow(identifier);
        this.DesktopTaskbar.focusTask(identifier);
      } else {
        this.DesktopItems.openWindow(href, identifier);
        this.DesktopTaskbar.openTask(identifier);
      }
    }
  }.bind(this))
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
  let task = new DesktopTask(this);
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
var DesktopTask = function(DesktopTaskbar){
  this.node = document.createElement("li");
  this.node.innerHTML = "my window";
  this.DesktopTaskbar = DesktopTaskbar;
}
DesktopTask.prototype.setIdentifier = function(identifier){
  this.identifier = identifier;
  this.node.setAttribute("id", identifier);
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
}
DesktopWindow.prototype.close = function(){
  let identifier = this.identifier;
  let Desktop = this.DesktopItems.Desktop;
  Desktop.DesktopTaskbar.removeTask(identifier);
  Desktop.DesktopItems.removeWindow(identifier);
  this.node.remove();
}
DesktopWindow.prototype.maximize = function(){

}
DesktopWindow.prototype.minimize = function(){

}
DesktopWindow.prototype.addEventListeners = function(){

  this.node.addEventListener("submit", function(e){
    e.preventDefault();
    e.stopPropagation();
    let form = e.target.matches("form") ? e.target : e.target.closest("form");
    console.log(form.method);
    if(form.method.toUpperCase() == "POST"){
      let elements = form.elements;
      let formData = new FormData();

      for(let i = 0; i < elements.length; i++){
        let element = elements[i];
        formData.append(element.name, element.value);
      }

      fetch(form.action, {
        method: "POST",
        body: formData
      })
      .then(res => res.text())
      .then((text)=>{
        this.node.innerHTML = text;
        this.addEventListeners();
      });
    } else {
      let data = new URLSearchParams(new FormData(form));
      console.log(form.action);

    }
  }.bind(this));

  let minimize = this.node.querySelector(".window-action.minimize");
  if(minimize){
    minimize.addEventListener("click", function(event){
      this.minimize();
    }.bind(this));
  }
  let maximize = this.node.querySelector(".window-action.maximize");
  if(maximize){
    maximize.addEventListener("click", function(event){
      this.maximize();
    }.bind(this));
  }
  let close = this.node.querySelector(".window-action.close");
  if(close){
    close.addEventListener("click", function(event){
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
