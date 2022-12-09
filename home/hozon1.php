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
		
		$sql_match = "SELECT * FROM user_account where user_id = '".$_POST['user_id']."'";
		$stm_match = $pdo->prepare($sql_match);
		$stm_match->execute();
		$result_match = $stm_match->fetchAll(PDO::FETCH_ASSOC);
		
		if($result_match){
			
			echo "このIDは既に登録済みです";
			return false;
		}
		
		$time = date("Y-m-d");
	
		//ユーザー設定初期化登録
		$sql='INSERT INTO user_account (user_id,time) VALUES (:user_id,:time)';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':user_id',$_POST['user_id'], PDO::PARAM_STR);
		$stmt->bindParam(':time',$time, PDO::PARAM_STR);
		$stmt->execute();
		
		$sql1='INSERT INTO summary (user_id,time,login_count,mail_count,surveillance_count,example_count,heredity_count,medical_count) VALUES (:user_id,:time,0,0,0,0,0,0)';
			$stmt1 = $pdo->prepare($sql1);
			$stmt1->bindParam(':user_id',$_POST['user_id'], PDO::PARAM_STR);
			$stmt1->bindParam(':time',$time, PDO::PARAM_STR);
			$stmt1->execute();
	
		echo "登録しました!";
	

}	catch (Exception $e) {
		echo '<span class="error">エラーがありました。</span><br>';
		echo $e->getMessage();
		exit();
	}
	
	
	
	?>
	