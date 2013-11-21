<?php 

  /**
   *  Production Configuration Settings
   */
  $dbHost                     = 'localhost';
  $dbName                     = 'mfbsync';
  $dbUser                     = 'root';
  $dbPass                     = 'Karlkarl1';
  
  // -- Connect to the Database
  mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
  mysql_select_db($this->dbName);
    
  /**
   * Secured Variables
   */
  $fb_app_id              =  '588715921194851';
  
  
  
?>