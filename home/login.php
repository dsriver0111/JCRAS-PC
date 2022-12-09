<?php 
session_start();

/*
$_SESSION = array();
if (isset($_COOKIE["PHPSESSID"])) {
	setcookie("PHPSESSID", '', time() - 1800, '/');
}
session_destroy();
header('location:login.php');


if(isset($_SESSION['user_id'])){
	session_regenerate_id(TRUE);
	header('location:family_top.php');
	exit();
}*/

require_once('db.php');

if (count($_POST) === 0) {
$message = "";
}else {//postされて来たが、ユーザー名またはパスワードが送信されて来なかった場合
if(empty($_POST["user_id"])) {
$message = "ユーザー名を入力してください";
}else {

try {

$pdo = new PDO($dsn, $user, $password);
// プリペアドステートメントのエミュレーションを無効にする
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// 例外がスローされる設定にする
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// SQL文を作る（全レコード）


//ユーザー名が送信されて来た場合はpost送信されてきたユーザー名がデータベースにあるか検索
$sql_match = "SELECT * FROM user_account WHERE user_id = '".$_POST['user_id']."'";
$stm_match = $pdo->prepare($sql_match);
$stm_match->execute();
$result_match = $stm_match->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
echo '<span class="error">エラーがありました。</span><br>';
echo $e->getMessage();
exit();
}
		
		
		

if($result_match){

foreach($result_match as $row){
$_SESSION['user_id'] = $row['user_id'];
}

	$sql_match = "UPDATE summary SET login_count = login_count+1 WHERE user_id = :user_id";
	$stmt1 = $pdo->prepare($sql_match);
	$stmt1->bindParam(':user_id',$_SESSION['user_id'], PDO::PARAM_STR);
	$stmt1->execute();
			
header('location:login_first.php');
exit;
}
}
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

<!-- Tempus Dominus CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0/css/tempusdominus-bootstrap-4.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  
<link rel="stylesheet" href="../css/spectrum.css">
<link rel="stylesheet" href="../css/style.css?20210724aasdadad">

		<style>
			
			body{
				background:#fff; 
				text-align:right;
				}
			
			.container {
				width: 100%;
				height:98vh;
			}
			
			.box {
				background-color: #f53838;
				width: 500px;
				height: 400px;
			}
			
			.container {
				display: flex;
				align-items: center;
			}
			
			form.cmxform label.error, label.error {
				color: red;
			}
			
			@media screen and (max-width: 767px){/*スマホ時本番用480*/
				body{
					
					text-align:left;
				}
				}
			
		</style>
		
		<title>日本版がんリスク評価システム：JCRAS-PC</title>

  </head>
	<body>
	
		
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xs-12 col-lg-5 m-3" style="background:#efefef; padding:50px 30px; text-align:center; -webkit-box-shadow: 1px 1px 10px; border-radius:7px;">
					<h3>日本版がんリスク評価システム：JCRAS-PC</h3>
					<div style="font-size:12px;">- Japanese version of Cancer Risk Assessment System in Primary Care -</div>
					<BR>
					<hr>
					<div style="font-weight:bold;">JCRAS-PCは、癌の既往歴と家族歴を入力すると遺伝カウンセリングや遺伝学的検査の適応になるかどうかを瞬時に判定します</div>
					<hr>
		
					<form action="login.php" method="POST" class="mt-5">
						<div class="text-left mb-1" style="font-weight:bold;">ログインID</div>
						<input type="text" class="form-control" name="user_id" placeholder="入力してください">
						<input type="submit" class="btn btn-primary btn-block pt-2 pb-2 mt-4" name="login" id="login" value="ログインする">
						<div class="text-center mt-3" style="text-decoration:underline;"><a href="id_register.php" style="color:#ff0000;">新規登録はこちら</a></div>
					</form>
				</div>
			</div>
		</div>
		<div style="font-size:11px;">
			JCRAS-PCの開発は、日本学術振興会科学研究費助成事業若手研究「プライマリケア遺伝診療の発展：がんリスク評価システムの開発と有用性の検証」の助成を受けています
		</div>
  
  
  
  <!--jquery  と　　bootstrap4読み込み-->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<!--ここまで-->
	
	 <!-- Tempus Dominus Script -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/ja.min.js" integrity="sha512-rElveAU5iG1CzHqi7KbG1T4DQIUCqhitISZ9nqJ2Z4TP0z4Aba64xYhwcBhHQMddRq27/OKbzEFZLOJarNStLg==" crossorigin="anonymous"></script>
	<!-- Moment.js -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0/js/tempusdominus-bootstrap-4.min.js"></script>
  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
		
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/hot-sneaks/jquery-ui.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

	<script src="../js/jquery-js.js?202110001sdadafffsfsdddffgdgdddddsddd"></script>
	
		<script>
			
			
			
			/*
			var hotelValid = {
			
			//ルールの設定
			rules: {
			user_id: {
			required: true,
			}
			},
			
			//エラーメッセージの設定
			messages: {
			user_id: {
			required: '入力してください'
			}
			}
			
			};
			
			
			
			$(function(){
			//ボタンクリックで発火
			$("#login").click(function(e){
			
			e.preventDefault();
			
			$("form").validate(hotelValid);
			//失敗で戻る
			
			if (!$("form").valid()) {
			return false;
			};
			
			})
			})
			
			*/
			
		</script>
		
		
  </body>
</html>

