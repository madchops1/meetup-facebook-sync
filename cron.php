<?php  
include 'config.php';
include 'classes.php';
//mail('ksteltenpohl@musicdealers.com','Cron From MFBsync Works!','asdfasdf asdf asdfasdf asdas dfasdfadfs');

//die();

// -- Loop Through Relationships
$select = "  SELECT * FROM fb_meetup_rel";
$result = mysql_query($select);
while($row = mysql_fetch_object($result)){
  
  // -- Load The Meetup Page Object
  $select = "  SELECT * FROM meetup_pages WHERE id='".$row->mid."' LIMIT 1";
  $meetup_object = mysql_fetch_object(mysql_query($select));
  
  // -- Load The Facebook Page Object
  $select = "  SELECT * FROM fb_pages WHERE id='".$row->fid."' LIMIT 1";
  $facebook_object = mysql_fetch_object(mysql_query($select));
  
  // -- See if our token is still good for Meetup
  // init curl
  $ch = curl_init();
  //Set the URL to work with
  curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/events?group_urlname='.$_SESSION['meetup_name'].'&access_token='.$meetup_response->access_token.'');
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
  else {
    echo "";
  }
  
  
  
}


?>