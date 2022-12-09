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
	
	
	$sql_match = "UPDATE history SET cause = :cause, introduction = :introduction, risk_jpg = :risk_img WHERE id = :id";
	$stmt1 = $pdo->prepare($sql_match);
	$stmt1->bindParam(':cause',$_POST['cause'], PDO::PARAM_STR);
	$stmt1->bindParam(':introduction',$_POST['Introduction'], PDO::PARAM_STR);
	$stmt1->bindParam(':risk_img',$fileName, PDO::PARAM_STR);
	$stmt1->bindParam(':id',$_POST['user_id'], PDO::PARAM_INT);
	$stmt1->execute();
	
	echo "編集しました";
	
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}



?>