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
  $numberOfStocks = 3; //How many unique stocks will be optimized
  $numShares = [30,20,10]; //Number of shares per stock (s)
  $riskValues = [0.3,0.3,0.3]; //risk values per stock (r)
  $numShareChanges = []; // change in number of shares after optimization (DeltaS)
  $valuePerShare = [10,10,10]; //exactly what it sounds like (v)

/*
    $r1 = $riskValues[0];
    $r2 = $riskValues[1];
    $r3 = $riskValues[2];
    $r4 = $riskValues[3];
    $r5 = $riskValues[4];
    $r6 = $riskValues[5];
    $r7 = $riskValues[6];
    $r8 = $riskValues[7];
    $r9 = $riskValues[8];
    $r10 = $riskValues[9];
    
    $v1 = $valuePerShare[0];
    $v2 = $valuePerShare[1];
    $v3 = $valuePerShare[2];
    $v4 = $valuePerShare[3];
    $v5 = $valuePerShare[4];
    $v6 = $valuePerShare[5];
    $v7 = $valuePerShare[6];
    $v8 = $valuePerShare[7];
    $v9 = $valuePerShare[8];
    $v10 = $valuePerShare[9];

    $s1v1 = $numShares[0]*$valuePerShare[0];
    $s2v2 = $numShares[1]*$valuePerShare[1];
    $s3v3 = $numShares[2]*$valuePerShare[2];
    $s4v4 = $numShares[3]*$valuePerShare[3];
    $s5v5 = $numShares[4]*$valuePerShare[4];
    $s6v6 = $numShares[5]*$valuePerShare[5];
    $s7v7 = $numShares[6]*$valuePerShare[6];
    $s8v8 = $numShares[7]*$valuePerShare[7];
    $s9v9 = $numShares[8]*$valuePerShare[8];
    $s10v10 = $numShares[9]*$valuePerShare[9];

    $r1v1 = $riskValues[0]*$valuePerShare[0];
    $r2v2 = $riskValues[1]*$valuePerShare[1];
    $r3v3 = $riskValues[2]*$valuePerShare[2];
    $r4v4 = $riskValues[3]*$valuePerShare[3];
    $r5v5 = $riskValues[4]*$valuePerShare[4];
    $r6v6 = $riskValues[5]*$valuePerShare[5];
    $r7v7 = $riskValues[6]*$valuePerShare[6];
    $r8v8 = $riskValues[7]*$valuePerShare[7];
    $r9v9 = $riskValues[8]*$valuePerShare[8];
    $r10v10 = $riskValues[9]*$valuePerShare[9];

    $r1s1v1 = $r1v1*$numShares[0];
    $r2s2v2 = $r2v2*$numShares[1];
    $r3s3v3 = $r3v3*$numShares[2];
    $r4s4v4 = $r4v4*$numShares[3];
    $r5s5v5 = $r5v5*$numShares[4];
    $r6s6v6 = $r6v6*$numShares[5];
    $r7s7v7 = $r7v7*$numShares[6];
    $r8s8v8 = $r8v8*$numShares[7];
    $r9s9v9 = $r9v9*$numShares[8];
    $r10s10v10 = $r10v10*$numShares[9];
    
*/

  if($numberOfStocks == 1){
      echo 'There is only one stock.';
  }

  if($numberOfStocks == 2){
    echo 'Number of Stocks = 2';
    include 'PortOptCode/portOpt2.php';
  }



    
  if($numberOfStocks == 3){
      echo 'Number of Stocks = 3';
      include 'PortOptCode/portOpt3.php';
  }
    
    
    
    









?>
</body>
</html>