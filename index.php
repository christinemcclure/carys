<?php include("includes/head.html");?>
<title>Cary's Lounge</title>
</head>
  <body>
        <?php include("includes/nav.html"); ?>
    
    <?php 
    
    
    $cwd= getcwd();
    echo $cqd;
    if (strpos($cwd,"Carys")!==false) //my Macbook
      $path="/Users/christine/Sites/Carys/includes";
    elseif (strpos($cwd,"dev")!==false) //online dev folder
      $path="/home/caryslng/public_html/dev/includes";
    else
      $path="/home/caryslng/public_html/includes"; // live site
    
    ?>
  
  </body>
</html>
