<?php  
include 'config.php';
include 'classes.php';

$meetups = array();
$facebooks = array();
$facebook_auth = 1;
$meetup_auth = 1;
//die(); // -- Prevent Running for now...

// -- Loop Through Relationships
$select = "  SELECT * FROM fb_meetup_rel";
$result = mysql_query($select);
$ii = 0;
while($row = mysql_fetch_object($result)){
  $ii++;
  echo "<Br><br>Relationship ".$ii."<br>";
  
  // -- Load The User Object
  $select = "  SELECT * FROM users WHERE id='".$row->uid."' LIMIT 1";
  $user_object = mysql_fetch_object(mysql_query($select));
    
  // -- Load The Meetup Page Object
  $select = "  SELECT * FROM meetup_pages WHERE id='".$row->mid."' LIMIT 1";
  $meetup_object = mysql_fetch_object(mysql_query($select));
  
  // -- Load The Facebook Page Object
  $select = "  SELECT * FROM fb_pages WHERE id='".$row->fid."' LIMIT 1";
  $facebook_object = mysql_fetch_object(mysql_query($select));
  
  // -- See if our meetup token is still good
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?group_urlname='.$meetup_object->name.'&access_token='.$meetup_object->access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  // -- Loop the results and put into array
  if(isset($return->results)){
    foreach($return->results as $result){
      $_SESSION['meetups'][] = $result;
    }
  }
  // -- Get A New Token With the Refresh Token
  else {
    //var_dump($return);
    
    // -- Refresh the old token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://secure.meetup.com/oauth2/access');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='.$meetup_app_id.''.
                                         '&client_secret='.$meetup_app_secret.''.
                                         '&grant_type=refresh_token'.
                                         '&refresh_token='.$meetup_object->refresh_token.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    $meetup_response = json_decode($return);
    
    if(isset($meetup_response->token_type) && $meetup_response->token_type == "bearer"){
      
      // -- Update the Database, access token and refresh token in the database
      $update = "  UPDATE meetup_pages 
                   SET 
                   access_token='".$meetup_response->access_token."',
                   access_token='".$meetup_response->refresh_token."' 
                   WHERE id='".$meetup_object->id."'";
      mysql_query($update);
      
      // -- Try for results again...
      // -- See if our meetup token is still good
      $ch = curl_init();
      //Set the URL to work with
      curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?group_urlname='.$meetup_object->name.'&access_token='.$meetup_response->access_token.'');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $return = curl_exec($ch);
      curl_close($ch);
      $return = json_decode($return);
      
      // -- Loop the results and put into array
      if(isset($return->results)){
        foreach($return->results as $result){
          $meetups[] = $result;
        }
      }
    } 
    
    // -- Token no good, and could not refresh, error
    else {
      $meetup_auth = 0;
      echo "**MEETUP ERRORZZZZZ**<br>";
      echo $meetup_response->error_description."<Br>";
    
      // -- Send Error Email...
      $to      = "";
      $subject = "";
      $message = "There was a authorization problem while automatically syncing {Meetup Name} and {Facebook Name}.
                  Go to the following url to fix reset the sync. http://mfbsync.com/?facebook_name={facebook id}&meetup_name={meetup name}";
      mail($to,$subject,$message);
      continue;
    } // -- Else Couldn't Refresh
  } // -- Else Refresh Token
  
  
  // -- See if our facebook token is still good
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$facebook_object->name.'/events?access_token='.$facebook_object->access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  //echo "Facebook /events response:<br><pre>";
  //var_dump($return);
  //echo "</pre><br>";
  //die();
  
  
  if(isset($return->data)){
    foreach($return->data as $facebook_result){
    
      // -- Get the Event Details
      $ch = curl_init();
      //Set the URL to work with
      curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$facebook_result->id.'?access_token='.$facebook_object->access_token.'');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $detailed_return = curl_exec($ch);
      curl_close($ch);
      $detailed_return = json_decode($detailed_return);
      $facebooks[] = $detailed_return;
    }
    
    
  } else {
    $facebook_auth = 0;
    // error, could not use facebook token, must have user mannually do it again...
    //mail()
    // @todo...
    echo "<br>**FACEBOOK ERROR**<br>";
    echo "Facebook /events response:<br><pre>";
    var_dump($return);
    echo "</pre><br>";
    //die();
    continue;
    
  }
  
  
  // -- Get the Meetup Group's Details
  //init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/groups?group_urlname='.$meetup_object->name.'&access_token='.$meetup_response->access_token.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $return = curl_exec($ch);
  curl_close($ch);
  $return = json_decode($return);
  
  // -- Loop the results and put into array
  if(isset($return->results)){
    foreach($return->results as $result){
      $meetup_group_object = $result;
    }
  }
  
  // -- Beign Formatting here...
  $formatted_meetups = format_meetups($meetups);
  $formatted_facebooks = format_facebooks($faceooks);
  
  
  echo "Formatted Meetups:<br>";
  echo "<pre>";
  var_dump($formatted_meetups);
  echo "</pre>";
  
  echo "Formatted Facebooks:<br>";
  echo "<pre>";
  var_dump($formatted_facebooks);
  echo "</pre>";
  
  // -- Begin Syncing here...
  // @todo...
  //sync_events($formatted_meetups,$formatted_facebooks,$meetup_group_object,$meetup_response->access_token);
  sync_events($formatted_meetups,$formatted_facebooks,$meetup_group_object->id,$meetup_object->name,$meetup_response->access_token,$facebook_object->name,$facebook_object->access_token);
  
  
  
}


?>