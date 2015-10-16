<?php
date_default_timezone_set('America/Chicago');
$debugGlobal=false;
$APIformat="Y-m-d";


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

// format date and time for Google calendar API: 2015-10-21T12:00:00.000Z
function format_calendarAPI_date_snippet($dateIn){
  $APIdateFormat="Y-m-d";
  $APItimeFormat="H:i";
  return date($APIdateFormat,$dateIn) . "T" . date($APItimeFormat,$dateIn) . ":00.000Z"; 
}

function format_GoogleAPI_calendar_url($calendar, $timeMin, $timeMax){
  $key = get_googleAPI_key();
  $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . 
     '/events?singleEvents=true&orderby=startTime&timeMin=' . $timeMin . 
     '&timeMax=' . $timeMax . '&key=' . $key;  
  //this works more reliably than only getting one event
  return $url;
}

function get_single_calendar_event($calendar, $timeMin, $timeMax){
  $debugLocal=false;
  $url=format_GoogleAPI_calendar_url($calendar, $timeMin, $timeMax);

  $events=retrieve_calendar_data($url);
  if (count($events)<=0) {
    return "no data retrieved";
  }
  else{
   if ($debugLocal){
      echo "<p>$url</p>";
      var_dump($events);
    }
   if (count($events)>1) { 
    $event=order_events_by_datetime($events);
   }
    $msg=format_calendar_event($event);
    return $msg;
  }
}

function get_multiple_calendar_events($calendar, $numEntries, $timeMin, $timeMax){
  if ( (!$timeMin)| (!$timeMax) ){ //get events 60 days out if blank
    $timeMin=format_calendarAPI_date_snippet(time()-7200); 
    $timeMax=format_calendarAPI_date_snippet(time()+5184000);    
  }

}

function order_events_by_datetime($arrIn){
  $localDebug=false;
  $check = 9999999999;
  for ($i=0; $i<count($arrIn); $i++){
    $start=get_event_data($arrIn[$i],"unixStartTime");

    if (time()>get_event_data($arrIn[$i],"unixEndTime")){// skip events that have ended
      continue; 
    }  
    if ($start < $check){
      $check=$start;
      $earliest=$arrIn[$i];
    }
    if ($localDebug){
      echo "<p>" . get_event_data($arrIn[$i],"unixStartTime") . "</p>";
      echo "<p>i=$i date=$start check=$check</p>";
      //var_dump($arrIn[$i]);
    }
  }
  return $earliest;
}

function format_calendar_event($dataObj){
  $message .=  "<h2>" . get_event_data($dataObj, "title") . "</h2>";
  $message .=  "<p>" . get_event_data($dataObj, "description") . "</p>";
  $message .=  "<h3>" . get_event_data($dataObj, "date") . "</h3>";
  $message .=  "<h4>" . get_event_data($dataObj, "start") . " - ";
  $message .=  get_event_data($dataObj, "end") . "</h4>";
  return $message;
}

function get_event_date_type($dateObj){
    if (isset($dateObj->start->dateTime)){ // non 24-hour event
      return 'dateTime';
      //return date($timeFormat,strtotime(substr($event->start->dateTime, 0,16)));
    }

    else{ // all day event
      return 'date';
      //return strtotime(substr($event->start->date, 0,16));
    }

  }

function get_event_data($eventObj, $itemToGet){
    $timeFormat="g:ia";
    $dateFormat="l, F jS";
    
    $eventDateType=get_event_date_type($eventObj);
    
    switch ($itemToGet){
      
      case "unixStartTime": 
        return strtotime(substr($eventObj->start->$eventDateType, 0,16));
        
      case "unixEndTime": 
        return strtotime(substr($eventObj->end->$eventDateType, 0,16));

        case "date":
        return date($dateFormat,strtotime(substr($eventObj->start->$eventDateType, 0,16)));
        
      case "start":
        return date($timeFormat,strtotime(substr($eventObj->start->$eventDateType, 0,16)));  
        
      case "end":
        return date($timeFormat,strtotime(substr($eventObj->end->$eventDateType, 0,16)));  
        
      case "summary":
        return $eventObj->summary;  

      case "title": //because I'll probably forget 'summary'
        return $eventObj->summary;  
      
      case "description":
        return $eventObj->description;
        
      default:
        return "wrong input for calendar event";
        
    }// end switch
      
 }
 

?>