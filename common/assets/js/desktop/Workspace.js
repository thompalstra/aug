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
Workspace.prototype.openDialog = function(url){
  let dialog = new Dialog(this, url);
}
Workspace.prototype.openWindow = function(url){
  let identifier = this.createIdentifier(url);
  let win = this.addWindow(new Win(this, identifier));
  this.getDesktop().getTaskbar().closeMenu();
  this.getDesktop()
    .getTaskbar()
    .addTaskByWindow(win);
  return win.loadFromURL(url)
    .then(function(){
      this.getNode().appendChild(win.getNode());
      this.getDesktop().focusWindow(win);
      win.getNode().dispatchEvent(new CustomEvent("window-loaded", {cancelable: false, bubbles: false}));
    }.bind(this));
}
Workspace.prototype.removeWindow = function(win){
  win.getNode().remove();
  this.getWindowsMap().delete(win.getIdentifier());
  win = null;
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
let Win = function(Workspace, identifier){
  this.data = {
    maximized: false,
    minimized: false,
    previousSize: new Rectangle(null, null, null, null),
    dragOffset: {
      x: null,
      y: null
    },
    nodes: {}
  };
  this.setWorkspace(Workspace);
  this.setIdentifier(identifier);
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
  this.getTitleNode().innerHTML = title;
  this.getTask().getTitleNode().innerHTML = title;
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
Win.prototype.setToolStrip = function(node){
  this.data.ToolStrip = new ToolStrip(this, node);
}
Win.prototype.getToolstrip = function(){
  return this.data.ToolStrip;
}
Win.prototype.setMaximized = function(maximized){
  this.data.maximized = maximized;
}
Win.prototype.getMaximized = function(){
  return this.data.maximized;
}
Win.prototype.setMinimized = function(minimized){
  this.data.minimized = minimized;
}
Win.prototype.getMinimized = function(){
  return this.data.minimized;
}
Win.prototype.toggleMinimize = function(e){
  if(this.getMinimized()){
    this.getNode().classList.remove("minimized");
    this.setMinimized(false);
    this.getWorkspace().getDesktop().focusWindow(this);
  } else {
    this.getNode().classList.add("minimized");
    this.getWorkspace().focusWindow(null);
    this.setMinimized(true);
  }
}
Win.prototype.toggleMaximize = function(e){
  if(this.getMaximized() == false){
    if(this.getMinimized() == true){
      this.toggleMinimize();
    }
    if(!this.hasFocus()){
      this.getWorkspace().focusWindow(this);
    }
    let computed = getComputedStyle(this.getNode());
    let workspaceNode = this.getWorkspace().getNode();
    let workspaceNodeBoundingClientRect = workspaceNode.getBoundingClientRect();

    this.data.previousSize = new Rectangle(
      parseInt(computed.getPropertyValue("left")),
      parseInt(computed.getPropertyValue("top")),
      parseInt(computed.getPropertyValue("width")),
      parseInt(computed.getPropertyValue("height"))
    );
    let node = this.getNode();
    node.style.left = 0;
    node.style.top = 0;
    node.style.width = workspaceNodeBoundingClientRect.width + "px";
    node.style.height = workspaceNodeBoundingClientRect.height + "px";
    this.setMaximized(true);
  } else {
    let node = this.getNode();
    console.log(this.data.previousSize.getPosition());
    node.style.left = this.data.previousSize.getPosition().getX() + "px";
    node.style.top = this.data.previousSize.getPosition().getY() + "px";
    node.style.width = this.data.previousSize.getSize().getWidth() + "px";
    node.style.height = this.data.previousSize.getSize().getHeight() + "px";
    this.setMaximized(false);
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
  return fetch(url, { referrer: this.getUrl() })
    .then(function(res){
      this.setUrl(res.url);
      return res.text();
    }.bind(this))
    .then(function(text){
      this.getContentNode().innerHTML = text;
      this.getContentNode().querySelectorAll("script").forEach(function(originalScriptNode){
        eval(originalScriptNode.innerHTML);
      }.bind(this));
    }.bind(this))
}
Win.prototype.reload = function(){
  this.loadFromURL(this.getUrl());
}
Win.prototype.addEventListeners = function(){
  this.getNode().addEventListener("window-close", function(event){
    event.preventDefault(); event.stopPropagation();
    this.close();
  }.bind(this))
  this.getNode().addEventListener("window-reload", function(event){
    event.preventDefault(); event.stopPropagation();
    this.reload();
  }.bind(this))
  this.getNode().addEventListener("click", function(event){
      this.getWorkspace().focusWindow(this);
  }.bind(this))
  this.getCloseNode().addEventListener("click", function(event){
    event.preventDefault(); event.stopPropagation();
    this.close();
  }.bind(this));
  this.getMinimizeNode().addEventListener("click", function(event){
    event.preventDefault(); event.stopPropagation();
    this.toggleMinimize();
  }.bind(this));
  this.getMaximizeNode().addEventListener("click", function(event){
    event.preventDefault(); event.stopPropagation();
    this.toggleMaximize();
  }.bind(this));
  this.getNode().on("click", "a", function(event){
    if(!this.hasAttribute("data-on") && this.dataset.on !== "click"){
      event.preventDefault(); event.stopPropagation();
      let win = this.closest('.desktop-window').win;
      win.loadFromURL(this.href);
    }
  });
  this.getNode().on("click", "form button", function(event){
    event.preventDefault(); event.stopPropagation();
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
        return res.text();
      }.bind(this))
      .then(function(text){
        let win = this.closest('.desktop-window').win;
        win.getContentNode().innerHTML = text;
        this.getContentNode().querySelectorAll("script").forEach(function(originalScriptNode){
          eval(originalScriptNode.innerHTML);
        }.bind(this));
      }.bind(this));
  });
  this.getNode().on("submit", "form", function(event){
    event.preventDefault(); event.stopPropagation();
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
        if(res.redirected){
          location.href = res.redirected;
          return;
        }
        return res.text();
      })
      .then(function(text){
        let win = this.closest('.desktop-window').win;
        win.getContentNode().innerHTML = text;
        win.getContentNode().querySelectorAll("script").forEach(function(originalScriptNode){
          eval(originalScriptNode.innerHTML);
        }.bind(win));
      }.bind(this));
  });
}
let ToolStrip = function(win, node){
  this.data = { nodes: { node: null }, Win: null };
  this.setNode(node);
  this.setWindow(win);
  this.addEventListeners();
}
ToolStrip.prototype.setNode = function(node){
  this.data.nodes.node = node;
}
ToolStrip.prototype.getNode = function(){
  return this.data.nodes.node;
}
ToolStrip.prototype.setWindow = function(win){
  this.data.win = win;
}
ToolStrip.prototype.getWindow = function(win){
  return this.data.win;
}
ToolStrip.prototype.addEventListeners = function(){
  this.getNode().find(".has-children").on("click", function(event){
    this.classList.toggle("open");
  });
  this.getNode().find(".has-children").on("mouseleave", function(event){
    this.classList.remove("open");
  });
}
let Dialog = function(Workspace, href){
  this.data = { nodes: { node: null, backdrop: null }, Workspace: null };
  this.setWorkspace(Workspace);
  this.setNode(document.createElement("div"));
  this.setBackdropNode(document.createElement("div"));
  this.addEventListeners();
  return this.loadFromURL(href)
    .then(function(){
    }.bind(this));
}
Dialog.prototype.setNode = function(node){
  this.data.nodes.node = node;
  this.getNode().Dialog = this;
  this.getWorkspace().getNode().appendChild(this.getNode());
  this.getNode().classList.add("dialog-default");
}
Dialog.prototype.getNode = function(){
  return this.data.nodes.node;
}
Dialog.prototype.setBackdropNode = function(node){
  this.data.nodes.backdrop = node;
  this.getBackdropNode().insertAfter(this.getNode());
  this.getBackdropNode().classList.add("dialog-backdrop");
}
Dialog.prototype.getBackdropNode = function(){
  return this.data.nodes.backdrop;
}
Dialog.prototype.setWorkspace = function(Workspace){
  this.data.Workspace = Workspace;
}
Dialog.prototype.getWorkspace = function(){
  return this.data.Workspace;
}
Dialog.prototype.loadFromURL = function(url){
  return fetch(url)
    .then(function(res){
      if(res.redirected){
        location.href = res.url;
      } else {
        return res.text();
      }
    }.bind(this))
    .then(function(text){
      this.getNode().innerHTML = text;
      this.getNode().querySelectorAll("script").forEach(function(originalScriptNode){
        eval(originalScriptNode.innerHTML);
      }.bind(this));
      this.getNode().do("dialog-show");
    }.bind(this));
}
Dialog.prototype.addEventListeners = function(){
  this.getNode().on("dialog-show", function(event){
    event.preventDefault(); event.stopPropagation();
    this.getNode().classList.add("open");
  }.bind(this))
  this.getNode().on("dialog-hide", function(event){
    event.preventDefault(); event.stopPropagation();
    this.getBackdropNode().remove();
    this.getNode().remove();
  }.bind(this));
  this.getBackdropNode().on("click", function(event){
    this.getNode().do("dialog-hide");
  }.bind(this));
  this.getNode().on("click", "a", function(event){
    if(!this.hasAttribute("data-on") && this.dataset.on !== "click"){
      event.preventDefault(); event.stopPropagation();
      let Dialog = this.closest('.dialog-default').Dialog;
      Dialog.loadFromURL(this.href);
    }
  });
  this.getNode().on("click", "form button[type='submit']", function(event){
    event.preventDefault(); event.stopPropagation();
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
        return new Promise(function(resolve, reject){
          if(res.redirected){
            location.href = res.url;
            reject("Cancelled promise due to redirect");
          } else {
            resolve(res.text());
          }
        }.bind(this));
      }.bind(this))
      .then(function(text){
        let Dialog = this.closest('.dialog-default').Dialog;
        Dialog.getNode().innerHTML = text;
        Dialog.getNode().querySelectorAll("script").forEach(function(originalScriptNode){
          eval(originalScriptNode.innerHTML);
        }.bind(this));
      }.bind(this))
      .catch(function(e){
        console.info(e);
      });
  });
  this.getNode().on("submit", "form", function(event){
    event.preventDefault(); event.stopPropagation();
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
        return new Promise(function(resolve, reject){
          if(res.redirected){
            location.href = res.url;
            reject("Cancelled promise due to redirect");
          } else {
            resolve(res.text());
          }
        }.bind(this));
      })
      .then(function(text){
        let Dialog = this.closest('.dialog-default').Dialog;
        Dialog.getNode().innerHTML = text;
        Dialog.getNode().querySelectorAll("script").forEach(function(originalScriptNode){
          eval(originalScriptNode.innerHTML);
        }.bind(win));
      }.bind(this))
      .catch(function(e){
        console.info(e);
      });
  });
}
