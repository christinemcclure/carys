<?php include("../includes/head.html"); ?>
<title>Cary's Lounge - Beers</title>
</head>
  <body>
    <h1>Beer Menu</h1>
    <?php include("../includes/nav.html"); ?>
    <ul id="beer-sub-nav">
      <li id="sub_featured">Featured Items</li>
      <li id="sub_on_tap">Beers On Tap</li>
      <li id="sub_bottles">Beers Bottled</li>
      <li id="sub_cans">Beers Canned</li>
      <li id="sub_cider">Cider</li>
      <li id="sub_wine">Wine on Tap</li>
    </ul>
    <div id="menu_widget">
      <div id="onTap">
      </div>
    </div>
   <script src="https://www.beermenus.com/menu_widgets/2827?no_links=1&beer_descriptions=1" type="text/javascript" charset="utf-8"></script>
   <script>
      $( document ).ready(function() {
        
        $("#beer-sub-nav li").click(function (event) {
          var thisItem=event.target.id.substring(4);// strip sub_ from item ID
          console.log(thisItem);
          highlightOneBeerType(thisItem);
        });
        
        highlightOneBeerType("featured");
        
      });
  </script>
  </body>
</html>
