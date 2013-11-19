<?php 
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ERROR);
error_reporting(E_ALL);

// Author: Karl Steltenpohl




class mfbsync {
  
  
  
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
    
    if($_REQUEST['code'] != '' && $_REQUEST['state'] != ''){
      $_SESSION['meetup_auth'] = TRUE;
    }
    
    // -- Step 1
    if($_SESSION['meetup_auth'] == FALSE) {
      header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=drdhpm0c4l1haeem1evkcdk2h5&response_type=code&redirect_uri=http://mfbsync.karlsteltenpohl.com/meetup-redirect.php");
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