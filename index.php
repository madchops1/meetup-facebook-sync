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
        margin-top:40px; 
        background:#fff; 
        padding:20px; 
        font-size:12px;
        border-radius:20px;
      }
      
      .header{
        width:500px;
        margin:100px auto 0 auto;
        display:block;
      }
      
      .header h1{
        font-size:20px;
        padding:0px;
      }
      
      .header h1 span.a{
        font-size:22px;
      }
      
      .header h1 span.b{
        font-size:14px;
        font-style:bold;
      }
      
      .header h1 span.c{
        font-size:60px;
        font-style:italic;
        color:cyan;
      }
      
      .anim750{
        transition: all 750ms ease-in-out;
      }

      
      form label{
        display:block;
        float:left;
        width:120px;
        
      }
      
      
#Awesome{
	position: relative;
	width: 180px;
	height: 180px;
	margin: -70px 0 0 0;
  
  backface-visibility: hidden;
  
  float:right;
}

#Awesome .sticky{
	transform: rotate(45deg);
}

#Awesome:hover .sticky{
	transform: rotate(10deg);
}

#Awesome .sticky{
	position: absolute;
	top: 0;
	left: 0;
	width:180px;
	height: 180px;
}

#Awesome .reveal .circle{
	box-shadow: 0 1px 0px rgba(0,0,0,.15);
  
  font-family: 'helvetica neue', arial;
  font-weight: 200;
  line-height: 140px;
  text-align: center;
  
  cursor: pointer;
}

#Awesome .reveal .circle{
	background: #fafafa;
}

#Awesome .circle_wrapper{
	position: absolute;
	width: 180px;
	height: 180px;
	left: 0px;
	top: 0px;
	overflow: hidden;
}

#Awesome .circle{
	position: absolute;
	width: 140px;
	height:  140px;
	margin: 20px;
	
	border-radius: 999px;
}

#Awesome .back{
	height: 10px;
	top: 30px;
}

#Awesome:hover .back{
	height: 90px;
	top: 110px;
}

#Awesome .back .circle{
	margin-top: -130px;
	background-color: #fbec3f;

	background-image: -webkit-linear-gradient(bottom, rgba(251,236,63,.0), rgba(255,255,255,.8));
}

#Awesome:hover .back .circle{
	margin-top: -50px;
}

#Awesome .front{
	height: 150px;
	bottom: 0;
	top: auto;
	
	-webkit-box-shadow: 0 -140px 20px -140px rgba(0,0,0,.3);
}

#Awesome:hover .front{
	height: 70px;
	
	-webkit-box-shadow: 0 -60px 10px -60px rgba(0,0,0,.1);
}

#Awesome .front .circle{
	margin-top: -10px;
	background: #fbec3f;

	background-image: -webkit-linear-gradient(bottom, rgba(251,236,63,.0) 75%, #f7bb37 95%);
  background-image: -moz-linear-gradient(bottom, rgba(251,236,63,.0) 75%, #f7bb37 95%);
  background-image: linear-gradient(bottom, rgba(251,236,63,.0) 75%, #f7bb37 95%);
}

#Awesome h4{
  font-family: 'helvetica neue', arial;
  font-weight: 200;
  text-align: center;
	position: absolute;
	width: 180px;
	height: 140px;
  line-height: 140px;
	
	transition: opacity 50ms linear 400ms;
}

#Awesome:hover h4{
	opacity: 0;
	
	transition: opacity 50ms linear 300ms;
}

#Awesome:hover .front .circle{
	margin-top: -90px;
	background-color: #e2d439;
	background-position: 0 100px;
}
    </style>
  
    <div class='header'>
    
      <h1><span class='c'></span>SYNC</span> Meetup.com <span class='a'>&</span> Facebook <span class='b'>Events</span></h1>
      
      <div id="Awesome" class="anim750">
	      <div class="reveal circle_wrapper">
      		<div class="circle">Great Service!</div>
      	</div>
      						
      	<div class="sticky anim750">
      		<div class="front circle_wrapper anim750">
      			<div class="circle anim750"></div>
      	  </div>
      	</div>
      	
        <h4>$5.00 a month</h4>
      						
        <div class="sticky anim750">
      		<div class="back circle_wrapper anim750">
      			<div class="circle anim750"></div>
      		</div>
      	</div>
      </div>
      
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