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
      $_SESSION['fb_events'][] = $result;
    }
  }
  
  
  // -- Success, format events
  foreach($_SESSION['fb_events'] AS $facebook_event){
    $_SESSION['formatted_fb_events'][]->title = $facebook_event->title;
    $_SESSION['formatted_fb_events'][]->start = $facebook_event->start_time;
    $_SESSION['formatted_fb_events'][]->location = $facebook_event->location;
  }
  
  foreach($_SESSION['meetups'] as $meetup_event){
    $_SESSION['formatted_meetups'][]->title = $meetup_event->name;
    $_SESSION['formatted_meetups'][]->start = $meetup_event->start_time;
    $_SESSION['formatted_meetups'][]->location = $meetup_event->venue->name;
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
  
} else {
  $facebook_response = json_decode($return);
  // If Error
  if(isset($facebook_response->error)){
    die("<br>**FACEBOOK ERROR**<br>");
  }
}



?>