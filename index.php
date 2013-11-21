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
  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>

  $( document ).ready(function() {

	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });

	  /*
	  $('.input').keyup(function(event) {
		    if(event.keyCode == 13) {
		    	  console.log('clicked enter');
            event.preventDefault();
		    }
		 });
		 */
  });

  $( document )
  .on("click", ".wepay-dialog-close a",function(e){
	  //alert('holy shit!');
	  $(".sync-once-button").hide();
	  $(".wepay-widget-button").show();
	})
	.on("click", ".wepay-widget-button",function(e){
		$(".sync-once-button").show();
		$(".wepay-widget-button").hide();
  });
  </script>
  
  </head>
  <body>
    <style>
      body{
        font-family:arial;
        background:#f5f5f5;
      }
      
      p{
        font-size:14px;
        
      }
      
      p a{
        color:#666;
      }
      
      .form-wrapper{
        margin:0px auto; 
        width:500px; 
        margin-top:0px; 
        background:#fff; 
        padding:20px; 
        font-size:12px;
        border-radius:20px;
      }
      
      .content{
        width:500px;
        margin:20px auto;
      }
      
      .content img{
        opacity:0.5;
      }
      
      .content img:hover{
        opacity:0.6;
      }
      
      
      .header{
        width:500px;
        margin:40px auto 0 auto;
        display:block;
      }
      
      .footer{
        font-size:10px;
        width:500px;
        margin:10px auto;
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
    clear: both;
    color: #FBEC3F;
    display: block;
    font-family: Verdana;
    font-size: 100px;
    font-style: italic;
    letter-spacing: -22px;
    margin-bottom: -15px;
    margin-right: -210px;
    
    text-shadow:0px 1px 1px #000;
    
      }
      
      .anim750{
        transition: all 750ms ease-in-out;
      }

      form{
        margin-top:20px;
      }
      
      h2{
        color: #999999;
        font-family: georgia;
        font-size: 14px;
        font-style: italic;
        margin: 0;
        padding: 0;
      }
      
      form label{
        display:block;
        float:left;
        width:120px;
        
      }
    
      .how{
        color:#666;
        font-weight:bold;
      }
    
      .sync-once-button{
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        display:none;
        background-color: #A0C500;
        background-image: -moz-linear-gradient(center top , #B6D600, #89B300);
        background-repeat: repeat-x;
        border: 1px solid #759A00;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.35) inset;
        color: #FFFFFF;
        text-shadow: 0 -1px 0 #5A7600;
        cursor:pointer;
        padding:10px 10px;
      }    
      
      .sync-once-button:hover{
        background-color: #B4DE00;
        background-image: -moz-linear-gradient(center top , #CCF000, #9DCD00);
        background-repeat: repeat-x;
        color: #FFFFFF;
      }
      
      .sync-once-button:hover:active, .sync-once-button:hover:focus {
        background-color: #A0C500;
        background-image: -moz-linear-gradient(center top , #89B300, #B6D600);
        background-repeat: repeat-x;
        border: 1px solid #759A00;
        box-shadow: none;
        color: #FFFFFF;
      }
      
#Awesome{
	position: relative;
	width: 180px;
	height: 180px;
	margin: -130px 0 0 0;
  
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
    
      <h1><span class='c'>SYNC</span> Meetup.com <span class='a'>&</span> Facebook <span class='b'>Events</span></h1>
      
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
    
      
      <p>
      <span class='how'>HOW IT WORKS:</span><br>
      Enter your meetup group url, your facebook page id, subscribe, and click "Sync". 
      </p>
    
      <form>
        
        <div>
          <label>Meetup Name</label>
          <input type='text' name='meetup_name' class='input' value='' /> ex. "Chicago-Foosball"
        </div><br>
        
        <div>
          <label>FB Page ID</label>
          <input type='text' name='fb_page_id' class='input' value='' /> ex. "474860665902713"
        </div><br>
        
        <div>
          <input type='submit' class='sync-once-button' value='Sync' />
        </div>
        
        <div>
          <a href="https://stage.wepay.com/subscribe/1422124486/plan/1261354322" id="wepay_widget_anchor_51c8c12ded995" class="wepay-widget-button wepay-green">Subscribe</a><script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script><script type="text/javascript" async="" src="https://stage.wepay.com/min/js/widgets.v2.js"></script><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 1261354322,widget_type: "subscription_plan",anchor_id: "wepay_widget_anchor_51c8c12ded995",widget_options: {group_id: 1422124486,show_plan_price: false,reference_id: "",button_text: "Subscribe"}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://stage.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script>
          <!-- 
          <a class="wepay-widget-button wepay-green" id="wepay_widget_anchor_528d51205f4a9" href="https://www.wepay.com/subscribe/2010588022/plan/2084467867">Sync Forever - $5.00/mo.</a><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 2084467867,widget_type: "subscription_plan",anchor_id: "wepay_widget_anchor_528d51205f4a9",widget_options: {group_id: 2010588022,show_plan_price: false,reference_id: ""}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://static.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script>
          -->
        </div>
        
      </form>
      
    </div>
    
    <div class="content">
      <h2>Happily Used By:</h2>
      <table style="width:100%;">
        <tr>
          <td><img src='images/hp_tedx.png' /></td>
          <td><img src='images/hp_target.png' /></td>
          <td><img src='images/hp_cisco.png' /></td>
        </tr>
      </table>
    
      <!-- 
      <h2>About Us</h2>
      <p>
      This service is provided by <a href='http://webksd.com'>KSD</a> and created by <a href='http://karlsteltenpohl.com'>Karl Steltenpohl</a> (founder of the <a href='http://meetup.com/Chicago-Foosball'>Chicago Foosball Meetup</a>).
      </p>
      
      <p>
      We ask for a $5.00 monthly subscription if the pages will stay synced in real-time forever!
      </p>
      -->
      
    </div>
    
    <div class='footer'>
     &copy; <?php echo date("Y"); ?> Karl Steltenpohl Development LLC. All Rights Reserved. <br>
     In No Way Affiliated with Facebook or Meetup.com.<br>
     <a href='mailto:syncsupport+karl@webksd.com;'>Get Help!</a>
    </div>

    
  </body>
</html>