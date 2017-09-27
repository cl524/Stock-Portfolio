<?php
  session_start();
  require 'connect.inc.php';
  require 'core.inc.php';
  
  if(!isset($_SESSION['user_id'])){
     header('Location: index.php');
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Portfolio Manager</title>
    <style type='text/css'>
      table, th, td {    border: 1px solid black; }
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
    <h1>Withdrawal Amount &#36;
     <form method="post" action="confirmwithdrawal.php" style="display: inline;">
        <input type="number" step="0.01" name="withdrawalAmount" value="00.00">
        <input type="submit" value="Withdraw">
    </form></h1>
  </body>
</html>
