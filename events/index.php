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
          $testCal="jsbqkranc44jmm1fqan0o7gmc4@group.calendar.google.com";
          $today=get_and_format_todays_date_time();
          echo "today: " . $today;
          echo "<br/>";
          
         $timeStart=format_calendarAPI_date_snippet(time()+9999999);
         echo "<p>$timeStart</p>";
//          $timeEnd=format_full_calendarAPI_date_snippet("October 30 2015","10pm");

//          $msg=get_single_calendar_event($calendar,$timeStart,$timeEnd);
//          $msg=get_multiple_calendar_events($calendar, 2);
//          $time= format_full_calendarAPI_date_snippet("tomorrow","8am");

          echo $msg;
          ?>
      </div>
      <?php include("../includes/footer.html"); ?>
    </div>
  </body>
</html>
