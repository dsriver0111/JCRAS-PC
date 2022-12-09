<?php
session_start();
require_once('db.php');

if(!isset($_SESSION['user_id'])){
	header('location:login.php');
	exit();
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
		
		<title>日本版がんリスク評価システム：JCRAS-PC</title>
		
	</head>
	<body>
		
		<div class="container" id="abc">
			<div class="row justify-content-center" style="margin:100px 0 0 0;">
				
				<input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
				
				<h3 class="col-xs-12 col-lg-12 mt-5 ml-3 mr-3 text-center">選択してください</h3>
				
				<div class="col-xs-12 col-lg-8 text-center sp mt-5">
					<button class="btn btn-custom2 btn_b" onclick="location.href='family_top.php';">がんのリスク評価</button>
					<button class="btn btn-custom1 btn_b" onclick="location.href='link.php'">情報リンクへ</button>
					<button class="btn btn-custom3 btn_b" data-toggle='modal' data-target='#history_modal_hyouzi' id="history">履歴一覧</button>
					<?php if($_SESSION['user_id'] == "admin0001"){ ?>
						<button class="btn btn-custom4 btn_b" data-toggle='modal' data-target='#summary_modal_hyouzi' id="summary">サマリ表示</button>
					<?php } ?>
				</div>
				
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
			<div class="modal-dialog modal-lg">
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
			
					$.ajax({
					type: 'POST',
					url: 'history_first.php',
					dataType: 'html',
					data:{
					'user_id':$("#user_id").val(),
					}
					})
					
					.done(function(data){
					$("#kokyaku_table").html(data);
					})
			
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