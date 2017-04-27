<?php

for ($x=0; $x<9999999; $x++) {
	$numerator = pow((-1), $x);
	$denominator = 2*$x+1;
	$pi += $numerator/$denominator;
	//echo $numerator." / ".$denominator."<br>";
}
echo 4*$pi;
?>