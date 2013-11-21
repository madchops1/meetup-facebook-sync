<?php
session_start();
include 'config.php';

// -- Step 2, Get oauth access token from meetup
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://secure.meetup.com/oauth2/access');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id=drdhpm0c4l1haeem1evkcdk2h5'.
                                     '&client_secret=r0jqlpdk6hdqsspb17iq7iftt'.
                                     '&grant_type=authorization_code'.
                                     '&redirect_uri=http://mfbsync.karlsteltenpohl.com/meetup-redirect.php'.
                                     '&code='.$_REQUEST['code'].'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
curl_close($ch);

$meetup_response = json_decode($return);

// -- Successfull response...
if(isset($meetup_response->token_type) && $meetup_response->token_type == "bearer"){
  
  // -- Save the meetup access token in a session
  $_SESSION['meetup_token'] = $meetup_response->access_token;
  $_SESSION['refresh_token'] = $meetup_response->refresh_token;
  // -- Get the Meetup Group's Details
  //init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/groups?group_urlname='.$_SESSION['meetup_name'].'&access_token='.$meetup_response->access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  // -- Loop the results and put into array
  if(isset($return->results)){
    foreach($return->results as $result){
      $_SESSION['meetup_group_object'] = $result;
    }
  }
  
  // -- Get the Meetups Events
  //init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?group_urlname='.$_SESSION['meetup_name'].'&access_token='.$meetup_response->access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  // -- Loop the results and put into array
  if(isset($return->results)){
    foreach($return->results as $result){
      $_SESSION['meetups'][] = $result;
    }
  }
  
  // -- Go to facebook now
  header("LOCATION: https://www.facebook.com/dialog/oauth?client_id=588715921194851&scope=manage_pages,create_event&redirect_uri=http://mfbsync.karlsteltenpohl.com/facebook-redirect.php");
  die;
} 

// -- error
else {
 
  die("<br>**MEETUP ERROR**<br>");
  
}



?>