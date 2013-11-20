<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// Author: Karl Steltenpohl




class mfbsync {
  
  var $meetup_redirect_uri       = "http://mfbsync.karlsteltenpohl.com";
  var $meetup_consumer_key       = "drdhpm0c4l1haeem1evkcdk2h5";
  var $meetup_consumer_secret    = "r0jqlpdk6hdqsspb17iq7iftt"; 
  
  var $facebook_redirect_uri     = "http://mfbsync.karlsteltenpohl.com/";
  var $facebook_consumer_key     = "588715921194851";
  var $facebook_consumer_secret  = "5509ae4d80411edb07787535d55e144f";
  
  var $meetup_events = array();
  var $facebook_events = array();
  
  /**
   * Constructor // Init...
   */
  function __construct(){
    
    // -- SESSION Setup
    if (!isset($_SESSION['meetup_auth'])) { $_SESSION['meetup_auth'] = FALSE; }
    if (!isset($_SESSION['facebook_auth'])) { $_SESSION['facebook_auth'] = FALSE; }
      
    $this->meetup_auth();
    $this->facebook_auth();
    
    $this->get_meetup_events();
    $this->get_facebook_events();
    $this->sync_events();
    
    $this->finish_up();
    
  }  
  
  /**
   * Meetup Auth Function
   */
  function meetup_auth(){
    //if(!isset($_SESSION['meetup_access_token'])){
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
      } else {
        $_SESSION['meetup_auth'] = FALSE;
        $_SESSION['meetup_auth_code'] = '';
        echo "<pre>";
        var_dump($meetup_response);
        echo "</pre>";
        die("Meetup Error Please Try Again.");
        
      }
    //}
  }
  
  /**
   * Facebook Auth Function
   */
  function facebook_auth(){
    
    //if(!isset($_SESSION['facebook_access_token'])){
      // -- Set Response from Step 1, save code
      if($_REQUEST['code'] && $_SESSION['facebook_auth'] == FALSE){
        $_SESSION['facebook_auth'] = TRUE;
        $_SESSION['facebook_auth_code'] = $_REQUEST['code'];
      }
      
      // -- Step 1, Direct User to Facebook for Authentication
      if($_SESSION['facebook_auth'] == FALSE){
        header("LOCATION: https://www.facebook.com/dialog/oauth?client_id=".$this->facebook_consumer_key."&scope=manage_pages&redirect_uri=".urlencode($this->facebook_redirect_uri));
      }
      
      // -- Step 2, Get access token from user
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?'.
                                    'client_id='.$this->facebook_consumer_key.
                                    '&redirect_uri='.urlencode($this->facebook_redirect_uri).
                                    '&client_secret='.$this->facebook_consumer_secret.
                                    '&code='.$_SESSION['facebook_auth_code']);
                                 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //execute the request
      $return = curl_exec($ch);
      curl_close($ch);
      
      if(strstr($return, "access_token")){
        $returnArray = explode("&",$return);
        $tokenArray = explode("=",$returnArray[0]);
        $_SESSION['facebook_access_token'] = $tokenArray[1];
        //$_SESSION['facebook_expires'] = $tokenArray
      } else {
        $facebook_response = json_decode($return);
      }
      
      // If Error
      if(isset($facebook_response->error)){
        $_SESSION['facebook_auth'] = FALSE;
        $_SESSION['facebook_auth_code'] = "";
        unset($_SESSION['facebook_access_token']);
        die("Facebook Error Please Try Again..."); // -kjs sometimes we get an error here...
      }
    //}
  }
  
  /**
   * Get Meetup Events
   */
  function get_meetup_events(){
    
    
    echo "Enter Meetup Url Name:";
    echo "<form><input type='text' name='meetup-group-name' /> <input type='submit' value='Submit' /></form>";
    
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?'.
                                  'group_url_name='.$token.'&limit=5000');
    
    
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
    
    //echo "<pre>";
    //var_dump($return);
    //echo "</pre>";
    
    // decode return;
    $return = json_decode($return);
    echo "<br><br><br>";
    //echo $return;
    */
           
  }
  
  /**
   * Fet Facebook Events
   */
  function get_facebook_events(){
    return true;
  }
  
  function sync_events(){
    return true;
  }
  
  
  /**
   * Finish up
   */
  function finish_up(){
    
    echo "<br><br><br><pre>";
    var_dump($_SESSION);
    echo "</pre>";
    
    echo "<br><br><br><pre>";
    var_dump($this);
    echo "</pre>";
    
    
    
    //var_dump($_REQUEST);
    //echo "Done!";
  }
  
}
?>