<?php
date_default_timezone_set('America/Chicago');
$debugGlobal=false;
$APIformat="Y-m-d";
$earliestArrayElementNumber="";

//load developer key
function get_googleAPI_key(){ 
  $debug=false;
// can't set environment variable on live site, so get working directory and figure it out
// add windows computers later
  $cwd= getcwd();
  if($debug) echo getcwd();
  
  if (strpos($cwd,"caryslng")!==false) { //online
    if (strpos($cwd,"dev")!==false)
     $path="/home/caryslng/public_html/dev/";
    else
     $path="/home/caryslng/public_html/"; // live site
  }  
  elseif (strpos($cwd,"carys")!==false) //my Macbook
    $path="/Users/christine/Sites/carys/";

  else{
    $path="C:\\Users\\christine\\Desktop\\carys\\"; // RHO 
  }
  
  $key="";
  $file=$path."includes/GoogleAPIkey.txt"; //can't access registry on RHO computer
  if (file_exists($file)){
   $key = file_get_contents($file);  
  }
  if(($key==NULL)||($key=="")){
    //trigger_error('Google API key not found', E_USER_NOTICE);
    return -1;
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
  $localDebug=false;
  $key = get_googleAPI_key();
  if ($key!=-1){
    $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . 
       '/events?singleEvents=true&orderby=startTime&timeMin=' . $timeMin . 
       '&timeMax=' . $timeMax . '&key=' . $key;  
    //this works more reliably than only getting one event
    if ($localDebug) {echo "<p>$url</p>";}
    return $url;
  }
  return -1;
}

function get_and_format_calendar_specials($calendar){
  $current=time();
  $msg="<ul class=\"specials\">";
  for ($i=0;$i<7;$i++){
    $timeMin=date("Y-m-d", $current) . "T13:00:00.000Z"; 
    $timeMax=date("Y-m-d",$current). "T20:00:00.000Z";    
    $url=format_GoogleAPI_calendar_url($calendar, $timeMin, $timeMax);
    if ($url==-1){
      return $msg . "Sorry. There was an error getting the specials data. Stupid technology.</ul>";
    }
    $events=retrieve_calendar_data($url);
    if (count($events)<=0) {
      return "no data retrieved";
    }
    else{
      $msg.=format_calendar_item($events[0]);
      $current+=86400;
    }
  }
  return $msg . "</ul>";
}


function get_and_format_calendar_events($calendar, $numEntries, $timeMin=0, $timeMax=0){
  $debugLocal=false;
  $msg="";
  global $earliestArrayElementNumber;
  if ( ($timeMin==0)| ($timeMax==0) ){ //get events 60 days out if blank
    $timeMin=format_calendarAPI_date_snippet(time()-7200); 
    $timeMax=format_calendarAPI_date_snippet(time()+5184000);    
  }
  $url=format_GoogleAPI_calendar_url($calendar, $timeMin, $timeMax);
  if ($url != -1){
    $events=retrieve_calendar_data($url);
    if (count($events)<=0) {
      return "no data retrieved";
    }
    else{
      for ($i=0; $i<$numEntries; $i++){
        $event=get_earliest_event($events);
        if ($event){
          unset($events[$earliestArrayElementNumber]);
          $msg.=format_calendar_event($event);
        }
      }
    }
  }
  else {
    $msg="Sorry. There was an error getting the calendar data. Stupid technology.";
  }
  return "<ul>" . $msg . "</ul>";
}

function get_earliest_event($arrIn){
  $earliest="";
  global $earliestArrayElementNumber;
  $localDebug=false;
  $check = 9999999999;
  for ($i=0; $i<count($arrIn); $i++){
    if (isset($arrIn[$i])){ // avoid php notice error
      $start=get_event_data($arrIn[$i],"unixStartTime");
      if (time()>get_event_data($arrIn[$i],"unixEndTime")){// skip events that have ended
        continue; 
      }  
      if ($start < $check){
        $check=$start;
        $earliest=$arrIn[$i];
        $earliestArrayElementNumber=$i;
      }
      if ($localDebug){
        echo "<p>" . get_event_data($arrIn[$i],"unixStartTime") . "</p>";
        echo "<p>i=$i date=$start check=$check</p>";
        //var_dump($arrIn[$i]);
      }
    }
  }
  return $earliest;
}

function format_calendar_event($dataObj){
  $message="";
  $message .=  "<li><span class=\"calTitle\">" . get_event_data($dataObj, "title") . "</span>";
  $desc = get_event_data($dataObj, "description");
  if ($desc){
    $message .=  "<span class=\"calDesc\">" . $desc . "</span>";
  }
  $message .=  "<span class=\"calDate\">" . get_event_data($dataObj, "date") . "</span";
  $message .=  "<<span class=\"calTime\">" . get_event_data($dataObj, "start") . " - ";
  $message .=  get_event_data($dataObj, "end") . "</span></li>";
  return $message;
}


function format_calendar_item($dataObj){
  $message="";
  $message .=  "<li><span class=\"calDate\">" . get_event_data($dataObj, "date", "l") . "s:</span><span=\"calTitle\">" . get_event_data($dataObj, "title") . "</span>";
  $desc = get_event_data($dataObj, "description");
  if ($desc){
    $message .=  "<span class=\"calDesc\">" . $desc . "</span></li>";
  }
  else {
    $message .= "</li>";
  }
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

function get_event_data($eventObj, $itemToGet, $dateFormat="l, F jS", $timeFormat="g:ia"){    
    $eventDateType=get_event_date_type($eventObj);
    
    switch ($itemToGet){
      
      case "unixStartTime":
        if (isset($eventObj->start->$eventDateType)){
           return strtotime(substr($eventObj->start->$eventDateType, 0,16));
        }
        else{
          return "";
        }
        
      case "unixEndTime": 
        if (isset($eventObj->end->$eventDateType)){
           return strtotime(substr($eventObj->end->$eventDateType, 0,16));
        }
        else{
          return "";
        }

      case "date":
        if (isset($eventObj->start->$eventDateType)){
          return date($dateFormat,strtotime(substr($eventObj->start->$eventDateType, 0,16)));
        }
        else{
          return "";
        }
        
      case "start":
        if (isset($eventObj->start->$eventDateType)){
           return date($timeFormat,strtotime(substr($eventObj->start->$eventDateType, 0,16))); 
        }
        else{
          return "";
        }
        
      case "end":
        if (isset($eventObj->end->$eventDateType)){
           return date($timeFormat,strtotime(substr($eventObj->end->$eventDateType, 0,16)));  
        }
        else{
          return "";
        }
        
      case "summary":
        return $eventObj->summary;  

      case "title": //because I'll probably forget 'summary'
        return $eventObj->summary;  
      
      case "description":
        if (isset($eventObj->description)){
          return $eventObj->description;
        }
        else{
          return "";
        }
        
      default:
        return "wrong input for calendar event";
        
    }// end switch
      
 }
 

?>