<?php  

mail('ksteltenpohl@musicdealers.com','Cron From MFBsync Works!','asdfasdf asdf asdfasdf asdas dfasdfadfs');

/*
// -- Loop Through Relationships
$select = "  SELECT * FROM fb_meetup_rel";
$result = mysql_query($select);
while($row = mysql_fetch_object($result)){
  

  // -- Load The Meetup Page Object
  $select = "  SELECT * FROM meetup_pages WHERE id='".$row->mid."' LIMIT 1";
  $meetup_object = mysql_fetch_object(mysql_query($select));
  
  // Load The Facebook Page Object
  $select = "  SELECT * FROM fb_pages WHERE id='".$row->fid."' LIMIT 1";
  $facebook_object = mysql_fetch_object(mysql_query($select));
  
  // -- 
  
  
}
*/

?>