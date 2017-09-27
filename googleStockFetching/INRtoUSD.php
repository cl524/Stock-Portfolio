<?php
$yahooExchangeRate = 'http://finance.yahoo.com/d/quotes.csv?f=l1&s=INRUSD=X';
$handle = fopen($yahooExchangeRate, 'r');
 
if ($handle) {
    $INRtoUSDrate = fgetcsv($handle);
    fclose($handle);
}
$INRtoUSDrate[0]; //show exchange rate
?>