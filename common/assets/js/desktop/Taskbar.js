let Taskbar = function(node, Desktop){
  this.setNode(node);
  this.setDesktop(Desktop);
  this.setTasksMap(new Map());
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

let Task = function(win, Taskbar){
  this.setWindow(win);
  this.setIdentifier(win.getIdentifier());
  this.setTaskbar(Taskbar);
  win.setTask(this);
  this.setNode(document.createElement("li"));
  this.addEventListeners();
}
Task.prototype.data = {};
Task.prototype.setIdentifier = function(identifier){
  this.data.identifier = identifier
}
Task.prototype.getIdentifier = function(){
  return this.data.identifier;
}
Task.prototype.setNode = function(node){
  node.Task = this;
  node.className = "taskbar-task";
  node.innerHTML = this.getWindow().getTitle();
  this.data.node = node;
}
Task.prototype.getNode = function(){
  return this.data.node;
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
  this.getNode().addEventListener("click", function(e){
    let win = this.getWindow();
    win.minimize();
  }.bind(this));
}