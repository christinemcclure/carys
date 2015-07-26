<?php include("../includes/head.html"); ?>
  <body>
    <div class="row">
      <div class="large-12 columns">
        <h1>Upcoming Events</h1>
        <?php include("../includes/nav.html"); 
 include("../includes/functions.php");
  $msg=galvin_hours_block("iit.edu_8l0d8qd4qtfn7skmgkiu55uv58@group.calendar.google.com");
  echo $msg;
  
  ?>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
