(function(global){
  var Element;
  var ElementPrototype;
  var matches;
  if (Element = global.Element) {
    ElementPrototype = Element.prototype;
    if (!(matches = ElementPrototype.matches)) {
      if ((
        matches = ElementPrototype.matchesSelector ||
          ElementPrototype.mozMatchesSelector ||
          ElementPrototype.msMatchesSelector ||
          ElementPrototype.oMatchesSelector ||
          ElementPrototype.webkitMatchesSelector ||
          (ElementPrototype.querySelectorAll && function matches(selectors) {
            var element = this;
            var nodeList = (element.parentNode || element.document || element.ownerDocument).querySelectorAll(selectors);
            var index = nodeList.length;

            while (--index >= 0 && nodeList.item(index) !== element) {}

            return index > -1;
          })
      )) {
        ElementPrototype.matches = matches;
      }
    }
    if (!ElementPrototype.closest && matches) {
      ElementPrototype.closest = function closest(selectors) {
        var element = this;

        while (element) {
          if (element.nodeType === 1 && element.matches(selectors)) {
            return element;
          }

          element = element.parentNode;
        }

        return null;
      };
    }
  }
}(Function('return this')()));

if(typeof Element.prototype.matches === "undefined"){

}
if(typeof Element.prototype.closest === "undefined"){

}
if(typeof NodeList.prototype.forEach === "undefined"){
  NodeList.prototype.forEach = Array.prototype.forEach;
}
