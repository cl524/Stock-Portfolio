<!DOCTYPE html>
    <html>

    <head>
        <title>Portfolio Manager</title>
        <style type='text/css'>
            table,
            th,
            td {
                border: 1px solid black;
            }
            
            li {
                display: inline;
            }
        </style>
    </head>

<body>


<?php
//include 'optimizedTransactions.php';
include('googleStockFetching/INRtoUSD.php');
include('googleStockFetching/allowedStocks.php');
//function optimizer($riskContribution, $amounts, $risk, $code, $portValue, $portReturns){

  $numberOfSharesToBuy = [];  //Total number of shares to buy
  $numberOfSharesToSell = [];  //Total number of shares to sell
  $MarketPriceINR = []; //(BSE only) price per shre in Indian rupees
  $MarketPriceUSD = []; // Price per sharein US dollars
  $StockExchange = []; //Stock exchange for each stock
  $StockFullNames = []; // The full name for each stock

$code = ['AAPL', 'KO' ,'MSFT']; //DEBUG: manually set stock codes
$numShares = [10, 10, 10]; //DEBUG: manually set # shares per stock
$riskValues = [0.3,0.3,0.3];//DEBUG: manually set risk values

  $numberOfStocks = (int)(count($code)); //Number of stocks being optimized
//  $numShares = $amounts; //Number of shares per stock (s)
//  $riskValues = $risk; //risk values per stock (r)
  $numShareChanges = []; // change in number of shares after optimization (DeltaS)
  $valuePerShare = []; //exactly what it sounds like (v)
  
/////////Get stock values from Google//////////
  for($i = 0; $i < $numberOfStocks; $i++){
    $currentStock = $code[i];
    include("googleStockFetching/queryGoogle.php");
    $StockExchange[i] = $googleDecoded -> e; //stock exchange

    if($StockExchange == "NSE"){
      $StockFullNames[i] = mysql_real_escape_string($Nifty50StockNames[$currentStock]);
      $MarketPriceINR[i] = floatval($googleDecoded->l_fix);
      $MarketPriceUSD[i] = floatval($sellMarketPriceINR*$INRtoUSDrate[0]);
    }
      
    else{
      $StockFullNames[i] = mysql_real_escape_string($DOW30StockNames[$currentStock]);
      $MarketPriceINR[i] = 0;
      $MarketPriceUSD[i] = floatval($googleDecoded->l_fix);
    }
      
      $valuePerShare[i] = floatval($MarketPriceUSD[i]); //fill array with market Values per share
  }
//+++++++++++++++++++++++++++++++++++++++++++++++++//

    $r1 = $riskValues[0];
    $r2 = $riskValues[1];
    $r3 = $riskValues[2];
    $r4 = $riskValues[3];
    $r5 = $riskValues[4];
    $r6 = $riskValues[5];
    $r7 = $riskValues[6];
    //$r8 = $riskValues[7];
    //$r9 = $riskValues[8];
    //$r10 = $riskValues[9];
    
    $v1 = $valuePerShare[0];
    $v2 = $valuePerShare[1];
    $v3 = $valuePerShare[2];
    $v4 = $valuePerShare[3];
    $v5 = $valuePerShare[4];
    $v6 = $valuePerShare[5];
    $v7 = $valuePerShare[6];
    //$v8 = $valuePerShare[7];
    //$v9 = $valuePerShare[8];
    //$v10 = $valuePerShare[9];

    $s1v1 = $numShares[0]*$valuePerShare[0];
    $s2v2 = $numShares[1]*$valuePerShare[1];
    $s3v3 = $numShares[2]*$valuePerShare[2];
    $s4v4 = $numShares[3]*$valuePerShare[3];
    $s5v5 = $numShares[4]*$valuePerShare[4];
    $s6v6 = $numShares[5]*$valuePerShare[5];
    $s7v7 = $numShares[6]*$valuePerShare[6];
    //$s8v8 = $numShares[7]*$valuePerShare[7];
    //$s9v9 = $numShares[8]*$valuePerShare[8];
    //$s10v10 = $numShares[9]*$valuePerShare[9];

    $r1v1 = $riskValues[0]*$valuePerShare[0];
    $r2v2 = $riskValues[1]*$valuePerShare[1];
    $r3v3 = $riskValues[2]*$valuePerShare[2];
    $r4v4 = $riskValues[3]*$valuePerShare[3];
    $r5v5 = $riskValues[4]*$valuePerShare[4];
    $r6v6 = $riskValues[5]*$valuePerShare[5];
    $r7v7 = $riskValues[6]*$valuePerShare[6];
    //$r8v8 = $riskValues[7]*$valuePerShare[7];
    //$r9v9 = $riskValues[8]*$valuePerShare[8];
    //$r10v10 = $riskValues[9]*$valuePerShare[9];

    $r1s1v1 = $r1v1*$numShares[0];
    $r2s2v2 = $r2v2*$numShares[1];
    $r3s3v3 = $r3v3*$numShares[2];
    $r4s4v4 = $r4v4*$numShares[3];
    $r5s5v5 = $r5v5*$numShares[4];
    $r6s6v6 = $r6v6*$numShares[5];
    $r7s7v7 = $r7v7*$numShares[6];
    //$r8s8v8 = $r8v8*$numShares[7];
    //$r9s9v9 = $r9v9*$numShares[8];
    //$r10s10v10 = $r10v10*$numShares[9];


  echo 'Number of Stocks = '.$numberOfStocks.'<br>'; //DEBUG: number of stocks


  switch ($numberOfStocks){
      case 1:
          break;

      case 2:
          include 'PortOptCode/portOpt2.php';
          break;

      case 3:
          include 'PortOptCode/portOpt3.php';
          break;
    
      case 4:
          include 'PortOptCode/portOpt4.php';
          break;
    
      case 5:
          include 'PortOptCode/portOpt5.php';
          break;

      case 6:
          include 'PortOptCode/portOpt6.php';
          break;

      case 7:
          include 'PortOptCode/portOpt7.php';
          break;

      default:
          echo 'You breaked the system!!!';
          break;
  }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//

////////Determine if shares are being bought or sold & fill appropriate table//////
  for($i=0; $i < $numberOfStocks; $i++){
    echo 'i = '.$i;
    if($numShareChanges[i] < 0){
      $numberOfSharesToBuy = 0;
      $numberOfSharesToSell = $numShareChanges[i]*(-1);
    }
    
    if($numShareChanges[i] > 0){
      $numberOfSharesToBuy = $numShareChanges[i];
      $numberOfSharesToSell = 0;
    }
    
    else{
      $numberOfSharesToBuy = 0;
      $numberOfSharesToSell = 0;
    }
  }

  //if($result<=$portReturns){
      //return "<p> Already Optimal </p>";
  //}
  //else{
//      optimizedTransactions($numberOfSharesToBuy, $numberOfSharesToSell, $MarketPriceUSD, $MarketPriceINR);
  //}
  //return "<p> Optimizer called </p>";
//}
?>
</body>
</html>