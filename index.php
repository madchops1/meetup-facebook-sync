<?php
session_start();
include 'config.php';
include 'classes.php';

// -- Clear Vars
$_SESSION['meetup_group_object']   = new stdClass();
$_SESSION['user_object']           = new stdClass();
$_SESSION['meetup_name']           = '';
$_SESSION['fb_page_id']            = '';
$_SESSION['meetups']               = array();
$_SESSION['fb_events']             = array();
$_SESSION['meetup_token']          = '';
$_SESSION['formatted_fb_events']   = array();
$_SESSION['formatted_meetups']     = array();
$_SESSION['meetup_group_venues']   = array();
$_SESSION['meetup_token']          = array();
$_SESSION['refresh_token']         = array();
$_SESSION['user_email']            = '';

// -- Form Action
if($_REQUEST['meetup_name'] && $_REQUEST['fb_page_id'] && $_REQUEST['email']){
  
  
  $_SESSION['meetup_name'] = $_REQUEST['meetup_name'];
  $_SESSION['fb_page_id'] = $_REQUEST['fb_page_id'];
  $_SESSION['user_email'] = $_REQUEST['email'];
  // -- Redirect to the meetup oauth page
  header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=".$meetup_app_id."&response_type=code&redirect_uri=".$meetup_redirect_uri."");
  die;
}

include 'includes/header.php';

?>

    
    
  
    <div class='form-wrapper'>
    
      <div class='form-inner-wrapper'>
        <p>
        <span class='how'>HOW IT WORKS:</span><br>
        Enter your meetup group url, your facebook page id, and click "Sync". Subscribe if you want your events to be in sync in real-time forever.
        </p>
      
        <form>
        
           <div>
            <label>Email</label>
            <input type='text' name='email' class='input' value='' /> <br>
            <label>&nbsp;</label>ex. "tibbertots@gmail.com"
          </div><br>
          
          <div>
            <label>Meetup Name</label>
            <input type='text' name='meetup_name' class='input' value='' /> <br>
            <label>&nbsp;</label>ex. "Chicago-Foosball"
          </div><br>
          
          <div>
            <label>FB Page ID</label>
            <input type='text' name='fb_page_id' class='input' value='' /> <br>
            <label>&nbsp;</label>ex. "474860665902713"
          </div><br>
          
          <div>
            <input type='submit' class='sync-once-button' value='Sync My Meetup & Facebook' />
          </div>
          
          <!-- 
          <div>
            
            <a class="wepay-widget-button wepay-green" id="wepay_widget_anchor_528d51205f4a9" href="https://www.wepay.com/subscribe/2010588022/plan/2084467867">Sync Forever</a><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 2084467867,widget_type: "subscription_plan",anchor_id: "wepay_widget_anchor_528d51205f4a9",widget_options: {group_id: 2010588022,show_plan_price: false,reference_id: ""}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://static.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script>
            
          </div>
          -->
        </form>
      </div>  
    </div>
    
    
    <div class="content">
    
      <!-- 
      <h2>Happily Used By:</h2>
      <table style="width:100%;">
        <tr>
          <td><img src='images/hp_tedx.png' /></td>
          <td><img src='images/hp_target.png' /></td>
          <td><img src='images/hp_cisco.png' /></td>
        </tr>
      </table>
      -->
      
      <h2>About:</h2>
      
      <p>
      Let's say you have a facebook page and/or a meetup.com group. 
      Use this service to sync your events easily in both places. 
      It will copy events from your meetup group page to your facebook group page and vice-versa. 
      You must have permission to create events in both places. 
      Provided by <a href='http://webksd.com'>KSD</a> and created by <a href='http://karlsteltenpohl.com'>Karl Steltenpohl</a> (founder of the <a href='http://meetup.com/Chicago-Foosball'>Chicago Foosball Meetup</a>).
      </p>
      
      <p>
      Once your pages are in sync, newly created events from your meetup page will be 
      automagically copied to your facebook page and vice-versa! So they will stay in sync in 
      real-time forever. Our sync app runs every 10 minutes so if you don't 
      see automated results immidiately please wait a few minutes.
      </p>
       
    </div>
    <?php 
    include 'includes/footer.php';
    ?>
    