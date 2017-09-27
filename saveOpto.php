<?php
    $indianCodeOld = $_GET['v1'];
    $indianAmountsOld = $_GET['v2'];
    $indianRiskContributionOld = $_GET['v3'];
    $domesticCodeOld = $_GET['v4'];
    $domesticAmountsOld = $_GET['v5'];
    $domesticRiskContributionOld = $_GET['v6'];
    $portfolioReturnOld = $_GET['v7'];
    $portfolioBetaOld = $_GET['v8'];
    $indianCode = $_GET['v9'];
    $indianAmounts = $_GET['v10'];
    $indianRiskContribution = $_GET['v11'];
    $domesticCode = $_GET['v12'];
    $domesticAmounts = $_GET['v13'];
    $domesticRiskContribution = $_GET['v14'];
    $portfolioReturn = $_GET['v15'];
    $portfolioBeta = $_GET['v16'];
    $indianRiskOld = $_GET['v17'];
    $domesticRiskOld = $_GET['v18'];
    $indianRisk = $_GET['v19'];
    $domesticRisk = $_GET['v20'];
    $indianReturnArrayOld = $_GET['v21'];
    $domesticReturnArrayOld = $_GET['v22'];
    $indianReturnArray = $_GET['v23'];
    $domesticReturnArray = $_GET['v24'];
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=Optimization.csv');
    $outputFile = fopen('php://output', 'wr');
    fputcsv($outputFile, array("PRE-OPTIMIZATION", "", ""));
    fputcsv($outputFile, array("Foreign Stocks", "", ""));
    fputcsv($outputFile, array("Stock Code", "Shares Owned", "Risk", "Risk Contribution"));
    for($b=0; $b<count($indianCodeOld); $b++){
      fputcsv($outputFile, array($indianCodeOld[$b], $indianAmountsOld[$b], round($indianRiskOld[$b], 5, PHP_ROUND_HALF_UP),  round($indianRiskContributionOld[$b], 5, PHP_ROUND_HALF_UP)));
    }
    fputcsv($outputFile, array("Domestic Stocks", "", ""));
    fputcsv($outputFile, array("Stock Code", "Shares Owned",  "Risk", "Risk Contribution"));
    for($b=0; $b<count($domesticCodeOld); $b++){
      fputcsv($outputFile, array($domesticCodeOld[$b], $domesticAmountsOld[$b], round($domesticRiskOld[$b], 5, PHP_ROUND_HALF_UP), round($domesticRiskContributionOld[$b], 5, PHP_ROUND_HALF_UP)));
    }
    fputcsv($outputFile, array("","",""));
    fputcsv($outputFile, array("Portfolio Expected Return:", "Portfolio Beta:", ""));
    fputcsv($outputFile, array(round($portfolioReturnOld, 3, PHP_ROUND_HALF_UP), round($portfolioBetaOld, 3, PHP_ROUND_HALF_UP), ""));
    fputcsv($outputFile, array("POST-OPTIMIZATION", "", ""));
    fputcsv($outputFile, array("Foreign Stocks", "", ""));
    fputcsv($outputFile, array("Stock Code", "Shares Owned",  "Risk", "Risk Contribution"));
    for($b=0; $b<count($indianCode); $b++){
      fputcsv($outputFile, array($indianCode[$b], $indianAmounts[$b], round($indianRisk[$b], 5, PHP_ROUND_HALF_UP), round($indianRiskContribution[$b], 5, PHP_ROUND_HALF_UP)));
    }
    fputcsv($outputFile, array("Domestic Stocks", "", ""));
    fputcsv($outputFile, array("Stock Code", "Shares Owned",  "Risk", "Risk Contribution"));
    for($b=0; $b<count($domesticCode); $b++){
      fputcsv($outputFile, array($domesticCode[$b], $domesticAmounts[$b], round($domesticRisk[$b], 5, PHP_ROUND_HALF_UP), round($domesticRiskContribution[$b], 5, PHP_ROUND_HALF_UP)));
    }
    fputcsv($outputFile, array("","",""));
    fputcsv($outputFile, array("Portfolio Expected Return:", "Portfolio Beta:", ""));
    fputcsv($outputFile, array(round($portfolioReturn, 3, PHP_ROUND_HALF_UP), round($portfolioBeta, 3, PHP_ROUND_HALF_UP), ""));
    fclose($outputFile);
?>