<?php
////////////////////////////////////////////////////
// Written By: Nicholas Kreidberg                 //
// Revised By: Nicholas Kreidberg                 //
// Revised On: 10/01/2010                         //
// Desc: fnSanitizePost() takes a $_POST array &  //
// sanitizes all strings for insertion into a db. //
// Call: $aclean_post = fnSanitizePost($_POST);   //
////////////////////////////////////////////////////

function fnSanitizePost($data, $sdb="PG")
{
  //escapes,strips and trims all members of the post array
  if(is_array($data))
  {
    $areturn = array();
    foreach($data as $skey=>$svalue)
    {
      $areturn[$skey] = fnSanitizePost($svalue);
    }
    return $areturn;
  }
  else
  {
    if(!is_numeric($data))
    {
      //with magic quotes on, the input gets escaped twice, we want to avoid this.
      if(get_magic_quotes_gpc()) //gets current configuration setting of magic quotes
      {
        $data = stripslashes($data);
      }
      //escapes a string for insertion into the database
      switch($sdb)
      {
      	case "MySQL":
      	  $data = mysql_real_escape_string($data);
      	  break;
      	case "PG":
      	  $data = pg_escape_string($data);
      	  break;
      }
      
      $data = strip_tags($data);  //strips HTML and PHP tags from a string
    }
    $data = trim($data);  //trims whitespace from beginning and end of a string
    return $data;
  }
}
?>
