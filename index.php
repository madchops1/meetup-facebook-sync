<?php
session_start();

// -- Clear Vars
$_SESSION['meetup_name']     = '';
$_SESSION['fb_page_id']      = '';
$_SESSION['meetups']         = array();
$_SESSION['fb_events']       = array();


// -- Form Action
if($_REQUEST['meetup_name'] && $_REQUEST['fb_page_id']){
  
  $_SESSION['meetup_name'] = $_REQUEST['meetup_name'];
  $_SESSION['fb_page_id'] = $_REQUEST['fb_page_id'];
  
  // -- Redirect to the meetup oauth page
  header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=drdhpm0c4l1haeem1evkcdk2h5&response_type=code&redirect_uri=http://mfbsync.karlsteltenpohl.com/meetup-redirect.php");
  die;
}

?>

<form>
  
  <div>
    <label>Meetup Name</label>
    <input type='text' name='meetup_name' />
  </div>
  
  <div>
    <label>FB Page ID</label>
    <input type='text' name='fb_page_id' />
  </div>
  
  <div>
  <input type='submit' value='Submit' />
  </div>
  
</form>