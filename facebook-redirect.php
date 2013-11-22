<?php 
session_start();
include 'config.php';
//die($_REQUEST['code']);

// -- Step 2, Get access token from user
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?'.
                              'client_id=588715921194851'.
                              '&redirect_uri=http://mfbsync.karlsteltenpohl.com/facebook-redirect.php'.
                              '&client_secret=5509ae4d80411edb07787535d55e144f'.
                              '&code='.$_REQUEST['code']);
                           
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//execute the request
$return = curl_exec($ch);
curl_close($ch);

if(strstr($return, "access_token")){
  $returnArray = explode("&",$return);
  $tokenArray = explode("=",$returnArray[0]);
  $access_token = $tokenArray[1];
  
  // -- Exchange for a long-lived token
  /*
  GET /oauth/access_token?
  grant_type=fb_exchange_token&
  client_id={app-id}&
  client_secret={app-secret}&
  fb_exchange_token={short-lived-token}*/
  // -- Step 2, Get access token from user
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?'.
                                'grant_type=fb_exchange_token'.
                                '&client_id='.$fb_app_id.''.
                                '&client_secret='.$fb_app_secret.''.
                                '&fb_exchange_token='.$access_token);
   
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  //execute the request
  $return = curl_exec($ch);
  curl_close($ch);
  //echo "Long-Term Token:<br>";
  //var_dump($return);
  //die();
  
  // -- If there is a long-term token, we need that long term token ;)
  if(strstr($return, "access_token")){
    $returnArray = explode("&",$return);
    $tokenArray = explode("=",$returnArray[0]);
    $access_token = $tokenArray[1];
  
    // -- Successfully retreived and exchanged access tokens from Meetup and Facebook at this point
    // so we are going to save it to the database.
    
    // -- Check User
    $user_select = "  SELECT * FROM users WHERE email='".$_SESSION['user_email']."' LIMIT 1";
    if($user_result = mysql_fetch_object(mysql_query($user_select))){
      $_SESSION['user_object'] = $user_result;
    } 
    // -- Insert User
    else {
      $query = "  INSERT INTO users SET email='".$_SESSION['user_email']."'";
      mysql_query($query);
      //$uid = mydql_insert_id;
      $user_select = "  SELECT * FROM users WHERE email='".$_SESSION['user_email']."' LIMIT 1";
      $user_result = mysql_fetch_object(mysql_query($user_select));
      $_SESSION['user_object'] = $user_result;
    }
    
    // -- The Page Relationships are per user.
    // -- Loop Through this users relationships and see if this one already exists for this user
    $rel_exists = 0;
    $rel_select = "  SELECT * FROM fb_meetup_rel WHERE uid='".$_SESSION['user_object']->id."'";
    $rel_result = mysql_query($rel_select);
    while($rel = mysql_fetch_object($rel_result)){
      // -- Check the meetup and facebook pages
      $fselect = "  SELECT * FROM fb_pages WHERE id='".$rel->fid."' LIMIT 1";
      $fobj = mysql_fetch_object(mysql_query($fselect));
      $mselect = "  SELECT * FROM meetup_pages WHERE id='".$rel->mid."' LIMIT 1";
      $mobj = mysql_fetch_object(mysql_query($mselect));
      // -- If Meetup and facegook pages exist
      if($mobj->name == $_SESSION['meetup_name'] && $fobj->name == $_SESSION['fb_page_id']){
        $rel_exists = 1;

        // -- Update Meetup
        $update = "  UPDATE meetup_pages
                     SET
                     access_token='".$_SESSION['meetup_token']."',
                     refresh_token='".$_SESSION['refresh_token']."'
                     WHERE id='".$mobj->id."'";
        mysql_query($update);
        
        // -- Update Facebook
        $update = "  UPDATE facebook_pages 
                     SET 
                     access_token='".$access_token."' 
                     WHERE id='".$fobj->id."'";
        
      }
    }
    
    // If the relationship doesn't exist then insert it into the database
    if($rel_exists == 0){
      // -- Insert FB Page
      $query = "  INSERT INTO `fb_pages` 
                  SET 
                  `name`='".$_SESSION['fb_page_id']."',
                  `access_token`='".$access_token."'";
      mysql_query($query);
      $fid = mysql_insert_id();
      
      // -- Insert MU page
      $query = "  INSERT INTO meetup_pages 
                  SET 
                  name='".$_SESSION['meetup_name']."',
                  access_token='".$_SESSION['meetup_token']."',
                  refresh_token='".$_SESSION['refresh_token']."'";
      mysql_query($query);
      $mid = mysql_insert_id();
      
      // -- Connect With the Relational Table
      $query = "  INSERT INTO fb_meetup_rel
                  SET 
                  fid='".$fid."',
                  mid='".$mid."',
                  uid='".$_SESSION['user_object']->id."'";
      mysql_query($query);
    } 
      
    // -- Get Facebook Page Events
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$_SESSION['fb_page_id'].'/events?access_token='.$access_token.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    $return = json_decode($return);
    
    //echo "<pre>";
    //var_dump($return);
    //echo "</pre>";
    //die();
    
    // -- Loop the fb event results and put into array
    if(isset($return->data)){
      foreach($return->data as $result){
        
        // -- Get the Event Details
        $ch = curl_init();
        //Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$result->id.'?access_token='.$access_token.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $detailed_return = curl_exec($ch);
        curl_close($ch);
        $detailed_return = json_decode($detailed_return);
        
        
        $_SESSION['fb_events'][] = $detailed_return;
      }
    }
    
    
    // -- Success, format events
    $i=0;
    foreach($_SESSION['fb_events'] AS $facebook_event){
      $_SESSION['formatted_fb_events'][$i]->title = $facebook_event->name;
      
      // -- Start Time Processing
      // If there is a time in the facebook time
      if(strstr($facebook_event->start_time,"T")){
        
        $date_time_array = explode("T",$facebook_event->start_time);
        $date = $date_time_array[0];
        $time = $date_time_array[1];
        $his = substr($time, 0,8);
        $zone = substr($time, -5);
        
        $formatdate = date("m/d/Y", strtotime($date));
        $new_time = round(strtotime( $formatdate . " " . $his . " " . $zone . "") * 1000);
        
      } 
      // -- else Just date Y-m-d...
      else {
        $new_time = round(strtotime($facebook_event->start_time) * 1000);
      }
      
      $_SESSION['formatted_fb_events'][$i]->start = $new_time;
      $_SESSION['formatted_fb_events'][$i]->location = $facebook_event->location;
      $_SESSION['formatted_fb_events'][$i]->description = '';
      $i++;
    }
    
    // -- Format Meetups
    $i=0;
    foreach($_SESSION['meetups'] as $meetup_event){
      $_SESSION['formatted_meetups'][$i]->title = $meetup_event->name;
      
      // -- Process Time
      $meetup_event->time = date("Y-m-dXXXXh:i:s",($meetup_event->time/1000))."-0000";
      $meetup_event->time = str_replace("XXXX","T",$meetup_event->time);
      $_SESSION['formatted_meetups'][$i]->start = $meetup_event->time;
      $_SESSION['formatted_meetups'][$i]->location = $meetup_event->venue->name;
      $_SESSION['formatted_meetups'][$i]->description = '';
      $i++;
    }
    
    // -- SYNC here...
    // ... Start with facebook...
    
    $synced_facebook_events=0;
    // -- Loop the Facebook events and add them to meetup if necessary
    foreach($_SESSION['formatted_fb_events'] as $facebook_event){
      
      // -- Loop throuhg each meetup event
      $fb_event_synced = 0;
      foreach($_SESSION['formatted_meetups'] as $meetup_event){
        if($meetup_event->title == $facebook_event->title){
          $fb_event_synced = 1;
          break;
        }
      }
      
      // -- If the fb event doesn't exist in meetup
      if($fb_event_synced == 0){
        $synced_facebook_events++;
        
        // -- Venue Processing
        // Get the Meetup Group's Venues...
        //init curl
        $ch = curl_init();
        //Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/venues?group_id='.$_SESSION['meetup_group_object']->id.'&access_token='.$meetup_response->access_token.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        $return = json_decode($return);
        
        // -- Loop the venue results to check for a match
        $location_found = 0;
        $venue = '';
        if(isset($return->results)){
          foreach($return->results as $result){
            if($result->name == $facebook_event->location){
              $location_found = 1;
              $venue_id = $result->id;
              $venue='&venue_id='.$venue_id.'';
            }
          }
        }
        
        
        // -- POST FB EVENT to Meetup
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/event');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'group_id='.$_SESSION['meetup_group_object']->id.''.
                                             '&group_urlname='.$_SESSION['meetup_name'].''.
                                             '&name='.urlencode($facebook_event->title) .
                                             '&time='.$facebook_event->start.''.
                                             $venue.
                                             '&access_token='.$_SESSION['meetup_token']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        
        $output .= $facebook_event->title . " (".$facebook_event->start.") => Meetup";
        
      }
    } 
    
    
    
    // --Loop the Meetup events and add them to Facebook if necessary
    $synced_meetup_events=0;
    foreach($_SESSION['formatted_meetups'] as $meetup_event){
      
      // -- Loop through each facebook event
      $mu_event_synced = 0;
      foreach($_SESSION['formatted_fb_events'] as $facebook_event){
        if($facebook_event->title == $meetup_event->title){
          $mu_event_synced = 1;
          break;
        }
      }
      
      if($mu_event_synced == 0){
        $synced_meetup_events++;
        
        // -- Post Meetup to Facebook Page as Event...
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$_SESSION['fb_page_id'].'/events');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'name='.$meetup_event->title.
                                             '&start_time='.$meetup_event->start.''.
                                             '&description='.$meetup_event->description.''.
                                             '&location='.$meetup_event->location.''.
                                             '&access_token='.$access_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        
        
      }
    }
    
    include 'includes/header.php';
    // -- Debriefing
    echo "<div class='content'>";
    echo "<br><Br>SUCCESS!<br>";
    echo $synced_facebook_events . " facebook events synced to meetup.<br>";
    echo $synced_meetup_events . " meetup events synced to facebook.<br>";
    echo $output;
    echo "</div>";
    include 'includes/footer.php';
    
  }
  // Could not exchange long-term token
  else {
    $facebook_response = json_decode($return);
    // If Error
    if(isset($facebook_response->error)){
      die("<br>**FACEBOOK ERROR**<br>");
    }
  }  
  
} 
// Could not get short-term token
else {
  $facebook_response = json_decode($return);
  // If Error
  if(isset($facebook_response->error)){
    die("<br>**FACEBOOK ERROR**<br>");
  }
}





?>