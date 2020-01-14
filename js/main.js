$(document).ready(function() {
  (function($){
    highlightOneBeerType=function(showType) {
      console.log(showType);
      var items = new Array ("featured", "on_tap", "bottles", "cans", "cider", "wine", "on_deck", "cocktails"); // ids from beermenus.com     

      for (var i in items) {
        if (items[i].indexOf(showType) > -1){
           $("#beer_menu > #"+items[i]).addClass('active');
           $("#beer_menu > #"+items[i]).removeClass('hide');
        }
        else{
           $("#beer_menu > #"+items[i]).removeClass('active');
          $("#beer_menu > #"+items[i]).addClass('hide');
        }
      }
      $("#beer-sub-nav").children().removeClass("active");//don't need to cycle through. just for highlighting menu item
      $("#sub_"+showType).addClass("active");

      var captionText = $("li#sub_"+showType).html();
      $("#beer_menu table caption").html(captionText);
      
      //fix beermenus bug of always displaying wine
      if (showType!=="wine"){
       $("span#white_wine").hide();
       $("span#red_wine").hide();
       $("span#rose_wine").hide();
      }
      else{
       $("span#white_wine").show();
       $("span#white_wine table caption").html("Whites");
       $("span#rose_wine").show();
       $("span#rose_wine table caption").html("Ros&#xe9;s");
       $("span#red_wine").show();        
       $("span#red_wine table caption").html("Reds");
      }
      
    }

   })($);
   
  //hide beer menus branding 
  $("#beer_menu a:last-child").hide();
  $( "#beer_menu" ).contents().filter(function() {
      return this.nodeType === 3;
    }).wrap( "<p></p>" ).end().filter( "br" ).remove();
  $("#beer_menu p:last-child").hide();
});