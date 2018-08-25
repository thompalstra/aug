let Workspace = function(node, Desktop){
  this.setNode(node);
  this.setDesktop(Desktop);
  this.setWindowsMap(new Map());
}
Workspace.prototype.data = {};
Workspace.prototype.setNode = function(node){
  node.Workspace = this;
  this.data.node = node;
}
Workspace.prototype.getNode = function(){
  return this.data.node;
}
Workspace.prototype.setDesktop = function(Desktop){
  this.data.Desktop = Desktop;
}
Workspace.prototype.getDesktop = function(){
  return this.data.Desktop;
}
Workspace.prototype.setWindowsMap = function(map){
  this.data.map = map;
}
Workspace.prototype.getWindowsMap = function(){
  return this.data.map;
}
Workspace.prototype.createRange = function(start, end){
  let out = [];
  end = (typeof end === "string") ? end.charCodeAt(0) : end;
  start = (typeof start === "string") ? start.charCodeAt(0) : start;
  if(start > end){
    for(let current = start; current <= end; current--){
      out.push(String.fromCharCode(current));
    }
  } else {
    for(let current = start; current <= end; current++){
      out.push(String.fromCharCode(current));
    }
  }
  return out;
}
Workspace.prototype.createUniqueId = function(length){
  let range = dt.getWorkspace().createRange("a", "x")
    .concat(dt.getWorkspace().createRange("A", "X"))
    .concat(dt.getWorkspace().createRange("0", "9"));

  let out = [];
  for(let i = 0; i < length; i++){
    var index = Math.floor(Math.random() * (length - 0 + 1)) + 0;
    out.push(range[index]);
  }
  return out.join("");
}
Workspace.prototype.createIdentifier = function(){
  let identifier;
  for(identifier = this.createUniqueId(32);this.getWindowsMap().get(identifier);){
  }
  return identifier;
}
Workspace.prototype.addWindow = function(win){
  this.getWindowsMap().set(win.getIdentifier(), win);
  return win;
}
Workspace.prototype.openWindow = function(url, title){
  let identifier = this.createIdentifier(url);
  let win = this.addWindow(new Win(this, identifier, title));
  this.getDesktop().getTaskbar().closeMenu();
  this.getDesktop()
    .getTaskbar()
    .addTaskByWindow(win);
  return win.loadFromURL(url)
    .then(function(){
      this.getNode().appendChild(win.getNode());
      this.getDesktop().focusWindow(win);
    }.bind(this));
}
Workspace.prototype.removeWindow = function(win){
  this.getWindowsMap().get(win.getIdentifier()).getNode().remove();
  this.getWindowsMap().delete(win.getIdentifier());
}
Workspace.prototype.closeWindow = function(win){
  this.getDesktop().getTaskbar().removeTaskByWindow(win);
  this.removeWindow(win);
}
Workspace.prototype.focusWindow = function(target){
  this.getWindowsMap().forEach(function(win){
    if(target !== null && win.getIdentifier() == target.getIdentifier() ){
      win.focusIn();
      win.getTask().focusIn();
    } else {
      win.focusOut();
      win.getTask().focusOut();
    }
  });
}
Workspace.prototype.unfocusWindow = function(target){
  target.focusOut();
}
Workspace.prototype.setDragWindow = function(win, event){
  if(win){
    win.setDragOffset({ x: event.offsetX, y: event.offsetY });
  } else {
    this.data.dragWindow.setDragOffset({ x: null, y: null });
  }
  this.data.dragWindow = win;
}
Workspace.prototype.moveDragWindow = function(event){
  let win = this.getDragWindow();
  let node = win.getNode();
  let offset = win.getDragOffset();
  let x = event.clientX - offset.x;
  let y = event.clientY - offset.y;
  node.style.left = x + "px";
  node.style.top = y + "px";
}
Workspace.prototype.getDragWindow = function(win){
  return this.data.dragWindow;
}
Workspace.prototype.ensureVisible = function(win){
  let node = win.getNode();
  let nodeBoundingClientRect = node.getBoundingClientRect();
  let workspaceNode = this.getNode();
  let workspaceNodeBoundingClientRect = workspaceNode.getBoundingClientRect();
  if(nodeBoundingClientRect.top < 0){
    node.style.top = 0 + "px";
  } else if(nodeBoundingClientRect.top > workspaceNodeBoundingClientRect.height){
    node.style.top = workspaceNodeBoundingClientRect.height - nodeBoundingClientRect.height + "px";
  }
  if(nodeBoundingClientRect.left < 0){
    node.style.left = 0 + "px";
  } else if(nodeBoundingClientRect.left + nodeBoundingClientRect.width > workspaceNodeBoundingClientRect.width){
    node.style.left = workspaceNodeBoundingClientRect.width - nodeBoundingClientRect.width;
  } else if(nodeBoundingClientRect.left > workspaceNodeBoundingClientRect.width){
    node.style.left = workspaceNodeBoundingClientRect.width - nodeBoundingClientRect.width;
  }
  if(nodeBoundingClientRect.width > workspaceNodeBoundingClientRect.width){
    node.style.width = workspaceNodeBoundingClientRect.width + "px";
  } else if(nodeBoundingClientRect.height > workspaceNodeBoundingClientRect.height){
    node.style.height = workspaceNodeBoundingClientRect.height + "px";
  }
}
let Win = function(Workspace, identifier, title){
  this.data = {
    isFullScreen: false,
    isMinimized: false,
    previousSize: {
      x: null,
      y: null,
      w: null,
      h: null
    },
    dragOffset: {
      x: null,
      y: null
    },
    nodes: {}
  };
  this.setWorkspace(Workspace);
  this.setIdentifier(identifier);
  this.setTitle(title);
  this.setNode(document.createElement("div"));
  this.getNode().setAttribute("id", identifier);
  this.addEventListeners();
}
Win.prototype.setTask = function(Task){
  this.data.Task = Task;
}
Win.prototype.getTask = function(){
  return this.data.Task;
}
Win.prototype.setDragOffset = function(offset){
  this.data.dragOffset = offset;
}
Win.prototype.getDragOffset = function(){
  return this.data.dragOffset;
}
Win.prototype.setWorkspace = function(Workspace){
  this.data.Workspace = Workspace
}
Win.prototype.getWorkspace = function(){
  return this.data.Workspace;
}
Win.prototype.setIdentifier = function(identifier){
  this.data.identifier = identifier
}
Win.prototype.getIdentifier = function(){
  return this.data.identifier;
}
Win.prototype.setNode = function(node){
  this.data.nodes.node = node;

  this.getNode().className = "desktop-window";
  this.getNode().win = this;

  this.setTitleBarNode(document.createElement("div"));
  this.setContentNode(document.createElement("div"));
}

