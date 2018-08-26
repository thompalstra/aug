let Taskbar = function(node, Desktop){
  this.data = { nodes: { node: null, tasks: null }, Desktop: null, map: null };
  this.setNode(node);
  this.setTasksNode(this.getNode().querySelector(".tasks"));
  this.setDesktop(Desktop);
  this.setTasksMap(new Map());
  this.addEventListeners();
}
Taskbar.prototype.setNode = function(node){
  node.Taskbar = this;
  this.data.nodes.node = node;
}
Taskbar.prototype.getNode = function(){
  return this.data.nodes.node;
}
Taskbar.prototype.setTasksNode = function(node){
  this.data.nodes.tasks = node;
}
Taskbar.prototype.getTasksNode = function(){
  return this.data.nodes.tasks;
}
Taskbar.prototype.setDesktop = function(Desktop){
  this.data.Desktop = Desktop;
}
Taskbar.prototype.getDesktop = function(){
  return this.data.Desktop;
}
Taskbar.prototype.setTasksMap = function(map){
  this.data.map = map;
}
Taskbar.prototype.getTasksMap = function(){
  return this.data.map;
}
Taskbar.prototype.addTaskByWindow = function(win){
  let task = new Task(win, this);
  this.getTasksMap().set(win.getIdentifier(), task);
  this.getTasksNode().appendChild(task.getNode());
}
Taskbar.prototype.removeTaskByWindow = function(win){
  this.getTasksMap().get(win.getIdentifier()).getNode().remove();
  this.getTasksMap().delete(win.getIdentifier());
}
Taskbar.prototype.focusTask = function(target){
  this.getTasksMap().forEach(function(task){
    if(target != null && task.getIdentifier() == target.getIdentifier()){
      task.focusIn();
    } else {
      task.focusOut();
    }
  });
}
Taskbar.prototype.unfocusTask = function(target){
  target.focusOut();
}
Taskbar.prototype.closeMenu = function(){
  this.getNode().querySelectorAll("li.open:not(.taskbar-task)").forEach(function(node){
    node.classList.remove("open");
  })
}
Taskbar.prototype.addEventListeners = function(){
  this.getNode().on("click", "li.has-children", function(e){
    let target = this;
    let ul = this.closest("ul");
    ul.querySelectorAll("li.has-children").forEach(function(node){
      if(node == target){
        console.log("toggle");
        node.classList.toggle("open");
      } else {
        node.classList.remove("open");
      }
    })
  });
  console.log(this.getDesktop().getWorkspace().getNode());
  this.getDesktop().getWorkspace().getNode().addEventListener("click", function(event){
    this.closeMenu();
  }.bind(this));
}
let Task = function(win, Taskbar){
  this.data = { nodes: { node: null, title: null }, Window: null, Taskbar: null, title: null };
  this.setWindow(win);
  this.setIdentifier(win.getIdentifier());
  this.setTaskbar(Taskbar);
  win.setTask(this);
  this.setNode(document.createElement("li"));
  this.addEventListeners();
}
Task.prototype.setIdentifier = function(identifier){
  this.data.identifier = identifier
}
Task.prototype.getIdentifier = function(){
  return this.data.identifier;
}
Task.prototype.setNode = function(node){
  this.data.nodes.node = node;
  this.getNode().dataset.on = "contextmenu";
  this.getNode().dataset.do = JSON.stringify({
    action: "open-context",
    params: {
      items: [
        {
          label: "Close",
          action: "close-window"
        },
        {
          label: "Show",
          action: "ensure-visible"
        }
      ]
    }
  });
  this.getNode().Task = this;
  this.getNode().className = "taskbar-task";
  this.setTitleNode(document.createElement("span"));
}
Task.prototype.getNode = function(){
  return this.data.nodes.node;
}
Task.prototype.removeContextMenu = function(){
  this.getContextMenu().getNode().remove();
}
Task.prototype.setTitleNode = function(node){
  this.data.nodes.title = node;
  this.getTitleNode().innerHTML = this.getWindow().getTitle();
  this.getNode().appendChild(node);
}
Task.prototype.getTitleNode = function(){
  return this.data.nodes.title;
}
Task.prototype.setWindow = function(win){
  this.data.Window = win;
}
Task.prototype.getWindow = function(){
  return this.data.Window;
}
Task.prototype.setTaskbar = function(Taskbar){
  this.data.Taskbar = Taskbar;
}
Task.prototype.getTaskbar = function(){
  return this.data.Taskbar;
}
Task.prototype.focusIn = function(){
  this.getNode().classList.add("focus");
}
Task.prototype.focusOut = function(){
  this.getNode().classList.remove("focus");
}
Task.prototype.addEventListeners = function(){
  this.getNode().addEventListener("click", function(event){
    let win = this.getWindow();
    if(!win.hasFocus() && !win.data.isMinimized){
      win.getWorkspace().focusWindow(win);
    } else {
      win.minimize();
    }
  }.bind(this));
  this.getNode().addEventListener("close-window", function(event){
    this.getWindow().close();
  }.bind(this));
  this.getNode().addEventListener("ensure-visible", function(event){
    this.getWindow().getWorkspace().ensureVisible(this.getWindow());
  }.bind(this));
}
