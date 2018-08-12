document.on("click", ".tabcontrol .controls a", function(e){
  e.preventDefault();
  var tabcontrol = this.closest(".tabcontrol");
  tabcontrol.find(".tabs .tab.active").forEach(function(tab){
    tab.classList.remove("active");
  });
  tabcontrol.find(".controls .active").forEach(function(a){
    a.classList.remove("active");
  });
  var tab = tabcontrol.one(this.getAttribute("href"));
  if(tab){
    tab.classList.add("active");
    this.parentNode.classList.add("active");
  }
})
