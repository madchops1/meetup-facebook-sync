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
  
  var_dump($return);
  die();
  
  // -- Loop the results and put into array
  if(isset($return->results)){
    foreach($return->results as $result){
      $_SESSION['fb_events'][] = $result;
    }
  }
  
  
  
} else {
  $facebook_response = json_decode($return);
  // If Error
  if(isset($facebook_response->error)){
    $_SESSION['facebook_auth'] = FALSE;
    $_SESSION['facebook_auth_code'] = "";
    unset($_SESSION['facebook_access_token']);
    //die("Facebook Error Please Try Again..."); // -kjs sometimes we get an error here...
  }
}



?>