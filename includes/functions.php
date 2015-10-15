<?php
date_default_timezone_set('America/Chicago');
$debugGlobal=false;
$APIformat="Y-m-d";
$APItimeFormat="H:i";

function get_and_format_todays_date_time(){
  $dateFormat="l, F j";
  $timeFormat="g:ia";
  $today = date($dateFormat).", " . date($timeFormat);
  return $today;
}

//load developer key
function get_googleAPI_key(){ 
  $debug=false;
// can't set environment variable on live site, so get working directory and figure it out
// add windows computers later
  $cwd= getcwd();
  if($debug) echo getcwd();
  
  if (strpos($cwd,"Carys")!==false) //my Macbook
    $path="/Users/christine/Sites/Carys/";
  elseif (strpos($cwd,"caryslng")!==false) { //online
    if (strpos($cwd,"dev")!==false)
     $path="/home/caryslng/public_html/dev/";
    else
     $path="/home/caryslng/public_html/"; // live site
  }
  else{
    $path="C:\\Users\\christine\\Desktop\\carys\\"; // RHO 
  }
  
  $key="";
  $file=$path."includes/GoogleAPIkey.txt"; //can't access registry on RHO computer
  if (file_exists($file)){
   $key = file_get_contents($file);  
  }
  if(($key==NULL)||($key=="")){
    trigger_error('Google API key not found', E_USER_NOTICE);
  }
  else{
    return $key;
  }
}

function retrieve_calendar_data($url){
  $debugLocal=false;
  $jsonFile = file_get_contents($url);
  if (!$jsonFile) {
      trigger_error('NO DATA returned from url.', E_USER_NOTICE);
  }
  else {
    // convert the string to a json object
    $jsonObj = json_decode($jsonFile);
    if ($debugLocal){
      echo "<p>JSON: <br>$jsonFile</p>"; // view T http://jsonviewer.stack.hu/
    }      
    $dateData = $jsonObj->items;
    return $dateData;
  }  
}
function format_calendarAPI_time($time){
  global $APItimeFormat;
  $unixTime=strtotime($time);
  $rc=date($APItimeFormat,$unixTime); 
 //  $rc=$rc . ":00.000Z"
  return $rc . ":00.000Z";
  
}

function format_full_calendarAPI_date_string($date, $time){
  $daysAhead = $daysAhead * 86400;
}

//retrieve JSON data from a Google Calendar (public)
function get_single_day_calendar_event($calendar, $daysAhead=0){//assume today if no date specified
  global $debugGlobal, $APIformat;  
  $debugLocal=false;
  $daysAhead = $daysAhead * 86400;
  $key = get_googleAPI_key();
  $timeMin = date($APIformat,time()+$daysAhead) . 'T12:00:00.000Z';
  $timeMax = date($APIformat,time()+$daysAhead) . 'T23:45:00.000Z';
  $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . '/events?singleEvents=true&orderby=startTime&timeMin=' . 
      $timeMin . '&timeMax=' . $timeMax . '&maxResults=1&key=' . $key;
    //this works more reliably than only getting one event
  if ($debugLocal){
    echo $url;
  }
  $event=retrieve_calendar_data($url);
  $msg=format_calendar_event($event);
  return $msg;
}

//retrieve JSON data from a Google Calendar (public)
function get_multiple_calendar_events($calendar, $numEvents){//assume today if no date specified
  global $debugGlobal, $APIformat;  
  $debugLocal=true;
  $key = get_googleAPI_key();
  $timeMin = date($APIformat,time()) . 'T12:00:00.000Z';// just get a month of entries starting from today
  $timeMax = date($APIformat,time()+2592000) . 'T23:45:00.000Z';
  $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . '/events?singleEvents=true&orderby=startTime&timeMin=' . 
      $timeMin . '&timeMax=' . $timeMax . '&key=' . $key;
    //this works more reliably than only getting one event
  $events=retrieve_calendar_data($url);
  $items=count($events);
  for($i=0; $i<$items; $i++){
    if ($debugLocal){
      echo "<p>";
      print_r($events[$i]);
      echo "</p>";
    }
  }  
  $msg=format_calendar_event($events);
  return $msg;
}



function get_event_data($dateData, $itemToGet){
    $timeFormat="g:ia";
    $dateFormat="l, F jS";
    $eventDateType="";
    if (count($dateData)<=0){
      return "no start date available";
    }
    else{
      $event = $dateData[0]; // no need to loop. just get first object
    }
    
    if (isset($event->start->dateTime)){ // non 24-hour event
      $eventDateType = 'dateTime';
      //return date($timeFormat,strtotime(substr($event->start->dateTime, 0,16)));
    }

    else{ // all day event
      $eventDateType = 'date';
      //return strtotime(substr($event->start->date, 0,16));
    }
    
    switch ($itemToGet){
      case "date":
        return date($dateFormat,strtotime(substr($event->start->$eventDateType, 0,16)));
        
      case "start":
        return date($timeFormat,strtotime(substr($event->start->$eventDateType, 0,16)));  
        
      case "end":
        return date($timeFormat,strtotime(substr($event->end->$eventDateType, 0,16)));  
        
      case "summary":
        return $event->summary;  

      case "title": //because I'll probably forget 'summary'
        return $event->summary;  
      
      case "description":
        return $event->description;
      default:
        return "wrong input for calendar event";
        
    }// end switch
      
 }
 
function format_calendar_event($dataObj){
  $message="";

  if (count($dataObj)>0){
    $message .=  "<h2>" . get_event_data($dataObj, "title") . "</h2>";
    $message .=  "<p>" . get_event_data($dataObj, "description") . "</p>";
    $message .=  "<h3>" . get_event_data($dataObj, "date") . "</h3>";
    $message .=  "<h4>" . get_event_data($dataObj, "start") . " - ";
    $message .=  get_event_data($dataObj, "end") . "</h4>";
    
  }
  else{
    $message = "No calendar data available.";
  }
  return $message;
}

?>