<?php include("includes/head.html");
  include("includes/functions.php"); 
?>
<title>Cary's Lounge</title>
</head>
  <body id="home">
    <div class="grid">
      <?php include "includes/header.html"; ?>
      <?php include "includes/nav.html"; ?>
        <div class="col-7-12">
          <div class="grid-pad content">
            <p>Cary's Lounge is your friendly neighborhood tap catering to all your drinking needs since 1972. No thirst is too big or too small for us here at Cary's! </p>
            <p>In addition to the great selection of beers (16 on draft!), fine wines on tap, and excellent cocktails, Cary's features free pool on our 9 foot Diamond table, free play for you and your teams on our Killer Queen arcade game, and a badass selection on the jukebox. Plus, you can enjoy your drink on our wonderful outdoor patio! You’re gonna love it!</p>
          </div>
        </div>  
        <div class="col-5-12">
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