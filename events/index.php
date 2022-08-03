<?php 
include("../includes/head.html"); 
include("../includes/functions.php"); ?>
<title>Cary's Lounge - Events</title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
</head>  
  <body id="events">
    
    <div class="grid">
      
      <?php include "../includes/header.html"; ?>
      
      <?php include "../includes/nav.html"; ?>

      <div class="col-1-1">
        <div class="grid-pad content">
          <h1>Upcoming Events</h1>
          <p>Here's what's coming up at Cary's. Check out our <a target="_blank" href="https://www.facebook.com/CarysChicago">Facebook page</a> for more info. </p>
          <?php 
            $calendar="su8pfjk4ecjl8fbmoq6uhi50hs@group.calendar.google.com";
            $timeMin=format_calendarAPI_date_snippet(time()-7200); 
            $timeMax=format_calendarAPI_date_snippet(time()+691200);    
            $msg=get_and_format_calendar_events($calendar,5);
            echo $msg;
            ?>
        </div>
      </div>

      <?php include "../includes/footer.html"; ?>

    </div>    

  </body>
</html>

