$(document).ready(function() {
  (function($){
    highlightOneBeerType=function(showType) {
      var items = new Array ("featured", "on_tap", "bottles", "cans", "cider", "wine"); // ids from beermenus.com      
      for (var i in items) {
        if (items[i].indexOf(showType) > -1){
           $("#beer_menu > #"+items[i]).addClass('active');
        }
        else{
          $("#beer_menu > #"+items[i]).addClass('hide');
        }
      }
    }
   })($);
  
});