<?php
//////solve for DeltaS1 = (s2v2 + s3v3 + s4v4 - r1s1v1/r2 - r1s1v1/r3 - r1s1v1/r4)/(r1v1/r2 + r1v1/r3 + r1v1/r4 + v1)
    //echo '<p>&Delta;s1 = </p>';
    $numShareChanges[0] = ($s2v2 + $s3v3 + $s4v4 - $r1s1v1/$r2 - $r1s1v1/$r3 - $r1s1v1/$r4)/($r1v1/$r2 + $r1v1/$r3 + $r1v1/$r4 + $v1);

    //echo "<br>";
//++++++++++++++++++++++//



//////solve for DeltaS2 = (s1v1 + s3v3 + s4v4 - r2s2v2/r1 - r2s2v2/r3 - r2s2v2/r4)/(r2v2/r1 + r2v2/r3 + r2v2/r4 + v2)

    //echo '<p>&Delta;s2 = </p>';
    
    $numShareChanges[1] = ($s1v1 + $s3v3 + $s4v4 - $r2s2v2/$r1 - $r2s2v2/$r3 - $r2s2v2/$r4)/($r2v2/$r1 + $r2v2/$r3 + $r2v2/$r4 + $v2);

    //echo "<br>";
//++++++++++++++++++++++//


//////solve for DeltaS3 = (s1v1 + s2v2 + s4v4 - r3s3v3/r1 - r3s3v3/r2 - r3s3v3/r4)/(r3v3/r1 + r3v3/r2 + r3v3/r4 + v3)

    //echo '<p>&Delta;s3 = </p>';
    
    $numShareChanges[2] = ($s1v1 + $s2v2 + $s4v4 - $r3s3v3/$r1 - $r3s3v3/$r2 - $r3s3v3/$r4)/($r3v3/$r1 + $r3v3/$r2 + $r3v3/$r4 + $v3);

    //echo "<br>";
//++++++++++++++++++++++//


//////solve for DeltaS4 = (s1v1 + s2v2 + s3v3 - r4s4v4/r1 - r4s4v4/r2 - r4s4v4/r3)/(r4v4/r1 + r4v4/r2 + r4v4/r3 + v4)

    //echo '<p>&Delta;s4 = </p>';
    
    $numShareChanges[3] = ($s1v1 + $s2v2 + $s3v3 - $r4s4v4/$r1 - $r4s4v4/$r2 - $r4s4v4/$r3)/($r4v4/$r1 + $r4v4/$r2 + $r4v4/$r3 + $v4);

    //echo "<br>";
//++++++++++++++++++++++//

?>