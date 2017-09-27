<?php
  $urlStock = urlencode('http://finance.google.com/finance/info?client=ig&q=NSE:'.$currentStock);
  
  $ASCIIchars = array("%0A", '%26', '%2F', '%3A', '%3D', '%3F'); //ASCII to replace
  $replaceChars = array( "",   '&',   '/',   ':',   '=',   '?'); //replacement chars
  $repGoogle = array_combine($ASCIIchars, $replaceChars); //Array where ASCII => repChars
  
  $urlStock = strtr($urlStock, $repGoogle); //Actually replace ASCII with chars
$googleInfo = file_get_contents($urlStock);
  $googleInfoOneLine = str_replace("\n", "", $googleInfo);   //make the file 1 line to conform with JSON format
  
  $googleInfoCleaning = substr($googleInfoOneLine, 4, strlen($googleInfoOneLine) - 5);  // "// [" and "]" mess up the JSON format, so substr deletes them
  
  $googleDecoded = json_decode(utf8_decode($googleInfoCleaning)); //Actually decode JSON (Must be utf8 encoding or the result will be a null object
  
  ////////////////Check to make sure Google Finance is 'up'////////////
  if($currentStock != $googleDecoded -> t){
      exit('Unable to connect to Google Finance. Maybe their servers are having issues?');
  }

?>