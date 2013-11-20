<?php 
session_start();

//die($_REQUEST['code']);

// -- Step 2, Get access token from user
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?'.
                              'client_id=588715921194851'.
                              '&redirect_uri=http://mfbsync.karlsteltenpohl.com/facebook-redirect.php'.
                              '&client_secret=5509ae4d80411edb07787535d55e144f'.
                              '&code='.$_REQUEST['code']);
                           
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//execute the request
$return = curl_exec($ch);
curl_close($ch);

if(strstr($return, "access_token")){
  $returnArray = explode("&",$return);
  $tokenArray = explode("=",$returnArray[0]);
  $access_token = $tokenArray[1];
  
  // Get Facebook Page Events
  //init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$_SESSION['fb_page_id'].'/events?access_token='.$access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  //echo "<pre>";
  //var_dump($return);
  //echo "</pre>";
  //die();
  
  // -- Loop the results and put into array
  if(isset($return->data)){
    foreach($return->data as $result){
      
      // -- Get the Event Details
      $ch = curl_init();
      //Set the URL to work with
      curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$result->id.'?access_token='.$access_token.'');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $detailed_return = curl_exec($ch);
      curl_close($ch);
      $detailed_return = json_decode($detailed_return);
      
      
      $_SESSION['fb_events'][] = $detailed_return;
    }
  }
  
  
  // -- Success, format events
  $i=0;
  foreach($_SESSION['fb_events'] AS $facebook_event){
    $_SESSION['formatted_fb_events'][$i]->title = $facebook_event->name;
    
    // -- Start Time Processing
    // If there is a time in the facebook time
    if(strstr($facebook_event->start_time,"T")){
      
      
      
      $date_time_array = explode("T",$facebook_event->start_time);
      $date = $date_time_array[0];
      $time = $date_time_array[1];
      $his = substr($time, 0,8);
      $zone = substr($time, -5);
      //$time = substr($time, 8);
      
      
      // -- Y-m-d => d/m/Y
      //$datetime = DateTime::createFromFormat('Y-m-d', $date);
      //$formatdate = $datetime->format('dd/mm/YY');
      //echo $datetime->format('Y-m-d');
      $formatdate = date("m/d/Y", strtotime($date));
      
      //dd "/" M "/" YY : HH ":" II ":" SS space tzcorrection
      //"10/Oct/2000:13:55:36 -0700"
      $new_time = round(strtotime( $formatdate . " " . $his . " " . $zone . "") * 1000);
            
      //echo "Time: ".$his."<br>";
      //echo "Zone: ".$zone."<br>";
      //echo "Format: ".$formatdate."<br>";
      //echo "New: ".$new_time."<br>";
      //die($facebook_event->start_time);
      
    } 
    
    // -- else Just date Y-m-d...
    else {
      //$datetime = DateTime::createFromFormat('Y-m-d', $date);
      //$formatdate = $datetime->format('YY/mm/dd');
      $new_time = round(strtotime($facebook_event->start_time) * 1000);
    }
    
    $_SESSION['formatted_fb_events'][$i]->start = $new_time;
    $_SESSION['formatted_fb_events'][$i]->location = $facebook_event->location;
    $_SESSION['formatted_fb_events'][$i]->description = '';
    $i++;
  }
  
  // -- Format Meetups
  $i=0;
  foreach($_SESSION['meetups'] as $meetup_event){
    $_SESSION['formatted_meetups'][$i]->title = $meetup_event->name;
    $_SESSION['formatted_meetups'][$i]->start = $meetup_event->time;
    $_SESSION['formatted_meetups'][$i]->location = $meetup_event->venue->name;
    $_SESSION['formatted_meetups'][$i]->description = '';
    $i++;
  }
  
  echo "<table cellpadding='10'><tr><td><pre>";
  var_dump($_SESSION['fb_events']);
  echo "</pre></td><td><pre>";
  var_dump($_SESSION['meetups']);
  echo "</pre></td></tr></table><br><br>";
  
  echo "<table cellpadding='10'><tr><td><pre>";
  var_dump($_SESSION['formatted_fb_events']);
  echo "</pre></td><td><pre>";
  var_dump($_SESSION['formatted_meetups']);
  echo "</pre></td></tr></table>";
  
  $synced_facebook_events=0;
  // Loop the Facebook events and add them to meetup if necessary
  foreach($_SESSION['formatted_fb_events'] as $facebook_event){
    foreach($_SESSION['formatted_meetups'] as $meetup_event){
      $fb_event_synced = 0;
      if($meetup_event->title == $facebook_event->title){
        $fb_event_synced = 1;
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
      curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/venues?group_id='.$_SESSION['meetup_group_object']->id.'&access_token='.$meetup_response->access_token.'');
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
      
      
      
      
      /**  ##MAYBE ONE DAY##
      // -- If no location found then add the location
      if($location_found == 0 && $facebook_event->location != ''){
        // -- POST Location to Meetup
        // -- POST FB EVENT to Meetup
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/'.$_SESSION['meetup_name'].'/venues');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'group_id='.$_SESSION['meetup_group_object']->id.''.
                                             '&group_urlname='.$_SESSION['meetup_name'].''.
                                             '&name='.urlencode($facebook_event->title).''.
                                             '&time='.round(strtotime($facebook_event->start)*1000).''.
                                             '&access_token='.$_SESSION['meetup_token']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
      }*/
      
      
      // -- POST FB EVENT to Meetup
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/event');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, 'group_id='.$_SESSION['meetup_group_object']->id.''.
                                           '&group_urlname='.$_SESSION['meetup_name'].''.
                                           '&name='.urlencode($facebook_event->title).''.
                                           '&time='.$facebook_event->start.''.
                                           $venue.
                                           '&access_token='.$_SESSION['meetup_token']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $return = curl_exec($ch);
      
      
      echo "<br><br><div style='border:5px solid red; display:block; clear:both;'>Synced Facebook Event To Meetup:<br><pre>";
      echo                                 'https://api.meetup.com/2/event?group_id='.$_SESSION['meetup_group_object']->id.''.
                                           '&group_urlname='.$_SESSION['meetup_name'].''.
                                           '&name='.urlencode($facebook_event->title).''.
                                           '&time='.$facebook_event->start.''.
                                           '&access_token='.$_SESSION['meetup_token'].'<br><br><br>';
      var_dump($return);
      echo "</pre></div>";
      
      curl_close($ch);
    }
  } 
  
  $synced_meetup_events=0;
  
  /*
  // Loop the Meetup events and add them to Facebook if necessary
  foreach($formatted_mu_events as $meetup_event){
    foreach($formatted_fb_events as $facebook_event){
      
    }
  }
  */
  
  // -- Debriefing
  echo "<br><br>DEBRIEF:<br>";
  echo $synced_facebook_events . " facebook events synced to meetup.<br>";
  echo $synced_meetup_events . " meetup events synced to facebook.<br>";
  
} else {
  $facebook_response = json_decode($return);
  // If Error
  if(isset($facebook_response->error)){
    die("<br>**FACEBOOK ERROR**<br>");
  }
}





?>