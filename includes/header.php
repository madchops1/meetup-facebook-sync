<html>
  <head>
  <title>SYNC Facebook and Meetup.com Events</title>
  <meta name="keywords" content="Sync Facebook Meetup, Sync Facebook Events, Sync Meetup Events, Synchronize Facebook and Meetup, Sync Facebook and Meetup, Meetup Group and Facebook Events">
  <meta name="description" content="Synk Facebook Page and Meetup.com Group Events">
  <meta name="author" content="Karl Steltenpohl">
  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>

  $( document ).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
  });

  </script>
  
  </head>
  <body>
  
  
    <style>
      body{
        font-family:arial;
        background:#f5f5f5;
        margin:0px;
        padding:0px;
      }
      
      p{
        font-size:14px;
        
      }
      
      p a{
        color:#666;
      }
      
      .form-wrapper{
        margin:-10px auto 0 auto; 
        width:500px; 
        background:#3B5998; 
        font-size:12px;
        border-radius:300px;
        color:#fff;
        height:500px;
        border:10px solid #fff;
       
      }
      
      .form-inner-wrapper{
        padding:95px 85px 0;
      }
        
      .wrapper-content{
        color: #83B34C;
        font-family: georgia;
        font-size: 30px;
        font-style: italic;
        font-weight: lighter;
        margin: 140px auto !important;
        text-align: center;
        width: 500px;
      }
            
      .content{
        width:500px;
        margin:20px auto;
      }
      
      .content p{
        color: #666666;
        font-family: georgia;
        font-size: 14px;
        line-height: 22px;
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
        margin:0px auto;
        color:#fff;
      }
      
      .footer a{
        color:#FBEC3F;
      }
      
      .big-footer{
        background-color:#333;
        width:100%;
        padding:20px 0px;
        position:relative;
        margin-top:40px; 
      }
      
      
      .header h1{
        font-size:20px;
        padding:0px;
        margin:0px;
      }
      
      .header h1 span.a{
        font-size:22px;
      }
      
      .header h1 span.b{
        font-size:14px;
        font-style:bold;
      }
      
      .header h1 span.c{
        color: #FBEC3F;
        display: block;
        font-family: Verdana;
        font-size: 50px;
        font-style: italic;
        letter-spacing: -11px;
        margin-bottom: -15px;
        margin-right: -210px;
        text-shadow: 0 1px 0 #000000;
    
      }
      
      .header h1 span.big{
        display: block;
        float: left;
        font-family: Verdana;
        font-size: 100px;
        font-style: italic;
        letter-spacing: -17px;
        text-shadow: 0 1px 1px #000000;
    
      }
      
      .header h1 span.mm{
        color: #E51937;
    
      }
      
      .header h1 span.ff{
        color: #3B5998;
    
    
      }
      
      .header h1 span.bb{
        color: #3B5998;
    
    
      }
      
      .header h1 div{
        clear:both;
        color: #666666;
        display: block;
        font-family: georgia;
        font-style: italic;
        font-weight: lighter;
        position: relative;
        top: -16px;
      }
      
      .footer-red{
        width:33.33%;
        background:#E51937;
        height:5px;
        position:absolute;
        top:0px;
        left:0px;
      }
      
      .footer-blue{
        width:33.33%;
        background:#3B5998;
        height:5px;
        position:absolute;
        top:0px;
        left:33.33%
      }
      
      .footer-yellow{
        width:33.33%;
        background:#FBEC3F;
        height:5px;
        position:absolute;
        top:0px;
        left:66.66%;
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
        font-size: 20px;
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
        color:#fff;
        font-weight:bold;
      }
    
      .sync-once-button{
        border-radius:3px;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        background-color: #A0C500;
        background-image: -moz-linear-gradient(center top , #B6D600, #89B300);
        background-repeat: repeat-x;
        border: 1px solid #759A00;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.35) inset;
        color: #FFFFFF;
        text-shadow: 0 -1px 0 #5A7600;
        cursor:pointer;
        padding:11px 6px;
        float:left;
        margin-right:10px;
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
	margin: -115px 30px 0 0;
  
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
    
      <h1>
        <span class='mm big'>M</span>
        <span class='ff big'>F</span>
        <span class='bb big'>B</span>
        <span class='c'>SYNC</span> 
        <div>
          <span class='b'>Sync</span> Meetup.com <span class='a'>&</span> Facebook <span class='b'>Events</span>
        </div>
      </h1>
      
      <div id="Awesome" class="anim750">
	      <div class="reveal circle_wrapper">
      		<div class="circle">...to sync now!</div>
      	</div>
      						
      	<div class="sticky anim750">
      		<div class="front circle_wrapper anim750">
      			<div class="circle anim750"></div>
      	  </div>
      	</div>
      	
        <h4>FREE!</h4>
      						
        <div class="sticky anim750">
      		<div class="back circle_wrapper anim750">
      			<div class="circle anim750"></div>
      		</div>
      	</div>
      </div>
      
    </div>
    