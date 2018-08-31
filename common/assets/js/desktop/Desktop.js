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
      contextMenu.getNode().style.left = (event.data.originalEvent.pageX - 5) + "px";
      contextMenu.getNode().style.top = (event.data.originalEvent.pageY - contextMenu.getNode().getBoundingClientRect().height + 5) + "px";
  }.bind(this));

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

let ContextMenu = function(Desktop, event){
  this.data = { nodes: { node: null, parent: null }, event: null };
  this.setDesktop(Desktop);
  this.setEvent(event);
  this.setNode(document.createElement("ul"));
  this.setMenuItems(event.data.items);
  this.setParentNode(event.target);
  this.getDesktop().getNode().appendChild(this.getNode());
  this.addEventListeners();
}
ContextMenu.prototype.setParentNode = function(parent){
  this.data.nodes.parent = parent;
}
ContextMenu.prototype.getParentNode = function(){
  return this.data.nodes.parent;
}
ContextMenu.prototype.setNode = function(node){
  this.data.nodes.node = node;
  this.getNode().className = "contextmenu";
}
ContextMenu.prototype.getNode = function(){
  return this.data.nodes.node;
}
ContextMenu.prototype.setDesktop = function(Desktop){
  this.data.Desktop = Desktop;
}
ContextMenu.prototype.getDesktop = function(){
  return this.data.Desktop;
}
ContextMenu.prototype.setMenuItems = function(menuItems){
  this.data.menuItems = menuItems;
  this.createMenuItems(this.getNode(), menuItems);
}
ContextMenu.prototype.createMenuItems = function(node, items){
  for(let i = 0; i < items.length; i++){
    let item = items[i];
    let menuNode = document.createElement("li");
    if(typeof item.action !== "undefined"){
      menuNode.dataset.action = item.action;
      menuNode.addEventListener("click", function(event){
        event.preventDefault();
        event.stopPropagation();
        let customEvent = new CustomEvent(menuNode.dataset.action, {
          bubbles: true,
          cancelable: true
        });
        this.getParentNode().dispatchEvent(customEvent);
        this.getNode().remove();
      }.bind(this));
    }
    let title = menuNode.appendChild(document.createElement("span"));
    title.innerHTML = item.label;
    if(typeof item.items !== "undefined"){
      menuNode.classList.add("has-children");
      let menuNodeList = menuNode.appendChild(document.createElement("ul"));
      this.createMenuItems(menuNodeList, item.items);
    }
    node.appendChild(menuNode);
  }
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
    // this.getNode().remove();
  }.bind(this));
}
ContextMenu.prototype.close = function(){
  this.data.nodes.node.remove();
}

var Position = function(x, y){
  this.data = { x: null, y: null };
}
Position.prototype.setX = function(x){
  this.data.x = x;
}
Position.prototype.getX = function(x){
  return this.data.x;
}
Position.prototype.setY = function(x){
  this.data.y = y;
}
Position.prototype.getY = function(x){
  return this.data.y;
}
var Size = function(width, height){
  this.data = { width: null, height: null };
  this.setWidth(width);
  this.setHeight(height);
}
Size.prototype.setWidth = function(width){
  this.data.width = width;
}
Size.prototype.getWidth = function(width){
  return this.data.width;
}
Size.prototype.setHeight = function(height){
  this.data.height = height;
}
Size.prototype.getHeight = function(height){
  return this.data.height;
}
var Rectangle = function(x, y, width, height){
  this.data = { Position: null, Size: null };
  this.data.Position = new Position(x, y);
  this.data.Size = new Size(width, height);
}
Rectangle.prototype.setPosition = function(x, y){
  this.data.Position = new Position(x, y);
}
Rectangle.prototype.getPosition = function(){
  return this.data.Position;
}
Rectangle.prototype.setSize = function(width, height){
  this.data.Size = new Size(width, height);
}
Rectangle.prototype.getSize = function(){
  return this.data.Size;
}
