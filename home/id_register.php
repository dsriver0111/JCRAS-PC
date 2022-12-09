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
			
			.container {
				width: 100%;
				height:100vh;
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
			
		</style>
		
		<title>日本版がんリスク評価システム：JCRAS-PC</title>

  </head>
	<body style="background:#fff;">
	
		
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xs-12 col-lg-5 m-3" style="background:#efefef; padding:50px 30px; text-align:center; -webkit-box-shadow: 1px 1px 10px; border-radius:7px; font-weight:bold;">
					<h3>ID登録</h3>
		
					<form class="mt-5">
						<div class="text-left mb-1">ログインIDを登録してください</div>
						<input type="text" class="form-control" id="user_id" name="user_id" placeholder="入力してください">
					</form>
					<button id="hozon1" class="btn btn-primary btn-block pt-2 pb-2 mt-4">登録する</button>
				</div>
			</div>
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

			
			var hotelValid = {
			
			//ルールの設定
			rules: {
				user_id: {
				required: true,
				rangelength:[4,20]
				}
			},
			
			//エラーメッセージの設定
			messages: {
				user_id: {
					required: '未入力です',
					rangelength:'4文字〜20文字以内で入力してください'
				}
			}
			
			};
	
			
			
			$(function(){
			//ボタンクリックで発火
				$("#hozon1").click(function(e){
			
				e.preventDefault();
			
				$("form").validate(hotelValid);
				//失敗で戻る
				
				if (!$("form").valid()) {
				return false;
				};
			
			
			
			$.ajax({
			type: 'POST',
			url: '../home/hozon1.php',
			dataType: 'html',
			data:{
				'user_id':$('#user_id').val(),
			}
			})
			
			.done(function(data){
			
			alert(data);
			location.href="login.php";
			
			})
			
			
			
			})
			
			})
			
			
</script>


  </body>
</html>

