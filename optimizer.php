<?php
include 'optimizedTransactions.php';
include 'googleStockFetching/INRtoUSD.php';
include 'googleStockFetching/allowedStocks.php';
function optimizer($riskContribution, $amounts, $risk, $code, $portValue, $portReturns, $id){


$yahooExchangeRate = 'http://finance.yahoo.com/d/quotes.csv?f=l1&s=INRUSD=X';
$handle = fopen($yahooExchangeRate, 'r');
 
if ($handle) {
    $INRtoUSDrate = fgetcsv($handle);
    fclose($handle);
}
$INRtoUSDrate[0]; //show exchange rate


        //Array with all stock codes in India Nifty 50 for searching portfolio
  $Nifty50Stocks = array("LT","ACC","ADANIPORTS","AMBUJACEM","ASIANPAINT","AUROPHARMA","AXISBANK","BAJAJ-AUTO","BANKBARODA","BPCL","BHARTIARTL","BOSCHLTD","CIPLA","COALINDIA","DRREDDY","EICHERMOT","GAIL","GRASIM","HCLTECH","HDFCBANK","HEROMOTOCO","HINDALCO","HINDUNILVR","HDFC","INFRATEL","ITC", "IOC","ICICIBANK","IBULHSGFIN","INDUSINDBK","INFY","KOTAKBANK","LUPIN","M&M","MARUTI","NTPC","ONGC","POWERGRID","RELIANCE","SBIN","SUNPHARMA","TCS","TATAMOTORS","TATAMTRDVR","TATAPOWER","TATASTEEL","TECHM","ULTRATECH","WIPRO","YESBANK","ZEEL");
        
  $Nifty50StockNames = array( "LT"  => "Larsen & Toubro Ltd.",
                              "ACC" => "ACC Limited",
                              "ADANIPORTS" => "Adani Ports & SEZ Limited",
                              "AMBUJACEM" => "Ambuja Cements Ltd.",
                              "ASIANPAINTSS" => "Asian Paints Ltd.",
                              "AUROPHARMA" => "Aurobindo Pharma Ltd.",
                              "AXISBANK" => "Axis Bank Ltd.",
                              "BAJAJ-AUTO" => "Bajaj Auto Ltd.",
                              "BANKBARODA" => "Bank of Baroda",
                              "BPCL" => "Bharat Petroleum Corporation",
                              "BHARATIARTL" => "BhartiAirtel Ltd.",
                              "BOSCHLTD" => "Bosch Ltd.",
                              "CIPLA" => "Cipla Ltd.",
                              "COALINDIA" => "Coal India Ltd.",
                              "DRREDDY" => "Dr. Reddy's Laboratories Ltd.",
                              "EICHERMOT" => "Eicher Motors",
                              "GAIL" => "GAIL (India) Ltd.",
                              "GRASIM" => "Grasim Industries Ltd.",
                              "HCLTECH" => "HCL Technologies Ltd.",
                              "HDFCBANK" => "HDFC Bank Ltd.",
                              "HEROMOTOCO" => "Hero MotoCorp Ltd.",
                              "HINDALCO" => "Hindalco Industries Ltd.",
                              "HINDUNILVR" => "HindustanUnilever Ltd.",
                              "HDFC" => "Housing Development Finance Corporation Ltd.",
                              "INFRATEL" => "Bharti Infratel",
                              "ITC" => "ITC Limited",
                              "IOC" => "Indian Oil Corporation",
                              "ICICIBANK" => "ICICI Bank Ltd.",
                              "IBULHSGFIN" => "Indiabulls Housing Finance",
                              "INDUSINDBK" => "IndusInd Bank Ltd.",
                              "INFY" => "Infosys Ltd.",
                              "KOTAKBANK" => "Kotak Mahindra Bank Ltd.",
                              "LUPIN" => "Lupin Limited",
                              "M&M" => "Mahindra & Mahindra Ltd.",
                              "MARUTI" => "Maruti Suzuki India Ltd.",
                              "NTPC" => "NTPC Limited",
                              "ONGC" => "Oil & Natural Gas Corporation Ltd.",
                              "POWERGRID" => "PowerGrid Corporation of India Ltd.",
                              "RELIANCE" => "Reliance Industries Ltd.",
                              "SBIN" => "State Bank of India",
                              "SUNPHARMA" => "Sun Pharmaceutical Industries",
                              "TCS" => "Tata Consultancy Services Ltd.",
                              "TATAMOTORS" => "Tata Motors Ltd.",
                              "TATAMTRDVR" => "Tata Motors (DVR)",
                              "TATAPOWER" => "Tata Power Co. Ltd.",
                              "TATASTEEL" => "Tata Steel Ltd.",
                              "TECHM" => "Tech Mahindra Ltd.",
                              "ULTRATECH" => "UltraTech Cement Ltd.",
                              "WIPRO" => "Wipro",
                              "YESBANK" => "Yes Bank Ltd.",
                              "ZEEL" => "Zee Entertainment Enterprises Ltd."
                              );
        
        //Array with all stocks codes for DOW 30 for searching portfolio
        $DOW30Stocks = array("V","MMM","AXP","AAPL","BA","CAT","CVX",
                             "CSCO","KO","DIS","DD","XOM","GE","GS",
                             "HD","IBM","INTC","JNJ","JPM","MCD","MRK",
                             "MSFT","NKE","PFE","PG","TRV","UTX","UNH",
                             "VZ","WMT");
        
        $DOW30StockNames = array("V" => "Visa",
                                 "MMM" => "3M",
                                 "AXP" => "American Express",
                                 "AAPL" => "Apple",
                                 "BA" => "Boeing",
                                 "CAT" => "Caterpillar",
                                 "CVX" => "Chevron",
                                 "CSCO" => "Cisco",
                                 "KO" => "Coca-Cola",
                                 "DIS" => "Disney",
                                 "DD" => "E I du Pont de Nemours and Co",
                                 "XOM" => "Exxon Mobil",
                                 "GE" => "General Electric",
                                 "GS" => "Goldman Sachs",
                                 "HD" => "Home Depot",
                                 "IBM" => "IBM",
                                 "INTC" => "Intel",
                                 "JNJ" => "Johnson & Johnson",
                                 "JPM" => "JPMorgan Chase",
                                 "MCD" => "McDonald's",
                                 "MRK" => "Merck",
                                 "MSFT" => "Microsoft",
                                 "NKE" => "Nike",
                                 "PFE" => "Pfizer",
                                 "PG" => "Proctor & Gamble",
                                 "TRV" => "Travelers Companies Inc",
                                 "UTX" => "United Technologies",
                                 "UNH" => "United Health",
                                 "VZ" => "Verizon",
                                 "WMT" => "Walmart",
                                 );






  $numberOfSharesToBuy = [0];  //Total number of shares to buy
  $numberOfSharesToSell = [0];  //Total number of shares to sell
  $MarketPriceINR = []; //(BSE only) price per shre in Indian rupees
  $MarketPriceUSD = []; // Price per sharein US dollars
  $StockExchange = []; //Stock exchange for each stock
  $StockFullNames = []; // The full name for each stock
//$result = 0; //total amount spent/gained after optimization

//$code = ['AAPL', 'KO','MSFT']; //DEBUG: manually set stock codes
//$numShares = [20, 100, 100]; //DEBUG: manually set # shares per stock
//$riskValues = [0.3,0.3,0.3];//DEBUG: manually set risk values

  $numberOfStocks = count($code); //Number of stocks being optimized
  $numShares = $amounts; //Number of shares per stock (s)
  $riskValues = $risk; //risk values per stock (r)
  $numShareChanges = []; // change in number of shares after optimization (DeltaS)
  $valuePerShare = []; //exactly what it sounds like (v)
  
/////////Get stock values from Google//////////
  for($i = 0; $i < $numberOfStocks; $i++){
    $currentStock = $code[$i];
    include 'googleStockFetching/queryGoogle.php';
    $StockExchange[$i] = ($googleDecoded -> e); //stock exchange
    if($StockExchange[$i] == "NSE"){ 
      $StockFullNames[$i] = mysql_real_escape_string($Nifty50StockNames[$currentStock]);
      $MarketPriceINR[$i] = floatval($googleDecoded->l_fix);
      $MarketPriceUSD[$i] = floatval($MarketPriceINR[$i]*$INRtoUSDrate[0]);
    }
      
    else{
      $StockFullNames[$i] = mysql_real_escape_string($DOW30StockNames[$currentStock]);
      $MarketPriceINR[$i] = 0;
      $MarketPriceUSD[$i] = floatval($googleDecoded->l_fix);
    }
      $valuePerShare[$i] = floatval($MarketPriceUSD[$i]); //fill array with market Values per share
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


  //echo 'Number of Stocks = '.$numberOfStocks.'<br>'; //DEBUG: number of stocks


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
  }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//

////////Determine if shares are being bought or sold & fill appropriate table//////
$BuyStockExchange=array();
$SellStockExchange=array();
$BuyStockFullNames=array();
$SellStockFullNames=array();
$BuyMarketPriceUSD=array();
$SellMarketPriceUSD=array();
$BuyMarketPriceINR=array();
$SellMarketPriceINR=array();
$BuyStockCode=array();
$SellStockCode=array();
$kount=0;
$kount2=0;

  for($i=0; $i < $numberOfStocks; $i++){
      $stockNum = $i+1;
    if($numShareChanges[$i] < 0){
      $numberOfSharesToSell[$kount] = round($numShareChanges[$i], 0, PHP_ROUND_HALF_UP)*(-1);
      if($numShares[$i]-$numberOfSharesToSell[$kount]<1){
        $numberOfSharesToSell[$kount]=$numShares[$i]-1;
      }
      if($numberOfSharesToSell[$kount]!=0){
        $SellStockExchange[$kount]=$StockExchange[$i];
        $SellStockFullNames[$kount]=$StockFullNames[$i];
        $SellMarketPriceUSD[$kount]=$MarketPriceUSD[$i];
        $SellMarketPriceINR[$kount]=$MarketPriceINR[$i];
        $SellStockCode[$kount]=$code[$i];
        $kount++;
        echo "sell ". $numberOfSharesToSell[$kount-1]." shares of ".$SellStockCode[$kount-1]."<br>";
      }
    }
    
    elseif($numShareChanges[$i] > 0){
      $numberOfSharesToBuy[$kount2] = round($numShareChanges[$i], 0, PHP_ROUND_HALF_UP);
      if($numberOfSharesToBuy[$kount2]!=0){
        $BuyStockExchange[$kount2]=$StockExchange[$i];
        $BuyStockFullNames[$kount2]=$StockFullNames[$i];
        $BuyMarketPriceUSD[$kount2]=$MarketPriceUSD[$i];
        $BuyMarketPriceINR[$kount2]=$MarketPriceINR[$i];
        $BuyStockCode[$kount2]=$code[$i];      
        $kount2++;
        echo "buy " . $numberOfSharesToBuy[$kount2-1] . " shares of ".$BuyStockCode[$kount2-1]."<br>";
      }
    }
  }
  
  optimizedTransactions($BuyStockCode, $numberOfSharesToBuy, $BuyMarketPriceUSD, $BuyStockFullNames, $BuyStockExchange, $BuyMarketPriceINR, $SellStockCode, $numberOfSharesToSell, $SellMarketPriceUSD, $SellStockFullNames, $SellStockExchange, $SellMarketPriceINR, $id);
}
?>