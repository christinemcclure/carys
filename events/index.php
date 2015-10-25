<?php 
include("../includes/head.html"); 
include("../includes/functions.php"); ?>
<title>Cary's Lounge - Events</title>
</head>  
  <body id="events">
    <div class="container">
      <?php include("../includes/header.html"); ?>
      <div id="main">
        <?php include("../includes/nav.html"); ?>
        <h1 class="clear">Upcoming Events</h1>
        <?php 
          $calendar="caryslounge@gmail.com";
          echo "<p>". date("l, F j") . "</p>";
          $timeMin=format_calendarAPI_date_snippet(time()-7200); 
          $timeMax=format_calendarAPI_date_snippet(time()+691200);    
          $msg=get_and_format_calendar_events($calendar,3);
          echo $msg;
          ?>
      </div>
      <?php include("../includes/footer.html"); ?>
    </div>
  </body>
</html>

