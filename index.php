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
  
  <link href="http://fonts.googleapis.com/css?family=Alfa+Slab+One" rel="stylesheet" type="text/css">
  
  
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
      
      .stickercontainer {
	position: relative;
	top: 50px;
	width: 400px;
	height: 400px;
	margin: auto;
	font-size: 400px;
	-moz-transform: rotate(-25deg);
	-webkit-transform: rotate(-25deg);
	-o-transform: rotate(-25deg);
	transform: rotate(-25deg);
}

.sticker {
	position: absolute;
	width: 95%;
	height: 95%;
	top: -10%;
	left: 2.5%;
	background: -moz-radial-gradient(center, ellipse cover, #3f82e7 0%, #000 400%);
	background: -webkit-radial-gradient(center, ellipse cover, #3f82e7 0%, #000 400%);
	background: -o-radial-gradient(center, ellipse cover, #3f82e7 0%, #000 400%);
	background: radial-gradient(center, ellipse cover, #3f82e7 0%, #000 400%);
	box-shadow: 0px 1px 5px -1px #000;
	border-radius: 50%;
}

.stickercrop {
	position: absolute;
	width: 100%;
	height: 100%;
	overflow: hidden;
}

.foldshadow {
	position: absolute;
	width: 80%;
	height: 95%;
	top: -85.5%;
	left: 10%;
	background-color: #000;
	box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.5);
	border-radius: 50%;
}

.foldshadowcrop {
	position: absolute;
	width: 100%;
	height: 100%;
	overflow: hidden;
}

.fold {
	position: absolute;
	width: 95%;
	height: 95%;
	top: -85%;
	left: 2.5%;
	background: -moz-linear-gradient(top, #000 30%, #6899e3 100%);
	background: -webkit-linear-gradient(top, #000 30%, #6899e3 100%);
	background: -o-linear-gradient(top, #000 30%, #6899e3 100%);
	background: linear-gradient(top, #000 30%, #6899e3 100%);
	box-shadow: 0px 1px 5px -1px rgba(0, 0, 0, 0.1);
	border-radius: 50%;
}

.foldcrop {
	position: absolute;
	width: 100%;
	height: 100%;
	overflow: hidden;
}

.text {
	position: absolute;
	width: 100%;
	height: 100%;
	top: 22%;
	overflow: hidden;
	text-align: center;
	font-size: 28%;
	color: #2660b9;
	text-shadow: 0px 2px 3px #6899e3;
	font-family: 'Alfa Slab One', cursive;
}
    </style>
  
    <div class='header'>
      <h1>Meetup.com <=> Facebook Event Sync</h1>
    </div>
    
    
    <div class="stickercontainer">
    <div class="stickercrop"><div class="sticker"></div></div>
    <div class="foldshadowcrop"><div class="foldshadow"></div></div>
    <div class="foldcrop"><div class="fold"></div></div>
    <div class="text">CSS3</div>
    </div>
  
    <div class='form-wrapper'>
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