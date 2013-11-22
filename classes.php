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
      $time = $facebook_event->start_time;
      $new_time = round(strtotime($facebook_event->start_time) * 1000);
    }
    
    // -- Make sure the date is not earlier than now...
    if(strtotime($time) < strtotime("now")){
      $formatted_facebooks[$i]->title = $facebook_event->name;
      $formatted_facebooks[$i]->start = $new_time;
      $formatted_facebooks[$i]->location = $facebook_event->location;
      $formatted_facebooks[$i]->description = '';
      $i++;
    }
  }
  return $formatted_facebooks;
}

function sync_events($formatted_meetups,$formatted_facebooks,$meetup_group_object,$meetup_token){

  // -- SYNC here...
  // ...Start with facebook...
  
  $synced_facebook_events  = 0;
  $synced_meetup_events    = 0;
  
  
  // -- Loop the Facebook events and add them to meetup if necessary
  foreach($formatted_facebooks as $facebook_event){
  
    // -- Loop through each meetup event
    $fb_event_synced = 0;
    foreach($formatted_meetups as $meetup_event){
      echo $meetup_event->title." == ".$facebook_event->title."<br>";
      if($meetup_event->title == $facebook_event->title){
        $fb_event_synced = 1;
        break;
      }
    }
  
    // -- If the fb event doesn't exist in meetup
    if($fb_event_synced == 0){
      $synced_facebook_events++;
  
      // -- Venue Processing
      // Get the Meetup Group's Venues...
      //init curl
      $ch = curl_init();
      //Set the URL to work with
      curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/venues?group_id='.$meetup_group_object->id.'&access_token='.$meetup_token.'');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $return = curl_exec($ch);
      curl_close($ch);
      $return = json_decode($return);
  
      // -- Loop the venue results to check for a match
      $location_found = 0;
      $venue = '';
      if(isset($return->results)){
        foreach($return->results as $result){
          if($result->name == $facebook_event->location){
            $location_found = 1;
            $venue_id = $result->id;
            $venue='&venue_id='.$venue_id.'';
          }
        }
      }
  
      // -- POST FB EVENT to Meetup
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/event');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, 'group_id='.$meetup_group_object->id.''.
                                           '&group_urlname='.$_SESSION['meetup_name'].''.
                                           '&name='.urlencode($facebook_event->title) .
                                           '&time='.$facebook_event->start.''.
                                           $venue.
                                           '&access_token='.$_SESSION['meetup_token']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $return = curl_exec($ch);
      curl_close($ch);

    }
  }
  
  
  
   // --Loop the Meetup events and add them to Facebook if necessary
   $synced_meetup_events=0;
   foreach($_SESSION['formatted_meetups'] as $meetup_event){
  
   // -- Loop through each facebook event
   $mu_event_synced = 0;
   foreach($_SESSION['formatted_fb_events'] as $facebook_event){
   if($facebook_event->title == $meetup_event->title){
   $mu_event_synced = 1;
  break;
  }
  }
  
  if($mu_event_synced == 0){
  $synced_meetup_events++;
  
  // -- Post Meetup to Facebook Page as Event...
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$_SESSION['fb_page_id'].'/events');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'name='.$meetup_event->title.
    '&start_time='.$meetup_event->start.''.
    '&description='.$meetup_event->description.''.
    '&location='.$meetup_event->location.''.
    '&access_token='.$access_token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
  
  
    }
    }
  return $events;
}
?>