Win.prototype.getNode = function(){
  return this.data.nodes.node;
}
Win.prototype.setTitleBarNode = function(node){
  node.className = "titlebar";
  this.getNode().appendChild(node);
  this.data.nodes.titlebar = node;

  this.setTitleNode(document.createElement("span"));
  this.setActionsNode(document.createElement("div"));

  this.getTitleBarNode().appendChild(this.getTitleNode());
  this.getTitleBarNode().appendChild(this.getActionsNode());
}
Win.prototype.getTitleBarNode = function(){
  return this.data.nodes.titlebar;
}
Win.prototype.setTitleNode = function(node){
  this.data.nodes.title = node;
  this.getTitleNode().innerHTML = this.getTitle();
}
Win.prototype.getTitleNode = function(node){
  return this.data.nodes.title;
}
Win.prototype.setContentNode = function(node){
  node.className = "content";
  this.getNode().appendChild(node);
  this.data.nodes.content = node;
}
Win.prototype.getContentNode = function(){
  return this.data.nodes.content;
}
Win.prototype.setActionsNode = function(node){
  this.data.nodes.actions = node;
  this.getActionsNode().className = "window-actions";
  this.setMinimizeNode(document.createElement("span"));
  this.setMaximizeNode(document.createElement("span"));
  this.setCloseNode(document.createElement("span"));
}
Win.prototype.getActionsNode = function(){
  return this.data.nodes.actions;
}
Win.prototype.setCloseNode = function(node){
  this.data.nodes.close = node;
  this.getActionsNode().appendChild(this.getCloseNode());
  this.getCloseNode().setAttribute("title", "close");
  this.getCloseNode().win = this;
  this.getCloseNode().className = "window-action close";
  // this.getCloseNode().innerHTML = "close";
}
Win.prototype.getCloseNode = function(node){
  return this.data.nodes.close;
}
Win.prototype.setMinimizeNode = function(node){
  this.data.nodes.minimize = node;
  this.getActionsNode().appendChild(this.getMinimizeNode());
  this.getMinimizeNode().setAttribute("title", "minimize");
  this.getMinimizeNode().win = this;
  this.getMinimizeNode().className = "window-action minimize";
}
Win.prototype.getMinimizeNode = function(node){
  return this.data.nodes.minimize;
}
Win.prototype.setMaximizeNode = function(node){
  this.data.nodes.maximize = node;
  this.getActionsNode().appendChild(this.getMaximizeNode());
  this.getMaximizeNode().setAttribute("title", "maximize");
  this.getMaximizeNode().win = this;
  this.getMaximizeNode().className = "window-action maximize";
}
Win.prototype.getMaximizeNode = function(node){
  return this.data.nodes.maximize;
}
Win.prototype.setTitle = function(title){
  this.data.title = title;
}
Win.prototype.getTitle = function(){
  return this.data.title;
}
Win.prototype.setUrl = function(url){
  this.data.url = url;
}
Win.prototype.getUrl = function(){
  return this.data.url;
}
Win.prototype.close = function(e){
  this.getWorkspace().closeWindow(this);
}
Win.prototype.minimize = function(e){
  if(this.data.isMinimized){
    this.getNode().classList.remove("minimized");
    this.data.isMinimized = false;
    this.getWorkspace().getDesktop().focusWindow(this);
  } else {
    this.getNode().classList.add("minimized");
    this.getWorkspace().focusWindow(null);
    this.data.isMinimized = true;
  }
}
Win.prototype.maximize = function(e){
  if(!this.data.isFullScreen){
    if(this.data.isMinimized){
      this.minimize();
    }
    if(!this.hasFocus()){
      this.getWorkspace().focusWindow(this);
    }
    let computed = getComputedStyle(this.getNode());
    let workspaceNode = this.getWorkspace().getNode();
    let workspaceNodeBoundingClientRect = workspaceNode.getBoundingClientRect();
    this.data.previousSize = {
      x: computed.getPropertyValue("left"),
      y: computed.getPropertyValue("top"),
      w: computed.getPropertyValue("width"),
      h: computed.getPropertyValue("height")
    };
    let node = this.getNode();
    node.style.left = 0;
    node.style.top = 0;
    node.style.width = workspaceNodeBoundingClientRect.width;
    node.style.height = workspaceNodeBoundingClientRect.height;
    this.data.isFullScreen = true;
  } else {
    let node = this.getNode();
    node.style.left = this.data.previousSize.x;
    node.style.top = this.data.previousSize.y;
    node.style.width = this.data.previousSize.w;
    node.style.height = this.data.previousSize.h;
    this.data.isFullScreen = false;
  }
}
Win.prototype.hasFocus = function(){
  return this.getNode().classList.contains("focus");
}
Win.prototype.focusIn = function(){
  this.getNode().classList.add("focus");
}
Win.prototype.focusOut = function(){
  this.getNode().classList.remove("focus");
}
Win.prototype.loadFromURL = function(url){
  this.setUrl(url);
  return fetch(url)
    .then(function(res){
      return res.text();
    }.bind(this))
    .then(function(text){
      this.getContentNode().innerHTML = text;
    }.bind(this))
}
Win.prototype.addEventListeners = function(){
  this.getNode().addEventListener("click", function(e){
    if(!this.hasFocus()){
      this.getWorkspace().focusWindow(this);
    }
  }.bind(this))
  this.getCloseNode().addEventListener("click", function(e){
    e.preventDefault(); e.stopPropagation();
    this.close();
  }.bind(this));
  this.getMinimizeNode().addEventListener("click", function(e){
    e.preventDefault(); e.stopPropagation();
    this.minimize();
  }.bind(this));
  this.getMaximizeNode().addEventListener("click", function(e){
    e.preventDefault(); e.stopPropagation();
    this.maximize();
  }.bind(this));
  this.getNode().on("click", "form button", function(e){
    e.preventDefault(); e.stopPropagation();
    let data = this.form.serialize();
    let params = { method: this.form.method };
    let url = this.form.action;
    if(this.form.method.toLowerCase() == "get"){
      if(url.indexOf("?") !== -1){
        url = url.substring(0, url.indexOf("?"))
      }
      url += "?" + data;
      if(this.name.length > 0){
        url += "&" + encodeURI(this.name) + "=" + encodeURI(this.value);
      }
    } else {
      if(this.name.length > 0){
        data += "&" + encodeURI(this.name) + "=" + encodeURI(this.value);
      }
      params.body = data;
      params.headers = new Headers({
        'Content-Type': 'application/x-www-form-urlencoded'
      });
    }
    fetch(url, params)
      .then(function(res){
        return res.text()
      })
      .then(function(text){
        let win = this.closest('.desktop-window').win;
        win.getContentNode().innerHTML = text;
      }.bind(this));
  });
  this.getNode().on("submit", "form", function(e){
    e.preventDefault(); e.stopPropagation();
    let data = this.serialize();
    let params = { method: this.method };
    let url = this.action;
    if(this.method.toLowerCase() == "get"){
      if(url.indexOf("?") !== -1){
        url = url.substring(0, url.indexOf("?"))
      }
      url += "?" + data;
    } else {
      params.body = data;
      params.headers = new Headers({
        'Content-Type': 'application/x-www-form-urlencoded'
      });
    }
    fetch(url, params)
      .then(function(res){
        return res.text()
      })
      .then(function(text){
        let win = this.closest('.desktop-window').win;
        win.getContentNode().innerHTML = text;
      }.bind(this));
  });
}
