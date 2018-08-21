// var Desktop = function(node){
//   this.node = node;
//   this.node.Desktop = this;
//   this.DesktopItems = new DesktopItems(this, this.node.querySelector(".desktop-shortcuts"));
//   this.DesktopTaskbar = new DesktopTaskbar(this, this.node.querySelector(".desktop-taskbar"));
//   this.addEventListeners();
// }
// Desktop.prototype.addEventListeners = function(){
//   this.node.on("click", ".desktop-window-open", function(event){
//     event.preventDefault();
//     event.stopPropagation();
//     let gridItem = event.target.matches(".desktop-window-open") ? event.target : event.target.closest(".desktop-window-open");
//     let href = gridItem.getAttribute("href");
//     let identifier = "desktop-window-" + href.replace(/\//g, "-").replace(/\./g,"-");
//     if(this.DesktopItems.windowExists(identifier)){
//       this.DesktopItems.focusWindow(identifier);
//       this.DesktopTaskbar.focusTask(identifier);
//     } else {
//       this.DesktopItems.openWindow(href, identifier);
//       this.DesktopTaskbar.openTask(identifier);
//     }
//   }.bind(this));
//   this.node.addEventListener("mousedown", function(e){
//     if(e.target.matches(".window-actions") || e.target.closest(".window-actions")){
//       return;
//     }
//     if(e.target.matches(".window-bar") || e.target.closest(".window-bar")){
//       e.preventDefault();
//       e.stopPropagation();
//
//       let node = e.target.closest(".desktop-window");
//       if(node){
//         let win = node.DesktopWindow;
//         this.DesktopItems.setDragWindow(event, win);
//         this.DesktopItems.focusWindow(win.getIdentifier());
//         this.DesktopTaskbar.focusTask(win.getIdentifier());
//       }
//     }
//   }.bind(this));
//   this.node.addEventListener("mousemove", function(e){
//       this.DesktopItems.moveDragWindow(e);
//   }.bind(this));
//   this.node.addEventListener("mouseup", function(e){
//     if(e.target.matches(".window-actions") || e.target.closest(".window-actions")){
//       return;
//     }
//     if(e.target.matches(".window-bar") || e.target.closest(".window-bar")){
//       e.preventDefault();
//       e.stopPropagation();
//       this.DesktopItems.unsetDragWindow();
//     }
//   }.bind(this));
// }
// Desktop.prototype.openWindow = function(href, identifier){
//   return this.DesktopItems.openWindow(this, href, identifier);
// }
//
// var DesktopTaskbar = function(Desktop, node){
//   this.node = node;
//   this.node.DesktopTaskbar = this;
//   this.Desktop = Desktop;
//
//   this.items = new Map();
// }
// DesktopTaskbar.prototype.openTask = function(identifier){
//   let DesktopWindow = this.Desktop.DesktopItems.windows.get(identifier);
//   console.log(DesktopWindow);
//   let task = new DesktopTask(this, DesktopWindow);
//   task.setIdentifier(identifier);
//   this.items.set(identifier, task);
//   this.node.appendChild(task.node);
// }
// DesktopTaskbar.prototype.removeTask = function(identifier){
//   let task = this.items.get(identifier);
//   task.node.remove();
//   this.items.delete(identifier);
// }
// DesktopTaskbar.prototype.focusTask = function(identifier){
//
// }
// var DesktopTask = function(DesktopTaskbar, DesktopWindow){
//   this.node = document.createElement("li");
//   this.node.innerHTML = "my window";
//   this.DesktopTaskbar = DesktopTaskbar;
//   this.DesktopWindow = DesktopWindow;
//   this.addEventListeners();
// }
// DesktopTask.prototype.setIdentifier = function(identifier){
//   this.identifier = identifier;
//   this.node.setAttribute("id", identifier);
// }
// DesktopTask.prototype.getIdentifier = function(){
//   return this.identifier;
// }
// DesktopTask.prototype.addEventListeners = function(){
//   this.node.addEventListener("click", function(event){
//     this.DesktopWindow.minimize();
//   }.bind(this));
// }
// var DesktopItems = function(Desktop, node){
//   this.node = node;
//   this.node.DesktopItems = this;
//   this.Desktop = Desktop;
//
//   this.windows = new Map();
// }
// DesktopItems.prototype.windowExists = function(identifier){
//   return (typeof this.windows.get(identifier) == "undefined") ? false : true;
// }
// let map;
// DesktopItems.prototype.focusWindow = function(identifier){
//   if(this.windowExists(identifier)){
//     if(!this.windows.get(identifier).hasFocus()){
//       this.windows.forEach(function(entry, entryIdentifier){
//         if(entryIdentifier == identifier){
//           entry.focusin();
//         } else {
//           entry.focusout();
//         }
//       }.bind(this));
//     }
//   } else {
//     console.warn("Attempting to `focus` a non-existant window: " + identifier);
//   }
// }
// DesktopItems.prototype.openWindow = function(href, identifier){
//   let win = new DesktopWindow(this);
//   win.setIdentifier(identifier);
//   win.setContentFromURL(href);
//   this.windows.set(identifier, win);
//   this.node.appendChild(win.node);
//   this.focusWindow(identifier);
// }
// DesktopItems.prototype.removeWindow = function(identifier){
//   let win = this.windows.get(identifier);
//   win.node.remove();
//   this.windows.delete(identifier);
// }
// DesktopItems.prototype.moveDragWindow = function(event){
//   if(this.dragWindow){
//     // event.preventDefault();
//     // event.stopPropagation();
//     let node = this.dragWindow.node;
//     let x = event.clientX - this.dragWindowOffset.x;
//     let y = event.clientY - this.dragWindowOffset.y;
//     node.style.left = x + "px";
//     node.style.top = y + "px";
//   }
// }
// DesktopItems.prototype.setDragWindow = function(event, DesktopWindow){
//   this.dragWindow = DesktopWindow;
//   this.dragWindowOffset = {
//     x: event.offsetX,
//     y: event.offsetY,
//   };
// }
// DesktopItems.prototype.unsetDragWindow = function(DesktopWindow){
//   this.dragWindow = null;
// }
// var DesktopWindow = function(DesktopItems){
//   this.node = document.createElement("div");
//   this.node.className = "desktop-window";
//   this.node.DesktopWindow = this;
//   this.DesktopItems = DesktopItems;
//   this.addEventListeners();
//
//   this.isFullScreen = false;
//   this.isHidden = false;
//   this.coords = {
//     previous : {
//       x: null,
//       y: null,
//       width: null,
//       height: null
//     }
//   };
// }
// DesktopWindow.prototype.close = function(){
//   let identifier = this.identifier;
//   let Desktop = this.DesktopItems.Desktop;
//   Desktop.DesktopTaskbar.removeTask(identifier);
//   Desktop.DesktopItems.removeWindow(identifier);
//   this.node.remove();
// }
// DesktopWindow.prototype.maximize = function(){
//
//   if(this.isFullScreen == false){
//     computed = getComputedStyle(this.node);
//     this.coords.previous = {
//       x: this.node.style.left,
//       y: this.node.style.top,
//       width: computed.getPropertyValue("width"),
//       height: computed.getPropertyValue("height")
//     };
//     this.node.style.left = 0;
//     this.node.style.top = 0;
//     this.node.style.width = window.innerWidth;
//     this.node.style.height = window.innerHeight;
//     this.isFullScreen = true;
//   } else {
//     this.node.style.left = this.coords.previous.x;
//     this.node.style.top = this.coords.previous.y;
//     this.node.style.width = this.coords.previous.width;
//     this.node.style.height = this.coords.previous.height;
//     this.isFullScreen = false;
//   }
// }
// DesktopWindow.prototype.minimize = function(){
//   if(this.isHidden == false){
//     this.node.classList.remove("minimize-in");
//     this.node.classList.add("minimize-out");
//     this.isHidden = true;
//   } else {
//     this.node.classList.add("minimize-in");
//     this.node.classList.remove("minimize-out");
//     this.isHidden = false;
//   }
// }
// DesktopWindow.prototype.addEventListeners = function(){
//   this.node.on("click", ".window-action.close", function(event){
//     event.preventDefault(); event.stopPropagation();
//     this.close();
//   }.bind(this));
//   this.node.on("click", ".window-action.minimize", function(event){
//     event.preventDefault(); event.stopPropagation();
//     this.minimize();
//   }.bind(this));
//   this.node.on("click", ".window-action.maximize", function(event){
//     event.preventDefault(); event.stopPropagation();
//     this.maximize();
//   }.bind(this));
//   this.node.on("click", function(event){
//     if(!this.hasFocus()){
//       this.DesktopItems.focusWindow(this.identifier);
//     }
//   }.bind(this));
// }
// DesktopWindow.prototype.hasFocus = function(){
//   return this.node.classList.contains("focus");
// }
// DesktopWindow.prototype.focusout = function(){
//   this.node.classList.remove("focus");
// }
// DesktopWindow.prototype.focusin = function(){
//   this.node.classList.add("focus");
// }
// DesktopWindow.prototype.setIdentifier = function(identifier){
//   this.identifier = identifier;
//   this.node.setAttribute("id", identifier);
// }
// DesktopWindow.prototype.getIdentifier = function(){
//   return this.identifier;
// }
// DesktopWindow.prototype.setContentFromURL = function(href){
//   fetch(href)
//     .then(function(res){
//       return res.text()
//     })
//     .then(function(text){
//       this.node.innerHTML = text;
//       this.addEventListeners();
//     }.bind(this))
// }
// document.on("submit click", ".desktop-window form", function(event){
//   if(event.type == "submit" || (event.type == "click" && event.target.matches("button") ? event.target : event.target.closest("button"))){
//     event.preventDefault();
//     let form = this;
//     let elements = form.elements;
//     let formData = new FormData();
//     let queryString = [];
//     for(let i = 0; i < elements.length; i++){
//       let element = elements[i];
//       if(element.tagName.toLowerCase() !== "button"){
//         formData.append(element.name, element.value);
//         queryString.push(element.name + "=" + element.value);
//       }
//     }
//     if(event.type == "click"){
//       let button = event.target.matches("button") ? event.target : event.target.closest("button");
//       if(button){
//         formData.append(button.name, button.value);
//         queryString.push(button.name + "=" + button.value);
//       }
//     }
//     console.log(queryString);
//     let params = { method: form.method };
//     let url = form.action;
//
//     if(form.method.toLowerCase() == "post"){
//       params.body = formData;
//     } else {
//       if(url.indexOf("?") !== -1){
//         url = url.substring(0, url.indexOf("?"))
//       }
//       if(queryString){
//         url = url + "?" + queryString.join("&");
//       }
//     }
//     fetch(url, params)
//     .then(res => res.text())
//     .then((text)=>{
//       this.closest(".desktop-window").innerHTML = text;
//     });
//   }
// })
//
//
// document.on("change", ".pagination.pagesize", function(e){
//   this.form.dispatchEvent(new CustomEvent("submit", {
//     cancelable: true,
//     bubbles: true
//   }));
// })
