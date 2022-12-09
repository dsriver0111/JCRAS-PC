<?php 
session_start();
require_once('db.php');

if(!isset($_SESSION['user_id'])){
	header('location:login.php');
	exit();
	}

try {
	
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// SQL文を作る（全レコード）


	/*$sql_match = "UPDATE summary SET login_count = login_count+1";
	$stmt1 = $pdo->prepare($sql_match);
	$stmt1->execute();*/

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
		
		
		<!--<link rel="stylesheet" href="../css/style.css?2022axvsdafxddrwsvxer">
		<!--<link rel="stylesheet" href="../css/jquery.datetimepicker.css">-->
		
		<style>
			
			table{
				border-collapse: separate;
				border-spacing:0;
				}
			
			/*table{
				border-collapse: collapse;
				border-spacing:0;
				vertical-align:middle;
				}*/
			
		.kekka_table{
			margin:100px auto;
				table-layout:fixed;
				width:560px;
				vertical-align:middle;
		}
		
		.kekka_table2{
				table-layout:fixed;
				width:320px;
			}
			
		.kekka_table th,
		.kekka_table td,
		#table2 th,
		#table2 td{
		width:80px;
		height:80px;
		text-align:center;
		/*border: 1px #264d99 solid;*/
		/*border-collapse: collapse;
				vertical-align:middle;*/
		padding:0;
		}
			
			.max-img{
				width:auto;
				height:auto;
				max-width:100%;
				max-height:100%;
			}
			
		.p_fixed{
			padding:10px 20px;
			border-bottom:2px solid #e1e1e1;
			position:fixed;
			top:0;
			z-index:1000;
			width:100%;
			height:70px;
			background:#efefef;
			text-align:center;
		}
		
			.p_fixed_family{
				padding:25px 0 25px 30px;
				border-top:2px solid #e1e1e1;
				position:fixed;
				top:0;
				left:0;
				width:200px;
				height:auto;
				}
			
			.btn{
			display:table-cell;
				}
			
			.child_menu{
				width:30px; 
				height:30px; 
				position:absolute;
				top:-30px;
				right:0; 
				background:#efefef;
				color:#888;
				border:1px solid #000;
				}
			
			.modal_hyouzi_hover{
				display:block;
			}
			
			.modal_hyouzi_hover2{
				display:none;
				}
				
			.text_tuika{
				display:none;
				width:100px;
				position:absolute;
				top:50px;
				left:50px; 
				color:#000;
				font-size:13px;
				text-align:left;
				z-index:999;
				background:#fff;
				border:1px solid #000;
				box-shadow: 3px 3px 3px;
				padding:4px;
				}
			
			.sex_men{
				border-radius:0.25m;
				}
			
			.sex_woman{
				border-radius:100px;
			}
			
			/*.mother_kyoutu{
				background:#e1e1e1;
					}*/
					
		</style>
		
		<title>家系図診断</title>
		
		<a href="#" data-toggle='modal' data-target='#modal_hyouzi_sex' style="display:none;" id="login_sex">ここ</a>
		
	</head>
	<body id="canvas_table">
		<table class="kekka_table">
			<?php 
			for($i=0;$i<10;$i++){
			echo "<tr id='gyou".$i."' data-this_tr='".$i."'>";
				for($z=0;$z<17;$z++){
					if($i==8&&$z==7){
						echo "<td class='retu".$z." btn btn-success btn_search' style='background-color:#baeef7; color:#000; position:relative; border:1px solid #000' data-toggle='modal' data-target='#modal_hyouzi' id='me' data-this_td='".$z."' data-sintou='0' data-sex='men'><span>あなた</span><div class='fff text_tuika'></div></td>";
					}else {
						echo "<td class='retu".$z."'></td>";
						}
					}
			echo "</tr>";
				}
			?>
		</table>
		
		
		
		<!--ここから最初の性別選択モーダル表示-->
		<div class="modal fade" id="modal_hyouzi_sex" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-body text-center mt-4">
						あなたの性別を選択してください
						
					<select class="form-control mt-5 mb-3" id="sex">
							<option value="men" selected>男性</option>
							<option value="woman">女性</option>
					</select>
								
					</div>
						
					<div class="modal-footer">
						<a class="btn btn-success" id="sex_kettei" data-dismiss="modal">決定</a>
					</div>
				</div>
			</div>
		</div>
		
		
		
		
		
		<!--ここから家系図用モーダル表示-->
		<div class="modal fade" id="modal_hyouzi" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-body text-center mt-4">
						どちらを追加しますか？
						<ul class="nav nav-tabs mt-4 mb-4">
							<li class="nav-item"><a href="#contents1" data-toggle="tab" class="nav-link pt-3 pl-4 pb-3 pr-4 active">癌種・発症年齢</a></li>
							<li class="nav-item"><a href="#contents3" data-toggle="tab" class="nav-link pt-3 pl-4 pb-3 pr-4">親族</a></li>
					</ul>
						
						<div class="tab-content">
							<!-----------ここから利用状況サマリタブ------------->
							<div id="contents1" class="tab-pane active">
								<div class="text-left mb-1">癌の種類</div>
								<select class="form-control mb-3" id="cancer">
									<option value="cancer1" selected>乳癌</option>
									<option value="cancer2">卵巣癌/腹膜癌/卵管癌</option>
									<option value="cancer3">子宮体癌</option>
									<option value="cancer4">前立腺癌</option>
									<option value="cancer5">膵癌</option>
									<option value="cancer6">大腸癌</option>
									<option value="cancer7">胃癌</option>
									<option value="cancer8">小腸癌</option>
									<option value="cancer9">胆道癌</option>
									<option value="cancer10">泌尿器癌（前立腺癌を含めない）</option>
									<option value="cancer11">脳腫瘍</option>
									<option value="cancer12">その他の癌</option>
								</select>
								<div class="text-left mb-1">診断年齢</div>
								<select class="form-control" id="cancer_old">
									<option value="old1" selected>４５歳以下</option>
									<option value="old2">４５歳〜５０歳</option>
									<option value="old3">５１歳以上</option>
									<option value="old4">不明</option>
								</select>
							</div>
							<div id="contents3" class="tab-pane">
								<div class="text-left mb-1">親族</div>
								<select class="form-control" id="family_tuika">
									<option value="oya" selected>父母</option>
									<option value="brother_men">兄弟姉妹（男性）</option>
									<option value="brother_woman">兄弟姉妹（女性）</option>
									<option value="haigusya">配偶者</option>
									<option value="children_men">子供（男性）</option>
									<option value="children_woman">子供（女性）</option>
									<option value="" disabled></option>
									<option value="delete">削除</option>
									<option value="death">死亡</option>
								</select>
								
							</div>
						</div>
						
						
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary text-white" id="clear" data-dismiss="modal">クリア</a>
						<a href="#" class="btn btn-secondary" data-dismiss="modal">キャンセル</a>
						<a class="btn btn-success" id="kettei" data-dismiss="modal">決定</a>
					</div>
				</div>
			</div>
		</div>
		
	<div class="p_fixed clearfix">
			<span class="float-left">JCRAS-PC</span>
			<a id="getImage" href="" style="display: none"></a>
			<form method="POST" name="send_form" action="judgment_result.php">
			<input type="hidden" id="cancer_init" name="cancer_init" value="">
			<input type="hidden" id="cancer_old_init" name="cancer_old_init" value="">
			<input type="hidden" id="cancer_sintou" name="cancer_sintou" value="">
			<input type="hidden" id="cancer_sex" name="cancer_sex" value="">
			<input type="hidden" id="cancer_id" name="cancer_id" value="">
			<input type="hidden" id="canvas_img" name="canvas_img" value="">
			<input type="hidden" id="now_time" name="now_time" value="">
			<button type="button" class="btn btn-primary float-right" style="padding:10px 50px;" id="diagnose">リスク評価判定</button></form>
	</div>
		
		
		
		<!--jquery  と　　bootstrap4読み込み-->
	<!--元<script src="https://code.jquery.com/jquery-3.6.0.min.js"integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<!--ここまで-->
	
	<!-- Tempus Dominus Script -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/ja.min.js" integrity="sha512-rElveAU5iG1CzHqi7KbG1T4DQIUCqhitISZ9nqJ2Z4TP0z4Aba64xYhwcBhHQMddRq27/OKbzEFZLOJarNStLg==" crossorigin="anonymous"></script>
	<!-- Moment.js -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0/js/tempusdominus-bootstrap-4.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	
		<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
	
	<!-- jQuery UI -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<!-- Datepicker日本語化 -->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
	<!-- jQuery UI のCSS -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	
	
	<script src="../js/jquery-js.js?2022ffgsffsfsgdgsfdfffdsffsffffsssczfdfsfsdffssfsfsfsads"></script>
	<!-- ここからQRコード自動作成-->	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script> 
	
	<!-- ここまでQRコード自動作成-->	
	<!--<input type="button" value="お客様ご自身で変更してください" onclick="window.open('https://yahoo.co.jp', 'null', 'top=100,left=200,height=597,width=400'); return false;">-->
	
	<!--ここからツールチップ表示-->
	<script src="https://unpkg.com/@popperjs/core@2"></script>
	<script src="https://unpkg.com/tippy.js@6"></script>
	<!--ここまでツールチップ表示-->

		<script>
			
		$("html,body").animate({scrollTop:$('#me').offset().top});
			$("html,body").animate({scrollLeft:(($('#me').offset().left)-($(window).width()/2))+40});
		
			//メニューアイコンSVG
		var menu = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/></svg>';
			
			var btn_dore="";
			var this_retu=0;
			var this_gyou=0;
			var me_brother_count=0;
			var me_father_brother_count=0;
			var me_mother_brother_count=0;
			var me_children_count=0;
			var me_children_mago_count=0;
			var me_oimei_count=0;
			var me_oimei2_count=0;
			var line_zougen=0;
			var me_brother_td_susumu=0;
			var all_saisyo_count=0;
			var all_count=0;
			var all_mago_count=0;
			var all_counter = {};
			var all_counter_brother = {};
			var search_counter="";
			var child_count_array=[];
			var child_count_array_brother=[];
			//ここから淡め
			var backcolor0 ="#baeef7";//０親等背景色
			var backcolor1 ="#ddff92";//１親等背景色
			var backcolor2 ="#ffaf78";//２親等背景色
			var backcolor3 ="#ff97e7";//３親等背景色
			//ここから濃いめ
			/*var backcolor0 ="#00CFFF";//０親等背景色
			var backcolor1 ="#DAFF00";//１親等背景色
			var backcolor2 ="#ff6700";//２親等背景色
			var backcolor3 ="#882255";//３親等背景色*/
			///ここまで
			var all_bordercolor = "1px solid #000";//ボーダー色
			var oya_oya="";
			var oya=0;
			
			
			
			
			var father_ozioba_count=0;
			var father_children_count=0;
			var father_sousohubo_count=0;
			var father_sousohubo_count2=0;
			var mother_sousohubo_count=0;
			var mother_sousohubo_count2=0;
			var mother_ozioba_count=0;
			var oimei_count=[];
			var itoko_count=[];
			var oimei_count2=[];
			var itoko_count2=[];
			var oimei_count3=[];
			var itoko_count3=[];
			
			$(function () {
			// 親メニュー処理
			$(document).on('click', '.btn_search', function() {
			
			var select_html="";
		
			this_all_class = $(this).attr('class');
			btn_dore = $(this).attr('id');
			
			this_id = $(this).parent().attr("id");
			this_gyou = Number(this_id.replace("gyou",""));
			this_gyou_brother = Number(this_id.replace("gyou_men_brother",""));
			this_retu = $(this).data("this_td");
			this_sex = $(this).data("sex");//menかwoman
			this_text = $(this).text();
			this_child_number = $(this).data("child");
			this_sintou = $(this).data("sintou");
			
			var F1="disabled";
			var F2="disabled";
			var F3="disabled";
			var F4="disabled";
			var F5="disabled";
			var F6="disabled";
			var F7="disabled";
			if($("#"+btn_dore).children("img").length == 0){//死亡の斜め画像がまだ無かったら
			var F8="";
			}else {
			var F8="disabled";
			}
			
			if(btn_dore == "me"){//あなた
					F8="disabled";
				if(!($("#me_haigusya").length)){//配偶者ボタンがなければ
					F4="";
				}else {//配偶者ボタンがあれば
					F5="";
					F6="";
				}
				if(!$("#me_father").length&&!$("#me_mother").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_father"){//あなた＞父
					F5="";
					F6="";
				if(!$("#me_father_sohu").length&&!$("#me_father_sobo").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_mother"){//あなた＞母
					F5="";
					F6="";
				if(!$("#me_mother_sohu").length&&!$("#me_mother_sobo").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_father_sohu"){//あなた＞父＞祖父
					F5="";
					F6="";
				if(!$("#me_father_sousohu").length&&!$("#me_father_sousobo").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_father_sobo"){//あなた＞父＞祖母
					F5="";
					F6="";
				if(!$("#me_father_sousohu2").length&&!$("#me_father_sousobo2").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_mother_sohu"){//あなた＞母＞祖父
					F5="";
					F6="";
				if(!$("#me_mother_sousohu").length&&!$("#me_mother_sousobo").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_mother_sobo"){//あなた＞母＞祖母
					F5="";
					F6="";
				if(!$("#me_mother_sousohu2").length&&!$("#me_mother_sousobo2").length){//両親ボタンがなければ
					F1="";
				}else {//両親ボタンがあれば
					F2="";
					F3="";
				}
			}else if(btn_dore == "me_father_sousohu"||btn_dore == "me_father_sousobo"||btn_dore == "me_father_sousohu2"||btn_dore == "me_father_sousobo2"||btn_dore == "me_mother_sousohu"||btn_dore == "me_mother_sousobo"||btn_dore == "me_mother_sousohu2"||btn_dore == "me_mother_sousobo2"){//曾祖父母共通
					F5="";
					F6="";
			}else if(btn_dore.indexOf("_haigusya") > -1){//押したボタンid名に_haigusyaが含まれる「配偶者共通」
				F5="";
				F6="";
				F7="";
			if(btn_dore == "me_haigusya"){//押したボタンid名が「me_haigusya」だったら
				F7="disabled";
			}
			}else if(btn_dore.indexOf("mother_ozioba") > -1||btn_dore.indexOf("father_ozioba") > -1){//押したボタンid名に左記が含まれてて
			F7="";
			if(this_sintou != "3"){//第３近親者じゃなく
			if(!($("#"+btn_dore+"_haigusya").length)){//配偶者ボタンがなければ
					F4="";
			}else {//配偶者ボタンがあれば子供を選択できるようにする
					F5="";
					F6="";
			}
			}
			
			}else if(btn_dore.indexOf("father_children") > -1||btn_dore.indexOf("me_children") > -1){//押したボタンid名に左記が含まれてて
			F7="";
			if(this_sintou == "1"){//第1近親者で
			if(!($("#"+btn_dore+"_haigusya").length)){//配偶者ボタンがなければ
			F4="";
			}else {//配偶者ボタンがあれば子供を選択できるようにする
			F5="";
			F6="";
			}
			
			}
			}else {
			F7="";
			}
	
			select_html+='<option value="oya" '+F1+'>父母</option>';
			select_html+='<option value="brother_men" '+F2+'>兄弟姉妹（男性）</option>';
			select_html+='<option value="brother_woman" '+F3+'>兄弟姉妹（女性）</option>';
			select_html+='<option value="haigusya" '+F4+'>配偶者</option>';
			select_html+='<option value="children_men" '+F5+'>子供（男性）</option>';
			select_html+='<option value="children_woman" '+F6+'>子供（女性）</option>';
			select_html+='<option value="" disabled></option>';
			select_html+='<option value="delete" '+F7+'>削除</option>';
			select_html+='<option value="death" '+F8+'>死亡</option>';
			
			$('#family_tuika').html(select_html);
			
			
			})
			
			})
			
			
			function on_kettei(syurui,who){
			
			//syuruiは親族追加のセレクトボックスの選択肢したvalue値
			//whoは各親族ボタンをクリックした際のどのid名かを取得
			
			///////////////////////////////////////////////
			///////////////////////////////////////////////
			////////////ここから死亡を選択時//////////////////
			///////////////////////////////////////////////
			///////////////////////////////////////////////
			
			if(syurui == "death"){
			
			$("#"+btn_dore).append("<img src='../img/death.png' style='position:absolute; top:-10px; left:-10px;'>");
			}
			
			///////////////////////////////////////////////
			///////////////////////////////////////////////
			////////////ここから削除を選択時//////////////////
			///////////////////////////////////////////////
			///////////////////////////////////////////////
		
			if(syurui == "delete"){
			
			$("."+btn_dore).remove();
			
			
			
			for(var i=0;i<10;i++){
			$('#gyou'+i).children(".mother_kyoutu").remove();
			}
			
			if($("#table4_tr1 td").length > 0){
			if($("#table4_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			var td_all="";
			var td_tuika = Math.trunc(-($("#table4_tr1 td").eq(0).offset().left)/80)+2;//tdを追加する数
			for(var z=0;z<td_tuika;z++){
			td_all+='<td class="mother_kyoutu"></td>';
			}
			for(var i=0;i<10;i++){
					$('#gyou'+i).children('.retu0').before(td_all);
				}
			}
			}
			
			if($("#table5_tr1 td").length > 0){
			if($("#table5_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			var td_all="";
			var td_tuika = Math.trunc(-($("#table5_tr1 td").eq(0).offset().left)/80)+2;//tdを追加する数
			for(var z=0;z<td_tuika;z++){
			td_all+='<td class="mother_kyoutu"></td>';
			}
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before(td_all);
			}
			}
			}
			
			if($("#table6_tr1 td").length > 0){
			if($("#table6_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			var td_all="";
			var td_tuika = Math.trunc(-($("#table6_tr1 td").eq(0).offset().left)/80)+2;//tdを追加する数
			for(var z=0;z<td_tuika;z++){
			td_all+='<td class="mother_kyoutu"></td>';
			}
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before(td_all);
			}
			}
			}
			
			/*
			if($(".mother_ozioba_woman").length > 0){
			console.log($(".mother_ozioba_woman").eq(0).offset().left);
			
			if($(".mother_ozioba_woman").eq(0).offset().left == -47.5||$(".mother_ozioba_woman").eq(0).offset().left == -80){
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
			}
			*/
			
		
			
			
			//////////////////////////////////////////////////////
			///////////ここから父方の叔父叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("father_ozioba") > -1){//押した削除ボタンのid名にfather_childrenが含まれていたら
			$("#table2_tr1 td").last().html('<img src="../img/line_left_bottom.png">');
			if($(".father_ozioba").length == 0){
			father_ozioba_count=0;
			itoko_count = [];
			$('#gyou3').children('.retu11').html("<img src='../img/line_left_top.png' class='max-img'>");
			}
			if(btn_dore.indexOf("_haigusya") > -1){//押した削除ボタンのid名に_haigusyaが含まれていたら
			btn_dore = btn_dore.replace('_haigusya','');
			$("#"+btn_dore).before('<td class="'+btn_dore+'"></td>');
			$("."+btn_dore).eq(4).before('<td class="'+btn_dore+'"></td>');
			var number = Number(btn_dore.replace(/[^0-9]/g,''));
			itoko_count[number-1] = 0;
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから母方の叔父叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("mother_ozioba") > -1){//押した削除ボタンのid名にmother_oziobaが含まれていたら
			$("#table5_tr1 td").eq(0).html('<img src="../img/line_right_bottom.png">');
			if($(".mother_ozioba").length == 0){
			mother_ozioba_count=0;
			itoko_count2 = [];
			$('#gyou3').children('.retu5').html("<img src='../img/line_right_top.png' class='max-img'>");
			}
			if(btn_dore.indexOf("_haigusya") > -1){//押した削除ボタンのid名に_haigusyaが含まれていたら
			btn_dore = btn_dore.replace('_haigusya','');
			$("#"+btn_dore).after('<td class="'+btn_dore+'"></td>');
			$("."+btn_dore).eq(4).after('<td class="'+btn_dore+'"></td>');
			var number = Number(btn_dore.replace(/[^0-9]/g,''));
			itoko_count2[number-1] = 0;
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私の兄弟を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("father_children") > -1){//押した削除ボタンのid名にfather_childrenが含まれていたら
			$("#table3_tr1 td").last().html('<img src="../img/line_left_bottom.png">');
			if($(".father_children").length == 0){
			father_children_count=0;
			oimei_count = [];
			$('#gyou7').children('.retu8').html("<img src='../img/line_left_top.png' class='max-img'>");
			}
			if(btn_dore.indexOf("_haigusya") > -1){//押した削除ボタンのid名に_haigusyaが含まれていたら
			btn_dore = btn_dore.replace('_haigusya','');
			$("#"+btn_dore).before('<td class="'+btn_dore+'"></td>');
			$("."+btn_dore).eq(4).before('<td class="'+btn_dore+'"></td>');
			var number = Number(btn_dore.replace(/[^0-9]/g,''));
			oimei_count[number-1] = 0;
			
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞父＞祖父＞大叔父大叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("father_sousohubo") > -1){//押した削除ボタンのid名にfather_childrenが含まれていたら
			$("#gyou1").children(".father_sousohubo").eq(0).html('<img src="../img/line_right_bottom.png">');
			if($(".father_sousohubo").length == 0){
			father_sousohubo_count=0;
			$('#gyou1').children('.retu10').html("<img src='../img/line_right_bottom.png' class='max-img'>");
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞母＞祖父＞大叔父大叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("mother_sousohubo") > -1){//押した削除ボタンのid名にmother_childrenが含まれていたら
			$("#gyou1").children(".mother_sousohubo").last().html('<img src="../img/line_left_bottom.png">');
			if($(".mother_sousohubo").length == 0){
			mother_sousohubo_count=0;
			$('#gyou1').children('.retu6').html("<img src='../img/line_left_bottom.png' class='max-img'>");
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞父＞祖母＞大叔父大叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("father_ozioba_woman") > -1){//押した削除ボタンのid名にfather_childrenが含まれていたら
			$("#table1_tr1 td").last().html('<img src="../img/line_left_bottom.png">');
			if($(".father_ozioba_woman").length == 0){
			father_sousohubo_count2=0;
			$('#gyou1').children('.retu15').html("<img src='../img/line_left_top.png' class='max-img'>");
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞母＞祖母＞大叔父大叔母を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("mother_ozioba_woman") > -1){//押した削除ボタンのid名にmother_ozioba_womanが含まれていたら
			$("#table4_tr1 td").eq(0).html('<img src="../img/line_right_bottom.png">');
			if($(".mother_ozioba_woman").length == 0){
			mother_sousohubo_count2=0;
			$('#gyou1').children('.retu1').html("<img src='../img/line_right_top.png' class='max-img'>");
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞兄弟＞甥姪を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("_oimei") > -1){//押した削除ボタンのid名にfather_oimeiが含まれていたら
			var cut1 = btn_dore.substr(0, btn_dore.indexOf('_oimei'));//「cut1」にはfather_children_1やfather_children_2が入っている
			var number = Number(cut1.replace(/[^0-9]/g,''));
			
			$("#table3_tr3").children("."+cut1).eq(0).html("");
			$("#table3_tr3").children("."+cut1).eq(1).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			
			if($("#table3_tr3").children("."+cut1).length == 4){
			oimei_count[number-1] = 0;
			$("#table3_tr2").children("."+cut1).eq(2).html('<img src="../img/line_yoko.png">');
			$("#table3_tr3").children("."+cut1).eq(2).html('');
			$("#table3_tr3").children("."+cut1).eq(1).html('');
			$("#table3_tr3").children("."+cut1).eq(0).html('');
			}
			}
			
			//////////////////////////////////////////////////////
			///////////ここから私＞父＞祖父母＞いとこを削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("_itoko") > -1){//押した削除ボタンのid名にfather_itokoが含まれていたら
			var cut1 = btn_dore.substr(0, btn_dore.indexOf('_itoko'));//「cut1」にはfather_ozioba_1やfather_ozioba_2が入っている
			var number = Number(cut1.replace(/[^0-9]/g,''));
			
			$("#table2_tr3").children("."+cut1).eq(0).html("");
			$("#table2_tr3").children("."+cut1).eq(1).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			
			if($("#table2_tr3").children("."+cut1).length == 4){
			itoko_count[number-1] = 0;
			$("#table2_tr2").children("."+cut1).eq(2).html('<img src="../img/line_yoko.png">');
			$("#table2_tr3").children("."+cut1).eq(2).html('');
			$("#table2_tr3").children("."+cut1).eq(1).html('');
			$("#table2_tr3").children("."+cut1).eq(0).html('');
			}
			}
			
			//////////////////////////////////////////////////////
			///////////ここから私＞母＞祖父母＞いとこを削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("_itoko") > -1){//押した削除ボタンのid名にfather_itokoが含まれていたら
			var cut1 = btn_dore.substr(0, btn_dore.indexOf('_itoko'));//「cut1」にはfather_ozioba_1やfather_ozioba_2が入っている
			var number = Number(cut1.replace(/[^0-9]/g,''));
			
			
			$("#table5_tr3").children("."+cut1).last().prev().html("<img src='../img/line_left_bottom.png' class='max-img'>");
			
			
			if($("#table5_tr3").children("."+cut1).length == 4){//4つだとブロックごとにいとこを全部削除したと判断できる
			itoko_count2[number-1] = 0;
			$("#table5_tr2").children("."+cut1).eq(1).html('<img src="../img/line_yoko.png">');
			$("#table5_tr3").children("."+cut1).eq(1).html('');
			$("#table5_tr3").children("."+cut1).eq(2).html('');
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞子供を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("me_children") > -1){//押した削除ボタンのid名にme_childrenが含まれていたら
			$("#table6_tr1 td").eq(0).html('<img src="../img/line_right_bottom.png">');
			if($(".me_children").length == 0){
			me_children_count=0;
			oimei_count3 = [];
			$('#gyou8').children('.retu6').html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou9').children('.retu6').html("");
			}
			if(btn_dore.indexOf("_haigusya") > -1){//押した削除ボタンのid名に_haigusyaが含まれていたら
			btn_dore = btn_dore.replace('_haigusya','');
			$("#"+btn_dore).after('<td class="'+btn_dore+'"></td>');
			$("."+btn_dore).eq(4).after('<td class="'+btn_dore+'"></td>');
			var number = Number(btn_dore.replace(/[^0-9]/g,''));
			oimei_count3[number-1] = 0;
			}
			}
			//////////////////////////////////////////////////////
			///////////ここから私＞子供＞孫を削除した際のアクション/////
			//////////////////////////////////////////////////////
			if(btn_dore.indexOf("_itoko") > -1){//押した削除ボタンのid名にfather_itokoが含まれていたら
			var cut1 = btn_dore.substr(0, btn_dore.indexOf('_itoko'));//「cut1」にはme_children_1やme_children_2が入っている
			var number = Number(cut1.replace(/[^0-9]/g,''));
			
			/*alert(cut1);
			alert(number);*/
			
			$("#table6_tr3").children("."+cut1).last().prev().html("<img src='../img/line_left_bottom.png' class='max-img'>");
			
			
			if($("#table6_tr3").children("."+cut1).length == 4){//4つだとブロックごとに孫を全部削除したと判断できる
			oimei_count3[number-1] = 0;
			$("#table6_tr2").children("."+cut1).eq(1).html('<img src="../img/line_yoko.png">');
			$("#table6_tr3").children("."+cut1).eq(1).html('');
			$("#table6_tr3").children("."+cut1).eq(2).html('');
			}
			}
			
			}
		
		
		
		

			
			///////////////////////////////////////////////
			////////////ここから両親を選択時/////////////////
			///////////////////////////////////////////////
			
			if(syurui == "oya"){
			
			var woman=0;
			var men=0;
			var line_up=2;
			
			if(who == "me"){
			var woman_id_settei = "me_mother";
			var woman_name = "母";
			var men_id_settei = "me_father";
			var men_name = "父";
			
			}else if(who == "me_mother"){///ここから母方の血族一式
			var woman_id_settei = "me_mother_sobo";
			var woman_name = "祖母";
			var men_id_settei = "me_mother_sohu";
			var men_name = "祖父";
			
			}else if(who == "me_mother_sobo"){
			var woman_id_settei = "me_mother_sousobo2";
			var woman_name = "曽祖母";
			var men_id_settei = "me_mother_sousohu2";
			var men_name = "曽祖父";
			
			}else if(who == "me_mother_sohu"){
			var woman_id_settei = "me_mother_sousobo";
			var woman_name = "曽祖母";
			var men_id_settei = "me_mother_sousohu";
			var men_name = "曽祖父";
			
			}else if(who == "me_father"){///ここから父方の血族一式
			var woman_id_settei = "me_father_sobo";
			var woman_name = "祖母";
			var men_id_settei = "me_father_sohu";
			var men_name = "祖父";
			
			}else if(who == "me_father_sobo"){
			var woman_id_settei = "me_father_sousobo2";
			var woman_name = "曽祖母";
			var men_id_settei = "me_father_sousohu2";
			var men_name = "曽祖父";
			
			}else if(who == "me_father_sohu"){
			var woman_id_settei = "me_father_sousobo";
			var woman_name = "曽祖母";
			var men_id_settei = "me_father_sousohu";
			var men_name = "曽祖父";
			
			}
			
			
			
			if(who == "me"){
			
			woman = this_retu;
			men = this_retu+2;
			var table3="";
			line_up = 4;
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_left_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_tate.png' class='max-img'>");
			$('#gyou'+(this_gyou-3)).children('.retu'+(this_retu+1)).html("<img src='../img/line_tate.png' class='max-img'>");
			$('#gyou'+(this_gyou-4)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			if(who == "me_mother"){///ここから母方の家系図表示一式
			woman = this_retu-3;
			men = this_retu-1;
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_right_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-2)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			
			if(who == "me_mother_sohu"){///ここから母方の家系図表示一式
			
			woman = this_retu-2;
			men = this_retu;
			var table1 = "";
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			
			if(who == "me_mother_sobo"){
			
			woman = this_retu-4;
			men = this_retu-2;
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-3)).html("<img src='../img/line_right_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-3)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			
			if(who == "me_father"){///ここから父方の家系図表示一式
			woman = this_retu+3;
			men = this_retu+1;
			var table2 = "";
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+2)).html("<img src='../img/line_left_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu+2)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			
			if(who == "me_father_sohu"){
			woman = this_retu+2;
			men = this_retu;
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_left_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			
			}
			
			
			if(who == "me_father_sobo"){
			woman = this_retu+4;
			men = this_retu+2;
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+2)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+3)).html("<img src='../img/line_left_top.png' class='max-img'>");
			$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu+3)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}
			
			
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).html("<span>"+woman_name+"</span><div class='fff text_tuika'></div>");
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).html("<span>"+men_name+"</span><div class='fff text_tuika'></div>");
			
			//ここから父方設定
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).addClass('btn btn-success btn_search');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('data-toggle','modal');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('data-target','#modal_hyouzi');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('data-this_td',(men));
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('data-sintou',this_sintou+1);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('id',men_id_settei);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).attr('data-sex','men');
			
			if(this_sintou == 0){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('background',backcolor1);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('position','relative');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('color',"#000000");
			}else if(this_sintou == 1){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('background',backcolor2);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('position','relative');
			}else if(this_sintou == 2){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('background',backcolor3);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('position','relative');
			}
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(men)).css('border',all_bordercolor);
			
			//ここから母方設定
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).addClass('btn btn-success btn_search');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('data-toggle','modal');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('data-target','#modal_hyouzi');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('data-this_td',(woman));
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('data-sintou',this_sintou+1);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('id',woman_id_settei);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).attr('data-sex','woman');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css("border-radius","100px");
			
			if(this_sintou == 0){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('background',backcolor1);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('position','relative');
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('color',"#000000");
			}else if(this_sintou == 1){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('background',backcolor2);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('position','relative');
			}else if(this_sintou == 2){
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('background',backcolor3);
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('position','relative');
			}
			$('#gyou'+(this_gyou-line_up)).children('.retu'+(woman)).css('border',all_bordercolor);
			
			
			
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			///////////////////////////////////////////////
			////////////ここから子供を選択時/////////////////
			///////////////////////////////////////////////
			if(syurui == "children_men"||syurui == "children_woman"){
			
			if(syurui == "children_men"){
			var sex_css = "sex_men";
			var sex = "men";
			}else if(syurui == "children_woman"){
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(syurui == "brother_woman"){
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(syurui == "brother_men"){
			var sex_css = "sex_men";
			var sex = "men";
			}
			
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable6の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me"||who == "me_haigusya"){////ここからあなたをクリック時
			
			oimei_count3.push(0);
			
			me_children_count++;
			var table6="";
			
			if(me_children_count == 1){
			
			table6+="<table id='table6' class='table6' style='position:absolute; top:0px; right:0px;'>";
			table6+="<tr id='table6_tr1'></tr>";
			table6+="<tr id='table6_tr2'></tr>";
			table6+="<tr id='table6_tr3'></tr>";
			table6+="<tr id='table6_tr4'></tr>";
			table6+="</table>";
			
			$('#gyou9').children('.retu5').css('position','relative');
			$('#gyou9').children('.retu5').append(table6);
			
			
			if(who == "me"){
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png?dada' class='max-img'>");
			}else if(who == "me_haigusya"){
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png?dada' class='max-img'>");
			}
			
			$('#gyou9').children('.retu6').html("<img src='../img/line_left_top.png?dada' class='max-img'>");
			
			}
			
			if(me_children_count == 1){
			$("#table6_tr1").append('<td class="me_children me_children_'+me_children_count+'"><img src="../img/line_right_bottom.png"></td><td class="me_children me_children_'+me_children_count+'"><img src="../img/line_yoko.png"></td>');
			$("#table6_tr2").append('<td class="me_children me_children_'+me_children_count+'" id="me_children_'+me_children_count+'"></td><td class="me_children me_children_'+me_children_count+'"></td>');
			$("#table6_tr3").append('<td class="me_children me_children_'+me_children_count+'"></td><td class="me_children me_children_'+me_children_count+'"></td>');
			$("#table6_tr4").append('<td class="me_children me_children_'+me_children_count+'"><td class="me_children me_children_'+me_children_count+'"></td>');
}else {
			$("#table6_tr1").append('<td class="me_children me_children_'+me_children_count+'"><img src="../img/line_huuhu.png"></td><td class="me_children me_children_'+me_children_count+'"><img src="../img/line_yoko.png"></td>');
			$("#table6_tr2").append('<td class="me_children me_children_'+me_children_count+'" id="me_children_'+me_children_count+'"></td><td class="me_children me_children_'+me_children_count+'"></td>');
			$("#table6_tr3").append('<td class="me_children me_children_'+me_children_count+'"></td><td class="me_children me_children_'+me_children_count+'"></td>');
			$("#table6_tr4").append('<td class="me_children me_children_'+me_children_count+'"></td><td class="me_children me_children_'+me_children_count+'"></td>');

}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

			$('#me_children_'+me_children_count).html("<span>子供</span><div class='fff text_tuika'></div>");

//ここから父方設定
			$('#me_children_'+me_children_count).addClass('btn btn-success btn_search '+sex_css);
			$('#me_children_'+me_children_count).attr('data-toggle','modal');
			$('#me_children_'+me_children_count).attr('data-target','#modal_hyouzi');
			$('#me_children_'+me_children_count).attr('data-this_td',me_children_count);
			$('#me_children_'+me_children_count).attr('data-sintou',1);
			$('#me_children_'+me_children_count).attr('data-sex',sex);

			$('#me_children_'+me_children_count).css('background',backcolor1);
			$('#me_children_'+me_children_count).css('position','relative');
			$('#me_children_'+me_children_count).css('border',all_bordercolor);
			$('#me_children_'+me_children_count).css('color',"#000000");
			if($("#table6_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここからtable6の孫の追加///////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

			if(btn_dore.indexOf("me_children") > -1){//クリックした配偶者ボタンのid名にme_childrenが含まれていたら

var new_btn_dore = btn_dore.replace("_haigusya","");
var number = Number(new_btn_dore.replace(/[^0-9]/g,''));
btn_dore = new_btn_dore.substr( 0, 13 );//先頭から15文字
			
			oimei_count3[number-1]++;


			if(oimei_count3[number-1] == 1){
$("#table6_tr2").children("."+btn_dore).eq(1).html('<img src="../img/line_huuhu.png">');
$("#table6_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_right_top.png">');
$("#table6_tr3").children("."+btn_dore).eq(2).html('<img src="../img/line_yoko.png">');

}



			$("#table6_tr1").children("."+btn_dore).eq(2).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"><img src="../img/line_yoko.png"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya ' +btn_dore+'_itoko_'+oimei_count3[number-1]+'"><img src="../img/line_yoko.png"></td>');
			$("#table6_tr2").children("."+btn_dore).eq(2).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"></td>');
			$("#table6_tr3").children("."+btn_dore).eq(2).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"><img src="../img/line_yoko.png"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"><img src="../img/line_left_bottom.png"></td>');
			$("#table6_tr4").children("."+btn_dore).eq(2).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+oimei_count3[number-1]+'"></td>');

			if(oimei_count3[number-1] > 1){
$("#table6_tr3").children("."+btn_dore).eq(4).html('<img src="../img/line_huuhu.png">');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$("#table6_tr4").children("."+btn_dore).eq(4).html("<span>孫</span><div class='fff text_tuika'></div>");

//ここから父方設定
$("#table6_tr4").children("."+btn_dore).eq(4).addClass('btn btn-success btn_search '+sex_css);
$("#table6_tr4").children("."+btn_dore).eq(4).attr('data-toggle','modal');
$("#table6_tr4").children("."+btn_dore).eq(4).attr('data-target','#modal_hyouzi');
$("#table6_tr4").children("."+btn_dore).eq(4).attr('data-sintou',2);
$("#table6_tr4").children("."+btn_dore).eq(4).attr('data-sex',sex);
			$("#table6_tr4").children("."+btn_dore).eq(4).attr('id',btn_dore+'_itoko_'+oimei_count3[number-1]);
$("#table6_tr4").children("."+btn_dore).eq(4).css('background',backcolor2);
$("#table6_tr4").children("."+btn_dore).eq(4).css('position','relative');
$("#table6_tr4").children("."+btn_dore).eq(4).css('border',all_bordercolor);

			if($("#table6_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
}
			
			
			
			
			
			if(who == "me_father_sousobo"||who == "me_father_sousohu"){////ここからあなた＞父＞祖父＞曾祖父母をクリック時
			
			father_sousohubo_count++;
			
			if(father_sousohubo_count == 1){
			if(who == "me_father_sousobo"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}else if(who == "me_father_sousohu"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}
			}
			
			$('#gyou0').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td>');
			$('#gyou1').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_right_bottom.png"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');
			$('#gyou2').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'" id="father_sousohubo_'+father_sousohubo_count+'"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td>');
			$('#gyou3').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');
		
			if(father_sousohubo_count > 1){
			$("#gyou1").children('.father_sousohubo').eq(2).html('<img src="../img/line_huuhu.png">');
			}
			
			/////////////////////////////////
			/////////ここからCSS//////////////
			/////////////////////////////////

			$('#father_sousohubo_'+father_sousohubo_count).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
			$('#father_sousohubo_'+father_sousohubo_count).addClass('btn btn-success btn_search '+sex_css);
			$('#father_sousohubo_'+father_sousohubo_count).attr('data-toggle','modal');
			$('#father_sousohubo_'+father_sousohubo_count).attr('data-target','#modal_hyouzi');
			$('#father_sousohubo_'+father_sousohubo_count).attr('data-this_td',father_sousohubo_count);
			$('#father_sousohubo_'+father_sousohubo_count).attr('data-sintou',3);
			$('#father_sousohubo_'+father_sousohubo_count).attr('data-sex',sex);

			$('#father_sousohubo_'+father_sousohubo_count).css('background',backcolor3);
			$('#father_sousohubo_'+father_sousohubo_count).css('position','relative');
			$('#father_sousohubo_'+father_sousohubo_count).css('border',all_bordercolor);

			}
			
			
			if(who == "me_mother_sousobo"||who == "me_mother_sousohu"){////ここからあなた＞母＞祖父＞曾祖父母をクリック時
			
			mother_sousohubo_count++;
			
			if(mother_sousohubo_count == 1){
			if(who == "me_mother_sousobo"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+2)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}else if(who == "me_mother_sousohu"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}
			}
			
		$('#gyou0').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td>');
		$('#gyou1').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_left_bottom.png"></td>');
		$('#gyou2').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'" id="mother_sousohubo_'+mother_sousohubo_count+'"></td>');
	$('#gyou3').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');
	
	for(var i=4;i<10;i++){
$('#gyou'+i).children('.retu0').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td>');
	}
	
	if(mother_sousohubo_count > 1){
	$("#gyou1").children('.mother_sousohubo').eq($("#gyou1").children('.mother_sousohubo').length-3).html('<img src="../img/line_huuhu.png">');
	}
	
	/////////////////////////////////
	/////////ここからCSS//////////////
	/////////////////////////////////
	
	$('#mother_sousohubo_'+mother_sousohubo_count).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");
	
	//ここから父方設定
	$('#mother_sousohubo_'+mother_sousohubo_count).addClass('btn btn-success btn_search '+sex_css);
	$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-toggle','modal');
	$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-target','#modal_hyouzi');
	$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-this_td',mother_sousohubo_count);
	$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-sintou',3);
	$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-sex',sex);
	
	$('#mother_sousohubo_'+mother_sousohubo_count).css('background',backcolor3);
	$('#mother_sousohubo_'+mother_sousohubo_count).css('position','relative');
	$('#mother_sousohubo_'+mother_sousohubo_count).css('border',all_bordercolor);
	
	}
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable1の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_father_sousobo2"||who == "me_father_sousohu2"){////ここからあなた＞父＞祖母＞曾祖父母をクリック時
			
			father_sousohubo_count2++;
			var table1="";
			
			if(father_sousohubo_count2 == 1){
			
			table1+="<table id='table1' class='table1' style='position:absolute; top:0px; left:0px;'>";
			table1+="<tr id='table1_tr1'></tr>";
			table1+="<tr id='table1_tr2'></tr>";
			table1+="</table>";
			
			$('#gyou1').children('.retu16').css('position','relative');
			$('#gyou1').children('.retu16').append(table1);
			
			
			if(who == "me_father_sousobo2"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}else if(who == "me_father_sousohu2"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
			
			if(father_sousohubo_count2 == 1){
			$("#table1_tr1").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_left_bottom.png"></td>');
		$("#table1_tr2").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'" id="father_ozioba_woman_'+father_sousohubo_count2+'"></td>');
		}else {
			$("#table1_tr1").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_huuhu.png"></td>');
		$("#table1_tr2").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'" id="father_ozioba_woman_'+father_sousohubo_count2+'"></td>');
}

			/////////////////////////////////
			/////////ここからCSS//////////////
			/////////////////////////////////

			$('#father_ozioba_woman_'+father_sousohubo_count2).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

			//ここから父方設定
			$('#father_ozioba_woman_'+father_sousohubo_count2).addClass('btn btn-success btn_search '+sex_css);
			$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-toggle','modal');
			$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-target','#modal_hyouzi');
			$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-this_td',father_sousohubo_count2);
			$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-sintou',3);
			$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-sex',sex);

			$('#father_ozioba_woman_'+father_sousohubo_count2).css('background',backcolor3);
			$('#father_ozioba_woman_'+father_sousohubo_count2).css('position','relative');
			$('#father_ozioba_woman_'+father_sousohubo_count2).css('border',all_bordercolor);
			
			
			}
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable4の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_mother_sousobo2"||who == "me_mother_sousohu2"){////ここからあなた＞母＞祖母＞曾祖父母をクリック時
			
			mother_sousohubo_count2++;
			var table4="";
			
			if(mother_sousohubo_count2 == 1){
			
			table4+="<table id='table4' class='table4' style='position:absolute; top:0px; right:0px;'>";
			table4+="<tr id='table4_tr1'></tr>";
			table4+="<tr id='table4_tr2'></tr>";
			table4+="</table>";
			
			$('#gyou1').children('.retu0').css('position','relative');
			$('#gyou1').children('.retu0').append(table4);
			
		
	
			if(who == "me_mother_sousobo2"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}else if(who == "me_mother_sousohu2"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
			
			if(mother_sousohubo_count2 == 1){
		$("#table4_tr1").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_right_bottom.png"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_yoko.png"></td>');
		$("#table4_tr2").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'" id="mother_ozioba_woman_'+mother_sousohubo_count2+'"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"></td>');
	}else {
			$("#table4_tr1").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_huuhu.png"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_yoko.png"></td>');
			$("#table4_tr2").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'" id="mother_ozioba_woman_'+mother_sousohubo_count2+'"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"></td>');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

			$('#mother_ozioba_woman_'+mother_sousohubo_count2).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).addClass('btn btn-success btn_search '+sex_css);
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-toggle','modal');
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-target','#modal_hyouzi');
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-this_td',mother_sousohubo_count2);
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-sintou',3);
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-sex',sex);

			$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('background',backcolor3);
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('position','relative');
			$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('border',all_bordercolor);

			
			
			if($("#table4_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
				for(var i=0;i<10;i++){
					$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
				}
			}
			
			

			}
			
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable2の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_father_sobo"||who == "me_father_sohu"){////ここからあなた＞父＞祖父母をクリック時
			
			itoko_count.push(0);
			
			father_ozioba_count++;
			var table2="";
			
			if(father_ozioba_count == 1){
			
			table2+="<table id='table2' class='table2' style='position:absolute; top:0px; left:0px;'>";
			table2+="<tr id='table2_tr1'></tr>";
			table2+="<tr id='table2_tr2'></tr>";
			table2+="<tr id='table2_tr3'></tr>";
			table2+="<tr id='table2_tr4'></tr>";
			table2+="</table>";
			
			$('#gyou3').children('.retu12').css('position','relative');
			$('#gyou3').children('.retu12').append(table2);
			
			
			if(who == "me_father_sobo"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}else if(who == "me_father_sohu"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
				if(father_ozioba_count == 1){
						$("#table2_tr1").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_left_bottom.png"></td>');
						$("#table2_tr2").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
					$("#table2_tr3").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td>');
					$("#table2_tr4").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
					}else {
				$("#table2_tr1").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_huuhu.png"></td>');
				$("#table2_tr2").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
				$("#table2_tr3").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
				$("#table2_tr4").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
				
				}
				
				/////////////////////////////////
				/////////ここからCSS//////////////
				/////////////////////////////////
				
				$('#father_ozioba_'+father_ozioba_count).html("<span>叔父叔母</span><div class='fff text_tuika'></div>");
				
				//ここから父方設定
				$('#father_ozioba_'+father_ozioba_count).addClass('btn btn-success btn_search '+sex_css);
				$('#father_ozioba_'+father_ozioba_count).attr('data-toggle','modal');
				$('#father_ozioba_'+father_ozioba_count).attr('data-target','#modal_hyouzi');
				$('#father_ozioba_'+father_ozioba_count).attr('data-this_td',father_ozioba_count);
				$('#father_ozioba_'+father_ozioba_count).attr('data-sintou',2);
				$('#father_ozioba_'+father_ozioba_count).attr('data-sex',sex);
				
				$('#father_ozioba_'+father_ozioba_count).css('background',backcolor2);
				$('#father_ozioba_'+father_ozioba_count).css('position','relative');
				$('#father_ozioba_'+father_ozioba_count).css('border',all_bordercolor);
				
				}
			
			////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////
			/////ここからいとこの追加////////////////////////////////////
			////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////
			
			if(btn_dore.indexOf("father_ozioba") > -1){//クリックした配偶者ボタンのid名にfather_childrenが含まれていたら
			
			var new_btn_dore = btn_dore.replace("_haigusya","");
			var number = Number(new_btn_dore.replace(/[^0-9]/g,''));
			btn_dore = new_btn_dore.substr( 0, 17 );//先頭から15文字
			
			itoko_count[number-1]++;
			
			
			if(itoko_count[number-1] == 1){
			$("#table2_tr2").children("."+btn_dore).eq(2).html('<img src="../img/line_huuhu.png">');
			$("#table2_tr3").children("."+btn_dore).eq(2).html('<img src="../img/line_left_top.png">');
			$("#table2_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_yoko.png">');
			$("#table2_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
			}
			
			if(itoko_count[number-1] > 1){
			$("#table2_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
			$("#table2_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_huuhu.png">');
			}
			
			$("#table2_tr1").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya ' +btn_dore+'_itoko_'+itoko_count[number-1]+'"><img src="../img/line_yoko.png"></td>');
			$("#table2_tr2").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"></td>');
			$("#table2_tr3").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"><img src="../img/line_right_bottom.png"></td>');
			$("#table2_tr4").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count[number-1]+'"></td>');
				
				/////////////////////////////////
				/////////ここからCSS//////////////
				/////////////////////////////////
				
				$("#table2_tr4").children("."+btn_dore).eq(1).html("<span>いとこ</span><div class='fff text_tuika'></div>");
				
				//ここから父方設定
				$("#table2_tr4").children("."+btn_dore).eq(1).addClass('btn btn-success btn_search '+sex_css);
				$("#table2_tr4").children("."+btn_dore).eq(1).attr('data-toggle','modal');
				$("#table2_tr4").children("."+btn_dore).eq(1).attr('data-target','#modal_hyouzi');
				$("#table2_tr4").children("."+btn_dore).eq(1).attr('data-sintou',3);
				$("#table2_tr4").children("."+btn_dore).eq(1).attr('data-sex',sex);
				$("#table2_tr4").children("."+btn_dore).eq(1).attr('id',btn_dore+'_itoko_'+itoko_count[number-1]);
				$("#table2_tr4").children("."+btn_dore).eq(1).css('background',backcolor3);
				$("#table2_tr4").children("."+btn_dore).eq(1).css('position','relative');
				$("#table2_tr4").children("."+btn_dore).eq(1).css('border',all_bordercolor);
				
			}
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable5の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_mother_sobo"||who == "me_mother_sohu"){////ここからあなた＞母＞祖父母をクリック時
			
			itoko_count2.push(0);
			
			mother_ozioba_count++;
			var table5="";
			
			if(mother_ozioba_count == 1){
			
			table5+="<table id='table5' class='table5' style='position:absolute; top:0px; right:0px;'>";
			table5+="<tr id='table5_tr1'></tr>";
			table5+="<tr id='table5_tr2'></tr>";
			table5+="<tr id='table5_tr3'></tr>";
			table5+="<tr id='table5_tr4'></tr>";
			table5+="</table>";
			
			$('#gyou3').children('.retu4').css('position','relative');
			$('#gyou3').children('.retu4').append(table5);
			
			
			if(who == "me_mother_sobo"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}else if(who == "me_mother_sohu"){
			$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
			if(mother_ozioba_count == 1){
			$("#table5_tr1").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_right_bottom.png"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_yoko.png"></td>');
		$("#table5_tr2").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'" id="mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
			$("#table5_tr3").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
		$("#table5_tr4").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
}else {
			$("#table5_tr1").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_huuhu.png"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_yoko.png"></td>');
			$("#table5_tr2").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'" id="mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
			$("#table5_tr3").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
			$("#table5_tr4").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');

}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

			$('#mother_ozioba_'+mother_ozioba_count).html("<span>叔父叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
			$('#mother_ozioba_'+mother_ozioba_count).addClass('btn btn-success btn_search '+sex_css);
			$('#mother_ozioba_'+mother_ozioba_count).attr('data-toggle','modal');
			$('#mother_ozioba_'+mother_ozioba_count).attr('data-target','#modal_hyouzi');
			$('#mother_ozioba_'+mother_ozioba_count).attr('data-this_td',mother_ozioba_count);
			$('#mother_ozioba_'+mother_ozioba_count).attr('data-sintou',2);
			$('#mother_ozioba_'+mother_ozioba_count).attr('data-sex',sex);

			$('#mother_ozioba_'+mother_ozioba_count).css('background',backcolor2);
			$('#mother_ozioba_'+mother_ozioba_count).css('position','relative');
			$('#mother_ozioba_'+mother_ozioba_count).css('border',all_bordercolor);

			if($("#table5_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここからtable5のいとこの追加///////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

if(btn_dore.indexOf("mother_ozioba") > -1){//クリックした配偶者ボタンのid名にmother_oziobaが含まれていたら

var new_btn_dore = btn_dore.replace("_haigusya","");
var number = Number(new_btn_dore.replace(/[^0-9]/g,''));
btn_dore = new_btn_dore.substr( 0, 17 );//先頭から15文字

itoko_count2[number-1]++;


if(itoko_count2[number-1] == 1){
	$("#table5_tr2").children("."+btn_dore).eq(1).html('<img src="../img/line_huuhu.png">');
	$("#table5_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_right_top.png">');
	$("#table5_tr3").children("."+btn_dore).eq(2).html('<img src="../img/line_yoko.png">');
	
}



			$("#table5_tr1").children("."+btn_dore).eq(2).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"><img src="../img/line_yoko.png"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya ' +btn_dore+'_itoko_'+itoko_count2[number-1]+'"><img src="../img/line_yoko.png"></td>');
			$("#table5_tr2").children("."+btn_dore).eq(2).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"></td>');
			$("#table5_tr3").children("."+btn_dore).eq(2).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"><img src="../img/line_yoko.png"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"><img src="../img/line_left_bottom.png"></td>');
			$("#table5_tr4").children("."+btn_dore).eq(2).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_itoko_'+itoko_count2[number-1]+'"></td>');

			if(itoko_count2[number-1] > 1){
			$("#table5_tr3").children("."+btn_dore).eq(4).html('<img src="../img/line_huuhu.png">');
			}
			
/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$("#table5_tr4").children("."+btn_dore).eq(4).html("<span>いとこ</span><div class='fff text_tuika'></div>");

//ここから父方設定
$("#table5_tr4").children("."+btn_dore).eq(4).addClass('btn btn-success btn_search '+sex_css);
$("#table5_tr4").children("."+btn_dore).eq(4).attr('data-toggle','modal');
$("#table5_tr4").children("."+btn_dore).eq(4).attr('data-target','#modal_hyouzi');
$("#table5_tr4").children("."+btn_dore).eq(4).attr('data-sintou',3);
$("#table5_tr4").children("."+btn_dore).eq(4).attr('data-sex',sex);
$("#table5_tr4").children("."+btn_dore).eq(4).attr('id',btn_dore+'_itoko_'+itoko_count2[number-1]);
$("#table5_tr4").children("."+btn_dore).eq(4).css('background',backcolor3);
$("#table5_tr4").children("."+btn_dore).eq(4).css('position','relative');
$("#table5_tr4").children("."+btn_dore).eq(4).css('border',all_bordercolor);

			if($("#table5_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
}
			
			
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable3の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_father"||who == "me_mother"){////ここからあなた＞父をクリック時
			
			oimei_count.push(0);
			
			father_children_count++;
			var table3="";
			
			if(father_children_count == 1){
			
			table3+="<table id='table3' class='table3' style='position:absolute; top:0px; left:0px;'>";
			table3+="<tr id='table3_tr1'></tr>";
			table3+="<tr id='table3_tr2'></tr>";
			table3+="<tr id='table3_tr3'></tr>";
			table3+="<tr id='table3_tr4'></tr>";
			table3+="</table>";
			
			$('#gyou7').children('.retu9').css('position','relative');
			$('#gyou7').children('.retu9').append(table3);
			
			
			if(who == "me_father"){
			$('#gyou'+(this_gyou+3)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}else if(who == "me_mother"){
			$('#gyou'+(this_gyou+3)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
			if(father_children_count == 1){
			$("#table3_tr1").prepend('<td class="father_children father_children_'+father_children_count+'"><img src="../img/line_yoko.png"></td><td class="father_children father_children_'+father_children_count+'"><img src="../img/line_left_bottom.png"></td>');
			$("#table3_tr2").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'" id="father_children_'+father_children_count+'"></td>');
			$("#table3_tr3").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
			$("#table3_tr4").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
				}else {
			$("#table3_tr1").prepend('<td class="father_children father_children_'+father_children_count+'"><img src="../img/line_yoko.png"></td><td class="father_children father_children_'+father_children_count+'"><img src="../img/line_huuhu.png"></td>');
			$("#table3_tr2").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'" id="father_children_'+father_children_count+'"></td>');
		$("#table3_tr3").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
	$("#table3_tr4").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
					}
					
					/////////////////////////////////
					/////////ここからCSS//////////////
					/////////////////////////////////
					
			$('#father_children_'+father_children_count).html("<span>兄弟姉妹</span><div class='fff text_tuika'></div>");
			
			//ここから父方設定
			$('#father_children_'+father_children_count).addClass('btn btn-success btn_search '+sex_css);
			$('#father_children_'+father_children_count).attr('data-toggle','modal');
			$('#father_children_'+father_children_count).attr('data-target','#modal_hyouzi');
			$('#father_children_'+father_children_count).attr('data-this_td',father_children_count);
			$('#father_children_'+father_children_count).attr('data-sintou',1);
			$('#father_children_'+father_children_count).attr('data-sex',sex);
					
			$('#father_children_'+father_children_count).css('background',backcolor1);
			$('#father_children_'+father_children_count).css('position','relative');
			$('#father_children_'+father_children_count).css('border',all_bordercolor);
			$('#father_children_'+father_children_count).css('color',"#000000");
			}
		
		
		
		////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////
		/////ここから甥姪の追加////////////////////////////////////
		////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////
		
			if(btn_dore.indexOf("father_children") > -1){//クリックした配偶者ボタンのid名にfather_childrenが含まれていたら
			
			var new_btn_dore = btn_dore.replace("_haigusya","");
			var number = Number(new_btn_dore.replace(/[^0-9]/g,''));
			btn_dore = new_btn_dore.substr( 0, 17 );//先頭から15文字
			
			oimei_count[number-1]++;
			
			
			if(oimei_count[number-1] == 1){
			$("#table3_tr2").children("."+btn_dore).eq(2).html('<img src="../img/line_huuhu.png">');
			$("#table3_tr3").children("."+btn_dore).eq(2).html('<img src="../img/line_left_top.png">');
			$("#table3_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_yoko.png">');
			$("#table3_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
			}
			
			if(oimei_count[number-1] > 1){
			$("#table3_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
			$("#table3_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_huuhu.png">');
			}
			
		$("#table3_tr1").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_yoko.png"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya ' +btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_yoko.png"></td>');
			$("#table3_tr2").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td>');
			$("#table3_tr3").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_right_bottom.png"></td>');
			$("#table3_tr4").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td>');
			
			/////////////////////////////////
			/////////ここからCSS//////////////
			/////////////////////////////////
			
			$("#table3_tr4").children("."+btn_dore).eq(1).html("<span>甥姪</span><div class='fff text_tuika'></div>");
			
			//ここから父方設定
			$("#table3_tr4").children("."+btn_dore).eq(1).addClass('btn btn-success btn_search '+sex_css);
			$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-toggle','modal');
			$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-target','#modal_hyouzi');
			$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-sintou',2);
			$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-sex',sex);
			$("#table3_tr4").children("."+btn_dore).eq(1).attr('id',btn_dore+'_oimei_'+oimei_count[number-1]);
			$("#table3_tr4").children("."+btn_dore).eq(1).css('background',backcolor2);
			$("#table3_tr4").children("."+btn_dore).eq(1).css('position','relative');
			$("#table3_tr4").children("."+btn_dore).eq(1).css('border',all_bordercolor);
			
			}
			
			}
			
			

			
			
			
			
			
			
			
			
			///////////////////////////////////////////////
			////////////ここから兄弟を選択時/////////////////
			///////////////////////////////////////////////
			if(syurui == "brother_men"||syurui == "brother_woman"){
			
			
			if(syurui == "brother_woman"){
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(syurui == "brother_men"){
			var sex_css = "sex_men";
			var sex = "men";
			}
			
			
			/////////////////////////////////////////////////////
			//////////ここからtable5の要素/////////////////////////
			/////////////////////////////////////////////////////
			if(who == "me_mother"){////ここからあなた＞母をクリック時
			
			itoko_count2.push(0);
			
			mother_ozioba_count++;
			var table5="";
			
			if(mother_ozioba_count == 1){
			
			table5+="<table id='table5' class='table5' style='position:absolute; top:0px; right:0px;'>";
			table5+="<tr id='table5_tr1'></tr>";
			table5+="<tr id='table5_tr2'></tr>";
			table5+="<tr id='table5_tr3'></tr>";
			table5+="<tr id='table5_tr4'></tr>";
			table5+="</table>";
			
			$('#gyou3').children('.retu4').css('position','relative');
			$('#gyou3').children('.retu4').append(table5);
			
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			
			}
			
			if(mother_ozioba_count == 1){
		$("#table5_tr1").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_right_bottom.png"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_yoko.png"></td>');
	$("#table5_tr2").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'" id="mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
$("#table5_tr3").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
$("#table5_tr4").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
}else {
$("#table5_tr1").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_huuhu.png"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"><img src="../img/line_yoko.png"></td>');
$("#table5_tr2").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'" id="mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
$("#table5_tr3").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');
$("#table5_tr4").append('<td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td><td class="mother_ozioba mother_ozioba_'+mother_ozioba_count+'"></td>');

}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#mother_ozioba_'+mother_ozioba_count).html("<span>叔父叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#mother_ozioba_'+mother_ozioba_count).addClass('btn btn-success btn_search '+sex_css);
$('#mother_ozioba_'+mother_ozioba_count).attr('data-toggle','modal');
$('#mother_ozioba_'+mother_ozioba_count).attr('data-target','#modal_hyouzi');
$('#mother_ozioba_'+mother_ozioba_count).attr('data-this_td',mother_ozioba_count);
$('#mother_ozioba_'+mother_ozioba_count).attr('data-sintou',2);
$('#mother_ozioba_'+mother_ozioba_count).attr('data-sex',sex);

$('#mother_ozioba_'+mother_ozioba_count).css('background',backcolor2);
$('#mother_ozioba_'+mother_ozioba_count).css('position','relative');
$('#mother_ozioba_'+mother_ozioba_count).css('border',all_bordercolor);

			if($("#table5_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
}
			
			
			
			if(who == "me_father_sohu"){////ここからあなた＞父＞祖父をクリック時
			
			father_sousohubo_count++;
			
			if(father_sousohubo_count == 1){
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}
			
			$('#gyou0').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td>');
		$('#gyou1').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_right_bottom.png"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');
	$('#gyou2').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'" id="father_sousohubo_'+father_sousohubo_count+'"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"></td>');
$('#gyou3').children('.retu9').after('<td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="father_sousohubo father_sousohubo_'+father_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');

if(father_sousohubo_count > 1){
$("#gyou1").children('.father_sousohubo').eq(2).html('<img src="../img/line_huuhu.png">');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#father_sousohubo_'+father_sousohubo_count).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#father_sousohubo_'+father_sousohubo_count).addClass('btn btn-success btn_search '+sex_css);
$('#father_sousohubo_'+father_sousohubo_count).attr('data-toggle','modal');
$('#father_sousohubo_'+father_sousohubo_count).attr('data-target','#modal_hyouzi');
$('#father_sousohubo_'+father_sousohubo_count).attr('data-this_td',father_sousohubo_count);
$('#father_sousohubo_'+father_sousohubo_count).attr('data-sintou',3);
$('#father_sousohubo_'+father_sousohubo_count).attr('data-sex',sex);

$('#father_sousohubo_'+father_sousohubo_count).css('background',backcolor3);
$('#father_sousohubo_'+father_sousohubo_count).css('position','relative');
$('#father_sousohubo_'+father_sousohubo_count).css('border',all_bordercolor);

}
			
		if(who == "me_mother_sohu"){////ここからあなた＞母＞祖父をクリック時
			
			mother_sousohubo_count++;
			
			if(mother_sousohubo_count == 1){
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_huuhu.png' class='max-img'>");
			}
			
			$('#gyou0').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td>');
			$('#gyou1').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_left_bottom.png"></td>');
		$('#gyou2').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'" id="mother_sousohubo_'+mother_sousohubo_count+'"></td>');
			$('#gyou3').children('.retu7').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"><img src="../img/line_yoko.png"></td>');

			for(var i=4;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td><td class="mother_sousohubo mother_sousohubo_'+mother_sousohubo_count+'"></td>');
			}
			
			if(mother_sousohubo_count > 1){
			$("#gyou1").children('.mother_sousohubo').eq($("#gyou1").children('.mother_sousohubo').length-3).html('<img src="../img/line_huuhu.png">');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

			$('#mother_sousohubo_'+mother_sousohubo_count).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
			$('#mother_sousohubo_'+mother_sousohubo_count).addClass('btn btn-success btn_search '+sex_css);
			$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-toggle','modal');
			$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-target','#modal_hyouzi');
			$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-this_td',mother_sousohubo_count);
			$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-sintou',3);
			$('#mother_sousohubo_'+mother_sousohubo_count).attr('data-sex',sex);

			$('#mother_sousohubo_'+mother_sousohubo_count).css('background',backcolor3);
			$('#mother_sousohubo_'+mother_sousohubo_count).css('position','relative');
			$('#mother_sousohubo_'+mother_sousohubo_count).css('border',all_bordercolor);

}





/////////////////////////////////////////////////////
//////////ここからtable1の要素/////////////////////////
/////////////////////////////////////////////////////
if(who == "me_father_sobo"){////ここからあなた＞父＞祖母をクリック時

father_sousohubo_count2++;
var table1="";

if(father_sousohubo_count2 == 1){

table1+="<table id='table1' class='table1' style='position:absolute; top:0px; left:0px;'>";
table1+="<tr id='table1_tr1'></tr>";
table1+="<tr id='table1_tr2'></tr>";
table1+="</table>";

$('#gyou1').children('.retu16').css('position','relative');
$('#gyou1').children('.retu16').append(table1);

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+3)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");

}


if(father_sousohubo_count2 == 1){
$("#table1_tr1").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_left_bottom.png"></td>');
$("#table1_tr2").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'" id="father_ozioba_woman_'+father_sousohubo_count2+'"></td>');
}else {
$("#table1_tr1").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"><img src="../img/line_huuhu.png"></td>');
$("#table1_tr2").prepend('<td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'"></td><td class="father_ozioba_woman father_ozioba_woman_'+father_sousohubo_count2+'" id="father_ozioba_woman_'+father_sousohubo_count2+'"></td>');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#father_ozioba_woman_'+father_sousohubo_count2).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#father_ozioba_woman_'+father_sousohubo_count2).addClass('btn btn-success btn_search '+sex_css);
$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-toggle','modal');
$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-target','#modal_hyouzi');
$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-this_td',father_sousohubo_count2);
$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-sintou',3);
$('#father_ozioba_woman_'+father_sousohubo_count2).attr('data-sex',sex);

$('#father_ozioba_woman_'+father_sousohubo_count2).css('background',backcolor3);
$('#father_ozioba_woman_'+father_sousohubo_count2).css('position','relative');
$('#father_ozioba_woman_'+father_sousohubo_count2).css('border',all_bordercolor);


}



/////////////////////////////////////////////////////
//////////ここからtable2の要素/////////////////////////
/////////////////////////////////////////////////////
if(who == "me_father"){////ここからあなた＞父をクリック時

itoko_count.push(0);

father_ozioba_count++;
var table2="";

if(father_ozioba_count == 1){

table2+="<table id='table2' class='table2' style='position:absolute; top:0px; left:0px;'>";
table2+="<tr id='table2_tr1'></tr>";
table2+="<tr id='table2_tr2'></tr>";
table2+="<tr id='table2_tr3'></tr>";
table2+="<tr id='table2_tr4'></tr>";
table2+="</table>";

$('#gyou3').children('.retu12').css('position','relative');
$('#gyou3').children('.retu12').append(table2);

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+2)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");

}

if(father_ozioba_count == 1){
$("#table2_tr1").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_left_bottom.png"></td>');
$("#table2_tr2").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
$("#table2_tr3").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td>');
$("#table2_tr4").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
}else {
$("#table2_tr1").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_yoko.png"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'"><img src="../img/line_huuhu.png"></td>');
$("#table2_tr2").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
$("#table2_tr3").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');
$("#table2_tr4").prepend('<td class="father_ozioba father_ozioba_'+father_ozioba_count+'"></td><td class="father_ozioba father_ozioba_'+father_ozioba_count+'" id="father_ozioba_'+father_ozioba_count+'"></td>');

}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#father_ozioba_'+father_ozioba_count).html("<span>叔父叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#father_ozioba_'+father_ozioba_count).addClass('btn btn-success btn_search '+sex_css);
$('#father_ozioba_'+father_ozioba_count).attr('data-toggle','modal');
$('#father_ozioba_'+father_ozioba_count).attr('data-target','#modal_hyouzi');
$('#father_ozioba_'+father_ozioba_count).attr('data-this_td',father_ozioba_count);
$('#father_ozioba_'+father_ozioba_count).attr('data-sintou',2);
$('#father_ozioba_'+father_ozioba_count).attr('data-sex',sex);

$('#father_ozioba_'+father_ozioba_count).css('background',backcolor2);
$('#father_ozioba_'+father_ozioba_count).css('position','relative');
$('#father_ozioba_'+father_ozioba_count).css('border',all_bordercolor);

}




/////////////////////////////////////////////////////
//////////ここからtable3の要素/////////////////////////
/////////////////////////////////////////////////////
if(who == "me"){////ここからあなたをクリック時

oimei_count.push(0);

father_children_count++;
var table3="";

if(father_children_count == 1){

table3+="<table id='table3' class='table3' style='position:absolute; top:0px; left:0px;'>";
table3+="<tr id='table3_tr1'></tr>";
table3+="<tr id='table3_tr2'></tr>";
table3+="<tr id='table3_tr3'></tr>";
table3+="<tr id='table3_tr4'></tr>";
table3+="</table>";

$('#gyou7').children('.retu9').css('position','relative');
$('#gyou7').children('.retu9').append(table3);



$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");

}

if(father_children_count == 1){
$("#table3_tr1").prepend('<td class="father_children father_children_'+father_children_count+'"><img src="../img/line_yoko.png"></td><td class="father_children father_children_'+father_children_count+'"><img src="../img/line_left_bottom.png"></td>');
$("#table3_tr2").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'" id="father_children_'+father_children_count+'"></td>');
$("#table3_tr3").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
$("#table3_tr4").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
}else {
$("#table3_tr1").prepend('<td class="father_children father_children_'+father_children_count+'"><img src="../img/line_yoko.png"></td><td class="father_children father_children_'+father_children_count+'"><img src="../img/line_huuhu.png"></td>');
$("#table3_tr2").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'" id="father_children_'+father_children_count+'"></td>');
$("#table3_tr3").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
$("#table3_tr4").prepend('<td class="father_children father_children_'+father_children_count+'"></td><td class="father_children father_children_'+father_children_count+'"></td>');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#father_children_'+father_children_count).html("<span>兄弟姉妹</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#father_children_'+father_children_count).addClass('btn btn-success btn_search '+sex_css);
$('#father_children_'+father_children_count).attr('data-toggle','modal');
$('#father_children_'+father_children_count).attr('data-target','#modal_hyouzi');
$('#father_children_'+father_children_count).attr('data-this_td',father_children_count);
$('#father_children_'+father_children_count).attr('data-sintou',1);
$('#father_children_'+father_children_count).attr('data-sex',sex);

$('#father_children_'+father_children_count).css('background',backcolor1);
$('#father_children_'+father_children_count).css('position','relative');
$('#father_children_'+father_children_count).css('border',all_bordercolor);
$('#father_children_'+father_children_count).css('color',"#000000");
}


			
/////////////////////////////////////////////////////
//////////ここからtable4の要素/////////////////////////
/////////////////////////////////////////////////////
			if(who == "me_mother_sobo"){////ここからあなた＞母＞祖母をクリック時
			
			mother_sousohubo_count2++;
			var table4="";
			
			if(mother_sousohubo_count2 == 1){
			
			table4+="<table id='table4' class='table4' style='position:absolute; top:0px; right:0px;'>";
			table4+="<tr id='table4_tr1'></tr>";
			table4+="<tr id='table4_tr2'></tr>";
			table4+="</table>";
			
			$('#gyou1').children('.retu0').css('position','relative');
			$('#gyou1').children('.retu0').append(table4);
			
			
			if(who == "me_mother_sobo"){
			$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-3)).html("<img src='../img/line_huuhu_top.png?dada' class='max-img'>");
			}
			}
			
			
			if(mother_sousohubo_count2 == 1){
		$("#table4_tr1").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_right_bottom.png"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_yoko.png"></td>');
	$("#table4_tr2").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'" id="mother_ozioba_woman_'+mother_sousohubo_count2+'"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"></td>');
	}else {
$("#table4_tr1").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_huuhu.png"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"><img src="../img/line_yoko.png"></td>');
$("#table4_tr2").append('<td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'" id="mother_ozioba_woman_'+mother_sousohubo_count2+'"></td><td class="mother_ozioba_woman mother_ozioba_woman_'+mother_sousohubo_count2+'"></td>');
}

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$('#mother_ozioba_woman_'+mother_sousohubo_count2).html("<span>大叔父<br>大叔母</span><div class='fff text_tuika'></div>");

//ここから父方設定
$('#mother_ozioba_woman_'+mother_sousohubo_count2).addClass('btn btn-success btn_search '+sex_css);
$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-toggle','modal');
$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-target','#modal_hyouzi');
$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-this_td',mother_sousohubo_count2);
$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-sintou',3);
$('#mother_ozioba_woman_'+mother_sousohubo_count2).attr('data-sex',sex);

$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('background',backcolor3);
$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('position','relative');
$('#mother_ozioba_woman_'+mother_sousohubo_count2).css('border',all_bordercolor);

if($("#table4_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
for(var i=0;i<10;i++){
	$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
}
}


}

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここから甥姪の追加////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

if(btn_dore.indexOf("father_children") > -1){//クリックした配偶者ボタンのid名にfather_childrenが含まれていたら

var new_btn_dore = btn_dore.replace("_haigusya","");
var number = Number(new_btn_dore.replace(/[^0-9]/g,''));
btn_dore = new_btn_dore.substr( 0, 17 );//先頭から15文字

oimei_count[number-1]++;


if(oimei_count[number-1] == 1){
$("#table3_tr2").children("."+btn_dore).eq(2).html('<img src="../img/line_huuhu.png">');
$("#table3_tr3").children("."+btn_dore).eq(2).html('<img src="../img/line_left_top.png">');
$("#table3_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_yoko.png">');
$("#table3_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
}

if(oimei_count[number-1] > 1){
$("#table3_tr3").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
$("#table3_tr3").children("."+btn_dore).eq(1).html('<img src="../img/line_huuhu.png">');
}

$("#table3_tr1").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_yoko.png"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya ' +btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_yoko.png"></td>');
$("#table3_tr2").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td>');
$("#table3_tr3").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"><img src="../img/line_right_bottom.png"></td>');
$("#table3_tr4").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya '+btn_dore+'_oimei_'+oimei_count[number-1]+'"></td>');

/////////////////////////////////
/////////ここからCSS//////////////
/////////////////////////////////

$("#table3_tr4").children("."+btn_dore).eq(1).html("<span>甥姪</span><div class='fff text_tuika'></div>");

//ここから父方設定
$("#table3_tr4").children("."+btn_dore).eq(1).addClass('btn btn-success btn_search '+sex_css);
$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-toggle','modal');
$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-target','#modal_hyouzi');
$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-sintou',2);
$("#table3_tr4").children("."+btn_dore).eq(1).attr('data-sex',sex);
$("#table3_tr4").children("."+btn_dore).eq(1).attr('id',btn_dore+'_oimei_'+oimei_count[number-1]);
$("#table3_tr4").children("."+btn_dore).eq(1).css('background',backcolor2);
$("#table3_tr4").children("."+btn_dore).eq(1).css('position','relative');
$("#table3_tr4").children("."+btn_dore).eq(1).css('border',all_bordercolor);

}

}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			///////////////////////////////////////////////
			////////////ここから配偶者を選択時/////////////////
			///////////////////////////////////////////////
			
			if(syurui == "haigusya"){
		
		
			if(who == "me"){//私の配偶者
			
			men_id_settei="me_haigusya";
			
			if(this_sex == "men"){//クリックした要素の性別の逆を設定する
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(this_sex == "woman"){
			var sex_css = "sex_men";
			var sex = "men";
			}
			
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).html("<img src='../img/line_yoko.png' class='max-img'>");
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).attr('data-this_td',this_retu-2);
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).addClass(men_id_settei);
			
			
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).html("<span style='color:#000000;'>配偶者</span><div class='fff text_tuika'></div>");
			//ここからCSS設定
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).addClass('btn btn-success btn_search '+men_id_settei+' '+sex_css);
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).attr('data-toggle','modal');
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).attr('data-target','#modal_hyouzi');
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).attr('data-this_td',this_retu-2);
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).attr('id',men_id_settei);
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).attr('data-sex',sex);
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).css('background','#fff');
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).css('position','relative');
			$('#gyou'+(this_gyou)).children('.retu'+(this_retu-2)).css('border',all_bordercolor);
			//ここまで
			
			}
			
			
			if(who.indexOf("father_children") >= 0) {//////私の兄弟姉妹の配偶者
			
			if(this_sex == "men"){//クリックした要素の性別の逆を設定する
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(this_sex == "men"){
			var sex_css = "sex_woman";
			var sex = "men";
			}
			
			
			$("#table3_tr1").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td>');
			$("#table3_tr2").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
			$("#table3_tr2").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table3_tr3").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table3_tr2").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table3_tr2").children("."+btn_dore).eq(1).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table3_tr3").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table3_tr4").children("."+btn_dore).eq(0).before('<td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="father_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table3_tr3").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table3_tr3").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table3_tr4").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table3_tr4").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			
			
			$("#table3_tr2").children("."+btn_dore).eq(1).html("<span style='color:#000000;'>配偶者</span><div class='fff text_tuika'></div>");
			//ここからCSS設定
			$("#table3_tr2").children("."+btn_dore).eq(1).addClass('btn btn-success btn_search '+sex_css);
			$("#table3_tr2").children("."+btn_dore).eq(1).attr('data-toggle','modal');
			$("#table3_tr2").children("."+btn_dore).eq(1).attr('data-target','#modal_hyouzi');
			$("#table3_tr2").children("."+btn_dore).eq(1).attr('id',btn_dore+"_haigusya");
			$("#table3_tr2").children("."+btn_dore).eq(1).attr('data-sex',sex);
			$("#table3_tr2").children("."+btn_dore).eq(1).css('background','#fff');
			$("#table3_tr2").children("."+btn_dore).eq(1).css('position','relative');
			$("#table3_tr2").children("."+btn_dore).eq(1).css('border',all_bordercolor);
			//ここまで
			
			}
			
			
			if(who.indexOf("father_ozioba") >= 0) {//////私＞父＞叔父叔母の配偶者
			
			if(this_sex == "men"){//クリックした要素の性別の逆を設定する
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(this_sex == "men"){
			var sex_css = "sex_woman";
			var sex = "men";
			}
			
			
			$("#table2_tr1").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td>');
					$("#table2_tr2").children("."+btn_dore).eq(0).html('<img src="../img/line_yoko.png">');
					$("#table2_tr2").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
					$("#table2_tr3").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table2_tr2").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table2_tr2").children("."+btn_dore).eq(1).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table2_tr3").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table2_tr4").children("."+btn_dore).eq(0).before('<td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="father_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table2_tr3").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table2_tr3").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table2_tr4").children("."+btn_dore).eq(0).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table2_tr4").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			
			
			$("#table2_tr2").children("."+btn_dore).eq(1).html("<span style='color:#000000;'>配偶者</span><div class='fff text_tuika'></div>");
			//ここからCSS設定
			$("#table2_tr2").children("."+btn_dore).eq(1).addClass('btn btn-success btn_search '+sex_css);
			$("#table2_tr2").children("."+btn_dore).eq(1).attr('data-toggle','modal');
			$("#table2_tr2").children("."+btn_dore).eq(1).attr('data-target','#modal_hyouzi');
			$("#table2_tr2").children("."+btn_dore).eq(1).attr('id',btn_dore+"_haigusya");
			$("#table2_tr2").children("."+btn_dore).eq(1).attr('data-sex',sex);
			$("#table2_tr2").children("."+btn_dore).eq(1).css('background','#fff');
			$("#table2_tr2").children("."+btn_dore).eq(1).css('position','relative');
			$("#table2_tr2").children("."+btn_dore).eq(1).css('border',all_bordercolor);
			//ここまで
			
			}
			
			if(who.indexOf("mother_ozioba") >= 0) {//////私＞母＞叔父叔母の配偶者
			
			if(this_sex == "men"){//クリックした要素の性別の逆を設定する
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(this_sex == "men"){
			var sex_css = "sex_woman";
			var sex = "men";
			}
			
			
			$("#table5_tr1").children("."+btn_dore).eq(1).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td>');
			$("#table5_tr2").children("."+btn_dore).eq(1).html('<img src="../img/line_yoko.png">');
			$("#table5_tr2").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table5_tr2").children("."+btn_dore).eq(1).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table5_tr2").children("."+btn_dore).eq(2).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table5_tr3").children("."+btn_dore).eq(1).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table5_tr4").children("."+btn_dore).eq(1).after('<td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="mother_ozioba '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table5_tr3").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');


$("#table5_tr2").children("."+btn_dore).eq(2).html("<span style='color:#000000;'>配偶者</span><div class='fff text_tuika'></div>");
//ここからCSS設定
$("#table5_tr2").children("."+btn_dore).eq(2).addClass('btn btn-success btn_search '+sex_css);
$("#table5_tr2").children("."+btn_dore).eq(2).attr('data-toggle','modal');
$("#table5_tr2").children("."+btn_dore).eq(2).attr('data-target','#modal_hyouzi');
$("#table5_tr2").children("."+btn_dore).eq(2).attr('id',btn_dore+"_haigusya");
$("#table5_tr2").children("."+btn_dore).eq(2).attr('data-sex',sex);
			$("#table5_tr2").children("."+btn_dore).eq(2).css('background','#fff');
$("#table5_tr2").children("."+btn_dore).eq(2).css('position','relative');
$("#table5_tr2").children("."+btn_dore).eq(2).css('border',all_bordercolor);
//ここまで

			if($("#table5_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
}
			
			
			if(who.indexOf("me_children") >= 0) {//////私＞子供の配偶者
			
			if(this_sex == "men"){//クリックした要素の性別の逆を設定する
			var sex_css = "sex_woman";
			var sex = "woman";
			}else if(this_sex == "men"){
			var sex_css = "sex_woman";
			var sex = "men";
			}
			
			
			$("#table6_tr1").children("."+btn_dore).eq(1).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"><img src="../img/line_yoko.png"></td>');
		$("#table6_tr2").children("."+btn_dore).eq(1).html('<img src="../img/line_yoko.png">');
		$("#table6_tr2").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');
			$("#table6_tr2").children("."+btn_dore).eq(1).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table6_tr2").children("."+btn_dore).eq(2).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table6_tr3").children("."+btn_dore).eq(1).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
			$("#table6_tr4").children("."+btn_dore).eq(1).after('<td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td><td class="me_children '+btn_dore+' '+btn_dore+'_haigusya"></td>');
$("#table6_tr3").children("."+btn_dore).eq(1).addClass(btn_dore+' '+btn_dore+'_haigusya');


$("#table6_tr2").children("."+btn_dore).eq(2).html("<span style='color:#000000;'>配偶者</span><div class='fff text_tuika'></div>");
//ここからCSS設定
$("#table6_tr2").children("."+btn_dore).eq(2).addClass('btn btn-success btn_search '+sex_css);
$("#table6_tr2").children("."+btn_dore).eq(2).attr('data-toggle','modal');
$("#table6_tr2").children("."+btn_dore).eq(2).attr('data-target','#modal_hyouzi');
$("#table6_tr2").children("."+btn_dore).eq(2).attr('id',btn_dore+"_haigusya");
$("#table6_tr2").children("."+btn_dore).eq(2).attr('data-sex',sex);
			$("#table6_tr2").children("."+btn_dore).eq(2).css('background','#fff');
$("#table6_tr2").children("."+btn_dore).eq(2).css('position','relative');
$("#table6_tr2").children("."+btn_dore).eq(2).css('border',all_bordercolor);
//ここまで

			if($("#table6_tr1 td").eq(0).offset().left < 0){//０より小さければ画面左端と判断できるのでtd追加
			for(var i=0;i<10;i++){
			$('#gyou'+i).children('.retu0').before('<td class="mother_kyoutu"></td><td class="mother_kyoutu"></td>');
			}
			}
			
}
			
			
			}
			
			}
			
			
			$(function () {
				$(document).on('click', 'a[data-toggle="tab"]', function() {
			
			
					if($(this).hasClass('active')){
						var active_text = $(this).text();
			}
			if(active_text ==  "癌種・発症年齢"){
						$("#clear").css("display","inline");
					}else {
						$("#clear").css("display","none");
					}
			
				})
			
			})
			
			$(function () {
				$(document).on('click', '#clear', function() {
			
			if($("#"+btn_dore).attr("data-sintou") == "0"){
			$("#"+btn_dore).css("background-color","#baeef7");
			$("#"+btn_dore).children("span").css("color","#000");
			}else if($("#"+btn_dore).attr("data-sintou") == "1"){
			$("#"+btn_dore).css("background-color",backcolor1);
			$("#"+btn_dore).children("span").css("color","#000");
			}else if($("#"+btn_dore).attr("data-sintou") == "2"){
			$("#"+btn_dore).css("background-color",backcolor2);
			}else if($("#"+btn_dore).attr("data-sintou") == "3"){
			$("#"+btn_dore).css("background-color",backcolor3);
			}else {
			$("#"+btn_dore).css("background-color","#fff");
			$("#"+btn_dore).children("span").css("color","#000");
			}
					/*$("#"+btn_dore).children("span").html("");*/
					$("#"+btn_dore).children("img").remove();
					$("#"+btn_dore).children("div").css("display","none");
					$("#"+btn_dore).children("div").html("");
				})
			})
			
			
			
			///ログイン後に性別設定
			$(function () {
				$(document).on('click', '#sex_kettei', function() {
			
				$("#me").attr("data-sex",$("#sex").val());
			
					if($("#sex").val() == "woman"){
						$("#me").css("border-radius","100px");
					}
				})
			
			})
			////ここまで
			
			$(function () {
				$(document).on('click', '#kettei', function() {
			
			var cancer_text="";
			
			$('a[data-toggle="tab"]').each(function () {
			if($(this).hasClass('active')){
			cancer_text = $(this).text();
			}
			});
			
			
			if(cancer_text == "癌種・発症年齢"){
			var text_go="";
			if($("#cancer_old option:selected").text() == "不明"){
			text_go="<span class='cancer_init'>"+$("#cancer option:selected").text()+"</span>:<span class='cancer_old_init'>年齢不明</span><br>";
			}else {
			text_go="<span class='cancer_init'>"+$("#cancer option:selected").text()+"</span>:<span class='cancer_old_init'>"+$("#cancer_old option:selected").text()+"</span><br>";
			}
			
			
			$('#'+btn_dore).css("background-color","#000");
			$('#'+btn_dore).children("span").css("color","#fff");
			$('#'+btn_dore).children('.fff').append(text_go);
			$('#'+btn_dore).children('.text_tuika').css("display","block");
			
			
			/*
			if($("#gyou"+this_gyou).children(".retu"+this_retu).children('.fff').text().length > 18){
			$("#gyou"+this_gyou).children(".retu"+this_retu).children('.fff').css('color','#ff0000');
			}
			*/
			
			return false;
			}
			
			
			on_kettei($('#family_tuika').val(),btn_dore);
			
			});
			});
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			$(function () {
				$(document).on('click', '#diagnose', function() {
			
			var element = document.getElementById("canvas_table");  //画像化したい要素
			
			/*window.scrollTo(0, 0);*/
			html2canvas(element,{
			scrollX: 0,
			scrollY: -70,//画面上部のヘッダーの高さ分を引く
			}).then(canvas => {
			
			// 現在日時を取得
			var today = new Date();
			// 年月日を取得
			var year = today.getFullYear();
			var month = today.getMonth() + 1;
			var date = today.getDate();
			
			// 時分秒を取得
			var hour = today.getHours();
			var minute = today.getMinutes();
			var second = today.getSeconds();
			
			// 現在時刻を表示する
			var now_time = String(year)+String(month)+String(date)+String(hour)+String(minute)+String(second);// 年月日時分秒
			
			
			
			//ここから家系図用データ
			var cancer_init=[];
			var cancer_old_init=[];
			var cancer_sintou=[];
			var cancer_sex=[];
			var cancer_id=[];
			
			$(".cancer_init").each(function(){
			if($(this).parent().parent().data("sintou") != undefined){
			cancer_init.push($(this).text());
			cancer_sintou.push($(this).parent().parent().data("sintou"));
			cancer_sex.push($(this).parent().parent().data("sex"));
			cancer_id.push($(this).parent().parent().attr("id"));
			}
			});
			$(".cancer_old_init").each(function(){
			if($(this).parent().parent().data("sintou") != undefined){
			cancer_old_init.push($(this).text());
			}
			});
			
			console.log(cancer_init);
			console.log(cancer_old_init);
			console.log(cancer_sintou);
			console.log(cancer_sex);
			console.log(cancer_id);
			
			var json_cancer_init = JSON.stringify(cancer_init);
			console.log(json_cancer_init);
			//ここまで家系図用データ
			
			//下記２つのデータを送信する
			$("#cancer_init").val(cancer_init.join(','));
			$("#cancer_old_init").val(cancer_old_init.join(','));
			$("#cancer_sintou").val(cancer_sintou.join(','));
			$("#cancer_sex").val(cancer_sex.join(','));
			$("#cancer_id").val(cancer_id.join(','));
			$("#now_time").val(now_time);
			$("#canvas_img").val(canvas.toDataURL("image/jpeg",0.75));
			
			document.send_form.submit();
			
			});
			
			});
			});
			
			
			/*
			$(function () {
			$(document).on('click', '#diagnose', function() {
			var cancer_init=[];
			var cancer_old_init=[];
			var cancer_sintou=[];
			var cancer_sex=[];
			var cancer_id=[];
			
			$(".cancer_init").each(function(){
			cancer_init.push($(this).text());
			cancer_sintou.push($(this).parent().parent().data("sintou"));
			cancer_sex.push($(this).parent().parent().data("sex"));
			cancer_id.push($(this).parent().parent().attr("id"));
			});
			$(".cancer_old_init").each(function(){
			cancer_old_init.push($(this).text());
			});
			
			console.log(cancer_init);
			console.log(cancer_old_init);
			console.log(cancer_sintou);
			console.log(cancer_sex);
			console.log(cancer_id);
			
			["何親等か","識別id","診断された癌名","診断年齢","性別"]
			var logic_cancer1 = [
								["0","me","cancer1","old1",""],
								["0","me","cancer1","",""],//２回ヒットすること
								["0","me","cancer1","","men"],
								["1","","cancer1","","men"],
								["2","","cancer1","","men"],
								["3","","cancer1","","men"],
								]
			
			
			
			
			for(var i=0;i<cancer_sintou;i++){
			if(cancer_sintou[i] == "0"&&cancer_init[i] == "cancer1"){
			if(cancer_sintou[i] == 1||cancer_sintou[i] == 2||cancer_sintou[i] == 3){
			if(cancer_init[i] == "cancer1"||cancer_init[i] == "cancer2"){
			kekka = "乳癌";
			}}}
			}
	
			})
			});
			*/
			
			
			$(window).on('load',function(){
			
			$("#login_sex").click();
			
			});
			
			
		</script>
		
</body>
</html>