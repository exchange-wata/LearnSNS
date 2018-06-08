<?php
// 1
function multiplication($num1,$num2){
	$result=$num1*$num2;

	return $result;
}

for ($i=1; $i <6 ; $i++) { 
	$num1=$i;
	$num2=$i+$i;
	$result=multiplication($num1,$num2);
}

// $result=multiplication($num1,$num2);
echo $result.'<br>';

// 2
function average($num3,$num4){
	$average=($num3+$num4)/2;
	// return $average;
	
	if ($average>=10) {
		$avg=$average;
	}

	else{
		$avg="0";
	}

	return $avg;
}

$num3=14;
$num4=6;

$avg=average($num3,$num4);
echo $avg.'<br>';

// 3
// $num5;所持金
// $num6;購入代金
function shopping($num5,$num6){
	$change=$num5-$num6;
	return $change;
}

$num5=100;
$num6=50;

$change=shopping($num5,$num6);
echo $change.'<br>';

// 4
// $num7,$num8;問題文における$num1,$num2
function compare($num7,$num8){
	if ($num7>=$num8) {
  		$result=$num7;
	}

	else {
  		$result=$num8;
	}

	return $result;
}

$num7=3;
$num8=9;

$result=compare($num7,$num8);
echo $result;


?>