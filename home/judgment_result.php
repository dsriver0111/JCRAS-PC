<?php
session_start();
require_once('db.php');

if(!isset($_POST['canvas_img'])){
	header('location:login.php');
}

$cancer_init = explode(',',$_POST['cancer_init']);
$cancer_old_init = explode(',',$_POST['cancer_old_init']);
$cancer_sintou = explode(',',$_POST['cancer_sintou']);
$cancer_sex = explode(',',$_POST['cancer_sex']);
$cancer_id = explode(',',$_POST['cancer_id']);

$kekka0=[];
$kekka1=[];
$kekka2=[];
$kekka3=[];
$kekka4=[];
$kekka5=[];
$kekka6=[];

$irekae_all=[];
$last_kekka="";
$kekka_text="";
$title="";
$main="";
$icon_text="";

foreach($cancer_sintou as $key => $row){
	$irekae=[];
	array_push($irekae,$cancer_sintou[$key],$cancer_id[$key],$cancer_init[$key],$cancer_old_init[$key],$cancer_sex[$key]);
	array_push($irekae_all,$irekae);
	}

try {
	
	/*
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$sql_match = "SELECT * FROM history where family_jpg_check ='".$_SESSION['user_id'].$_POST['now_time']."'";
	$stm_match_a = $pdo->prepare($sql_match);
	$stm_match_a->execute();
	$result_match = $stm_match_a->fetchAll(PDO::FETCH_ASSOC);


if($result_match){
		header('location:family_top.php');
	}
	*/
	
$file_rand = md5(uniqid(rand(),true));

$img = $_POST['canvas_img'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$fileName = $file_rand.'.jpg';//ランダムなファイル名をつける
file_put_contents('../img/'.$fileName, $fileData);

/*echo $_SESSION['user_id'].$_POST['now_time'];
echo "ファイル名：".$fileName;*/

	
//////////////////////////////////////ここから診断結果ロジック//////////////////////////////////////////////////////
	
	//ここからロジック判定　["何親等か","id名","癌腫","診断年齢","性別"]
	
	/*ここからリンチ症候群定義
	["0","me","大腸癌"],//or
	["0","me","子宮体癌"],//or
	["0","me","卵巣癌/腹膜癌/卵管癌"],//or
	["0","me","胃癌"],//or
	["0","me","小腸癌"],//or
	["0","me","胆道癌"],//or
	["0","me","泌尿器癌（前立腺癌を含めない）"],//or
	["0","me","膵癌"],//or
	["0","me","脳腫瘍"]
	*/
	
	//ここから共通
	$cancer0_hit_all = ["既往歴：リンチ症候群関連腫瘍のいずれかの癌を3回診断されている","既往歴：リンチ症候群関連腫瘍のいずれかの癌と診断されている<BR>家族歴：第一度、第二度近親者のうちそれぞれ一人ずつが、リンチ症候群関連腫瘍のいずれかの癌と診断されている","家族歴：第一度近親者の1名がリンチ症候群関連腫瘍のいずれかの癌1つ、第二度近親者の1名がリンチ症候群関連腫瘍のいずれか2つ癌（別の臓器）と診断されている","家族歴：第二度近親者の3名のうち、それぞれがリンチ症候群関連腫瘍のいずれかの癌と診断されている","家族歴：第二度近親者の1名が、リンチ症候群関連腫瘍のいずれか3つの癌と診断されている"];
	
	//ここから乳癌
	$cancer1_hit_all = ["既往歴：45歳以下で診断された乳癌","既往歴：2度あるいは2カ所に乳癌と診断された","既往歴：男性で乳癌と診断された","家族歴：第一度、第二度、第三度近親者のうち男性1名が乳癌と診断されている","既往歴：乳癌の診断あり<BR>家族歴：第一度、第二度、第三度近親者で1名に乳癌または卵巣癌の診断がある","家族歴：第一度近親者に1名、乳癌が40歳未満で診断されている","家族歴：第一度～第三度近親者のうち少なくとも1名が、2カ所以上の乳癌と診断されている","家族歴：第一度近親者に1名、2度または2カ所に乳癌の診断があり、最初の診断は50歳未満である","家族歴：第一度近親者に2名、乳癌の診断がある","家族歴：第一度近親者に1名かつ第二度近親者に1名、乳癌の診断がある","家族歴：第一度近親者に1名、乳癌の診断があり、第一度、第二度近親者に1名、卵巣癌の診断がある","家族歴：第二度近親者に1名、乳癌の診断があり、第一度近親者に1名、卵巣癌の診断がある","家族歴：第一度、第二度、第三度近親者のうち1名、乳癌と卵巣癌の診断をもつ","家族歴：第一度、第二度、第三度近親者のうち少なくとも2名が、乳癌または卵巣癌の診断がある","家族歴：第一度、第二度近親者に、乳癌、卵巣癌、膵癌、前立腺癌の中から少なくとも3つの診断がある","家族歴：第一度、第二度近親者に2名、乳癌または卵巣癌の診断があり、乳癌は50歳未満で診断されている","家族歴：第一度、第二度近親者に2名、乳癌または卵巣癌の診断があり、いずれかに人に2度または2カ所以上の乳癌の診断がある","家族歴：第一度、第二度近親者に2名、乳癌または卵巣癌の診断があり、ひとりの人に乳癌と卵巣癌の両方の診断がある"];
	
	//ここから卵巣癌／卵管癌／腹膜癌
	$cancer2_hit_all = ["既往歴：卵巣癌／卵管癌／腹膜癌の診断がある","家族歴：第一度、第二度近親者で1名の卵巣癌／卵管癌／腹膜癌の診断がある","家族歴：第一度、第二度近親者で1名に卵巣癌／卵管癌／腹膜癌の診断があり、さらに1名に、卵巣癌／卵管癌／腹膜癌、乳癌、50歳未満で診断された大腸癌、50歳未満で診断された子宮体癌のいずれかの診断がある","家族歴：第一度、第二度近親者に1名、卵巣癌／卵管癌／腹膜癌の診断と1つ以上のリンチ症候群関連腫瘍の診断をもつ"];
	
	//ここから子宮体癌
	$cancer3_hit_all = ["既往歴：50歳未満での子宮体癌の診断","既往歴：50歳以上での子宮体癌の診断<BR>家族歴：第一度近親者に大腸癌または子宮体癌の診断がある","既往歴：子宮体癌を2カ所あるいは2度診断されている","既往歴：子宮体癌と、1つのリンチ症候群関連腫瘍の診断がある","既往歴：子宮体癌の診断あり<BR>家族歴：第一度、第二度近親者に少なくとも1名、50歳未満でリンチ症候群関連腫瘍と診断されている","家族歴：第一度近親者に少なくとも1名、50歳未満での子宮体癌の診断がある","家族歴：第一度、第二度近親者に1名、子宮体癌の診断があり、且つ1つ以上のリンチ症候群関連腫瘍が診断されている","家族歴：第一度近親者に少なくとも1名、子宮体癌と1つのリンチ症候群関連腫瘍が診断されている","家族歴：第一度、第二度近親者に少なくとも2名、リンチ症候群関連腫瘍の診断があり、そのうち少なくとも一つは50歳未満で診断されている"];
	//ここから前立腺癌
	$cancer4_hit_all = ["既往歴：転移性前立腺癌の診断（遠隔転移またはリンパ節転移を有する）","既往歴：前立腺癌の診断<BR>家族歴：第一度、第二度、第三度近親者で少なくとも2名、乳癌、卵巣癌/卵管癌/腹膜癌、膵癌または前立腺癌の診断がある","既往歴：前立腺癌の診断<BR>家族歴：第一度、第二度、第三度近親者に少なくとも1名、50歳以下で乳癌を診断されている","既往歴：前立腺癌の診断<BR>家族歴：第一度、第二度、第三度近親者に少なくとも1名、卵巣癌/卵管癌/腹膜癌の診断あり","既往歴：前立腺癌の診断<BR>家族歴：第一度、第二度、第三度近親者に少なくとも1名、膵癌の診断あり","既往歴：前立腺癌の診断<BR>家族歴：第一度、第二度近親者に、乳癌、卵巣癌/卵管癌/腹膜癌あるいは膵癌のうち、2つ診断がある"];
	//ここから膵癌
	$cancer5_hit_all = ["既往歴：膵癌の診断<BR>家族歴：第一度、第二度近親者で少なくとも2名に、乳癌、卵巣癌/卵管癌/腹膜癌、膵癌、前立腺癌の診断がある","既往歴：膵癌の診断<BR>家族歴：第一度、第二度、第三度近親者で少なくとも2名に乳癌、卵巣癌／卵管癌／腹膜癌、膵癌あるいは前立腺癌の診断がある"];
	//ここから大腸癌
	$cancer6_hit_all = ["既往歴：50歳未満での大腸癌の診断","既往歴：大腸癌の診断と、1つのリンチ症候群関連腫瘍の診断をもつ","既往歴：大腸癌の診断あり<BR>家族歴：第一度、第二度近親者に少なくとも1名、大腸癌または子宮体癌の診断があり、そのうちいずれかは50歳未満で診断されている","家族歴：第一度、第二度近親者に少なくとも2名、大腸癌または子宮体癌の診断があり、それらのうち少なくとも一つは50歳未満で診断されている","既往歴：50歳以上での大腸癌の診断<BR>家族歴：第一度近親者に1名、大腸癌または子宮体癌の診断がある","既往歴：大腸癌の診断あり<BR>家族歴：第一度、第二度近親者に1名、1つのリンチ症候群関連腫瘍が50歳未満で診断されている","家族歴：第一度近親者に少なくとも1名、50歳未満での大腸癌の診断がある","家族歴：第一度近親者に少なくとも1名、大腸がんの診断と、1つのリンチ症候群関連腫瘍が診断されている","家族歴：第一度、第二度近親者に少なくとも2名、リンチ症候群関連腫瘍の診断があり、その一つ以上は50歳未満で診断されている","家族歴：第一度近親者で少なくとも1名、大腸癌と1つのリンチ関連腫瘍と診断され、そのうち一つの大腸癌は50歳未満で診断されている","家族歴：第一度、第二度近親者で少なくとも2名それぞれが、大腸癌かつリンチ関連腫瘍の診断がある","既往歴：胃癌と1つのリンチ症候群関連腫瘍の診断がある"];
	
	/////////////////////////////////////////////////////
	///////////////ここから共通の診断結果////////////////////
	/////////////////////////////////////////////////////
	$logic_cancer0_1 = [
	//case1
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer0_2 = [
	//case2
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer0_3 = [
	//case3
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer0_4 = [
	//case4
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer0_5 = [
	//case5
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	/////////////////////////////////////////////////////
	///////////////ここから乳癌の診断結果////////////////////
	/////////////////////////////////////////////////////
	$logic_cancer1_1 = [
	//case1
	["乳癌"]
	];
	$logic_cancer1_2 = [
	//case2
	["乳癌"]
	];
	$logic_cancer1_3 = [
	//case3
	["0","乳癌"]
	];
	$logic_cancer1_4 = [
	//case4
	["乳癌"]
	];
	$logic_cancer1_5 = [
	//case5
	["0","乳癌"],//and
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_6 = [
	//case6
	["乳癌"]
	];
	$logic_cancer1_7 = [
	//case7
	["乳癌"]
	];
	$logic_cancer1_8 = [
	//case8
	["乳癌"]
	];
	$logic_cancer1_9 = [
	//case9
	["乳癌"]
	];
	$logic_cancer1_10 = [
	//case10
	["乳癌"]
	];
	$logic_cancer1_11 = [
	//case11
	["乳癌"],//and
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_12 = [
	//case12
	["乳癌"],//and
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_13 = [
	//case13
	["乳癌"],//and
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_14 = [
	//case14
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_15 = [
	//case15
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["膵癌"],//or
	["前立腺癌"]
	];
	$logic_cancer1_16 = [
	//case16
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_17 = [
	//case17
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer1_18 = [
	//case18
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"]
	];
	/////////////////////////////////////////////////////
	/////////ここから卵巣癌/腹膜癌/卵管癌の診断結果////////////
	/////////////////////////////////////////////////////
	$logic_cancer2_1 = [
	//case1
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer2_2 = [
	//case2
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer2_3 = [
	//case3
	["卵巣癌/腹膜癌/卵管癌"],//and
	["乳癌"],//or
	["大腸癌"],//or
	["子宮体癌"]
	];
	$logic_cancer2_4 = [
	//case4
	["卵巣癌/腹膜癌/卵管癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	
	/////////////////////////////////////////////////////
	//////////////ここから子宮体癌の診断結果/////////////////
	/////////////////////////////////////////////////////
	
	$logic_cancer3_1 = [
	//case1＝＞結果：$cancer3_hit_all[0]
	["0","me","子宮体癌","４５歳以下"],//or
	["0","me","子宮体癌","４５歳〜５０歳"]
	];
	$logic_cancer3_2 = [
	//case2＝＞結果：$cancer3_hit_all[1]
	["0","me","子宮体癌","５１歳以上"],//and
	["1","子宮体癌"],//or」どっちか１人
	["1","大腸癌"]
	];
	$logic_cancer3_3 = [
	//case3
	["0","me","子宮体癌"]//x2回
	];
	$logic_cancer3_4 = [
	//case4
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer3_5 = [
	//case5
	["0","me","子宮体癌"],//and
	["1","大腸癌","４５歳以下"],//or
	["1","子宮体癌","４５歳以下"],//or
	["1","卵巣癌/腹膜癌/卵管癌","４５歳以下"],//or
	["1","胃癌","４５歳以下"],//or
	["1","小腸癌","４５歳以下"],//or
	["1","胆道癌","４５歳以下"],//or
	["1","泌尿器癌（前立腺癌を含めない）","４５歳以下"],//or
	["1","膵癌","４５歳以下"],//or
	["1","脳腫瘍","４５歳以下"],//or
	["1","大腸癌","４５歳〜５０歳"],//or
	["1","子宮体癌","４５歳〜５０歳"],//or
	["1","卵巣癌/腹膜癌/卵管癌","４５歳〜５０歳"],//or
	["1","胃癌","４５歳〜５０歳"],//or
	["1","小腸癌","４５歳〜５０歳"],//or
	["1","胆道癌","４５歳〜５０歳"],//or
	["1","泌尿器癌（前立腺癌を含めない）","４５歳〜５０歳"],//or
	["1","膵癌","４５歳〜５０歳"],//or
	["1","脳腫瘍","４５歳〜５０歳"],//or
	["2","大腸癌","４５歳以下"],//or
	["2","子宮体癌","４５歳以下"],//or
	["2","卵巣癌/腹膜癌/卵管癌","４５歳以下"],//or
	["2","胃癌","４５歳以下"],//or
	["2","小腸癌","４５歳以下"],//or
	["2","胆道癌","４５歳以下"],//or
	["2","泌尿器癌（前立腺癌を含めない）","４５歳以下"],//or
	["2","膵癌","４５歳以下"],//or
	["2","脳腫瘍","４５歳以下"],//or
	["2","大腸癌","４５歳〜５０歳"],//or
	["2","子宮体癌","４５歳〜５０歳"],//or
	["2","卵巣癌/腹膜癌/卵管癌","４５歳〜５０歳"],//or
	["2","胃癌","４５歳〜５０歳"],//or
	["2","小腸癌","４５歳〜５０歳"],//or
	["2","胆道癌","４５歳〜５０歳"],//or
	["2","泌尿器癌（前立腺癌を含めない）","４５歳〜５０歳"],//or
	["2","膵癌","４５歳〜５０歳"],//or
	["2","脳腫瘍","４５歳〜５０歳"]
	];
	$logic_cancer3_6 = [
	//case6
	["1","子宮体癌","４５歳〜５０歳"],
	["1","子宮体癌","４５歳以下"]//or
	];
	$logic_cancer3_7 = [
	//case7
	["1","子宮体癌"],//or
	["2","子宮体癌"],//and
	["1","大腸癌"],//or
	["1","卵巣癌/腹膜癌/卵管癌"],//or
	["1","胃癌"],//or
	["1","小腸癌"],//or
	["1","胆道癌"],//or
	["1","泌尿器癌（前立腺癌を含めない）"],//or
	["1","膵癌"],//or
	["1","脳腫瘍"],//or
	["2","大腸癌"],//or
	["2","卵巣癌/腹膜癌/卵管癌"],//or
	["2","胃癌"],//or
	["2","小腸癌"],//or
	["2","胆道癌"],//or
	["2","泌尿器癌（前立腺癌を含めない）"],//or
	["2","膵癌"],//or
	["2","脳腫瘍"]
	];
	$logic_cancer3_8 = [
	//case8
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer3_9 = [
	//case9
	["1","大腸癌"],//or
	["1","子宮体癌"],//or
	["1","卵巣癌/腹膜癌/卵管癌"],//or
	["1","胃癌"],//or
	["1","小腸癌"],//or
	["1","胆道癌"],//or
	["1","泌尿器癌（前立腺癌を含めない）"],//or
	["1","膵癌"],//or
	["1","脳腫瘍"],//or
	["2","大腸癌"],//or
	["2","子宮体癌"],//or
	["2","卵巣癌/腹膜癌/卵管癌"],//or
	["2","胃癌"],//or
	["2","小腸癌"],//or
	["2","胆道癌"],//or
	["2","泌尿器癌（前立腺癌を含めない）"],//or
	["2","膵癌"],//or
	["2","脳腫瘍"]
	];
	/////////////////////////////////////////////////////
	////////////ここから前立腺癌の診断結果///////////////////////
	/////////////////////////////////////////////////////
	$logic_cancer4_1 = [
	//case1
	["0","前立腺癌"]
	];
	$logic_cancer4_2 = [
	//case2
	["0","前立腺癌"],//and
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["膵癌"],//or
	["前立腺癌"]
	];
	$logic_cancer4_3 = [
	//case3
	["0","前立腺癌"],//and
	["乳癌"]
	];
	$logic_cancer4_4 = [
	//case4
	["0","前立腺癌"],//and
	["卵巣癌/腹膜癌/卵管癌"]
	];
	$logic_cancer4_5 = [
	//case5
	["0","前立腺癌"],//and
	["膵癌"]
	];
	$logic_cancer4_6 = [
	//case6
	["0","前立腺癌"],//and
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["膵癌"]
	];
	/////////////////////////////////////////////////////
	////////////ここから膵癌の診断結果///////////////////////
	/////////////////////////////////////////////////////
	$logic_cancer5_1 = [
	//case1
	["0","膵癌"],//and
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["膵癌"],//or
	["前立腺癌"]
	];
	$logic_cancer5_2 = [
	//case2
	["0","膵癌"],//and
	["乳癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["膵癌"],//or
	["前立腺癌"]
	];
	
	/////////////////////////////////////////////////////
	////////////ここから大腸癌の診断結果///////////////////////
	/////////////////////////////////////////////////////
	$logic_cancer6_1 = [
	//case1
	["0","大腸癌"]
	];
	$logic_cancer6_2 = [
	//case2
	["0","大腸癌"],//and
	["0","大腸癌"],//or
	["0","子宮体癌"],//or
	["0","卵巣癌/腹膜癌/卵管癌"],//or
	["0","胃癌"],//or
	["0","小腸癌"],//or
	["0","胆道癌"],//or
	["0","泌尿器癌（前立腺癌を含めない）"],//or
	["0","膵癌"],//or
	["0","脳腫瘍"]
	];
	$logic_cancer6_3 = [
	//case3
	["0","大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"]
	];
	$logic_cancer6_4 = [
	//case4
	["大腸癌"],//or
	["子宮体癌"]
	];
	$logic_cancer6_5 = [
	//case5
	["0","大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"]
	];
	$logic_cancer6_6 = [
	//case6
	["0","大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer6_7 = [
	//case7
	["大腸癌"]
	];
	$logic_cancer6_8 = [
	//case8
	["大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer6_9 = [
	//case9
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer6_10 = [
	//case10
	["大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer6_11 = [
	//case11
	["大腸癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	$logic_cancer6_12 = [
	//case12
	["胃癌"],//and
	["大腸癌"],//or
	["子宮体癌"],//or
	["卵巣癌/腹膜癌/卵管癌"],//or
	["胃癌"],//or
	["小腸癌"],//or
	["胆道癌"],//or
	["泌尿器癌（前立腺癌を含めない）"],//or
	["膵癌"],//or
	["脳腫瘍"]
	];
	
//////////////////////////////////////ここまで診断結果ロジック//////////////////////////////////////////////////////
	
	$cancer0_case1_count1=0;
	$cancer0_case2_count1=0;
	$cancer0_case2_count2=0;
	$cancer0_case2_count3=0;
	$cancer0_case3_count1=0;
	$cancer0_case3_count2=0;
	$cancer0_case4_count1=0;
	$cancer0_case4_count2=0;
	$cancer0_case5_count1=0;
	
	$cancer1_case1_count1=0;
	$cancer1_case2_count1=0;
	$cancer1_case3_count1=0;
	$cancer1_case4_count1=0;
	$cancer1_case5_count1=0;
	$cancer1_case5_count2=0;
	$cancer1_case6_count1=0;
	$cancer1_case7_count1=0;
	$cancer1_case8_count1=0;
	$cancer1_case9_count1=0;
	$cancer1_case10_count1=0;
	$cancer1_case10_count2=0;
	$cancer1_case11_count1=0;
	$cancer1_case11_count2=0;
	$cancer1_case12_count1=0;
	$cancer1_case12_count2=0;
	$cancer1_case13_count1=0;
	$cancer1_case14_count1=0;
	$cancer1_case14_count2=0;
	$cancer1_case15_count1=0;
	$cancer1_case15_count2=0;
	$cancer1_case16_count1=0;
	$cancer1_case16_count2=0;
	$cancer1_case17_count1=0;
	$cancer1_case17_count2=0;
	$cancer1_case18_count1=0;
	$cancer1_case18_count2=0;
	
	$cancer2_case1_count1=0;
	$cancer2_case2_count1=0;
	$cancer2_case3_count1=0;
	$cancer2_case3_count2=0;
	$cancer2_case4_count1=0;
	$cancer2_case4_count2=0;
	
	$cancer3_case1_count1=0;
	$cancer3_case2_count1=0;
	$cancer3_case2_count2=0;
	$cancer3_case3_count1=0;
	$cancer3_case4_count1=0;
	$cancer3_case4_count2=0;
	$cancer3_case5_count1=0;
	$cancer3_case5_count2=0;
	$cancer3_case6_count1=0;
	$cancer3_case7_count1=0;
	$cancer3_case7_count2=0;
	$cancer3_case8_count1=0;
	$cancer3_case8_count2=0;
	$cancer3_case9_count1=0;
	$cancer3_case9_count2=0;
	
	$cancer4_case1_count1=0;
	$cancer4_case2_count1=0;
	$cancer4_case2_count2=0;
	$cancer4_case3_count1=0;
	$cancer4_case3_count2=0;
	$cancer4_case4_count1=0;
	$cancer4_case4_count2=0;
	$cancer4_case5_count1=0;
	$cancer4_case5_count2=0;
	$cancer4_case6_count1=0;
	$cancer4_case6_count2=0;
	
	$cancer5_case1_count1=0;
	$cancer5_case1_count2=0;
	$cancer5_case2_count1=0;
	$cancer5_case2_count2=0;
	
	$cancer6_case1_count1=0;
	$cancer6_case2_count1=0;
	$cancer6_case2_count2=0;
	$cancer6_case3_count1=0;
	$cancer6_case3_count2=0;
	$cancer6_case4_count1=0;
	$cancer6_case5_count1=0;
	$cancer6_case5_count2=0;
	$cancer6_case6_count1=0;
	$cancer6_case6_count2=0;
	$cancer6_case7_count1=0;
	$cancer6_case8_count1=0;
	$cancer6_case8_count2=0;
	$cancer6_case9_count1=0;
	$cancer6_case9_count2=0;
	$cancer6_case10_count1=0;
	$cancer6_case10_count2=0;
	$cancer6_case11_count1=0;
	$cancer6_case11_count2=0;
	$cancer6_case12_count1=0;
	$cancer6_case12_count2=0;
	
	$array_user0_3_1=[];
	$array_user0_4=[];
	$array_user0_5=[];
	$array_user0_3=[];
	$array_user1_7_1=[];
	$array_user1_7_2=[];
	$array_user1_7_3=[];
	$array_user1_8_1=[];
	$array_user1_8_2=[];
	$array_user1_9_1=[];
	$array_user1_13_1=[];
	$array_user1_14_1=[];
	$array_user1_16_1=[];
	$array_user1_16_2=[];
	$array_user1_16_3=[];
	$array_user2_4_1=[];
	$array_user2_4_2=[];
	$array_user3_8_1=[];
	$array_user3_8_2=[];
	$array_user4_2_1=[];
	$array_user4_2_2=[];
	$array_user5_1_1=[];
	$array_user5_2_1=[];
	$array_user6_8_1=[];
	
	foreach($irekae_all as $user){
		
		/////////////////////////////////////////////////////
		////////////ここから共通の一致検索///////////////////////
		/////////////////////////////////////////////////////
		//OK!
		foreach($logic_cancer0_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				for($i=0;$i<count($logic_cancer0_1);$i++){
				if($row3 == $logic_cancer0_1[$i]){
					if($user[0] == "0"){
							$cancer0_case1_count1++;
					}
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer0_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
				for($i=0;$i<count($logic_cancer0_2);$i++){
				if($row3 == $logic_cancer0_2[$i]){
					if($user[0] == "0"){
						$cancer0_case2_count1++;
					}
					if($user[0] == "1"){
						$cancer0_case2_count2++;
					}
					if($user[0] == "2"){
						$cancer0_case2_count3++;
					}
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer0_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
					if($user[0] == "1"){
						$cancer0_case3_count1++;
					break;
					}
					
					if($user[0] == "2"){
							if(!in_array($user[1],$array_user0_3)){//$array_user0_3内に既に同じid名がなかったら
								$array_user0_3[] = $user[1];//新しい（違う）id名と判断できるので配列にidを追加していく
								$array_user0_3_1[] = $user[2];//新しい（違う）id名と判断できるので配列に癌腫を追加していく
						}else {//既に同じid名があったら
							$aru_index0 = array_search($user[1],$array_user0_3);//既にあるindex番号取得
							if($array_user0_3_1[$aru_index0] != $user[2]){//既にある癌腫名と$user[2]が違う癌腫名だったら
								$cancer0_case3_count2++;
						}
					}
				}
			}
		}
		
		//OK!
		foreach($logic_cancer0_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				for($i=0;$i<count($logic_cancer0_4);$i++){
					if($row3 == $logic_cancer0_4[$i]){
						if($user[0] == "2"){
							if(!in_array($user[1],$array_user0_4)){//$array_user0_4内に既に同じid名がなかったら
								$array_user0_4[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
							}
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer0_5 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case5
				for($i=0;$i<count($logic_cancer0_5);$i++){
					if($row3 == $logic_cancer0_5[$i]){
						if($user[0] == "2"){
								$array_user0_5[] = $user[1];
							}
						}
					}
				}
			}
		
		/////////////////////////////////////////////////////
		////////////ここから乳癌の一致検索///////////////////////
		/////////////////////////////////////////////////////
		//OK!
		foreach($logic_cancer1_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				if($row3 == $logic_cancer1_1[0]){
					if($user[0] == "0"){
					if($user[3] == "４５歳以下"){
						$cancer1_case1_count1++;
						}
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
				if($row3 == $logic_cancer1_2[0]){
					if($user[0] == "0"){
						$cancer1_case2_count1++;
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
				if($row3 == $logic_cancer1_3[0]){
					if($user[4] == "men"){
						$cancer1_case3_count1++;
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				if($row3 == $logic_cancer1_4[0]){
					if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
						if($user[4] == "men"){
								$cancer1_case4_count1++;
							}
						}
					}
				}
			}
		//OK!
		foreach($logic_cancer1_5 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case5
				if($row3 == $logic_cancer1_5[0]){
					$cancer1_case5_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer1_5);$i++){
				if($row3 == $logic_cancer1_5[$i]){
					if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
								$cancer1_case5_count2++;
							}
						}
					}
				}
			}
		//OK!
		foreach($logic_cancer1_6 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case6
				if($row3 == $logic_cancer1_6[0]){
					if($user[0] == "1"){
						if($user[3] == "４５歳以下"){
							$cancer1_case6_count1++;
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_7 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case7
				if($row3 == $logic_cancer1_7[0]){
					if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
					if(!in_array($user[1],$array_user1_7_1)){//$array_user1_7内に既に同じid名がなかったら
						$array_user1_7_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
						}else {//既に同じid名があったら
							$cancer1_case7_count1++;
							}
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_8 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case8
				if($row3 == $logic_cancer1_8[0]){
					if($user[0] == "1"){
							if(!in_array($user[1],$array_user1_8_1)){//$array_user1_8_1内に既に同じid名がなかったら
								$array_user1_8_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
								$array_user1_8_2[] = $user[3];//最初の年齢を入れる
							}else {//既に同じid名があったら
								$aru_index = array_search($user[1],$array_user1_8_1);//既にあるindex番号取得
									if($array_user1_8_2[$aru_index] == "４５歳〜５０歳"||$array_user1_8_2[$aru_index] == "４５歳以下"){
									$cancer1_case8_count1++;
								}
					}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_9 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case9
				if($row3 == $logic_cancer1_9[0]){
					if($user[0] == "1"){
						if(!in_array($user[1],$array_user1_9_1)){//$array_user1_7内に既に同じid名がなかったら
							$array_user1_9_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_10 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case10
				if($row3 == $logic_cancer1_10[0]){
					if($user[0] == "1"){
						$cancer1_case10_count1++;
					}else if($user[0] == "2"){
						$cancer1_case10_count2++;
					}
				}
			}
		}
		
		/*質問事項
		foreach($logic_cancer1_11 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case11
				if($row3 == $logic_cancer1_11[0]){
					if($user[0] == "1"){
						$cancer1_case11_count1++;
					}
					if($user[0] == "1"||$user[0] == "2"){
						$cancer1_case11_count2++;
					}
				}
			}
		}*/
		//OK!
		foreach($logic_cancer1_12 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case12
				if($row3 == $logic_cancer1_12[0]){
					if($user[0] == "2"){
						$cancer1_case12_count1++;
					}
					}
				if($row3 == $logic_cancer1_12[1]){
					if($user[0] == "1"){
						$cancer1_case12_count2++;
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_13 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case13
				if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
					if(!in_array($user[1],$array_user1_13_1)){//$array_user1_13_1内に既に同じid名がなかったら
						$array_user1_13_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
						$array_user1_13_2[] = $user[2];//癌腫名を入れる
					}else {//既に同じid名があったら
						$aru_index1 = array_search($user[1],$array_user1_13_1);//既にあるindex番号取得
						if($array_user1_13_2[$aru_index1] != $user[2]){//既にある癌腫名と$user[2]が違う癌腫名だったら
						$cancer1_case13_count1++;
							}
						}
					}
				}
			}
		//OK!
		foreach($logic_cancer1_14 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case14
				if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
					if(!in_array($user[1],$array_user1_14_1)){//$array_user1_13_1内に既に同じid名がなかったら
						$array_user1_14_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer1_15 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case15
					if($user[0] == "1"||$user[0] == "2"){
						$cancer1_case15_count1++;
				}
			}
		}
		
		
		/*ロジック見直しムズイ
		foreach($logic_cancer1_16 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case16
				if($user[0] == "1"||$user[0] == "2"){
					if(!in_array($user[1],$array_user1_16_1)){//$array_user1_13_1内に既に同じid名がなかったら
						$array_user1_16_0[] = $user[0];//親等を入れる
						$array_user1_16_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
						$array_user1_16_2[] = $user[2];//癌腫名を入れる
						$array_user1_16_3[] = $user[3];//診断年齢を入れる
					}else {
					
					$aru_index2 = array_search($user[1],$array_user1_16_1);//既にあるindex番号取得
					if($array_user1_16_2[$aru_index2] =="乳癌"){
						if($array_user1_16_3[$aru_index2] == "４５歳〜５０歳"||$array_user1_16_3[$aru_index2] == "４５歳以下"){//既にある癌腫名と$user[2]が違う癌腫名だったら
							$cancer1_case13_count1++;
						}
					}else if($array_user1_16_2[$aru_index2] =="卵巣癌/腹膜癌/卵管癌"){
						$cancer1_case13_count1++;
					}
					
						}
				}
					/*if($user[0] == "1"||$user[0] == "2"){
						if($user[0] == "乳癌"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
									$cancer1_case16_count1++;
						}
					}
				}*/
			/*}
		}*/
		
		
		/*ロジック見直しムズイ
		foreach($logic_cancer1_17 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case17
				for($i=0;$i<count($logic_cancer1_17);$i++){
					if($user[0] == "1"||$user[0] == "2"){
						$cancer1_case17_count1++;
						break;
						}
						if($user[0] == "乳癌"){
							$cancer1_case17_count2++;
								break;
						}
					}
					}
				}
		foreach($logic_cancer1_18 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case18
				for($i=0;$i<count($logic_cancer1_18);$i++){
					if($user[0] == "1"||$user[0] == "2"){
						$cancer1_case18_count1++;
						break;
					}
				}
			}
		}
		*/
		/////////////////////////////////////////////////////
		////////////ここから卵巣癌/腹膜癌/卵管癌の一致検索/////////
		/////////////////////////////////////////////////////
		//OK!
		foreach($logic_cancer2_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				if($row3 == $logic_cancer2_1[0]){
					if($user[0] == "0"){
						$cancer2_case1_count1++;
					}
					}
				}
			}
		//OK!
		foreach($logic_cancer2_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
					if($row3 == $logic_cancer2_2[0]){
					if($user[0] == "1"||$user[0] == "2"){
						$cancer2_case2_count1++;
							}
				}
			}
		}
		/*複雑、保留foreach($logic_cancer2_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
				if($row3 == $logic_cancer2_3[0]){
				if($user[0] == "1"||$user[0] == "2"){
					$cancer2_case3_count1++;
						break;
				}
					}
				for($i=1;$i<count($logic_cancer2_3);$i++){
					if($row3 == $logic_cancer2_3[$i]){
							if($user[0] == "1"||$user[0] == "2"){
								if($user[0] == "大腸癌"){
									if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
											$cancer2_case3_count2++;
										}
									}
							}
						}
					}
				}
			}*/
		/*保留
		foreach($logic_cancer2_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				if($row3 == $logic_cancer2_4[0]){
					if($user[0] == "1"||$user[0] == "2"){
						$cancer2_case4_count1++;
					}
					}
				for($i=1;$i<count($logic_cancer2_4);$i++){
					if($row3 == $logic_cancer2_4[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if(!in_array($user[1],$array_user2_4_1)){//$array_user1_13_1内に既に同じid名がなかったら
								$array_user2_4_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
								$array_user2_4_2[] = $user[2];//癌腫名を入れる
							}else {//既に同じid名があったら
								$aru_index2_4 = array_search($user[1],$array_user2_4_1);//既にあるindex番号取得
								if($array_user2_4_2[$aru_index2_4] != $user[2]){//既にある癌腫名と$user[2]が違う癌腫名だったら
									$cancer2_case4_count2++;
								}
							}
							/*$cancer2_case4_count2++;*/
						/*}
					}
				}
			}
		}*/
		/////////////////////////////////////////////////////
		////////////ここから子宮体癌の一致検索///////////////////
		/////////////////////////////////////////////////////
		//OK!
		foreach($logic_cancer3_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				if($row3 == $logic_cancer3_1[0]||$row3 == $logic_cancer3_1[1]){
					$cancer3_case1_count1++;
					}
				}
			}
		//OK!
		foreach($logic_cancer3_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
				if($row3 == $logic_cancer3_2[0]){
						$cancer3_case2_count1++;
					}
					if($row3 == $logic_cancer3_2[1]||$row3 == $logic_cancer3_2[2]){
						$cancer3_case2_count2++;
					}
				}
			}
		//OK!
		foreach($logic_cancer3_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
				if($row3 == $logic_cancer3_3[0]){
					$cancer3_case3_count1++;
				}
				}
			}
		//OK!
		foreach($logic_cancer3_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				if($row3 == $logic_cancer3_4[1]){//子宮体癌だったら
					if($user[0] == "0"){
						if($cancer3_case4_count1 == 0){//空っぽだったら
						$cancer3_case4_count1++;
							break;
							}
						}
					}
				for($i=0;$i<count($logic_cancer3_4);$i++){
				if($row3 == $logic_cancer3_4[$i]){
					if($user[0] == "0"){
							$cancer3_case4_count2++;
						}
					}
				}
			}
		}
		
		//OK!
		foreach($logic_cancer3_5 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case5
				if($row3 == $logic_cancer3_5[0]){
					$cancer3_case5_count1++;
					break;
				}
				for($i=1;$i<33;$i++){
					if($row3 == $logic_cancer3_5[$i]){
							$cancer3_case5_count2++;
						}
			}
		}
	}
		//OK!
		foreach($logic_cancer3_6 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case6
				for($i=0;$i<count($logic_cancer3_6);$i++){
				if($row3 == $logic_cancer3_6[$i]){
					$cancer3_case6_count1++;
				}
			}
				}
		}
		
		/*保留
		foreach($logic_cancer3_7 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case7
				if($row3 == $logic_cancer3_7[0]||$row3 == $logic_cancer3_7[1]){
					$cancer3_case7_count1++;
					break;
				}
				for($i=2;$i<18;$i++){
					if($row3 == $logic_cancer3_7[$i]){
						$cancer3_case7_count2++;
					}
				}
			}
		}*/
		
		//OK!
		foreach($logic_cancer3_8 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case8
				if($user[0] == "1"){
					if($row3 == $logic_cancer3_8[1]){//子宮体癌で
						if(!in_array($user[1],$array_user3_8_1)){//$array_user3_8_1内に既に同じid名がなかったら
							$array_user3_8_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
							$array_user3_8_2[] = $user[2];//癌腫名を入れる
						}else {//既に同じid名があったら
							$aru_index1 = array_search($user[1],$array_user3_8_1);//既にあるindex番号取得
							if($array_user3_8_2[$aru_index1] != $user[2]){//既にある癌腫名と$user[2]が違う癌腫名だったら
								$cancer3_case8_count1++;
									break;
							}
						}
					}
					}
			}
		}
		//OK!
		foreach($logic_cancer3_9 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case9
				
				for($i=0;$i<count($logic_cancer3_9);$i++){
					if($row3 == $logic_cancer3_9[$i]){
						$cancer3_case9_count1++;
						if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
						$cancer3_case9_count2++;
						}
					}
				}
			}
		}
		/////////////////////////////////////////////////////
		////////////ここから前立腺癌の一致検索///////////////////
		/////////////////////////////////////////////////////
		foreach($logic_cancer4_1 as $row3){
		if(array_intersect($row3,$user) == $row3){
			//case1
				if($row3 == $logic_cancer4_1[0]){
					$cancer4_case1_count1++;
					break;
			}
		}
	}
		//OK!
		foreach($logic_cancer4_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
					if($row3 == $logic_cancer4_2[0]){
						$cancer4_case2_count1++;
					break;
					}
				for($i=1;$i<count($logic_cancer4_2);$i++){
					if($row3 == $logic_cancer4_2[$i]){
				if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
					if(!in_array($user[1],$array_user4_2_1)){//$array_user3_8_1内に既に同じid名がなかったら
						$array_user4_2_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
					}
				}
						}
					}
			}
		}
		//OK!
		foreach($logic_cancer4_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
					if($row3 == $logic_cancer4_3[0]){
							$cancer4_case3_count1++;
					break;
					}
					if($row3 == $logic_cancer4_3[1]){
						if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								$cancer4_case3_count2++;
							}
						}
					}
				}
		}
		//OK!
		foreach($logic_cancer4_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				if($row3 == $logic_cancer4_4[0]){
					$cancer4_case4_count1++;
					break;
				}
				if($row3 == $logic_cancer4_4[1]){
					if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
							$cancer4_case4_count2++;
						}
				}
			}
		}
		//OK!
		foreach($logic_cancer4_5 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case5
				if($row3 == $logic_cancer4_5[0]){
					$cancer4_case5_count1++;
					break;
				}
				if($row3 == $logic_cancer4_5[1]){
					if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
						$cancer4_case5_count2++;
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer4_6 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case6
				if($row3 == $logic_cancer4_6[0]){
					$cancer4_case6_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer4_6);$i++){
				if($row3 == $logic_cancer4_6[$i]){
					if($user[0] == "1"||$user[0] == "2"){
							$cancer4_case6_count2++;
							}
						}
				}
			}
		}
		/////////////////////////////////////////////////////
		////////////ここから膵癌の一致検索///////////////////////
		/////////////////////////////////////////////////////
		//OK!
		foreach($logic_cancer5_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				if($row3 == $logic_cancer5_1[0]){
					$cancer5_case1_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer5_1);$i++){
					if($row3 == $logic_cancer5_1[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if(!in_array($user[1],$array_user5_1_1)){//$array_user3_8_1内に既に同じid名がなかったら
								$array_user5_1_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
							}
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer5_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
				if($row3 == $logic_cancer5_2[0]){
					$cancer5_case2_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer5_2);$i++){
					if($row3 == $logic_cancer5_2[$i]){
						if($user[0] == "1"||$user[0] == "2"||$user[0] == "3"){
							if(!in_array($user[1],$array_user5_2_1)){//$array_user3_8_1内に既に同じid名がなかったら
								$array_user5_2_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
							}
						}
					}
				}
			}
		}
		/////////////////////////////////////////////////////
		////////////ここから大腸癌の一致検索///////////////////////
		/////////////////////////////////////////////////////
		foreach($logic_cancer6_1 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case1
				if($row3 == $logic_cancer6_1[0]){
					if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
						$cancer6_case1_count1++;
						break;
						}
				}
			}
		}
		foreach($logic_cancer6_2 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case2
				if($row3 == $logic_cancer6_2[0]){
					$cancer6_case2_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer6_2);$i++){
					if($row3 == $logic_cancer6_2[$i]){
							$cancer6_case2_count2++;
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer6_3 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case3
				if($row3 == $logic_cancer6_3[0]){
					$cancer6_case3_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer6_3);$i++){
					if($row3 == $logic_cancer6_3[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								$cancer6_case3_count2++;
							}
						}
					}
				}
			}
		}
		
		/*保留
		foreach($logic_cancer6_4 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case4
				for($i=0;$i<count($logic_cancer6_4);$i++){
					if($row3 == $logic_cancer6_4[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if(!in_array($user[1],$array_user5_2_1)){//$array_user3_8_1内に既に同じid名がなかったら
								$array_user5_2_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
							}else {//既に同じid名があったら
								$aru_index1 = array_search($user[1],$array_user3_8_1);//既にあるindex番号取得
								if($array_user3_8_2[$aru_index1] != $user[2]){//既にある癌腫名と$user[2]が違う癌腫名だったら
									$cancer3_case8_count1++;
									break;
								}
							}
						}
						/*if($user[0] == "1"||$user[0] == "2"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								$cancer6_case4_count1++;
							}
						}*/
					/*}
				}
			}
		}*/
		//OK!
		foreach($logic_cancer6_5 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case5
				if($row3 == $logic_cancer6_5[0]){
					if($user[3] == "５１歳以上"){
						$cancer6_case5_count1++;
						break;
					}
				}
				for($i=1;$i<count($logic_cancer6_5);$i++){
					if($row3 == $logic_cancer6_5[$i]){
						if($user[0] == "1"){
							$cancer6_case5_count2++;
						}
					}
				}
			}
		}
		foreach($logic_cancer6_6 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case6
				if($row3 == $logic_cancer6_6[0]){
						$cancer6_case6_count1++;
					break;
				}
				for($i=1;$i<count($logic_cancer6_6);$i++){
					if($row3 == $logic_cancer6_6[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								$cancer6_case6_count2++;
							}
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer6_7 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case7
				if($row3 == $logic_cancer6_7[0]){
					if($user[0] == "1"){
						if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
							$cancer6_case7_count1++;
							break;
						}
					}
				}
			}
		}
		foreach($logic_cancer6_8 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case8
				if($row3 == $logic_cancer6_8[0]){
					if($user[0] == "1"){
						$cancer6_case8_count1++;
						break;
						}
				}
				//OK!
				for($i=1;$i<count($logic_cancer6_8);$i++){
					if($row3 == $logic_cancer6_8[$i]){
						if($user[0] == "1"){
						if(!in_array($user[1],$array_user6_8_1)){//$array_user3_8_1内に既に同じid名がなかったら
							$array_user6_8_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
						}
							}
						/*if($user[0] == "1"){
								$cancer6_case8_count2++;
						}*/
					}
				}
			}
		}
		/*保留foreach($logic_cancer6_9 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case9
				for($i=0;$i<count($logic_cancer6_9);$i++){
					if($row3 == $logic_cancer6_9[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							if(!in_array($user[1],$array_user6_9_1)){//$array_user3_8_1内に既に同じid名がなかったら
								$array_user6_9_1[] = $user[1];//新しい（違う）id名と判断できるので配列に追加していく
								$array_user6_9_2[] = $user[3];//新しい（違う）id名と判断できるので配列に追加していく
							}
							/*if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								$cancer6_case9_count1++;
							}*/
						/*}
					}
				}
			}
		}*/
		foreach($logic_cancer6_10 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case10
				if($row3 == $logic_cancer6_10[0]){
					if($user[0] == "1"){
						$cancer6_case10_count1++;
						break;
					}
				}
				for($i=1;$i<count($logic_cancer6_10);$i++){
					if($row3 == $logic_cancer6_10[$i]){
						if($user[0] == "1"){
							if($user[3] == "４５歳〜５０歳"||$user[3] == "４５歳以下"){
								if($user[0] == "大腸癌"){
									$cancer6_case10_count2++;
								}
							}
						}
					}
				}
			}
		}
		foreach($logic_cancer6_11 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case11
				if($row3 == $logic_cancer6_11[0]){
					if($user[0] == "1"||$user[0] == "2"){
						$cancer6_case11_count1++;
						break;
					}
				}
				for($i=1;$i<count($logic_cancer6_11);$i++){
					if($row3 == $logic_cancer6_11[$i]){
						if($user[0] == "1"||$user[0] == "2"){
							$cancer6_case11_count2++;
						}
					}
				}
			}
		}
		//OK!
		foreach($logic_cancer6_12 as $row3){
			if(array_intersect($row3,$user) == $row3){
				//case12
				if($row3 == $logic_cancer6_12[0]){
					if($user[0] == "0"){
						$cancer6_case12_count1++;
					}
				}
				for($i=1;$i<count($logic_cancer6_12);$i++){
					if($row3 == $logic_cancer6_12[$i]){
						if($user[0] == "0"){
							$cancer6_case12_count2++;
						}
					}
				}
			}
		}
		
		
		
		
		
	}
	
	var_dump($array_user3_8_1);
	var_dump($array_user3_8_2);
	/*var_dump(count($array_user4_2_1));*/
	
	//ここから共通の最終診断結果
	if($cancer0_case1_count1 > 2){
		$kekka0[]=$cancer0_hit_all[0];
	}
	if($cancer0_case2_count1 > 0&&$cancer0_case2_count2 > 0&&$cancer0_case2_count3 > 0){
		$kekka0[]=$cancer0_hit_all[1];
	}
	if($cancer0_case3_count1 > 0&&$cancer0_case3_count2 > 0){
		$kekka0[]=$cancer0_hit_all[2];
	}
	if(count($array_user0_4) > 2){
		$kekka0[]=$cancer0_hit_all[3];
	}
	foreach(array_count_values($array_user0_5) as $row){
	if($row > 2){
		$kekka0[]=$cancer0_hit_all[4];
	}
		}
	//ここから乳癌の最終診断結果
	if($cancer1_case1_count1 > 0){
		$kekka1[]=$cancer1_hit_all[0];
	}
	if($cancer1_case2_count1 > 1){
		$kekka1[]=$cancer1_hit_all[1];
	}
	if($cancer1_case3_count1 > 0){
		$kekka1[]=$cancer1_hit_all[2];
	}
	if($cancer1_case4_count1 > 0){
	$kekka1[]=$cancer1_hit_all[3];
	}
	if($cancer1_case5_count1 > 0&&$cancer1_case5_count2 > 0){
		$kekka1[]=$cancer1_hit_all[4];
	}
	if($cancer1_case6_count1 > 0){
	$kekka1[]=$cancer1_hit_all[5];
	}
	if($cancer1_case7_count1 > 0){
		$kekka1[]=$cancer1_hit_all[6];
	}
	if($cancer1_case8_count1 > 0){
		$kekka1[]=$cancer1_hit_all[7];
	}
	if(count($array_user1_9_1) > 1){
		$kekka1[]=$cancer1_hit_all[8];
	}
	if($cancer1_case10_count1 > 0&&$cancer1_case10_count2 > 0){
		$kekka1[]=$cancer1_hit_all[9];
	}
	if($cancer1_case11_count1 > 0&&$cancer1_case11_count2 > 0){
		$kekka1[]=$cancer1_hit_all[10];
	}
	if($cancer1_case12_count1 > 0&&$cancer1_case12_count2 > 0){
		$kekka1[]=$cancer1_hit_all[11];
	}
	if($cancer1_case13_count1 > 0){
		$kekka1[]=$cancer1_hit_all[12];
	}
	if(count($array_user1_14_1) > 1){
		$kekka1[]=$cancer1_hit_all[13];
	}
	if($cancer1_case15_count1 > 2){
		$kekka1[]=$cancer1_hit_all[14];
	}
	if($cancer1_case16_count1 > 1&&$cancer1_case16_count2 > 0){
		$kekka1[]=$cancer1_hit_all[15];
	}
	if($cancer1_case17_count1 > 1&&$cancer1_case17_count2 > 0){
		$kekka1[]=$cancer1_hit_all[16];
	}
	if($cancer1_case18_count1 > 1){
		$kekka1[]=$cancer1_hit_all[17];
	}
	//ここから卵巣癌/腹膜癌/卵管癌の最終診断結果
	if($cancer2_case1_count1 > 0){
		$kekka2[]=$cancer2_hit_all[0];
	}
	if($cancer2_case2_count1 > 0){
		$kekka2[]=$cancer2_hit_all[1];
	}
	if($cancer2_case3_count1 > 0&&$cancer2_case3_count2 > 0){
		$kekka2[]=$cancer2_hit_all[2];
	}
	if($cancer2_case4_count1 > 0&&$cancer2_case4_count2 > 0){
		$kekka2[]=$cancer2_hit_all[3];
	}
	//ここから子宮体癌の最終診断結果
	if($cancer3_case1_count1 > 0){
		$kekka3[]=$cancer3_hit_all[0];
	}
	if($cancer3_case2_count1 > 0&&$cancer3_case2_count2 > 0){
		$kekka3[]=$cancer3_hit_all[1];
	}
	if($cancer3_case3_count1 > 1){
		$kekka3[]=$cancer3_hit_all[2];
	}
	if($cancer3_case4_count1 > 0&&$cancer3_case4_count2 > 0){
		$kekka3[]=$cancer3_hit_all[3];
	}
	if($cancer3_case5_count1 > 0&&$cancer3_case5_count2 > 0){
		$kekka3[]=$cancer3_hit_all[4];
	}
	if($cancer3_case6_count1 > 0){
		$kekka3[]=$cancer3_hit_all[5];
	}
	if($cancer3_case7_count1 > 0&&$cancer3_case7_count2 > 0){
		$kekka3[]=$cancer3_hit_all[6];
	}
	if($cancer3_case8_count1 > 0){
		$kekka3[]=$cancer3_hit_all[7];
	}
	if($cancer3_case9_count1 > 1&&$cancer3_case9_count2 > 0){
		$kekka3[]=$cancer3_hit_all[8];
	}
	
	//ここから前立腺癌の最終診断結果
	if($cancer4_case1_count1 > 0){
		$kekka4[]=$cancer4_hit_all[0];
	}
	if($cancer4_case2_count1 > 0&&count($array_user4_2_1) > 1){
		$kekka4[]=$cancer4_hit_all[1];
	}
	if($cancer4_case3_count1 > 0&&$cancer4_case3_count2 > 0){
		$kekka4[]=$cancer4_hit_all[2];
	}
	if($cancer4_case4_count1 > 0&&$cancer4_case4_count2 > 0){
		$kekka4[]=$cancer4_hit_all[3];
	}
	if($cancer4_case5_count1 > 0&&$cancer4_case5_count2 > 0){
		$kekka4[]=$cancer4_hit_all[4];
	}
	if($cancer4_case6_count1 > 0&&$cancer4_case6_count2 > 1){
		$kekka4[]=$cancer4_hit_all[5];
	}
	//ここから膵癌の最終診断結果
	if($cancer5_case1_count1 > 0&&count($array_user5_1_1) > 1){
		$kekka5[]=$cancer5_hit_all[0];
	}
	if($cancer5_case2_count1 > 0&&count($array_user5_2_1) > 1){
		$kekka5[]=$cancer5_hit_all[1];
	}
	//ここから大腸癌の最終診断結果
	if($cancer6_case1_count1 > 0){
		$kekka6[]=$cancer6_hit_all[0];
	}
	if($cancer6_case2_count1 > 0&&$cancer6_case2_count2 > 0){
		$kekka6[]=$cancer6_hit_all[1];
	}
	if($cancer6_case3_count1 > 0&&$cancer6_case3_count2 > 0){
		$kekka6[]=$cancer6_hit_all[2];
	}
	if($cancer6_case4_count1 > 0){
		$kekka6[]=$cancer6_hit_all[3];
	}
	if($cancer6_case5_count1 > 0&&$cancer6_case5_count2 > 0){
		$kekka6[]=$cancer6_hit_all[4];
	}
	if($cancer6_case6_count1 > 0&&$cancer6_case6_count2 > 0){
		$kekka6[]=$cancer6_hit_all[5];
	}
	if($cancer6_case7_count1 > 0){
		$kekka6[]=$cancer6_hit_all[6];
	}
	if($cancer6_case8_count1 > 0&&count($array_user6_8_1) > 0){
		$kekka6[]=$cancer6_hit_all[7];
	}
	if($cancer6_case9_count1 > 1){
		$kekka6[]=$cancer6_hit_all[8];
	}
	if($cancer6_case10_count1 > 0&&$cancer6_case10_count2 > 0){
		$kekka6[]=$cancer6_hit_all[9];
	}
	if($cancer6_case11_count1 > 1&&$cancer6_case11_count2 > 1){
		$kekka6[]=$cancer6_hit_all[10];
	}
	if($cancer6_case12_count1 > 0&&$cancer6_case12_count2 > 0){
		$kekka6[]=$cancer6_hit_all[11];
	}
	/*var_dump($kekka);*/
	/*$eee = str_replace("　","\r",$kekka[0]);*/
	
	/*$kekka = array_unique($kekka3);*/
	
	if(!empty($kekka0)){
		/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 子宮体癌 -</div>";*/
		foreach($kekka0 as $row){
			$last_kekka.=$row."<BR><BR>";
		}
	}
	if(!empty($kekka1)){
		/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 子宮体癌 -</div>";*/
		foreach($kekka1 as $row){
			$last_kekka.=$row."<BR><BR>";
		}
	}
	if(!empty($kekka2)){
		/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 子宮体癌 -</div>";*/
		foreach($kekka2 as $row){
			$last_kekka.=$row."<BR><BR>";
		}
	}
	if(!empty($kekka3)){
	/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 子宮体癌 -</div>";*/
	foreach($kekka3 as $row){
		$last_kekka.=$row."<BR><BR>";
		}
		}
	if(!empty($kekka4)){
	/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 前立腺癌 -</div>";*/
	foreach($kekka4 as $row){
		$last_kekka.=$row."<BR><BR>";
	}
		}
	if(!empty($kekka5)){
		/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 膵癌 -</div>";*/
		foreach($kekka5 as $row){
			$last_kekka.=$row."<BR><BR>";
		}
	}
	if(!empty($kekka6)){
		/*$last_kekka.="<div class='mb-4 mt-4 text-center' style='font-size:21px;color:#000'>- 大腸癌 -</div>";*/
		foreach($kekka6 as $row){
			$last_kekka.=$row."<BR><BR>";
		}
	}
	/*$kekka = array_unique($kekka);//重複削除
	$last_kekka = implode("、 ", $kekka);//配列をカンマ区切り文字列にする*/
	
	if(empty($last_kekka)){
		$icon_text = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/></svg>';
		$title = '遺伝カウンセリングおよび遺伝学的検査の<span style="color:#ff0000;">適応基準には該当しません。</span>';
		$main = 'この癌リスク評価ツールは、遺伝性癌において最も頻度の高い遺伝性乳癌卵巣癌症候群とリンチ症候群に焦点をあてていますので　本判定結果では遺伝カウンセリング／遺伝学的検査の適応基準を満たさないとしても、他の遺伝性癌の可能性は残ることご留意ください。<BR><BR>

本判定結果にかかわらず、クライエント（または患者）ご本人が癌の遺伝的側面について心配されているようでしたら、遺伝カウンセリングは選択肢となります。以下の「情報リンク」から「最寄りの医療機関」を検索し、該当する医療機関の相談窓口に関する情報をお伝えしましょう。';
	}else {
		$icon_text = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16"><path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/><path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/></svg>';
	$title = '本症例の癌の既往歴／家族歴は、<BR>遺伝カウンセリングまたは遺伝学的検査が適応となる<span style="color:#ff0000;">以下の基準項目を満たしています。</span>';
	
		$main = '<h4 class="text-left" style="font-size:17px;" id="kekka_text">'.$last_kekka.'</h4><BR><BR><BR>
		
		適切な遺伝カウンセリングの中で遺伝学的検査を受け、癌の遺伝について判明することで、以下のようなメリットとデメリットがあります。<BR><BR>
		【メリット】<BR><BR>
		(1)癌の発生予防につながる介入があり得る<BR>
		(2)具体的な癌のサーベイランス方法が推奨され得る（一般の癌検診とは異なる）<BR>
		(3)自分だけではなく家族のケアにもつながり得る<BR>
		(4)明確な癌の遺伝が無いことが分かり得る<BR><BR>

		【デメリット】<BR><BR>
		(1)心理的な負担<BR>
		(2)病的意義が現時点では明確に分からない遺伝子変異が検査で見つかり得る<BR><BR>
		
		クライエント（または患者）ご本人の意向に沿うのであれば、遺伝専門職への紹介を提案しましょう。以下の「情報リンク」から「最寄りの医療機関」を検索し、該当する医療機関を見つけることができます。<BR>
		クライエント（または患者）ご本人の中で、気持ちの整理ができていないようであれば、上述した医療機関の相談窓口に関する情報をお伝えしたり、次回の診察時に改めてお話したりすることを提案してみましょう。<BR>
		この癌のリスク評価ツール（JCRAS-PC）では、癌の既往歴／家族歴が適応基準を満たしたとしても、必ずしも癌の遺伝があるとはいえません。また、癌の診断年齢が「不明」の場合には過大評価を、癌種の不正確さがある場合には過少または過大評価している可能性があることにご留意ください。';
	
}
	
	
	} catch (Exception $e) {
		echo '<span class="error">エラーがありました。</span><br>';
		echo $e->getMessage();
		exit();
	}
	
	
?>

<!doctype html>
<html lang="ja">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
		<!--ここまでbootstrap-->
		
		
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
		
		
		<link rel="stylesheet" href="../css/style.css?2022axvsdafxddrwsvxer">
		<!--<link rel="stylesheet" href="../css/jquery.datetimepicker.css">-->
		
		<style>
			
			.container {
				display: flex;
				align-items: center;
			}
			
			
			/* 通常のボタン色 */
			.btn-custom1,
			.btn-custom1.disabled, .btn-custom1:disabled {
				color: #fff;
				background-color: #50753c;
				border-color: #50753c;
			}
			
			/* focusされた時の枠線の色 */
			.btn-custom1:focus, .btn-custom1.focus,
			.btn-custom1:not(:disabled):not(.disabled):active:focus, .btn-custom1:not(:disabled):not(.disabled).active:focus,
			.show > .btn-custom1.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(200, 123, 255, 0.5);
			}
			
			/* hover時（マウスカーソルを重ねた時）の色（通常より濃いor暗めの色を指定） */
			.btn-custom1:hover {
				color: #fff;
				background-color: #3e592e;
				border-color: #3e592e;
			}
			
			/* active時の色（hover時と同等かさらに濃いor暗めの色を指定） */
			.btn-custom1:not(:disabled):not(.disabled):active, .btn-custom1:not(:disabled):not(.disabled).active,
			.show > .btn-custom1.dropdown-toggle {
				color: #fff;
				background-color: #304423;
				border-color: #304423;
			}
			
			
			/* 通常のボタン色 */
			.btn-custom2,
			.btn-custom2.disabled, .btn-custom2:disabled {
				color: #fff;
				background-color: #5184a1;
				border-color: #5184a1;
			}
			
			/* focusされた時の枠線の色 */
			.btn-custom1:focus, .btn-custom1.focus,
			.btn-custom1:not(:disabled):not(.disabled):active:focus, .btn-custom1:not(:disabled):not(.disabled).active:focus,
			.show > .btn-custom1.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(200, 123, 255, 0.5);
			}
			
			/* hover時（マウスカーソルを重ねた時）の色（通常より濃いor暗めの色を指定） */
			.btn-custom2:hover {
				color: #fff;
				background-color: #487487;
				border-color: #487487;
			}
			
			/* active時の色（hover時と同等かさらに濃いor暗めの色を指定） */
			.btn-custom2:not(:disabled):not(.disabled):active, .btn-custom2:not(:disabled):not(.disabled).active,
			.show > .btn-custom2.dropdown-toggle {
				color: #fff;
				background-color: #3a5e6d;
				border-color: #3a5e6d;
			}
			
			
			/* 通常のボタン色 */
			.btn-custom3,
			.btn-custom3.disabled, .btn-custom3:disabled {
				color: #fff;
				background-color: #7f91c2;
				border-color: #7f91c2;
			}
			
			/* focusされた時の枠線の色 */
			.btn-custom1:focus, .btn-custom1.focus,
			.btn-custom1:not(:disabled):not(.disabled):active:focus, .btn-custom1:not(:disabled):not(.disabled).active:focus,
			.show > .btn-custom1.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(200, 123, 255, 0.5);
			}
			
			/* hover時（マウスカーソルを重ねた時）の色（通常より濃いor暗めの色を指定） */
			.btn-custom3:hover {
				color: #fff;
				background-color: #69799e;
				border-color: #69799e;
			}
			
			/* active時の色（hover時と同等かさらに濃いor暗めの色を指定） */
			.btn-custom3:not(:disabled):not(.disabled):active, .btn-custom3:not(:disabled):not(.disabled).active,
			.show > .btn-custom3.dropdown-toggle {
				color: #fff;
				background-color: #525f7c;
				border-color: #525f7c;
			}
			
			
			/* 通常のボタン色 */
			.btn-custom4,
			.btn-custom4.disabled, .btn-custom4:disabled {
				color: #fff;
				background-color: #c1aebf;
				border-color: #c1aebf;
			}
			
			/* focusされた時の枠線の色 */
			.btn-custom1:focus, .btn-custom1.focus,
			.btn-custom1:not(:disabled):not(.disabled):active:focus, .btn-custom1:not(:disabled):not(.disabled).active:focus,
			.show > .btn-custom1.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(200, 123, 255, 0.5);
			}
			
			/* hover時（マウスカーソルを重ねた時）の色（通常より濃いor暗めの色を指定） */
			.btn-custom4:hover {
				color: #fff;
				background-color: #998c98;
				border-color: #998c98;
			}
			
			/* active時の色（hover時と同等かさらに濃いor暗めの色を指定） */
			.btn-custom4:not(:disabled):not(.disabled):active, .btn-custom4:not(:disabled):not(.disabled).active,
			.show > .btn-custom4.dropdown-toggle {
				color: #fff;
				background-color: #756b75;
				border-color: #756b75;
			}
			
			.btn_b{
				display:inline;
				width:24%;
				height:50px;
			}
			
			@media screen and (max-width: 767px){/*スマホ時本番用480*/
			
			.btn_b{
					display:block;
					width:95%;
					margin:0 auto 10px auto;
					}
			}
			
			.modal_main{
				max-height:60vh;
				overflow-y:auto;
				}
			
		</style>
		
		<title>リスク評価判定結果</title>
		
	</head>
	<body>
		
		<div class="container">
			<div class="row justify-content-center" id="abc">
				<h3 class="col-xs-12 col-lg-12 mt-5 ml-3 mr-3 text-center">
					<div class=""><?php echo $icon_text; ?></div>
					<div class="mt-5"><?php echo $title; ?></div>
				</h3>
				<div class="col-xs-12 col-lg-8 mt-5 ml-3 mr-3">
					<?php echo $main; ?>
				</div>
				
				<input type="hidden" id="family_img" value="<?php echo $fileName; ?>">
				<input type="hidden" id="family_img_check" value="<?php echo $_SESSION['user_id'].$_POST['now_time']; ?>">
				
				<div class="col-xs-12 col-lg-8 mt-5 ml-3 mr-3">
					■ 転帰<BR><BR>
							<select name="Introduction" class="form-control">
								<option value="k1" selected>現時点ではわからない</option>
								<option value="k2">遺伝子診療部の相談窓口の情報提供</option>
								<option value="k3">遺伝専門職へのメール相談</option>
								<option value="k4">遺伝子診療部への紹介</option>
								<option value="k5">特になし</option>
							</select>
				</div>
				
				
				
				<div class="col-xs-12 col-lg-8 mt-5 ml-3 mr-3 mb-5">
					■ 理由<BR><BR>
				<div id="cause" style="border:1px solid #efefef;width:100%;height:200px;overflow:auto;" contenteditable="true">入力してください。
				</div>
				</div>
				
				
				
				<div class="col-xs-12 col-lg-8 text-center sp">
					<button class="btn btn-custom1 btn_b" onclick="location.href='link.php'">情報リンクへ</button>
					<button class="btn btn-custom2 btn_b" data-toggle='modal' data-target='#mail_modal_hyouzi' id="outputBtn">メール相談</button>
					<button class="btn btn-custom3 btn_b" data-toggle='modal' data-target='#history_modal_hyouzi' id="history">履歴一覧</button>
					<!--<button class="btn btn-custom4 btn_b" data-toggle='modal' data-target='#summary_modal_hyouzi' id="summary">サマリ表示</button>-->
				</div>
				<div class="col-xs-12 col-lg-8 mt-5 mb-5 ml-3 mr-3 text-center"><a href="family_top.php" style="color:#ff0000;text-decoration:underline;">がんのリスク評価へ戻る</a></div>
			</div>
		</div>
		
		
		
		
		<!--ここからメール送信モーダル表示-->
		<div class="modal fade" id="mail_modal_hyouzi" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-body text-center mt-4">
						<h5>メール相談</h5>
					</div>
						<div class="ml-4 mr-4 mb-5">
						<form>
						<label class="font-weight-bold">宛先</label>
						<input type="text" class="form-control" id="atesaki" name="atesaki">
						<label class="font-weight-bold mt-3">差出人</label>
						<input type="text" class="form-control" id="sashidashi" name="sashidashi">
						<label class="font-weight-bold mt-3">件名</label>
						<input type="text" class="form-control" id="kenmei" name="kenmei">
						<label class="font-weight-bold mt-3">本文</label>
						<textarea class="form-control" rows="5" id="honbun" name="honbun"></textarea>
						</form>
						</div>
					
					
					<div class="modal-footer">
						<button href="#" class="btn btn-success btn_b" data-dismiss="modal">キャンセル</button>
						<button class="btn btn-primary btn_b" id="mail_kettei" data-dismiss="modal" onclick="counter('mail');">送信</button>
					</div>
					
				</div>
			</div>
		</div>
		<!--ここまでメール送信モーダル表示-->
		
		<!--ここから履歴一覧モーダル表示-->
		<div class="modal fade" id="history_modal_hyouzi" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-body text-center mt-4">
						<h5>履歴一覧</h5>
					</div>
					
					<div class="m-3 modal_main" id="kokyaku_table">
					</div>
					
					
					<div class="modal-footer">
						<button href="#" class="btn btn-primary btn_b" data-dismiss="modal">閉じる</button>
					</div>
					
				</div>
			</div>
		</div>
		<!--ここまで履歴一覧モーダル表示-->
		
		<!--ここからサマリ表示モーダル表示-->
		<div class="modal fade" id="summary_modal_hyouzi" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-body text-center mt-4">
						<h5>サマリ表示</h5>
					</div>
					
					<div class="m-3 modal_main" id="summary_table">
					</div>
					
					
					<div class="modal-footer">
						<button href="#" class="btn btn-primary btn_b" data-dismiss="modal">閉じる</button>
					</div>
					
				</div>
			</div>
		</div>
		<!--ここまでサマリ表示モーダル表示-->
		
		
		<a id="getImage" href="" style=""></a>
				
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
		
		<script>
			
			var outputBtn = document.getElementById("outputBtn");  //ボタン
			var element = document.getElementById("abc");  //画像化したい要素
			var getImage = document.getElementById("getImage");  //ダウンロード用隠しリンク
			
			
		$(function(){
		
			$(document).on('click', '#history', function() {
		
			window.scrollTo(0, 0);
			html2canvas(element).then(canvas => {
			
			$.ajax({
			type: 'POST',
			url: 'history.php',
			dataType: 'html',
			data:{
			'family_img_check':$("#family_img_check").val(),
			'family_img':$("#family_img").val(),
			'canvas_img':canvas.toDataURL("image/jpeg",0.75),
			'Introduction':$('[name="Introduction"]').val(),
			'cause':$("#cause").html(),
			'kekka_text':$("#kekka_text").html(),
			}
			})
			
			.done(function(data){
			$("#kokyaku_table").html(data);
			})
			
			});
			
		})
		})
		
			$(function(){
			
			$(document).on('click', '#summary', function() {
			
			
			$.ajax({
			type: 'POST',
			url: 'summary.php',
			dataType: 'html',
			data:{
			'user_id':"aaa",
			}
			
			})
			
			.done(function(data){
			$("#summary_table").html(data);
			})
			
			})
			
			})
			
		
			
			outputBtn.addEventListener('click', function() {
			
					window.scrollTo(0, 0);
					html2canvas(element).then(canvas => {
					
					$.ajax({
					type: 'POST',
					url: 'db_canvas.php',
					dataType: 'html',
					data:{
					'family_img_check':$("#family_img_check").val(),
					'family_img':$("#family_img").val(),
					'canvas_img':canvas.toDataURL("image/jpeg",0.75),
					'Introduction':$('[name="Introduction"]').val(),
					'cause':$("#cause").html(),
					}
					
					})
					
					.done(function(data){
					})
			
			
					});			
					});
					
			
			
			function counter(tab_number){
			
			$.ajax({
			type: 'POST',
			url: 'counter.php',
			dataType: 'html',
			data:{
			'tab_number':tab_number,
			}
			
			})
			
			.done(function(data){
			})
			
			}
			
			$(function(){
			
				$(document).on('click', '#mail_kettei', function() {
			
			$.ajax({
			type: 'POST',
			url: '../home/send_mail.php',
			dataType: 'html',
			data:{
			'family_img_check':$("#family_img_check").val(),
			'atesaki':$("#atesaki").val(),
			'sashidashi':$("#sashidashi").val(),
			'kenmei':$("#kenmei").val(),
			'honbun':$("#honbun").val(),
			}
			})
			
			.done(function(data){
			/* 通信成功時 */
			
			alert(data);
			
			})
			
			})
			})



		</script>
		
	</body>
</html>