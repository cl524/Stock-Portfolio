<?php
  session_start();
  $id=$_SESSION['user_id'];
  require 'connect.inc.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sell Stocks</title>
    <style>
          li {
            display: inline;
          }
      </style>
  </head>
  <body>
      <ul>
        <li><a href="https://web.njit.edu/~ajw38/index.php">Home</a></li>
        <li><a href="https://web.njit.edu/~ajw38/myportfolio.php">Portfolio</a></li>
        <li><a href="https://web.njit.edu/~ajw38/transactionspage.php">Stock Transaction History</a></li>
        <li><a href="https://web.njit.edu/~ajw38/buystocks.php">Buy Stocks</a></li>
        <li><a href="https://web.njit.edu/~ajw38/sellstocks.php">Sell Stocks</a></li>
        <li><a href="https://web.njit.edu/~ajw38/cashtransactionspage.php">Cash Transaction History</a></li>
        <li><a href="https://web.njit.edu/~ajw38/withdraw.php">Withdraw</a></li>
        <li><a href="https://web.njit.edu/~ajw38/deposit.php">Deposit</a></li>
        <li><a href="https://web.njit.edu/~ajw38/checkbalance.php">Check Balance</a></li>
        <li><a href="https://web.njit.edu/~ajw38/portfoliobeta.php">Portfolio Optimization</a></li>
     </ul>

    <h1>My Portfolio</h1>
    <h2>My Stocks:</h2>
    
    
    <?php          
    
      include("googleStockFetching/allowedStocks.php"); //list of allowed stocks
      include("googleStockFetching/INRtoUSD.php"); //INR to USD exchange rate    
      
      
      
      //GET THESE FROM DB
      $getStockCodesSQL = "SELECT id, shareCode, ExchangePlace FROM ShareStatus WHERE userId=$id";
      $getNumStocksSQL = "SELECT id, numOfStocks FROM ShareStatus WHERE userId=$id";
      $stockStatusSQL = "SELECT * FROM ShareStatus WHERE userId=$id";
        

        
      $getStockCodes = mysql_query($getStockCodesSQL);
      $getNumStocks = mysql_query($getNumStocksSQL);
      $stockStatus = mysql_query($stockStatusSQL);
 

    
        
      if(! $stockStatus ){
        die('Could not connect to the database: ' . mysql_error());
      }
      
      
      
      /*Printing out the data*/
         
//////////////////////Show the user's current account balance/////////////////
      $sqlMoney = "SELECT totalMoney FROM MoneyBalance WHERE userId = $id";
      $retvalM = mysql_query($sqlMoney)
        or die('Could not get data: ' . mysql_error());
      while($rowM = mysql_fetch_array($retvalM, MYSQL_ASSOC)) {
        $totalMoney = floatval($rowM['totalMoney']);
        //echo $rowM['totalMoney'];
      }
      echo "\n\nYour Money: $".$totalMoney."\n\n";
//-----------------------------------------------------------

//////////////Variables that will be used multiple times/////////////////
      $incrementVar = 0; //incremental variable to be used when cycling rows
      $immediateStockValue = []; //array to house each stock's current share value
      $amountOfEachStock= []; //array to hold the amount of shares per stock
      $rowIDs = []; //assign each row of the soon-to-be created table a temp ID
      $getStockCodeArray = []; //array that contains all stock codes in order of appearance
      $valuePerShare = []; //value of each stock in order of appearance
      $exchangeOfCurrentStock = []; //Get stock exchange for each stock in order of appearance
      $stockNameArray = []; //array with each stock's name in order of appeareance
//---------------------------------------------------------------


//////////////Get Portfolio's basic stock info/////////////////// 
      while($row = mysql_fetch_row($getStockCodes)){
        $exchangeOfCurrentStock[$incrementVar] = $row[2];
        $getStockCodeArray[$incrementVar] = $row[1];
        $rowIDs[$incrementVar]=intval($row[0]);
        $incrementVar++;
      }
//-----------------------------------------------------------------

///////////Create a table to display stock info//////////////////
      $incrementVar = 0;
      echo "<table id='display', border = '1'>"; 
      echo "<tr><td>Row</td>".
           "<td>Stock Name</td>".
           "<td>Stock Code</td>".
           "<td>Amount of Shares</td>".
           "<td>Price Per Share (Current Market Rate)</td>".
           "<td>Total Value (Currently)</td></tr>";
//-----------------------------------------------------------------
           
           
///////////Read stock info from Google and display in a table///////////
      while($row = mysql_fetch_row($stockStatus)){
        $currentStock = $row[1];
        $currentStock = preg_replace('/[\x00-\x1F\x7F]/', '', $currentStock);
        
        include("googleStockFetching/queryGoogle.php");
        
        $amountOfEachStock[$incrementVar] = $row[3];
        if($exchangeOfCurrentStock[$incrementVar] == "NSE"){
          $stockNameArray[$incrementVar] = $Nifty50StockNames[$currentStock];
          $valuePerShare[$incrementVar] = floatval(($googleDecoded->l_fix)*$INRtoUSDrate[0]);
        }
        else{
          $stockNameArray[$incrementVar] = $DOW30StockNames[$currentStock];
          $valuePerShare[$incrementVar] = floatval($googleDecoded->l_fix);
        }
        $immediateStockValue[$incrementVar]=$valuePerShare[$incrementVar]*$row[3]; 
        $rowNum = $incrementVar + 1;
         
         
        echo "<tr><td>{$rowNum}</td>".
             "<td>{$stockNameArray[$incrementVar]}</td>".
             "<td>{$row[2]}</td>".
             "<td>{$amountOfEachStock[$incrementVar]}</td>".
             "<td>$"."{$valuePerShare[$incrementVar]}".
             "<td>$"."{$immediateStockValue[$incrementVar]}</td></tr>";
        $incrementVar++;
        }
//-------------------------------------------------------------
        
        echo "</table>\n\n"; //close the table
    ?>
    
    
<!--//Choose stock to sell and Submit-->
    <form action="sellstocks.php" method="post">
    Which stock are you going to sell? <input type="number" name="rowNumber"><br>
    How many shares will you sell? <input type="number" name="numSharesToSell"><br>
    <input type="submit">
    </form>
<!--/////////////////////////////////-->




<!--//Take the stock chosen to be sold and sell it-->
    <?php   
      include('googleStockFetching/sellStocksSubmitCode.php');
    ?>
  </body>
</html>