$(document).ready(function() {
  (function($){
    highlightOneBeerType=function(showType) {
      var items = new Array ("featured", "on_tap", "bottles", "cans", "cider", "wine");
      $("#beer_menu > #"+showType).addClass('active');
      var hideItems = items.filter(showType);
    }
   })($);
  
});