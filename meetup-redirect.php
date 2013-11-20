<?php
session_start();

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
  echo "<br>".$meetup_response->refresh_token;
  echo "<br>".$meetup_response->access_token;
  
  // -- Get the Pages Events
  // Get All the users Photos
  //init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?group_urlname='.$_SESSION['meetup_name'].'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  echo "<br>";
  var_dump($return);
  //echo "<pre>";
  
} else {
 
  echo "<br><br>**MEETUP ERROR**<br>";
  
  //die("Meetup Error Please Try Again.");

}

//include 'mfbsyc.php';
//$mfbsync = new mfbsync;



?>