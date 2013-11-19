<?php 
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ERROR);
error_reporting(E_ALL);

// Author: Karl Steltenpohl




class mfbsync {
  
  var $meetup_redirect_uri       = "http://mfbsync.karlsteltenpohl.com/meetup-redirect.php";
  var $meetup_consumer_key       = "drdhpm0c4l1haeem1evkcdk2h5";
  var $meetup_consumer_secret    = "r0jqlpdk6hdqsspb17iq7iftt"; 
  
  /**
   * Constructor // Init...
   */
  function __construct(){
    
    // -- SESSION setup
    if (!isset($_SESSION['meetup_auth'])) { $_SESSION['meetup_auth'] = FALSE; }
    
    $this->meetup_auth();
    $this->finish_up();
    
  }  
  
  /**
   * Meetup Auth Function
   */
  function meetup_auth(){
    
  
    // -- Set Response from Step 1, save code
    if($_REQUEST['code'] != ''){
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
    //Set the URL to work with
    curl_setopt($ch, CURLOPT_URL, 'https://secure.meetup.com/oauth2/access');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='.$this->meetup_consumer_key.
                                         '&client_secret='.$this->meetup_consumer_secret.
                                         '&grant_type=authorization_code'.
                                         '&redirect_uri='.$this->meetup_redirect_uri.
                                         '&code='.$_SESSION['meetup_auth_code'].'');
    
    // ENABLE HTTP POST
    //curl_setopt($ch, CURLOPT_POST, 1);
    // FOllOW REDIRECTS
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // Set the post parameters
    //curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='.$this->instagramClientId.'&client_secret='.$this->instagramClientSecret.'&grant_type=authorization_code&redirect_uri='.$redirectURI.'&code='.$code.'');
    //Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
    //not to print out the results of its query.
    //Instead, it will return the results as a string return value
    //from curl_exec() instead of the usual true/false.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //execute the request
    $return = curl_exec($ch);
    curl_close($ch);
    
    var_dump($return);
  }
  
  /**
   * Finish up
   */
  function finish_up(){
    echo "Done!";
  }
  
}
?>