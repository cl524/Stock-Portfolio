<?php
//////solve for DeltaS1 = (s2v2 + s3v3 - r1s1v1/r2 - r1s1v1/r3)/(r1v1/r2 + r1v1/r3 + v1)

    //echo '<p>&Delta;s1 = </p>';

    $numShareChanges[0] = ($s2v2 - $r1s1v1/$r2)/($r1v1/$r2 + $v1);

    //echo "<br>";
//++++++++++++++++++++++//



//////solve for DeltaS2 = (s1v1 + s3v3 - r2s2v2/r1 - r2s2v2/r3)/(r2v2/r1 + r2v2/r3 + v2)

    //echo '<p>&Delta;s2 = </p>';

    $numShareChanges[1] = ($s1v1 - $r2s2v2/$r1)/($r2v2/$r1 + $v2);

    //echo "<br>";

//++++++++++++++++++++++//



?>