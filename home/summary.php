<?php
require_once('db.php');

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	$sql_match = "SELECT * FROM summary";
	/*$sql_match = "SELECT SUM(login_count), SUM(mail_count), SUM(surveillance_count), SUM(example_count), SUM(heredity_count), SUM(medical_count) FROM summary group by user_id";*/
	$stm_match = $pdo->prepare($sql_match);
	$stm_match->execute();
	$result = $stm_match->fetchAll(PDO::FETCH_ASSOC);
	
	
} catch (Exception $e) {
	echo '<span class="error">エラーがありました。</span><br>';
	echo $e->getMessage();
	exit();
}



?>


<table class="oowaku_table2">
	<thead style="border-top:2px solid #296FBC;">
	<tr>
		<th scope="col">ユーザーID</th>
		<th scope="col" class="text-center">ログイン数</th>
		<th scope="col" class="text-center">メール相談数</th>
		<th scope="col" class="text-center">サーベイランス案クリック数</th>
		<th scope="col" class="text-center">参考例文集クリック数</th>
		<th scope="col" class="text-center">臨床遺伝学・遺伝性腫瘍クリック数</th>
		<th scope="col" class="text-center">最寄り医療機関クリック数</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($result as $row){ ?>
		<tr>
			<td ><?php echo $row['user_id']; ?></td>
			<td class="text-right"><?php echo $row['login_count']; ?></td>
			<td class="text-right"><?php echo $row['mail_count']; ?></td>
			<td class="text-right"><?php echo $row['surveillance_count']; ?></td>
			<td class="text-right"><?php echo $row['example_count']; ?></td>
			<td class="text-right"><?php echo $row['heredity_count']; ?></td>
			<td class="text-right"><?php echo $row['medical_count']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
</table>