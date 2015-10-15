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
        <h1>Upcoming Events</h1>
        <?php 
          $calendar="caryslounge@gmail.com";
          $today=get_and_format_todays_date_time();
          echo "today: " . $today;
          echo "<br/>";

          $msg=get_single_day_calendar_event($calendar,7);
//          $msg=get_multiple_calendar_events($calendar, 2);
//          $time= format_calendarAPI_date("today");
          echo "<p>$time</p>";

          echo "<br/>";
          echo $msg;
          ?>
      </div>
      <?php include("../includes/footer.html"); ?>
    </div>
  </body>
</html>
