$(document).ready(function() {
  (function($){
    console.log('in file');
    highlightOneBeerType=function(showType){
      var items = new Array ("on_tap", "bottles", "cans", "cider", "wine", "on_deck"); // ids from beermenus.com     

      for (var i in items) {
        if (items[i].indexOf(showType) > -1){
           $("#drinks > #"+items[i]).addClass('active');
           $("#drinks > #"+items[i]).removeClass('hide');
        }
        else{
           $("#drinks > #"+items[i]).removeClass('active');
          $("#drinks > #"+items[i]).addClass('hide');
        }
      }
      $("#beer-sub-nav").children().removeClass("active");//don't need to cycle through. just for highlighting menu item
      $("#sub_"+showType).addClass("active");
    };
   })($);

});