if(typeof window.Desktop !== "undefined"){
  throw new Error("'Desktop' constructor already exists!");
}

let Desktop = function(node){
  this.setNode(node);
  this.setWorkspace(new Workspace(this.getNode().querySelector(".desktop-workspace"), this));
  this.setTaskbar(new Taskbar(this.getNode().querySelector(".desktop-taskbar"), this));
  this.addEventListeners();
}
Desktop.prototype.data = {};
Desktop.prototype.setNode = function(node){
  node.Desktop = this;
  this.data.node = node;
}
Desktop.prototype.getNode = function(){
  return this.data.node;
}
Desktop.prototype.setWorkspace = function(Workspace){
  this.data.Workspace = Workspace;
}
Desktop.prototype.getWorkspace = function(){
  return this.data.Workspace;
}
Desktop.prototype.setTaskbar = function(Taskbar){
  this.data.Taskbar = Taskbar;
}
Desktop.prototype.getTaskbar = function(){
  return this.data.Taskbar;
}
Desktop.prototype.focusWindow = function(win){
  if(win instanceof Win){
    this.getTaskbar().focusTask(win.getTask());
    this.getWorkspace().focusWindow(win);
  } else {
    this.getTaskbar().focusTask(null);
    this.getWorkspace().focusWindow(null);
  }
}
Desktop.prototype.addEventListeners = function(){
  this.getNode().on("mousedown", ".desktop-window .titlebar > span", function(event){
    event.preventDefault(); event.stopPropagation();
    let node = this.closest(".desktop-window");
    let win = node.win;
    let workspace = win.getWorkspace();
    win.getWorkspace().getDesktop().focusWindow(win);
    win.getWorkspace().setDragWindow(win, event);
  });
  this.getNode().on("mousemove", function(event){
    if(this.Desktop.getWorkspace().getDragWindow()){
      this.Desktop.getWorkspace().moveDragWindow(event);
    }
  });
  this.getNode().on("mouseup", function(e){
    if(this.Desktop.getWorkspace().getDragWindow()){
      this.Desktop.getWorkspace().setDragWindow(null);
    }
  });
  this.getNode().on("open-window", function(event){
    if(typeof event.data === "undefined"){ throw Error("Missing 'data' attributes") }
    if(typeof event.data.href === "undefined"){ throw Error("Missing 'href' attribute") }
    if(typeof event.data.title === "undefined"){ throw Error("Missing 'title' attribute") }
    this.getWorkspace().openWindow(event.data.href, event.data.title);
  }.bind(this));
  this.getNode().on("focus-window", function(event){

  })
  this.getNode().on("open-context", function(event){
    contextMenu = new ContextMenu(this, event);
    event.target.appendChild(contextMenu.getNode());
    contextMenu.getNode().style.left = (event.data.originalEvent.pageX - 5) + "px";
    contextMenu.getNode().style.top = (event.data.originalEvent.pageY - contextMenu.getNode().getBoundingClientRect().height + 5);
  });

  let eventList = ["click", "contextmenu"];
  eventList.forEach(function(eventType){
    this.getNode().on(eventType, '[data-on="'+eventType+'"]', this.onHandler);
  }.bind(this));
}
Desktop.prototype.onHandler = function(event){
  let target = this.dataset.target ? document.querySelector(this.dataset.target) : this;
  let data = JSON.parse(this.dataset.do);
  if(target){
    event.preventDefault();
    event.stopPropagation();
    let customEvent = new CustomEvent(data.action, {
      cancelable: true,
      bubbles: true
    });
    customEvent.data = {};
    customEvent.data.originalEvent = event;
    Object.keys(data.params).forEach(function(key){
      customEvent.data[key] = data.params[key];
    });
    target.dispatchEvent(customEvent);
  } else {
    throw Error("Invalid target specified in data attributes 'target'.");
  }
}

let ContextMenu = function(parent, event){
  this.data = { nodes: { node: null }, Task: null, event: null };
  this.setParent(parent);
  this.setEvent(event);
  this.setNode(document.createElement("ul"));
  this.setMenuItems(event.data.items);
  this.addEventListeners();
}
ContextMenu.prototype.setNode = function(node){
  this.data.nodes.node = node;
  this.getNode().className = "contextmenu";
}
ContextMenu.prototype.getNode = function(){
  return this.data.nodes.node;
}
ContextMenu.prototype.setParent = function(Task){
  this.data.parent = parent;
}
ContextMenu.prototype.getParent = function(){
  return this.data.parent;
}
ContextMenu.prototype.setMenuItems = function(menuItems){
  this.data.menuItems = menuItems;
  menuItems.forEach(function(menuItem){
    let node = document.createElement("li");
    node.dataset.action = menuItem.action;
    let title = node.appendChild(document.createElement("span"));
    title.innerHTML = menuItem.label;
    node.addEventListener("click", function(event){
      event.preventDefault();
      event.stopPropagation();
      let customEvent = new CustomEvent(node.dataset.action, {
        bubbles: true,
        cancelable: true
      });
      this.getNode().dispatchEvent(customEvent);
      this.getNode().remove();
    }.bind(this))
    this.getNode().appendChild(node);
  }.bind(this));
}
ContextMenu.prototype.getMenuItems = function(){
  return this.data.menuItems;
}
ContextMenu.prototype.setEvent = function(event){
  this.data.event = event;
}
ContextMenu.prototype.getEvent = function(event){
  return this.data.event;
}
ContextMenu.prototype.addEventListeners = function(){
  this.getNode().addEventListener("mouseleave", function(event){
    this.getNode().remove();
  }.bind(this));
}
