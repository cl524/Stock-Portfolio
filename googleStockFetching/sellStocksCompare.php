<?php

////////////////If # shares sold = total # shares////////////////
  if($numberSharesToSell == $stockTotalNumShares){

                    ////////Update Account Balance///////////
    $sqlupdM = "UPDATE MoneyBalance SET totalMoney = $BalanceAfterSell WHERE userId = $id";
    $updShareM = mysql_query( $sqlupdM);
           
    if(! $updShareM ){
      die('Could not update money balance: ' . mysql_error());
    }
                    //---------------------------//
                    
                    ////////Record Transaction///////////                    
    $sql = "INSERT INTO ShareTransactions (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode) VALUES('$sellStockName','$numberSharesToSell','$sellMarketPriceUSD','$sellStockExchange','USD','$id','Sell','$sellStockCode')";
    
    $checkStoredData = mysql_query($sql);
    if(! $checkStoredData ){
      die('Could not insert row: ' . mysql_error()); 
    }
                    //---------------------------//

                    ///////Remove stock from portfolio////////
    $sqlSellQuery = "DELETE FROM ShareStatus WHERE id=$rowAboutToBeSold"; 
    $sqlSell = mysql_query($sqlSellQuery)
      or die('Could not delete row: ' . mysql_error()); 
                    //---------------------------//
  }
//---------------------------------------------------------




////////////////If # shares sold < total # shares////////////////
  if($numberSharesToSell < $stockTotalNumShares){
                    ////////Update Account Balance///////////
    $sqlupdM = "UPDATE MoneyBalance SET totalMoney = $BalanceAfterSell WHERE userId = $id";
    $updShareM = mysql_query( $sqlupdM);
           
    if(! $updShareM ){
      die('Could not update money balance: ' . mysql_error());
    }
                    //---------------------------//
    
                    ////////Record Transaction///////////
    $sql = "INSERT INTO ShareTransactions (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode) VALUES('$sellStockName','$numberSharesToSell','$sellMarketPriceUSD','$sellStockExchange','USD','$id','Sell','$sellStockCode')";
       
    $checkStoredData = mysql_query($sql);
    if(! $checkStoredData ){
      die('Could not insert data: ' . mysql_error()); 
    }
                    //---------------------------//
                    
                    ///////Update number of shares left////////
    $sharesLeftAfterSelling = $stockTotalNumShares - $numberSharesToSell; //# shares left 
    $sqlUpdateQuery = "UPDATE ShareStatus SET numOfShares = $sharesLeftAfterSelling WHERE id=$rowAboutToBeSold"; 
    $sqlSell = mysql_query($sqlUpdateQuery)
      or die('Could not update # shares: ' . mysql_error()); 
                    //---------------------------//
  }
//--------------------------------------------------------------

  
/////////////If # shares sold > total # shares///////////

  if($numberSharesToSell > $stockTotalNumShares){
      echo "You cannot sell more shares than you own!";
    }
?>