<?php include("includes/head.html");
  include("includes/functions.php"); 
?>
<title>Cary's Lounge</title>
</head>
  <body id="home">
    <div class="container">
      <?php include("includes/header.html"); ?>
        <?php include("includes/nav.html"); ?>
        <div class="clear column">
          <p>Cary's Lounge is your friendly neighborhood tap catering to all your drinking needs since 1972. No thirst is too big or too small for us here at Cary's. In addition to the fine selection of beers, wines, and spirits, Cary's features free pool, free wi-fi, and a bad-ass selection on the jukebox.</p>          
          <p>Home page text. Here is <a target="_blank" href="images/sample-layout.png">the layout</a> the site will use (Except there will be "weekly specials" instead of where the "Coming Up at Cary's" is listed.). 
          </p>
        </div>
        <div class="column">
          <h2>Weekly Specials</h2>
          <?php
            $calendar="dnfn2uq4avupk716k95a5cvpdk@group.calendar.google.com";
            $timeMin=format_calendarAPI_date_snippet(time()-7200); 
            $timeMax=format_calendarAPI_date_snippet(time()+691200);    
            $msg=get_and_format_calendar_specials($calendar,7);
            echo $msg;          
          ?>
        </div>
      <?php include("includes/footer.html"); ?>
    </div>
  </body>
</html>
