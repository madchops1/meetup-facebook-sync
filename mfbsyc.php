<?php 
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ERROR);
error_reporting(E_ALL);

// Author: Karl Steltenpohl




class mfbsync {
  
  var $meetup_redirect_uri       = "http://mfbsync.karlsteltenpohl.com";
  var $meetup_consumer_key       = "drdhpm0c4l1haeem1evkcdk2h5";
  var $meetup_consumer_secret    = "r0jqlpdk6hdqsspb17iq7iftt"; 
  
  var $facebook_redirect_uri     = "http://mfbsync.karlsteltenpohl.com";
  var $facebook_consumer_key     = "588715921194851";
  var $facebook_consumer_secret  = "5509ae4d80411edb07787535d55e144f";
  
  /**
   * Constructor // Init...
   */
  function __construct(){
    
    // -- SESSION setup
    if (!isset($_SESSION['meetup_auth'])) { $_SESSION['meetup_auth'] = FALSE; }
    
    $this->meetup_auth();
    $this->facebook_auth();
    $this->finish_up();
    
  }  
  
  /**
   * Meetup Auth Function
   */
  function meetup_auth(){
    
    // -- Set Response from Step 1, save code
    if($_REQUEST['code'] != '' && $_SESSION['meetup_auth'] == FALSE){
      $_SESSION['meetup_auth'] = TRUE;
      $_SESSION['meetup_auth_code'] = $_REQUEST['code'];
    }
    
    // -- Step 1, Direct User to Meetup for authentication
    if($_SESSION['meetup_auth'] == FALSE) {
      header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=".$this->meetup_consumer_key."&response_type=code&redirect_uri=".$this->meetup_redirect_uri."");
      exit;
    }
    
    // -- Step 2, Get access token from meetup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://secure.meetup.com/oauth2/access');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='.$this->meetup_consumer_key.
                                         '&client_secret='.$this->meetup_consumer_secret.
                                         '&grant_type=authorization_code'.
                                         '&redirect_uri='.$this->meetup_redirect_uri.
                                         '&code='.$_SESSION['meetup_auth_code'].'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    
    $meetup_response = json_decode($return);
    // -- Successfull response...
    if(isset($meetup_reponse->token_type) && $meetup_response->token_type == "bearer"){
      $_SESSION['meetup_refresh_token'] = $meetup_response->refresh_token;
      $_SESSION['meetup_access_token'] = $meetup_response->access_token;
    }
  }
  
  function facebook_auth(){
    
    // -- Set Response from Step 1, save code
    if($_REQUEST['code'] && $_SESSION['facebook_auth'] == FALSE){
      $_SESSION['facebook_auth'] = TRUE;
      $_SESSION['facebook_auth_code'] = $_REQUEST['code'];
    }
    
    // -- Step 1, Direct User to Facebook for Authentication
    if($_SESSION['facebook_auth'] == FALSE){
      header("LOCATION: https://www.facebook.com/dialog/oauth?client_id=".$this->facebook_consumer_key."&scope=user_groups&redirect_uri=".$this->facebook_redirect_uri);
    }
    
    // -- Step 2, Get access token from user

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?'.
                                  'client_id='.$this->facebook_consumer_key.
                                  '&redirect_uri='.$this->facebook_redirect_uri.
                                  '&client_secret='.$this->facebook_consumer_secret.
                                  '&code='.$_SESSION['facebook_auth_code']);
                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //execute the request
    $return = curl_exec($ch);
    curl_close($ch);
    var_dump($return);
    die;
    
    // -- Step 3, Get access token from page
    
    
  }
  
  /**
   * Finish up
   */
  function finish_up(){
    var_dump($_REQUEST);
    echo "Done!";
  }
  
}
?>