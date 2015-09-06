<?php
date_default_timezone_set('America/Chicago');


function get_and_format_todays_date_time(){
  $dateFormat="l, F j";
  $timeFormat="g:ia";
  $today = date($dateFormat).", " . date($timeFormat);
  return $today;
}

//load developer key
function get_googleAPI_key(){
  $key="";
  $file="C:\Users\christine\Documents\GoogleAPIkey.txt"; //can't access registry on RHO computer
  if (file_exists($file)){
   $key = file_get_contents($file);  
  }
  else{
    $key=$_ENV["GOOGLE_API"];
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
  $debug=true;
  $key = get_googleAPI_key();
  $APIformat="Y-m-d";
  $timeMin = date($APIformat,time()+$dateToGet) . 'T12:00:00.000Z';
  $timeMax = date($APIformat,time()+$dateToGet) . 'T13:00:00.000Z';
  if ($debug){
    $timeMin="2015-08-02T04:00:00.000Z";
    $timeMax="2015-08-02T23:00:00.000Z";
  }
  $url='https://www.googleapis.com/calendar/v3/calendars/' . $calendar . '/events?singleEvents=true&orderby=startTime&timeMin=' . 
      $timeMin . '&timeMax=' . $timeMax . '&maxResults=1&key=' . $key;
    //this works more reliably than only getting one event
  if ($debug){
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
    $message .=  get_event_data($dataObj, "title") . "<br/>";
    $message .=  get_event_data($dataObj, "description") . "<br/>";
    $message .=  get_event_data($dataObj, "date") . "<br/>";
    $message .=  get_event_data($dataObj, "start") . " - ";
    $message .=  get_event_data($dataObj, "end");
    
  }
  else{
    $message = "No data retrieved.";
  }
  return $message;
}

?>