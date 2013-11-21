<?php
session_start();

// -- Clear Vars
$_SESSION['meetup_group_object']   = new stdClass();
$_SESSION['meetup_name']           = '';
$_SESSION['fb_page_id']            = '';
$_SESSION['meetups']               = array();
$_SESSION['fb_events']             = array();
$_SESSION['meetup_token']          = '';
$_SESSION['formatted_fb_events']   = array();
$_SESSION['formatted_meetups']     = array();
$_SESSION['meetup_group_venues']   = array();

// -- Form Action
if($_REQUEST['meetup_name'] && $_REQUEST['fb_page_id']){
  
  $_SESSION['meetup_name'] = $_REQUEST['meetup_name'];
  $_SESSION['fb_page_id'] = $_REQUEST['fb_page_id'];
  
  // -- Redirect to the meetup oauth page
  header("LOCATION: https://secure.meetup.com/oauth2/authorize?client_id=drdhpm0c4l1haeem1evkcdk2h5&response_type=code&redirect_uri=http://mfbsync.karlsteltenpohl.com/meetup-redirect.php");
  die;
}

?>
<html>
  <head>
  
  
  
  </head>
  <body>
    <style>
      body{
        font-family:arial;
        background:#f5f5f5;
      }
      
      .form-wrapper{
        margin:0px auto; 
        width:500px; 
        margin-top:200px; 
        background:rgba(100,100,100,0.6); 
        padding:20px; 
        font-size:12px;
        border-radius:20px;
        
      }
    </style>
  
    <div class='form-wrapper'>
      <h1>Meetup.com <=> Facebook Event Sync</h1>
      <p>Enter your meetup group url, your facebook page id, andclick "Sync". All upcoming events will be synced on both sites!</p>
      <form>
        
        <div>
          <label>Meetup Name</label>
          <input type='text' name='meetup_name' value='' /> ex. "Chicago-Foosball"
        </div><br>
        
        <div>
          <label>FB Page ID</label>
          <input type='text' name='fb_page_id' value='' /> ex. "474860665902713"
        </div><br>
        
        <div>
          <input type='submit' value='Sync' />
        </div>
        
        <div>
          <a class="wepay-widget-button wepay-green" id="wepay_widget_anchor_528d51205f4a9" href="https://www.wepay.com/subscribe/2010588022/plan/2084467867">Sync</a><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 2084467867,widget_type: "subscription_plan",anchor_id: "wepay_widget_anchor_528d51205f4a9",widget_options: {group_id: 2010588022,show_plan_price: false,reference_id: ""}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://static.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script>
        </div>
        
      </form>
    </div>

  </body>
</html>