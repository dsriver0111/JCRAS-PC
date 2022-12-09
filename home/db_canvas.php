<?php
session_start();
require_once('db.php');

$file_rand = md5(uniqid(rand(),true));

$img = $_POST['canvas_img'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$fileName = $file_rand.'.jpg';
file_put_contents('../img/'.$fileName, $fileData);

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$time = date("Y-m-d");
	
	$sql_match = "SELECT * FROM history where family_jpg_check ='".$_POST['family_img_check']."'";
	$stm_match_a = $pdo->prepare($sql_match);
	$stm_match_a->execute();
	$result_match = $stm_match_a->fetchAll(PDO::FETCH_ASSOC);
	
	
	if(!$result_match){
	
		$sql='INSERT INTO history (user_id,family_jpg,risk_jpg,time,Introduction,cause,family_jpg_check) VALUES (:user_id,:family_jpg,:risk_jpg,:time,:Introduction,:cause,:family_jpg_check)';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':user_id',$_SESSION['user_id'], PDO::PARAM_STR);
	$stmt->bindParam(':family_jpg',$_POST['family_img'], PDO::PARAM_STR);
	$stmt->bindParam(':family_jpg_check',$_POST['family_img_check'], PDO::PARAM_STR);
	$stmt->bindParam(':risk_jpg',$fileName, PDO::PARAM_STR);
	$stmt->bindParam(':Introduction',$_POST['Introduction'], PDO::PARAM_STR);
	$stmt->bindParam(':cause',$_POST['cause'], PDO::PARAM_STR);
	$stmt->bindParam(':time',$time, PDO::PARAM_STR);
	$stmt->execute();
	
	}
		
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}



?>