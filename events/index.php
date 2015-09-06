<?php include("../includes/head.html"); ?>
<title>Cary's Lounge - Events</title>
</head>  
<body>
        <h1>Upcoming Events</h1>
        <?php include("../includes/nav.html"); 
 include("../includes/functions.php");
 $calendar="iit.edu_8l0d8qd4qtfn7skmgkiu55uv58@group.calendar.google.com";
 $calendar="caryslounge@gmail.com";
 $today=get_and_format_todays_date_time();
 echo "today: " . $today;
 echo "<br/>";

 $msg=retrieve_calendar_event($calendar);
 echo "<br/>";
 echo $msg;
 
 
  ?>

  </body>
</html>
