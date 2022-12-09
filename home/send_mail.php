<?php
session_start();
require_once('db.php');


$body="";
$text="";

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$sql_match = "SELECT * FROM history WHERE family_jpg_check = '".$_POST['family_img_check']."'";
	$stm_match = $pdo->prepare($sql_match);
	$stm_match->execute();
	$result = $stm_match->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($result as $row){
		$family_jpg = $row['family_jpg'];
		$risk_jpg = $row['risk_jpg'];
	}
	


	
	//日本語の使用宣言
	mb_language("ja");
	mb_internal_encoding("UTF-8");
	
	//初期化
	$res = false;
	
	// 送信元の設定
	$sender_email = $_POST['sashidashi'];
	$org = 'JCRAS-PC';
	$from = mb_encode_mimeheader("JCRAS-PC","iso-2022-jp")."<".$_POST['sashidashi'].">";
	
	
	$text.=$_POST['honbun']."\n\n";
	$text.="【家系図】\n";
	$text.="https://kutikomi100.com/JCRAS-PC/img/".$family_jpg."\n\n";
	$text.="【リスク評価判定結果】\n";
	$text.="https://kutikomi100.com/JCRAS-PC/img/".$risk_jpg."\n";
	
	// ヘッダー設定
	$header = '';
	$header .= "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
	$header .= "Return-Path: " . $sender_email . " \n";
	$header .= "From: " . $from ." \n";
	$header .= "Sender: " . $from ." \n";
	$header .= "Reply-To: " . $sender_email . " \n";
	$header .= "Organization: " . $org . " \n";
	$header .= "X-Sender: " . $org . " \n";
	$header .= "X-Priority: 3 \n";
	
	// テキストメッセージを記述
	$body = "--__BOUNDARY__\n";
	$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
	$body .= $text . "\n";
	$body .= "--__BOUNDARY__\n";
	
	/*
	// ファイル1を添付
	$body .= "Content-Type: application/octet-stream; name=\"{$family_jpg}\"\n";
	$body .= "Content-Disposition: attachment; filename=\"{$family_jpg}\"\n";
	$body .= "Content-Transfer-Encoding: base64\n";
	$body .= "\n";
	$body .= chunk_split(base64_encode(file_get_contents("../img/".$family_jpg)));
	$body .= "--__BOUNDARY__--";
	
	// ファイル2を添付
	$body .= "Content-Type: application/octet-stream; name=\"{$risk_jpg}\"\n";
	$body .= "Content-Disposition: attachment; filename=\"{$risk_jpg}\"\n";
	$body .= "Content-Transfer-Encoding: base64\n";
	$body .= "\n";
	$body .= chunk_split(base64_encode(file_get_contents("../img/".$risk_jpg)));
	$body .= "--__BOUNDARY__--";
	*/
	
	//メール送信
	$addParam = '-f' . $_POST['sashidashi'];//gmail宛に届くようにおまじない
	mb_send_mail( $_POST['atesaki'], $_POST['kenmei'], $body, $header,$addParam);
	
	
	
	echo "送信しました！";
	
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}


?>