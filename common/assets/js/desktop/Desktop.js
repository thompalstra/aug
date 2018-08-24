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
  console.log("focus");
  if(win instanceof Win){
    this.getTaskbar().focusTask(win.getTask());
    this.getWorkspace().focusWindow(win);
  } else {
    this.getTaskbar().focusTask(null);
    this.getWorkspace().focusWindow(null);
  }
}
Desktop.prototype.addEventListeners = function(){
  this.getNode().on("mousedown", ".desktop-window .titlebar span", function(event){
    event.preventDefault(); event.stopPropagation();
    this.parentNode.parentNode.win.getWorkspace().getDesktop().focusWindow(this.parentNode.parentNode.win);
    this.parentNode.parentNode.win.getWorkspace().setDragWindow(this.parentNode.parentNode.win, event);
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
  this.getNode().on("click", ".open-win", function(e){
    e.preventDefault();
    e.stopPropagation();
    let node = this;
    let desktop = this.closest(".desktop").Desktop.getWorkspace().openWindow(this.dataset.href, this.dataset.title);
  });
}
