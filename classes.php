<?php
/**
 * I want to OOP this baby out...
 * @todo...
 * @author karlsteltenpohl
 *
 */

class meetup_group {
  
  function __construct(){
    
  }
  
}


class facebook_page {
  
  function __construct(){
    
  }
  
}

// << Other Functions

/**
 * Format Meetup Groups
 */
function format_meetups($meetups){
  // -- Format Meetups
  $i=0;
  $formatted_meetups = array();
  foreach($meetups as $meetup_event){
    
    // -- Process Time
    $meetup_event->time = date("Y-m-dXXXXh:i:s",($meetup_event->time/1000))."-0000";
    $meetup_event->time = str_replace("XXXX","T",$meetup_event->time);
    
    $formatted_meetups[$i]->title = $meetup_event->name;
    $formatted_meetups[$i]->start = $meetup_event->time;
    $formatted_meetups[$i]->location = $meetup_event->venue->name;
    $formatted_meetups[$i]->description = '';
    $i++;
  }
  
  return $formatted_meetups;
}

/**
 * Format Facebook Group
 */
function format_facebooks($facebooks){
  $i=0;
  $formatted_facebooks = array();
  
  foreach($facebooks AS $facebook_event){
    
    // -- Start Time Processing
    // If there is a time in the facebook time
    if(strstr($facebook_event->start_time,"T")){
  
      $date_time_array = explode("T",$facebook_event->start_time);
      $date = $date_time_array[0];
      $time = $date_time_array[1];
      $his = substr($time, 0,8);
      $zone = substr($time, -5);
  
      $formatdate = date("m/d/Y", strtotime($date));
      $new_time = round(strtotime( $formatdate . " " . $his . " " . $zone . "") * 1000);
  
    }
    // -- else Just date Y-m-d...
    else {
      $new_time = round(strtotime($facebook_event->start_time) * 1000);
    }
  
    $formatted_facebooks[$i]->title = $facebook_event->name;
    $formatted_facebooks[$i]->start = $new_time;
    $formatted_facebooks[$i]->location = $facebook_event->location;
    $formatted_facebooks[$i]->description = '';
    $i++;
  }
  return $formatted_facebooks;
}

function sync_events($formatted_meetups,$formatted_facebooks){
  
  return $events;
}
?>