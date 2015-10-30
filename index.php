<?php include("includes/head.html");
  include("includes/functions.php"); 
?>
<title>Cary's Lounge</title>
</head>
  <body id="home">
    <div class="grid">
      <?php include "includes/header.html"; ?>
      <?php include "includes/nav.html"; ?>
        <div class="col-2-3">
          <div class="content">
            <p>Cary's Lounge is your friendly neighborhood tap catering to all your drinking needs since 1972. No thirst is too big or too small for us here at Cary's. In addition to the fine selection of beers, wines, and spirits, Cary's features free pool, free wi-fi, and a bad-ass selection on the jukebox.</p>          
            <blockquote>
              Smack in the middle of Indo/Pak territory, this tiny German-style hideaway has been plugging away since ’72[...] Bartenders flip the TV channels back and forth between basketball games and The Simpsons, but if that doesn’t interest you, there’s free pool and a stash of frisky regulars to keep you occupied. &mdash; <a href="http://www.timeout.com/chicago/bars/carys-lounge">TimeOut Chicago</a>
            </blockquote>
            <p>More reviews at <a href="http://www.yelp.com/biz/carys-lounge-chicago">Yelp</a> and <a href="http://chicago.metromix.com/venues/mmxchi-carys-lounge-liquors-venue">Metromix</a></p>
          </div>
        </div>  
        <div class="col-1-3">
          <div class="content">
            <h2>Weekly Specials</h2>
            <?php
              $calendar="dnfn2uq4avupk716k95a5cvpdk@group.calendar.google.com";
              $timeMin=format_calendarAPI_date_snippet(time()-7200); 
              $timeMax=format_calendarAPI_date_snippet(time()+691200);    
              $msg=get_and_format_calendar_specials($calendar,7);
              echo $msg;          
            ?>
          </div>
        </div>

      <?php include("includes/footer.html"); ?>
    </div>
  </body>
</html>
