<?php
require_once('db.php');

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）
	
	$sql_match = "SELECT * FROM history WHERE id ='".$_POST['user_id']."'";
	$stm_match = $pdo->prepare($sql_match);
	$stm_match->execute();
	$result = $stm_match->fetchAll(PDO::FETCH_ASSOC);
	
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
			
			.btn_b{
				display:inline;
				width:24%;
				height:50px;
			}
			
			.max-img{
				width:auto;
				height:auto;
				max-width:100%;
				max-height:100%;
			}

			
			@media screen and (max-width: 767px){/*スマホ時本番用480*/
				
				.btn_b{
					display:block;
					width:95%;
					margin:0 auto 10px auto;
				}
			}
			
			
		</style>
		
		<title>日本版がんリスク評価システム：JCRAS-PC</title>
		
	</head>
	<body style="text-align:center;">
		
		<?php foreach($result as $row){ ?>
			
			<img src="../img/<?php echo $row['family_jpg']; ?>" class='max-img'>
			
		<?php } ?>
		
			
				
			
			</body>
		</html>