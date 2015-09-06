<?php include("../includes/head.html"); ?>
<title>Cary's Lounge - Beers</title>
</head>
  <body>
    <h1>Beer Menu</h1>
    <?php include("../includes/nav.html"); ?>
    <ul class="sub-nav">
      <li id="featured">Featured Items</li>
      <li id="beersTap">Beers On Tap</li>
      <li id="beersBottled">Beers Bottled</li>
      <li id="beersCanned">Beers Canned</li>
      <li id="ciders">Cider</li>
      <li id="wines">Wine on Tap</li>
    </ul>
    <div id="menu_widget">
      <div id="onTap">
      </div>
    </div>
   <script src="https://www.beermenus.com/menu_widgets/2827?no_links=1&beer_descriptions=1" type="text/javascript" charset="utf-8"></script>
   <script>
      $( document ).ready(function() {
          $('#on_tap').addClass('hide');
          $('#bottles').addClass('hide');
          $('#cans').addClass('hide');
          $('#cider').addClass('hide');
          $('#wine').addClass('hide');
      });
  </script>
  </body>
</html>
