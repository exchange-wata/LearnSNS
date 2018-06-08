<?php

// 1,「seedくん」という文字列を出力する「nexseed」という名前の関数を作成してみましょう。（引数はなし）
// function nexseed(){
// 	echo 'seedくん';
// }

// 2,１で作成した「nexseed」関数を呼び出してみましょう。
// nexseed();

// 3,２で作成した「nexseed」関数に、「greeting」という引数を追加して「△△△△△、seedくん」と表示されるように呼び出してみましょう。「△△△△△」のところに、受け取った$greetingの値が入るようにしてください。
// function nexseed($greeting){
// 	echo $greeting.'、seedくん'.'<br>';
// }

// $greeting="hello";
// nexseed($greeting);

// nexseed('グーテンターク');

// 4,３で作成した「nexseed」関数に、「name」という引数を追加して「△△△△△、○○さん」と表示されるように呼び出してみましょう。「△△△△△」には$greetingの値が、「○○」には$nameの値が入るようにしてください。
function FGO($greet,$Name){
	echo $greet.' '.$Name.'<br>';
}

$greet=array("グーテンターク","グーテンモルゲン");
$Name=array("セイバー","ランサー");
// FGO($greet[0],$Name[0]);
// FGO($greet[0],$Name[1]);
// FGO($greet[1],$Name[1]);
// FGO($greet[1],$Name[0]);


for ($i=0; $i<2; $i++) { 
	FGO($greet[$i],$Name[$i]);
	// FGO($greet[0],$Name[$i]);

}	

// function plus($num1,$num2){
// 	$result=$num1+$num2;
// 	// ここから下は実行しない
// 	echo "足し算終わりました";
// }

// function checkExam($score){
// 	if ($score>89) {
// 		$kekka="合格！";
// 	}

// 	else{
// 		$kekka="不合格";
// 	}

// 	return $kekka;
// }

// 5,４で作成した「nexseed」関数が、あいさつ文を戻り値として返すように修正しましょう。（関数内では出力しない）戻り値を受け取ってから出力してください。
// function nexseed($greeting,$name){
// 	return $greeting.' '.$name.'<br>';
// }
// echo nexseed("comeback","imaginn");

// $aisatsu=nexseed("comeback","imaginn");
// echo $aisatsu;




?>