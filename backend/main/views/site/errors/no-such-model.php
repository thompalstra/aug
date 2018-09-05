<script>
  this.getNode().addEventListener("window-loaded", function(event){
    alert("The model you're trying to view is not available.\nTry again later.");
    this.close();
  }.bind(this));
</script>
