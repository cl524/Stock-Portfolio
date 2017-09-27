<?php
  
function optimizationToCSV($domesticReturnOld, $indianReturnOld, $domesticAmountsOld, $indianAmountsOld, $portfolioReturnOld, $portfolioBetaOld, $domesticCodeOld, $indianCodeOld, $domesticRiskContributionOld, $indianRiskContributionOld, $id, $domesticRiskOld, $indianRiskOld, $domesticReturnArrayOld, $indianReturnArrayOld){
//require 'connect.inc.php';
//require 'core.inc.php';
       $getStockCodesSQL = "SELECT * FROM ShareStatus WHERE userId=$id";
       $getStockCodes = mysql_query($getStockCodesSQL);
       if(! $getStockCodes ){
         die('Could not connect to the database: ' . mysql_error());
       }
       $i=0;
       $shareCode=array();
       $sharePrice=array();
       $shareAmount=array();
       $shareValue=array();
       $query_num_rows=mysql_num_rows($getStockCodes);

       while($rowM = mysql_fetch_row($getStockCodes)) {//get all the user's stock tickers
         $shareCode[$i]=$rowM[2];//substr($rowM[2],0,-1);
         $shareAmount[$i]=$rowM[3];
         $gParse = file("https://www.google.com/finance/info?q=NSE:".$shareCode[$i]."&f=etl");
         $exchange = getStockInfo($gParse);//get current stock price
         $sharePrice[$i]=floatval($exchange);
         $shareValue[$i]=$rowM[3]*floatval($exchange);//share value is stock price * number of stocks
         $i++;
       }
       $index=array();
       $stockPrice=array();
       $beta=array();
       $geomean_return=array();

       $indianGeomean_return=array();
       $domesticGeomean_return=array();
       //$indianReturnArray=array();
       //$domesticReturnArray=array();
       $indianPortfolioValue=0;
       $domesticPortfolioValue=0;

       $risk=array();
       $portfolioBeta=0;
       $portfolioReturn=0;
       $portfolioValue=0;
       $domesticTickers = array("V","MMM","AXP","AAPL","BA","CAT","CVX","CSCO","KO","DIS","DD","XOM","GE","GS","HD","IBM","INTC","JNJ","JPM","MCD","MRK","MSFT","NKE","PFE","PG","TRV","UTX","UNH","VZ","WMT");
       for($b=0; $b<count($shareCode); $b++){
         $portfolioValue=$portfolioValue+$shareValue[$b];//This is the total dollar value of the portfolio

                             
         if(!in_array($shareCode[$b],$domesticTickers)==1){
           $indianPortfolioValue=$indianPortfolioValue+$shareValue[$b];
         }
         else{
           $domesticPortfolioValue=$domesticPortfolioValue+$shareValue[$b];
         }


       }
       $innerIndex=0;
       $innerDomdex=0;
       $indianReturn=0;
       $domesticReturn=0;
       for($q=0; $q<count($shareCode); $q++){
         $return=array();
         $indianShareRatio=0;
         $domesticShareRatio=0;
                
         $indexShort=array();
         $stockPriceB=getHistInfo($shareCode[$q],1);//2 years worth of daily historical stock prices
         $stockPrice=array_slice($stockPriceB,1,count($stockPriceB));
         $indexB=getHistInfo($shareCode[$q],0); //2 years worth of daily historical stock prices for the index (DOW or NIFTY 50)
         $index=array_slice($indexB,1,count($indexB));
         $shareRatio=$shareValue[$q]/$portfolioValue;
         $checkInnerIndex=0;
         $checkInnerDomdex=0;

           if(!in_array($shareCode[$q],$domesticTickers)==1){
             $indianShareRatio=$shareRatio*$portfolioValue/$indianPortfolioValue;
           }
           else{
             $domesticShareRatio=$shareRatio*$portfolioValue/$domesticPortfolioValue;
           }
         
         $geomean_return[$q]=0;
         $countIndianReturn=0;
         $countDomReturn=0;
         for($p=0; $p<count($index)-1; $p++){
           $return[$p]=($stockPrice[$p]-$stockPrice[$p+1])/$stockPrice[$p+1];// (Price(DAYn)-Price(DAYn-1))/Price(DAYn-1)*100 is the percent change in returns
           $indexShort[$p]=($index[$p]-$index[$p+1])/$index[$p+1]; //the percent change in returns of the index
           $geomean_return[$q]=$return[$p]+$geomean_return[$q]; //sum of all percent changes in returns

                     
           if(!in_array($shareCode[$q],$domesticTickers)==1){
             $indianGeomean_return[$innerIndex]=$return[$p]+$indianGeomean_return[$innerIndex];
             $checkInnerIndex=1;
             $countIndianReturn++;
           }
           else{
             $domesticGeomean_return[$innerDomdex]=$return[$p]+$domesticGeomean_return[$innerDomdex];
             $checkInnderDomdex=1;
             $countDomReturn++;
           }


         }
         $geomean_return[$q]=$geomean_return[$q]/count($return); //divide sum of all percent changes of returns by n to get the mean percent change in returns
         if($checkInnerIndex==1){
           $indianGeomean_return[$innerIndex]=$indianGeomean_return[$innerIndex]/$countIndianReturn;
           //$indianReturnArray[$innerIndex]=$indianGeomean_return[$innerIndex]*$indianShareRatio;
           $indianReturn=$indianReturn+$indianGeomean_return[$innerIndex]*$indianShareRatio;
           $innerIndex++;
           $checkInnerIndex=0;
         }
         if($checkInnerDomdex==1){
           $domesticGeomean_return[$innerDomdex]=$domesticGeomean_return[$innerDomdex]/$countDomReturn;
           //$domesticReturnArray[$innerDomdex]=$domesticGeomean_return[$innerDomdex]*$domesticShareRatio;
           $domesticReturn=$domesticReturn+$domesticGeomean_return[$innerDomdex]*$domesticShareRatio;
           $innerDomdex++;
           $checkInnerDomdex=0;
         }


         $covari = getCovariance($indexShort,$return); //covariance of the percent change in returns and percent change in returns of the index
         $vari = getVariance($indexShort); //variance of the percent change in the index
         $risk[$q] = getVariance($return);
         $beta[$q] = $covari/$vari;//beta for this stock.
         $portfolioBeta=$portfolioBeta+$beta[$q]*$shareRatio; //portfolio beta is the sum of each stock's beta * its percent value in the portfolio
         $portfolioReturn=$portfolioReturn+$geomean_return[$q]*$shareRatio; //portfolio's return rate is the sum of each stock's rate of return * its percent value in the portfolio
         
         //echo "<p>Stock: " . $shareCode[$q] . ", Beta: " . $beta[$q] . "</p>";
         //echo "<p>Stock: " . $shareCode[$q] . ", Expected Returns: " . $geomean_return[$q] . "%</p>";

       }
       //echo "<p>Portfolio Beta: " . $portfolioBeta . "</p>";
       //echo "<p>Portfolio Expected Returns: " . $portfolioReturn . "%</p>";
       
       
       
       
       
       //CALL OPTIMIZER FUNCTION FROM OPTIMIZER.PHP TO OPTIMIZE PORTFOLIO
       $domesticPrices=array();
       $domesticAmounts=array();
       $indianPrices=array();
       $indianAmounts=array();
       $domesticRisk=array();
       $domesticRiskContribution=array();
       $domesticWeights=array();
       $indianRisk=array();
       $indianRiskContribution=array();
       $indianWeights=array();
       $domesticTickers = array("V","MMM","AXP","AAPL","BA","CAT","CVX","CSCO","KO","DIS","DD","XOM","GE","GS","HD","IBM","INTC","JNJ","JPM","MCD","MRK","MSFT","NKE","PFE","PG","TRV","UTX","UNH","VZ","WMT");
       $domesticIndex=0;
       $indianIndex=0;
       $indianPortfolioValue=0;
       $indianShareValue=array();
       $domesticPortfolioValue=0;
       $domesticShareValue=array();
       $indianCode=array();
       $domesticCode=array();
       for($c=0; $c<count($shareCode); $c++){
         if (!in_array($shareCode[$c], $domesticTickers)==1){
           $indianCode[$indianIndex]=$shareCode[$c];
           $indianRisk[$indianIndex]=$risk[$c];
           $indianPrices[$indianIndex]=$sharePrice[$c];
           $indianAmounts[$indianIndex]=$shareAmount[$c];
           $indianShareValue[$indianIndex]=$shareValue[$c];
           $indianIndex++;
         }
         else{
           $domesticCode[$domesticIndex]=$shareCode[$c];
           $domesticRisk[$domesticIndex]=$risk[$c];
           $domesticPrices[$domesticIndex]=$sharePrice[$c];
           $domesticAmounts[$domesticIndex]=$shareAmount[$c];
           $domesticShareValue[$domesticIndex]=$shareValue[$c];
           $domesticIndex++;             
         }
       }
       for($b=0; $b<count($indianPrices); $b++){
         $indianPortfolioValue=$indianPortfolioValue+$indianShareValue[$b];
       }
       for($a=0; $a<count($domesticPrices); $a++){
         $domesticPortfolioValue=$domesticPortfolioValue+$domesticShareValue[$a];
       }
       for($k=0; $k<count($indianShareValue); $k++){
         $indianWeights[$k]=$indianShareValue[$k]/$indianPortfolioValue;
         $indianRiskContribution[$k]=$indianWeights[$k]*$indianRisk[$k];
       }
       for($j=0; $j<count($domesticShareValue); $j++){
         $domesticWeights[$j]=$domesticShareValue[$j]/$domesticPortfolioValue;
         $domesticRiskContribution[$j]=$domesticWeights[$j]*$domesticRisk[$j];
       }
       
       
       echo "<p>PRE-OPTIMIZATION: </p>";
       echo "<p>Portfolio Beta: " . round($portfolioBetaOld, 3, PHP_ROUND_HALF_UP) . "</p>";
       echo "<p>Portfolio Expected Returns: " . round($portfolioReturnOld*100, 3, PHP_ROUND_HALF_UP) . "%</p>";   
       echo "<p>Foreign Risk Contributions: </p>";
       for($p=0;$p<count($indianCodeOld); $p++){
          echo "<p>" . $indianCodeOld[$p] . ", Risk: " . round($indianRiskOld[$p], 5, PHP_ROUND_HALF_UP) . ", Risk Contribution: " . round($indianRiskContributionOld[$p], 5, PHP_ROUND_HALF_UP) . "</p>";
       }
       echo "<p>Domestic Risk Contributions: </p>"; 
       for($p=0;$p<count($domesticCodeOld); $p++){
          echo "<p>" . $domesticCodeOld[$p] . ", Risk: " . round($domesticRiskOld[$p], 5, PHP_ROUND_HALF_UP) . ",  Risk Contribution: " . round($domesticRiskContributionOld[$p], 5, PHP_ROUND_HALF_UP) . "</p>";
       }  
       echo "<p>POST-OPTIMIZATION: </p>";
       echo "<p>Portfolio Beta: " . round($portfolioBeta, 3, PHP_ROUND_HALF_UP) . "</p>";
       echo "<p>Portfolio Expected Returns: " . round($portfolioReturn*100, 3, PHP_ROUND_HALF_UP) . "%</p>";  
       echo "<p>Foreign Risk Contributions: </p>";
       for($p=0;$p<count($indianCode); $p++){
          echo "<p>" . $indianCode[$p] . ", Risk: " . round($indianRisk[$p], 5, PHP_ROUND_HALF_UP) . ",  Risk Contribution: " . round($indianRiskContribution[$p], 5, PHP_ROUND_HALF_UP) . "</p>";
       }
       echo "<p>Domestic Risk Contributions: </p>"; 
       for($p=0;$p<count($domesticCode); $p++){
          echo "<p>" . $domesticCode[$p] . ", Risk: " . round($domesticRisk[$p], 5, PHP_ROUND_HALF_UP) . ",  Risk Contribution: " . round($domesticRiskContribution[$p], 5, PHP_ROUND_HALF_UP) . "</p>";
       }
       $queryString1=http_build_query(array("v1" =>$indianCodeOld));
       $queryString2=http_build_query(array("v2" =>$indianAmountsOld));
       $queryString3=http_build_query(array("v3" =>$indianRiskContributionOld));
       $queryString4=http_build_query(array("v4" =>$domesticCodeOld));
       $queryString5=http_build_query(array("v5" =>$domesticAmountsOld));
       $queryString6=http_build_query(array("v6" =>$domesticRiskContributionOld));
       $queryString7=http_build_query(array("v9" =>$indianCode));
       $queryString8=http_build_query(array("v10" =>$indianAmounts));
       $queryString9=http_build_query(array("v11" =>$indianRiskContribution));
       $queryString10=http_build_query(array("v12" =>$domesticCode));
       $queryString11=http_build_query(array("v13" =>$domesticAmounts));
       $queryString12=http_build_query(array("v14" =>$domesticRiskContribution));
       $queryString13=http_build_query(array("v17" =>$indianRiskOld));
       $queryString14=http_build_query(array("v18" =>$domesticRiskOld));
       $queryString15=http_build_query(array("v19" =>$indianRisk));
       $queryString16=http_build_query(array("v20" =>$domesticRisk));
       $queryString17=http_build_query(array("v21" =>$indianReturnArrayOld));
       $queryString18=http_build_query(array("v22" =>$domesticReturnArrayOld));
       $queryString19=http_build_query(array("v23" =>$indianGeomean_return));
       $queryString20=http_build_query(array("v24" =>$domesticGeomean_return));
       echo "<p><li><a href=\"https://web.njit.edu/~ajw38/saveOpto.php?$queryString1&$queryString2&$queryString3&$queryString4&$queryString5&$queryString6&v7=$portfolioReturnOld&v8=$portfolioBetaOld&$queryString7&$queryString8&$queryString9&$queryString10&$queryString11&$queryString12&v15=$portfolioReturn&v16=$portfolioBeta&$queryString13&$queryString14&$queryString15&$queryString16&$queryString17&$queryString18&$queryString19&$queryString20\">Download Optimization Report</a></li></p>";
/*       echo "<p><li><a href=\"https://web.njit.edu/~ajw38/saveOpto.php?v1=$indianCodeOld&v2=$indianAmountsOld&v3=$indianRiskContributionOld&v4=$domesticCodeOld&v5=$domesticAmountsOld&v6=$domesticRiskContributionOld&v7=$portfolioReturnOld&v8=$portfolioBetaOld&v9=$indianCode&v10=$indianAmounts&v11=$indianRiskContribution&v12=$domesticCode&v13=$domesticAmounts&v14=$domesticRiskContribution&v15=$portfolioReturn&v16=$portfolioBeta\">Download Optimization Report</a></li></p>";
  */     
              //Function to get Variance
       function getVariance($values){
          $mean = array_sum($values)/count($values);
          $diffs=array();
          for($m=0; $m<count($values); $m++){
            $diffs[$m]=$values[$m]-$mean;
            $diffs[$m]=pow($diffs[$m],2);
          }         
          return array_sum($diffs)/count($diffs);
       }
       
       //Function to get Covariance
       function getCovariance($valsA,$valsB){
          $meanA=array_sum($valsA)/count($valsA);
          $meanB=array_sum($valsB)/count($valsB);
          $sumCov=0;
          for($pos=0;$pos<count($valsA);$pos++){
            $vA=$valsA[$pos];
            $vB=$valsB[$pos];
            $difA=$vA-$meanA;
            $difB=$vB-$meanB;
            $sumCov=$sumCov+($difA*$difB);
          }
          return ($sumCov /(count($valsA)));
       }
       
       //Function to call historical stock prices from Google
       function getHistInfo($symbol, $which) {
            $Nifty50Stocks = array("LT","ACC","ADANIPORTS","AMBUJACEM","ASIANPAINT","AUROPHARMA","AXISBANK","BAJAJ-AUTO","BANKBARODA","BPCL","BHARTIARTL","BOSCHLTD","CIPLA","COALINDIA","DRREDDY","EICHERMOT","GAIL","GRASIM","HCLTECH","HDFCBANK","HEROMOTOCO","HINDALCO","HINDUNILVR","HDFC","INFRATEL","ITC", "IOC","ICICIBANK","IBULHSGFIN","INDUSINDBK","INFY","KOTAKBANK","LUPIN","M&M","MARUTI","NTPC","ONGC","POWERGRID","RELIANCE","SBIN","SUNPHARMA","TCS","TATAMOTORS","TATAMTRDVR","TATAPOWER","TATASTEEL","TECHM","ULTRATECH","WIPRO","YESBANK","ZEEL");
            $historicalPrices = array();
            $kount=0;
            if($which==0){//if which is 0, get the historical data for the DOW index for local stocks, NIFTY 50 index for Indian stocks
              if (in_array($symbol, $Nifty50Stocks)){
                $parseNoCSV = file("https://www.google.com/finance/getprices?q=NIFTY&p=2Y&f=c");
              }
              else{
                $parseNoCSV = file("https://www.google.com/finance/getprices?q=DOW&p=2Y&f=c");
              }
              for($k=count($parseNoCSV); $k>6; $k--){
                $checkThis="".$parseNoCSV[$k];
                $pos=strpos($checkThis,"T");
                if($pos===FALSE){
                  $historicalPrices[$kount]=$checkThis;
                  $kount=$kount+1;
                }
              }
            }
            else{//else, get the historical data for the user's stock
              $parseNoCSV = file("https://www.google.com/finance/getprices?q=$symbol&p=2Y&f=c");
              for($k=count($parseNoCSV); $k>6; $k--){
                $checkThis="".$parseNoCSV[$k];
                $pos=strpos($checkThis,"T");
                if($pos===FALSE){
                  $historicalPrices[$kount]=$checkThis;
                  $kount=$kount+1;
                }
              }
            }
            return $historicalPrices;
      }
      
      //Function to call current stock data
      function getStockInfo($goParse) {    
         $exPlace2 = "NSE";  
         $exPlace3 = "BSE";
         $shareUnitPrice = 0;
         $garbageToReplace = array(',"e" : ',',"l" : ');
         $goParse[5] = str_replace($garbageToReplace, "", $goParse[5]);
         $goParse[6] = str_replace($garbageToReplace, "", $goParse[6]); 
         $goParse[5] = substr($goParse[5],1,-2);
         $goParse[6] = substr($goParse[6],1,-2);  
         if(strcmp($exPlace2,trim($goParse[5]))==0 || strcmp($exPlace3,trim($goParse[5]))==0 ){        
            $shareUnitPrice = currency("INR", "USD", floatval(preg_replace('/[^\d.]/', '', $goParse[6])));
         }
         else{
            $shareUnitPrice =  $goParse[6]; 
         }
         return $shareUnitPrice;
      }
      
      //Function to exchange currency       
      function currency($from_Currency,$to_Currency,$amount) {
            $amount = urlencode($amount);
            $from_Currency = urlencode($from_Currency);
            $to_Currency = urlencode($to_Currency);
            $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
            $get = explode("<span class=bld>",$get);
            $get = explode("</span>",$get[1]);  
            $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
            return round($converted_amount,2);
      }
      
       return;
} 
?>
