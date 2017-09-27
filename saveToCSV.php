<?php 
    session_start();
    $id=$_SESSION['user_id'];
    require 'connect.inc.php';
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=myPortfolio.csv');  

    include('googleStockFetching/allowedStocks.php');
    $stockCode = '';
    $saveToFileQuery = mysql_query("SELECT shareCode,NumOfShares, exchangePlace FROM ShareStatus WHERE userId=$id"); //find all info from 'Users' table dood!

    //////Save the database info to a CSV file dood!//////
    $outputFile = fopen('php://output', 'wr'); // file pointer connected to the PHP output datastream dood!

    fputcsv($outputFile, array('Stock Name','Stock Code','Number of Shares'));//write column headers to file dood!

    while($currentRow = mysql_fetch_row($saveToFileQuery)){//While query is going on, read each row in order and write to file dood!
      
      if($currentRow[2] == 'NSE'){$stockName = $Nifty50StockNames[trim($currentRow[0])];}
      else{$stockName = $DOW30StockNames[trim($currentRow[0])];}
      fputcsv($outputFile, array($stockName, trim($currentRow[0]), $currentRow[1]));
    }
      fclose($outputFile);
?>