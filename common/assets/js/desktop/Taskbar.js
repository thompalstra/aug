let Taskbar = function(node, Desktop){
  this.setNode(node);
  this.setDesktop(Desktop);
  this.setTasksMap(new Map());
  this.addEventListeners();
}
Taskbar.prototype.data = {};
Taskbar.prototype.setNode = function(node){
  node.Taskbar = this;
  this.data.node = node;
}
Taskbar.prototype.getNode = function(){
  return this.data.node;
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
  this.getNode().appendChild(task.getNode());
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
Taskbar.prototype.addEventListeners = function(){
  this.getNode().on("click", "li.has-children", function(e){
    this.classList.toggle("open");
  });
}
Taskbar.prototype.closeMenu = function(){
  this.getNode().querySelectorAll("li.open:not(.taskbar-task)").forEach(function(node){
    node.classList.remove("open");
  })
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
  this.getNode().Task = this;
  this.getNode().className = "taskbar-task";
  this.setTitleNode(document.createElement("span"));
}
Task.prototype.getNode = function(){
  return this.data.nodes.node;
}
Task.prototype.removeTaskContextMenu = function(){
  this.getTaskContextMenu().getNode().remove();
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
Task.prototype.setTaskContextMenu = function(TaskContextMenu){
  this.data.TaskContextMenu = TaskContextMenu;
}
Task.prototype.getTaskContextMenu = function(){
  return this.data.TaskContextMenu;
}
Task.prototype.addContextMenu = function(event){
  this.setTaskContextMenu(new TaskContextMenu(this, event));
  this.getNode().appendChild(this.getTaskContextMenu().getNode());
  let taskContextMenu = this.getTaskContextMenu();
  taskContextMenu.getNode().style.left = (taskContextMenu.getEvent().pageX - 5) + "px";
  taskContextMenu.getNode().style.top = (taskContextMenu.getEvent().pageY - taskContextMenu.getNode().getBoundingClientRect().height + 5) + "px";
}
Task.prototype.setTaskContextMenu = function(TaskContextMenu){
  this.data.TaskContextMenu = TaskContextMenu;
}
Task.prototype.getTaskContextMenu = function(){
  return this.data.TaskContextMenu;
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
  this.getNode().addEventListener("contextmenu", function(event){
    event.preventDefault();
    this.addContextMenu(event);
  }.bind(this));
}
var TaskContextMenu = function(Task, event){
  this.data = { nodes: { node: null }, Task: null, event: null };
  this.setTask(Task);
  this.setEvent(event);
  this.setNode(document.createElement("ul"));
  this.setMenuItems([
    {
      action: "ensureVisible",
      label: "Bring into view"
    },
    {
      action: "close",
      label: "Close"
    }
  ]);
  this.addEventListeners();
}
TaskContextMenu.prototype.setNode = function(node){
  this.data.nodes.node = node;
  this.getNode().className = "task-contextmenu";
}
TaskContextMenu.prototype.getNode = function(){
  return this.data.nodes.node;
}
TaskContextMenu.prototype.setTask = function(Task){
  this.data.Task = Task;
}
TaskContextMenu.prototype.getTask = function(){
  return this.data.Task;
}
TaskContextMenu.prototype.setMenuItems = function(menuItems){
  this.data.menuItems = menuItems;
  menuItems.forEach(function(menuItem){
    let node = document.createElement("li");
    node.dataset.action = menuItem.action;
    let title = node.appendChild(document.createElement("span"));
    title.innerHTML = menuItem.label;
    node.addEventListener("click", function(event){
      event.preventDefault();
      event.stopPropagation();
      let win = this.getTask().getWindow();
      let workspace = win.getWorkspace();
      if(typeof win[node.dataset.action] === "function"){
        win[node.dataset.action].apply(win, []);
      } else if(typeof workspace[node.dataset.action] === "function"){
        workspace[node.dataset.action].apply(workspace, [win]);
      }
      this.getTask().removeTaskContextMenu();
    }.bind(this))
    this.getNode().appendChild(node);
  }.bind(this));
}
TaskContextMenu.prototype.getMenuItems = function(){
  return this.data.menuItems;
}
TaskContextMenu.prototype.setEvent = function(event){
  this.data.event = event;
}
TaskContextMenu.prototype.getEvent = function(event){
  return this.data.event;
}
TaskContextMenu.prototype.addEventListeners = function(){
  this.getNode().addEventListener("mouseleave", function(event){
    this.getTask().removeTaskContextMenu();
  }.bind(this));
}
