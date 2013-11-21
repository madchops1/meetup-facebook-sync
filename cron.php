<?php  
include 'config.php';
include 'classes.php';
die();
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
  
  // -- See if our meetup token is still good
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
  // -- Get A New Token With the Refresh Token
  else {
    var_dump($return);
    /*
    // -- Get a new token
    // here...
    To refresh an access_token, have your server make an HTTP application/x-www-form-urlencoded encoded POST request for an access token with the following format, this time setting grant_type to refresh_token.
    https://secure.meetup.com/oauth2/access
    with the body of the request being (line breaks are for readability)
    
    client_id=YOUR_CONSUMER_KEY
    &client_secret=YOUR_CONSUMER_SECRET
    &grant_type=refresh_token
    &refresh_token=REFRESH_TOKEN_YOU_RECEIVED_FROM_ACCESS_RESPONSE
    
    We also support the usage of query string parameters for this flow but the request method must be POST.
    A successful response will contain the following data in application/json format
    
    {
      "access_token":"ACCESS_TOKEN_TO_STORE",
      "token_type": "bearer",
      "expires_in":3600,
      "refresh_token":"TOKEN_USED_TO_REFRESH_AUTHORIZATION"
    }
    */
    
    
    // -- Else no access error out, send email to user...
    // here....
  }
  
  // -- See if our facebook token is still good
  
  
  
}


?>