<?php
  if(isset($_POST['rowNumber']) && isset($_POST['numSharesToSell'])){
  

  if($incrementVar <= 7){
    echo "You cannot have less than 7 stocks.";
    exit();
  }
/////////Make sure user isn't selling "negativestocks"/////////////
   if($_POST['numSharesToSell'] <0){
     echo "A negative value, really? I already thought of that.\n Wouldn't it be weird if that worked?";
     exit();
   }

//-----------------------------------


  /////////Make sure the user is selecting stocks in the table (not value > last row)
    if($_POST['rowNumber'] > $incrementVar){
    echo "<meta http-equiv='refresh' content='0'>";
    echo 'That is not a valid row.';
    exit();
    }
//-----------------------------------------

////////////////Check to make sure no inputs are 0///////////  
  if($_POST['rowNumber'] == 0 || $_POST['numSharesToSell'] == 0){
    echo "<meta http-equiv='refresh' content='0'>";
    echo 'Inputs cannot be 0 or empty.';
    exit();
  }
//-------------------------------------


////////////////////If submit fields are filled properly/////////////////////
    if(!empty($_POST['rowNumber']) && !empty($_POST['numSharesToSell'])){
      $stockToSellRow = $_POST['rowNumber']; // row of stock being sold
      $numberSharesToSell = $_POST['numSharesToSell']; // # shares that will be sold
      $rowAboutToBeSold = $rowIDs[$stockToSellRow-1];//locate row in MySQL table to be sold and deleted from ShareStatus based on row #
      $currentStock = $getStockCodeArray[$stockToSellRow-1]; //stock about to be sold

      include("googleStockFetching/queryGoogle.php");

              /////Get Current exchange rate from INR (rupees) to USD/////
      include("googleStockFetching/INRtoUSD.php"); //refresh the exchange rate
              //-----------------------------------//


        /////Gather info about stock being sold/////////////
      $sellStockCode = $googleDecoded -> t; //stock code
      $sellStockExchange = $googleDecoded -> e; //stock exchange
      
      if($sellStockExchange == "NSE"){
        $sellStockName = mysql_real_escape_string($Nifty50StockNames[$sellStockCode]);
        $sellMarketPriceINR = floatval($googleDecoded->l_fix);
        $sellMarketPriceUSD = floatval($sellMarketPriceINR*$INRtoUSDrate[0]);
      }
      
      else{
        $sellStockName = mysql_real_escape_string($DOW30StockNames[$sellStockCode]);
        $sellMarketPriceUSD = floatval($googleDecoded->l_fix);
      }

      $stockTotalNumShares = $amountOfEachStock[$stockToSellRow-1]; //total # of shares the stock has
      $sellAmount = floatval($numberSharesToSell*$sellMarketPriceUSD);
      $BalanceAfterSell = floatval($totalMoney + $sellAmount); //Portfolio account balance after selling

                 //-----------------------------------//
      include("googleStockFetching/sellStocksCompare.php");
  } //End !Empty
      echo "<meta http-equiv='refresh' content='0'>";
} //End isset rowNum & numberSharesToSell
    mysql_close($connect);
?>