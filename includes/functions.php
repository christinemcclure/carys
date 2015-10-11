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
  $debug=true;
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


//retrieve JSON data from a Google Calendar (public)
function get_calendar_data($calendar, $dateToGet=0){
  global $debugGlobal, $APIformat;  
  $debugLocal=true;
  $key = get_googleAPI_key();
  $timeMin = date($APIformat,time()+$dateToGet) . 'T12:00:00.000Z';
  $timeMax = date($APIformat,time()+$dateToGet) . 'T13:00:00.000Z';
  if ($debugLocal){
    $timeMin="2015-08-02T04:00:00.000Z";
    $timeMax="2015-08-02T23:00:00.000Z";
  }
  $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . '/events?singleEvents=true&orderby=startTime&timeMin=' . 
      $timeMin . '&timeMax=' . $timeMax . '&maxResults=1&key=' . $key;
    //this works more reliably than only getting one event
  if ($debugGlobal){
    echo $url;
  }
  $jsonFile = file_get_contents($url);
  if (!$jsonFile) {
      trigger_error('NO DATA returned from url.', E_USER_NOTICE);
  }
  else {
    // convert the string to a json object
    $jsonObj = json_decode($jsonFile);
    $dateData = $jsonObj->items;
    return $dateData;
  }
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
 
function retrieve_calendar_event($calendar,$dateToGet=0){
  $message="";
  $dataObj=get_calendar_data($calendar,$dateToGet);

  if (count($dataObj)>0){
    $message .=  "<h2>" . get_event_data($dataObj, "title") . "</h2>";
    $message .=  "<p>" . get_event_data($dataObj, "description") . "</p>";
    $message .=  "<h3>" . get_event_data($dataObj, "date") . "</h3>";
    $message .=  "<h4>" . get_event_data($dataObj, "start") . " - ";
    $message .=  get_event_data($dataObj, "end") . "</h4>";
    
  }
  else{
    $message = "No data retrieved.";
  }
  return $message;
}

function get_multiple_calendar_events($calendar, $numEvents){
  $message="";
  $timeMin = date($APIformat,time()) . 'T12:00:00.000Z';
  $timeMax = date($APIformat,time()+$dateToGet) . 'T13:00:00.000Z';  
}

?>