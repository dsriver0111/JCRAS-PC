<?php
session_start();
require_once('db.php');

if(!isset($_POST['user_id'])){
	header('location:login.php');
	}

$file_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16"><path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/><path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/></svg>';

$family_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"/></svg>';

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
	<body>
		
		<?php foreach($result as $row){ ?>
			
		<div class="container" id="abc">
			<div class="row justify-content-center">
				
					<?php if($row['kekka_text'] == ""){ ?>
						<h3 class="col-xs-12 col-lg-12 mt-5 ml-3 mr-3 text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
							<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
							<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
							</svg>
					<div class="mt-5">遺伝カウンセリングおよび遺伝学的検査の<span style="color:#ff0000;">適応基準には該当しません。</span></div>
					</h3>
					<div class="col-xs-12 col-lg-8 mt-5 ml-3 mr-3">
							この癌リスク評価ツールは、遺伝性癌において最も頻度の高い遺伝性乳癌卵巣癌症候群とリンチ症候群に焦点をあてていますので　本判定結果では遺伝カウンセリング／遺伝学的検査の適応基準を満たさないとしても、他の遺伝性癌の可能性は残ることご留意ください。<BR><BR>
							
							本判定結果にかかわらず、クライエント（または患者）ご本人が癌の遺伝的側面について心配されているようでしたら、遺伝カウンセリングは選択肢となります。以下の「情報リンク」から「最寄りの医療機関」を検索し、該当する医療機関の相談窓口に関する情報をお伝えしましょう。
						</div>
						<?php }else {?>
							
						<h3 class="col-xs-12 col-lg-12 mt-5 ml-3 mr-3 text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
							<path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
							<path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
							</svg>
							<div class="mt-5">本症例の癌の既往歴／家族歴は、<BR>遺伝カウンセリングまたは遺伝学的検査が適応となる<span style="color:#ff0000;">以下の基準項目を満たしています。</span></div>
						</h3>
						<div class="col-xs-12 col-lg-8 mt-5 ml-3 mr-3">
						<h4 class="text-left" style="font-size:17px;" id="kekka_text"><?php echo $row['kekka_text']; ?></h4><BR><BR><BR>
						</div>
						<div class="col-xs-12 col-lg-8 ml-3 mr-3">
							適切な遺伝カウンセリングの中で遺伝学的検査を受け、癌の遺伝について判明することで、以下のようなメリットとデメリットがあります。<BR><BR>
							【メリット】<BR><BR>
							(1)癌の発生予防につながる介入があり得る<BR>
							(2)具体的な癌のサーベイランス方法が推奨され得る（一般の癌検診とは異なる）<BR>
							(3)自分だけではなく家族のケアにもつながり得る<BR>
							(4)明確な癌の遺伝が無いことが分かり得る<BR><BR>
							
							【デメリット】<BR><BR>
							(1)心理的な負担<BR>
							(2)病的意義が現時点では明確に分からない遺伝子変異が検査で見つかり得る<BR><BR>
							
							クライエント（または患者）ご本人の意向に沿うのであれば、遺伝専門職への紹介を提案しましょう。以下の「情報リンク」から「最寄りの医療機関」を検索し、該当する医療機関を見つけることができます。<BR>
							クライエント（または患者）ご本人の中で、気持ちの整理ができていないようであれば、上述した医療機関の相談窓口に関する情報をお伝えしたり、次回の診察時に改めてお話したりすることを提案してみましょう。<BR>
							この癌のリスク評価ツール（JCRAS-PC）では、癌の既往歴／家族歴が適応基準を満たしたとしても、必ずしも癌の遺伝があるとはいえません。また、癌の診断年齢が「不明」の場合には過大評価を、癌種の不正確さがある場合には過少または過大評価している可能性があることにご留意ください。
						</div>
						<?php } ?>
					
					
						
					<input type="hidden" id="family_img" value="<?php echo $fileName; ?>">
					<input type="hidden" id="family_img_check" value="<?php echo $row['family_jpg_check']; ?>">
				
					
					<div class="col-xs-12 col-lg-8 mt-5">
						<div class="form-inline mb-4">
							<div class="form-check">
								<label class="font-weight-bold mr-5">■ 転帰</label>
								<select name="Introduction" class="form-control">
									<option value="k1" <?php if($row['Introduction'] == "k1"){echo "selected"; } ?>>現時点ではわからない</option>
									<option value="k2" <?php if($row['Introduction'] == "k2"){echo "selected"; } ?>>遺伝子診療部の相談窓口の情報提供</option>
									<option value="k3" <?php if($row['Introduction'] == "k3"){echo "selected"; } ?>>遺伝専門職へのメール相談</option>
									<option value="k4" <?php if($row['Introduction'] == "k4"){echo "selected"; } ?>>遺伝子診療部への紹介</option>
									<option value="k5" <?php if($row['Introduction'] == "k5"){echo "selected"; } ?>>特になし</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-xs-12 col-lg-8 mt-3">
						<div class="form-inline mb-4">
							<label class="font-weight-bold mr-5">■ 理由</label>
							<div id="cause" style="border:1px solid #efefef;width:80%;height:200px;overflow:auto;" contenteditable="true"><?php echo $row['cause']; ?></div>
						</div>
					</div>
					
					
					<div class="col-xs-12 col-lg-8 text-center sp mb-5">
						<input type="hidden" id="id" value="<?php echo $_POST['user_id']; ?>">
						<button class="btn btn-custom2 btn_b" data-toggle='modal' data-target='#mail_modal_hyouzi' id="outputBtn">メール相談</button>
						<button class="btn btn-success btn_b" id="update">保存する</button>
						</div>
					</div>
				
		</div>
				
		<?php } ?>
		
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
		
		
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
				<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
				
		<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
		
				<script>
					
					$(function(){
					
					$(document).on('click', '#history', function() {
					
					
					$.ajax({
					type: 'POST',
					url: 'history.php',
					dataType: 'html',
					data:{
					'user_id':"aaa",
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
			
			
				$(function(){
				
				$(document).on('click', '#update', function() {
				
				var element = document.getElementById("abc");  //画像化したい要素
			
				html2canvas(element).then(canvas => {
			
				$.ajax({
				type: 'POST',
				url: 'risk_updata.php',
				dataType: 'html',
				data:{
				'canvas_img':canvas.toDataURL("image/jpeg",0.75),
				'user_id':$("#id").val(),
				'Introduction':$('[name="Introduction"]').val(),
				'cause':$("#cause").html(),
				}
				
				})
				
				.done(function(data){
				alert(data);
				})
				
				})
			
				})
				
				})
				
			
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