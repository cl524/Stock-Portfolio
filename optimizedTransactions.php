<?php
  require 'connect.inc.php';


function optimizedTransactions($buyStockCode,  $buyAmount,  $buyValue,  $buyStockFullName,  $buyExchangePlace,  $buyForeignCurrency,
                               $sellStockCode, $sellAmount, $sellValue, $sellStockFullName, $sellExchangePlace, $sellForeignCurrency, $id)
{



    for($x = 0; $x < count($sellStockCode); $x++)
    {

           if($nShare=mysql_query("SELECT `numOfShares` FROM `ShareStatus` WHERE `shareCode` = '$sellStockCode[$x]' AND userId = '$id'")){
              $query_num_rows=mysql_num_rows($nShare);        
              while($row = mysql_fetch_row($nShare)){   
                  $numOfShare=floatVal($row[0]);
                  mysql_query(" UPDATE `ShareStatus` SET `numOfShares` = ('$numOfShare' - '$sellAmount[$x]') WHERE `shareCode` = '$sellStockCode[$x]' AND `userId` = '$id' ");
             }
         }   
        if($blnce=mysql_query(" SELECT `totalMoney` FROM `MoneyBalance` WHERE `userId` = '$id' ")){        
              $query_num_rows=mysql_num_rows($blnce);        
              while($row = mysql_fetch_row($blnce)){    
                  $balance=floatVal($row[0]);
                  mysql_query(" UPDATE `MoneyBalance` SET `totalMoney` = ('$balance' + '$sellAmount[$x]' * '$sellValue[$x]') WHERE `userId` = '$id' ");
             }
         }  
          
       if($sellForeignCurrency[$x]!=0){
              $query="INSERT INTO `ShareTransactions` (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode,ForeignCurrency,UnitPriceForeignCurrency) VALUES ('$sellStockFullName[$x]','$sellAmount[$x]','$sellValue[$x]','$sellExchangePlace[$x]','USD','$id','Sell','$sellStockCode[$x]','INR','$sellForeignCurrency[$x]')";
              $query_run=mysql_query($query);
       }
       if($sellForeignCurrency[$x]==0){
             $query="INSERT INTO `ShareTransactions` (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode) VALUES ('$sellStockFullName[$x]','$sellAmount[$x]','$sellValue[$x]','$sellExchangePlace[$x]','USD','$id','Sell','$sellStockCode[$x]')";
             $query_run=mysql_query($query);
       }
    }
    
    for($y = 0; $y < count($buyStockCode) ; $y++)
    {
       if($nShare=mysql_query(" SELECT `numOfShares` FROM `ShareStatus` WHERE `shareCode` = '$buyStockCode[$y]' AND `userId` = '$id' ")){        
             while($row = mysql_fetch_row($nShare)){    
                  $numOfShare=floatVal($row[0]);
                  mysql_query(" UPDATE `ShareStatus` SET `numOfShares` = ('$nunOfShare' + '$buyAmount[$y]') WHERE `shareCode` = '$buyStockCode[$y]' AND `userId` = '$id' ");
             }
         }
       
       if($blnce=mysql_query(" SELECT `totalMoney` FROM `MoneyBalance` WHERE `userId` = '$id' ")){       
             while($row = mysql_fetch_row($blnce)){    
                  $bbalance=floatVal($row[0]);
                  mysql_query(" UPDATE `MoneyBalance` SET `totalMoney` = ('$bbalance' - '$buyAmount[$y]' * '$buyValue[$y]') WHERE `userId` = '$id' ");   
             }
         } 
       if($buyForeignCurrency[$y]!=0){
              $query1="INSERT INTO `ShareTransactions` (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode,ForeignCurrency,UnitPriceForeignCurrency) VALUES ('$buyStockFullName[$y]','$buyAmount[$y]','$buyValue[$y]','$buyExchangePlace[$y]','USD','$id','buy','$buyStockCode[$y]','INR','$buyForeignCurrency[$y]')";
              $query_run=mysql_query($query1);
       }
       if($buyForeignCurrency[$y]==0){
              $query1="INSERT INTO `ShareTransactions` (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode) VALUES ('$buyStockFullName[$y]','$buyAmount[$y]','$buyValue[$y]','$buyExchangePlace[$y]','USD','$id','buy','$buyStockCode[$y]')";
             $query_run=mysql_query($query1);
       }     
    }  
}

?>