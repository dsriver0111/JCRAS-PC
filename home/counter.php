<?php
session_start();
require_once('db.php');

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$time = date("Y-m-d");
	
	if($_POST['tab_number'] == "login"){
		$sql_match = "UPDATE summary SET login_count = login_count+1 WHERE user_id = :user_id";
	}else if($_POST['tab_number'] == "mail"){
		$sql_match = "UPDATE summary SET mail_count = mail_count+1 WHERE user_id = :user_id";
	}else if($_POST['tab_number'] == "tab1"){
		$sql_match = "UPDATE summary SET surveillance_count = surveillance_count+1 WHERE user_id = :user_id";
	}else if($_POST['tab_number'] == "tab2"){
		$sql_match = "UPDATE summary SET example_count = example_count+1 WHERE user_id = :user_id";
	}else if($_POST['tab_number'] == "tab3"){
		$sql_match = "UPDATE summary SET heredity_count = heredity_count+1 WHERE user_id = :user_id";
	}else if($_POST['tab_number'] == "tab4"){
		$sql_match = "UPDATE summary SET medical_count = medical_count+1 WHERE user_id = :user_id";
	}
	$stmt1 = $pdo->prepare($sql_match);
	$stmt1->bindParam(':user_id',$_SESSION['user_id'], PDO::PARAM_STR);
	$stmt1->execute();
	
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}



?>