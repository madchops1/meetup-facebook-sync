<?php 
session_start();


// Author: Karl Steltenpohl




class mfbsync {
  
  
  
  /**
   * Constructor // Init...
   */
  function __construct(){
    
    // -- SESSION setup
    if (!isset($_SESSION['meetup_auth'])) { $_SESSION['meetup_auth'] = FALSE; }
    
    meetup_auth();
    finish_up();
    
  }  
  
  /**
   * Meetup Auth Function
   */
  function meetup_auth(){
    
    if($_REQUEST['code'] != '' && $_REQUEST['state'] != ''){
      $_SESSION['meetup_auth'] = TRUE;
    }
    
    // -- Step 1
    if($_SESSION['meetup_auth'] == FALSE) {
      header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=drdhpm0c4l1haeem1evkcdk2h5&response_type=code&redirect_uri=http://mfbsync.karlsteltenpohl.com/meetup_redirect.php");
      exit;
    }
    
  }
  
  /**
   * Finish up
   */
  function finish_up(){
    echo "Done!";
  }
  
}
?>