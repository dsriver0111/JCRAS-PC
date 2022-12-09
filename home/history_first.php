<?php
session_start();
require_once('db.php');
$total_count=0;

$file_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16"><path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/><path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/></svg>';

$family_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"/></svg>';

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$sql_match = "SELECT * FROM history where user_id ='".$_POST['user_id']."' order by id DESC";
	$stm_match_a = $pdo->prepare($sql_match);
	$stm_match_a->execute();
	$result = $stm_match_a->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($result as $row){
		$total_count++;
	}
	
	
	
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}



?>

<?php if($result){ ?>
	
<table class="oowaku_table2">
	<thead style="border-top:2px solid #296FBC;">
	<tr class="f_size12">
		<th scope="col" style="width:50px">ID</th>
		<th scope="col">リスク評価判定診断日</th>
		<th scope="col" style="width:50px">家系図</th>
		<th scope="col">リスク評価判定結果</th>
	</tr>
	</thead>
	<tbody>
	<?php 
		foreach($result as $key => $row){ ?>
		<tr>
			<td class="text-center"><?php echo $total_count-$key; ?></td>
			<td class="text-center"><?php echo $row['time']; ?></td>
			<td class="text-center"><form method="POST" name="img_form<?php echo $key; ?>" action="history_img.php"><input type="hidden" name="user_id" value="<?php echo $row['id']; ?>"><a href="#" onclick="document.img_form<?php echo $key; ?>.submit();"><?php echo $family_icon; ?></a></form></td>
			<td class="text-center"><form method="POST" name="a_form<?php echo $key; ?>" action="history_risk.php"><input type="hidden" name="user_id" value="<?php echo $row['id']; ?>"><a href="#" onclick="document.a_form<?php echo $key; ?>.submit();"><?php echo $file_icon; ?></a></form></td>
		</tr>
	<?php 
		}
	?>
	</tbody>
</table>

<?php

}else {
	echo '<div class="search_no text-center mt-5 mb-5 font-weight-bold">まだデータがありません</div>';
}

?>
