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
				width:2400px;
				vertical-align:middle;
			}
			
			.kekka_table2{
				table-layout:fixed;
				width:320px;
			}
			
			.kekka_table th,
			.kekka_table td,
			.kekka_table2 th,
			.kekka_table2 td{
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
			
			/*.display_hyouzi1,
			.display_hyouzi2{
				visibility:hidden;
				}
		*/
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
			
		</style>
		
		<title>トップページ</title>
		
	</head>
	<body id="canvas_table">
		<table class="kekka_table">
			<?php 
			for($i=0;$i<7;$i++){
				echo "<tr id='gyou".$i."' data-this_tr='".$i."'>";
				for($z=0;$z<33;$z++){
					if($i==6&&$z==24){
					echo "<td class='retu".$z." btn btn-success btn_search' style='background:#ff0000; color:#fff; border-radius:100px;' data-toggle='modal' data-target='#modal_hyouzi' id='me' data-this_td='".$z."' data-sintou='0'>あなた</td>";
				}else {
					echo "<td class='retu".$z."'></td>";
				}
			}
			echo "</tr>";
		}
		?>
	</table>
	
	
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
								<option value="" selected>乳癌</option>
								<option value="">卵巣癌/腹膜癌/卵管癌</option>
								<option value="">子宮体癌</option>
								<option value="">前立腺癌</option>
								<option value="">膵癌</option>
								<option value="">大腸癌</option>
								<option value="">胃癌</option>
								<option value="">小腸癌</option>
								<option value="">胆道癌</option>
								<option value="">泌尿器癌（前立腺癌を含めない）</option>
								<option value="">その他の癌</option>
							</select>
							<div class="text-left mb-1">診断年齢</div>
							<select class="form-control" id="cancer_old">
								<option value="" selected>４５歳以下</option>
								<option value="">４５歳〜５０歳</option>
								<option value="">５１歳以上</option>
								<option value="">不明</option>
							</select>
						</div>
						<div id="contents3" class="tab-pane">
							<div class="text-left mb-1">親族</div>
							<select class="form-control" id="family_tuika">
								<option value="oya" selected>父母</option>
								<option value="kyoudai">兄弟姉妹</option>
								<option value="haigusya">配偶者</option>
								<option value="children">子供</option>
								<option value="" disabled></option>
								<option value="delete">削除</option>
								<option value="death">死亡</option>
							</select>
							
						</div>
					</div>
					
					
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-secondary" data-dismiss="modal">キャンセル</a>
					<a class="btn btn-success" id="kettei" data-dismiss="modal">決定</a>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="p_fixed clearfix">
		<span class="float-left">JCRAS-PC</span>
		<a id="getImage" href="" style="display: none"></a>
		<form method="POST" name="send_form" action="judgment_result.php"><input type="hidden" id="canvas_img" name="canvas_img" value=""><input type="hidden" id="now_time" name="now_time" value=""><button type="button" class="btn btn-primary float-right" style="padding:10px 50px;" id="diagnose">リスク評価判定</button></form>
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


<script src="../js/jquery-js.js?2022ffgsffsfsgdgsfdfffdsffsffffsssczfdfsfsdffssfsfsfsadsaaa"></script>
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
	$("html,body").animate({scrollLeft:$('#me').offset().left-250});
	
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
	var backcolor0 ="#ff0000";//０親等背景色
	var backcolor1 ="#75ba8d";//１親等背景色
	var backcolor2 ="#c39bc6";//２親等背景色
	var backcolor3 ="#4780c3";//３親等背景色
	var all_bordercolor = "1px solid #000";//ボーダー色
	var oya_oya="";
	var oya=0;
	
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
	
	select_html+='<option value="oya" selected>父母</option>';
	select_html+='<option value="kyoudai">兄弟姉妹</option>';
	select_html+='<option value="haigusya">配偶者</option>';
	select_html+='<option value="children">子供</option>';
	select_html+='<option value="" disabled></option>';
	select_html+='<option value="delete">削除</option>';
	select_html+='<option value="death">死亡</option>';
	
	$('#family_tuika').html(select_html);
	
	if(btn_dore == "me"){
	if(!($("#me_haigusya").length)){//押したボタンid名の配偶者ボタンがなければ
	$('#family_tuika option[value="children"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("selected",true);
	}else {//押したボタンid名の配偶者ボタンがあれば
	$('#family_tuika option[value="haigusya"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("selected",true);
	}
	if($("#me_father").length&&$("#me_mother").length){//押したボタンid名の両親ボタンがあれば
	$('#family_tuika option[value="oya"]').prop("disabled",true);
	$('#family_tuika option[value="children"]').prop("selected",true);
	}
	}
	
	if(btn_dore.indexOf("_haigusya") > -1){//押したボタンid名にhaigusyaが含まれていれば
	$('#family_tuika option[value="haigusya"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("disabled",true);
	$('#family_tuika option[value="children"]').prop("selected",true);
	}else if(btn_dore.indexOf("me_child") > -1){//押したボタンid名にme_childが含まれていて
	if(!($("#"+btn_dore+"_haigusya").length)){//押したボタンid名の配偶者ボタンがなければ
	$('#family_tuika option[value="children"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("disabled",true);
	$('#family_tuika option[value="haigusya"]').prop("selected",true);
	}else {//押したボタンid名の配偶者ボタンがあれば
	$('#family_tuika option[value="haigusya"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("disabled",true);
	$('#family_tuika option[value="children"]').prop("selected",true);
	}
	}else if(this_text == "父母"){
	$('#family_tuika option[value="haigusya"]').prop("disabled",true);
	}else if(this_text == "兄弟"){
	$('#family_tuika option[value="children"]').prop("disabled",true);
	$('#family_tuika option[value="oya"]').prop("disabled",true);
	}
	
	})
	
	})
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function haigusya_tuika(main_line,bordercolor){
	
	
	
	if(main_line == "me_haigusya"){
	
	var men_name = "配偶者";
	var men_id_settei="me_haigusya";
	
	for(var i=1;i<16;i++){
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).html("<img src='../img/line_yoko.png' class='max-img'>");
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).attr('data-this_td',this_retu-i);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).addClass(men_id_settei);
	}
	
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).html(men_name);
	//ここからCSS設定
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).addClass('btn btn-success btn_search '+men_id_settei);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-toggle','modal');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-target','#modal_hyouzi');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-this_td',this_retu-16);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('id',men_id_settei);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).css('background','#6679ff');
	//ここまで
	}
	
	
	if(main_line == "me_children"){
	
	var men_name = "配偶者";
	var bordercolor = "5px solid #000";//1親等
	
	var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
	class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");
	
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_yoko.png' class='max-img'>");
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).attr('data-this_td',this_retu+1);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).html(men_name);
	
	//ここからCSS設定
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).addClass(class_delete_kekka+" "+btn_dore+"_haigusya");
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).addClass('btn btn-success btn_search '+class_delete_kekka);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-toggle','modal');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-target','#modal_hyouzi');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-this_td',this_retu+2);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-sintou','1');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-child',me_children_count);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('id',btn_dore+'_haigusya');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).css('background',backcolor1);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).css('border',all_bordercolor);
	//ここまで
	
	}
	
	
	if(main_line == "me_mago"){
	
	var men_name = "配偶者";
	var bordercolor = "5px solid #FFFF00";//2親等
	
	
	var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
	class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");
	
	
	
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_yoko.png' class='max-img'>");
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).attr('data-this_td',this_retu+1);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).html(men_name);
	
	//ここからCSS設定
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).addClass(class_delete_kekka+' '+btn_dore+' '+btn_dore+'_haigusya');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).addClass('btn btn-success btn_search '+class_delete_kekka+' '+btn_dore);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-toggle','modal');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-target','#modal_hyouzi');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-this_td',this_retu+2);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-sintou','2');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('data-child',me_children_count);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).attr('id',btn_dore+'_haigusya');
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).css('background',backcolor2);
	$('#gyou'+(this_gyou)).children('.retu'+(this_retu+2)).css('border',all_bordercolor);
	//ここまで
	
	}
	
	
	if(main_line == "me_brother_mago"){
	
	var men_name = "配偶者";
	var bordercolor = "5px solid #FFFF00";//2親等
	
	
	var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
	class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");
	
	
	
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+1)).html("<img src='../img/line_yoko.png' class='max-img'>");
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+1)).attr('data-this_td',this_retu+1);
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).html(men_name);
	
	//ここからCSS設定
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+1)).addClass(class_delete_kekka+' '+btn_dore+' '+btn_dore+'_haigusya');
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).addClass('btn btn-success btn_search '+class_delete_kekka+' '+btn_dore);
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('data-toggle','modal');
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('data-target','#modal_hyouzi');
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('data-this_td',this_retu+2);
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('data-sintou','2');
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('data-child',me_children_count);
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).attr('id',btn_dore+'_haigusya');
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).css('background',backcolor2);
	$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+2)).css('border',all_bordercolor);
	//ここまで
	
	}
	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function children_tuika(main_line,what_sintou,bordercolor,me_children_mago_count){
	
	
	var kara1="";
	var under_bar=[];
	var under_bar_usiro=[];
	var under_bar_usiro_split=[];
	var under_bar_usiro_img=[];
	var under_bar_usiro_split_img=[];
	var kekka1=[];
	var btn_dore_delete_kekka_split=[];
	var kekka2=[];
	var kekka_nokori_last=[];
	var kekka_last=[];
	var kekka_img_nokori_last=[];
	var kekka_img_last=[];
	var child_count_array_img=[];
	
	
	if($('#gyou'+(this_gyou+1)).children('.retu15').html() == undefined){
	var kara1 ="空っぽ";
	}
	if($('#gyou'+(this_gyou+1)).children('.retu15').css("visibility") == "hidden"){
	var kara1 ="空っぽ";
	}
	
	/*main_line = "me_children";
	what_sintou = "孫";
	bordercolor = "5px solid #000";//1親等 "5px solid #FFFF00";//2親等 "5px solid #FF0000";//3親等
	me_children_mago_count//それぞれ子供ごとのカウンター
	*/
	
	var men_id_settei="me_child"+this_child_number+"_"+me_children_mago_count;
	var haigusya = " me_child"+this_child_number+"_haigusya";
	var bordercolor = bordercolor;
	var on="";
	var html="";
	var men_name="";
	
	if(btn_dore.indexOf("_haigusya") > -1){
	search_counter = btn_dore.replace("_haigusya","");
	}else {
	search_counter = btn_dore;
	}
	
	
	//all_counterは連想配列、search_counterはボタンクリックした時のid名、all_counter[search_counter]はカウントアップ用の数字（初期値は下記にそれぞれ１に設定）
	if(search_counter in all_counter == true){//既にid名のkeyがあれば数字を１追加していく
	all_counter[search_counter]++;
	men_name = what_sintou+all_counter[search_counter];
	}else {//初めてなら数字を１の初期値に設定する
	all_counter[search_counter] = 1;
	men_name = what_sintou+all_counter[search_counter];
	}
	
	
	
	
	
	//////////////////////////////////////////////////////////////
	//////////////////////重要////////////////////////////////////
	if(tuika_kyoutu == undefined){
	var tuika_kyoutu = $(".me_children:last").parent().data("this_tr");
	}else {//子供を追加した時の、一つ前の既に表示されてる子供の親の列番号取得
	var tuika_kyoutu = $("#"+btn_dore+"_"+(all_counter[search_counter]-1)).parent().data("this_tr");
	}
	//////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////
	//配偶者(haigusya)ボタンじゃなければ血族/////////////////////////
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	if(btn_dore.indexOf("haigusya") <= 0 ){//押したボタンのid名に「haigusya」が含まれていなければ
	
	var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
	class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");
	
	html+="<tr id='gyou"+(this_gyou+1)+"' data-this_tr='"+(this_gyou+1)+"'>";
	for(var i=0;i<33;i++){
	if(i==this_retu+1){
html+="<td class='retu"+(this_retu+1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu+1)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-1){//孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-1)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-3&&main_line == "me_mago"){//ひ孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-3)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-3)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else {
html+="<td class='retu"+i+"'></td>";
}
}
html+="</tr>";

html+="<tr id='gyou"+(this_gyou+2)+"' data-this_tr='"+(this_gyou+2)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu+2){
html+="<td class='retu"+(this_retu+2)+"'>"+men_name+"</td>";
}else if(i==this_retu+1){
html+="<td class='retu"+(this_retu+1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu+1)+"><img src='../img/line_men_brother.png' class='max-img'></td>";
}else if(i==this_retu-1){//孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-1)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-3&&main_line == "me_mago"){//ひ孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-3)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore+"_haigusya "+btn_dore+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-3)+"><img src='../img/line_tate.png' class='max-img'></td>";

}else {
html+="<td class='retu"+i+"'></td>";
}
}
html+="</tr>";


$(html).insertAfter("#gyou"+this_gyou);

if(all_counter[search_counter] == 1){//カウント１の時は画像を夫婦に変更
$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

child_count_array.push(btn_dore+'_'+all_counter[search_counter]);


var btn_dore_delete_kekka_usiro = btn_dore.slice(8);//これでme_childより後ろを全て取得　例　）2
btn_dore_delete_kekka_split = btn_dore_delete_kekka_usiro.split('_');//これでme_childより後ろの連番数字を分割



for(var i=0;i<child_count_array.length;i++){
if(this_sintou == 1){//クリックしたボタンが親等１の場合で
if(child_count_array[i].match(/_/g || []).length == 2){//「 _ 」が１つあれば
under_bar.push(child_count_array[i]);//「 _ 」が１つの配列を全て入れる
child_count_array_img.push(child_count_array[i]);
}
}else if(this_sintou == 2){//クリックしたボタンが親等２の場合で
if(child_count_array[i].match(/_/g || []).length == 3){//「 _ 」が２つあれば
under_bar.push(child_count_array[i]);//「 _ 」が２つの配列を全て入れる
}
if(child_count_array[i].match(/_/g || []).length == 2){//「 _ 」が１つあれば
child_count_array_img.push(child_count_array[i]);
}
}
}

/*console.log(under_bar);//親等１の場合["me_child1_1","me_child2_1"]など、親等２の場合["me_child1_1_1","me_child2_2_1"]などになる*/


if(this_sintou == 1){//クリックしたボタンが親等１の場合

for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

/*for(var i=0;i<under_bar_usiro.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}*/

for(var i=0;i<under_bar_usiro.length;i++){
if("me_child"+under_bar_usiro[i][0] == "me_child"+btn_dore_delete_kekka_split[0]){
kekka1.push(under_bar_usiro[i]);
}
}

if(kekka1.length == 1){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

}else if(this_sintou == 2){//クリックしたボタンが親等2の場合


/*console.log("me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]);*/


for(var i=0;i<child_count_array_img.length;i++){//配列を全てループさせる
under_bar_usiro_img.push(child_count_array_img[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro_img.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split_img.push(under_bar_usiro_img[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}

/*console.log(under_bar_usiro_split_img);*/

for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1_1"、"2_1_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro.length;i++){//["1_1_1"、"2_1_1"]などループ
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1","1"],["2","1","1"]]などを取得できる
}

for(var i=0;i<under_bar_usiro_split.length;i++){
if("me_child"+under_bar_usiro_split[i][0]+"_"+under_bar_usiro_split[i][1] == "me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]){
kekka1.push(under_bar_usiro_split[i]);
}
}

if(kekka1.length == 1){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

}




//ここからCSS設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).addClass('btn btn-success btn_search '+class_delete_kekka+' '+btn_dore+' '+btn_dore+'_haigusya');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('data-this_td',this_retu+2);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('data-sintou',this_sintou+1);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('data-child',me_children_mago_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).attr('id',btn_dore+'_'+all_counter[search_counter]);
if(this_sintou == 1){
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).css('background',backcolor2);
}else if(this_sintou == 2){
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).css('background',backcolor3);
}
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+2)).css('border',all_bordercolor);
//ここまで


if(kara1 == "空っぽ"){
$('#gyou'+(this_gyou+1)).children('.retu15').css("visibility","hidden");
$('#gyou'+(this_gyou+2)).children('.retu15').css("visibility","hidden");
}

for(var i=0; i<under_bar_usiro_split_img.length; i++){
if(Number(btn_dore_delete_kekka_split[0]) == under_bar_usiro_split_img[i][0]){
if(Number(btn_dore_delete_kekka_split[1]) < under_bar_usiro_split_img[i][1]){
kekka_img_last.push(under_bar_usiro_split_img[i]);
}
if(Number(btn_dore_delete_kekka_split[1]) > under_bar_usiro_split_img[i][1]){
kekka_img_nokori_last.push(under_bar_usiro_split_img[i]);
}
}
}

/*console.log(kekka_img_last);//クリックした要素より上を取得はこっち（クリックした要素が一番上なら[]になる）
console.log(kekka_img_nokori_last);//クリックして要素が一番下の場合はこっち（クリックした要素が一番下なら[]になる）
*/

if(this_sintou == 2){
if(kekka_img_nokori_last.length == ""){//クリックした要素が一番下だったら
$('#gyou'+(this_gyou+1)).children('.retu17').css("visibility","hidden");
$('#gyou'+(this_gyou+2)).children('.retu17').css("visibility","hidden");
}
}

$(".kekka_table tr").each(function(index){
$(this).attr("id","gyou"+index);
});

}else {

//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//配偶者(haigusya)が含まれていれば配偶者をクリックした事になるので下記を実行
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////

var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");

var btn_dore_delete = btn_dore.replace("_haigusya","");


html+="<tr id='gyou"+(this_gyou+1)+"' data-this_tr='"+(this_gyou+1)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu-1){
html+="<td class='retu"+(this_retu-1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-1)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-3){//孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-3)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-3)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-5&&main_line == "me_mago"){//ひ孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-5)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-5)+"><img src='../img/line_tate.png' class='max-img'></td>";

}else {
html+="<td class='retu"+i+"'></td>";
}
}
html+="</tr>";

html+="<tr id='gyou"+(this_gyou+2)+"' data-this_tr='"+(this_gyou+2)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu){
html+="<td class='retu"+(this_retu)+"'>"+men_name+"</td>";
}else if(i==this_retu-1){
html+="<td class='retu"+(this_retu-1)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-1)+"><img src='../img/line_men_brother.png' class='max-img'></td>";
}else if(i==this_retu-3){//孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-3)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-3)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==this_retu-5&&main_line == "me_mago"){//ひ孫が追加されたら子供の縦列に連動して縦画像を表示
html+="<td class='retu"+(this_retu-5)+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete+"_"+all_counter[search_counter]+"' data-this_td="+(this_retu-5)+"><img src='../img/line_tate.png' class='max-img'></td>";

}else {
html+="<td class='retu"+i+"'></td>";
}
}
html+="</tr>";

$(html).insertAfter("#gyou"+this_gyou);



if(all_counter[search_counter] == 1){//カウント１の時は画像を夫婦に変更
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

child_count_array.push(btn_dore.replace("_haigusya","")+'_'+all_counter[search_counter]);

var btn_dore_delete_kekka_usiro = btn_dore.replace("_haigusya","").slice(8);//これでme_childより後ろを全て取得　例　）2
btn_dore_delete_kekka_split = btn_dore_delete_kekka_usiro.split('_');//これでme_childより後ろの連番数字を分割

for(var i=0;i<child_count_array.length;i++){
if(this_sintou == 1){//クリックしたボタンが親等１の場合で
if(child_count_array[i].match(/_/g || []).length == 2){//「 _ 」が１つあれば
under_bar.push(child_count_array[i]);//「 _ 」が１つの配列を全て入れる
child_count_array_img.push(child_count_array[i]);
}
}else if(this_sintou == 2){//クリックしたボタンが親等２の場合で
if(child_count_array[i].match(/_/g || []).length == 3){//「 _ 」が２つあれば
under_bar.push(child_count_array[i]);//「 _ 」が２つの配列を全て入れる
}
if(child_count_array[i].match(/_/g || []).length == 2){//「 _ 」が１つあれば
child_count_array_img.push(child_count_array[i]);
}
}
}

/*console.log(under_bar);//親等１の場合["me_child1_1","me_child2_1"]など、親等２の場合["me_child1_1_1","me_child2_2_1"]などになる*/


if(this_sintou == 1){//クリックしたボタンが親等１の場合

for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

/*for(var i=0;i<under_bar_usiro.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}*/

for(var i=0;i<under_bar_usiro.length;i++){
if("me_child"+under_bar_usiro[i][0] == "me_child"+btn_dore_delete_kekka_split[0]){
kekka1.push(under_bar_usiro[i]);
}
}

if(kekka1.length == 1){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

}else if(this_sintou == 2){//クリックしたボタンが親等１の場合

/*console.log("me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]);*/

for(var i=0;i<child_count_array_img.length;i++){//配列を全てループさせる
under_bar_usiro_img.push(child_count_array_img[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro_img.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split_img.push(under_bar_usiro_img[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}


for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1_1"、"2_1_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro.length;i++){//["1_1_1"、"2_1_1"]などループ
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1","1"],["2","1","1"]]などを取得できる
}

for(var i=0;i<under_bar_usiro_split.length;i++){
if("me_child"+under_bar_usiro_split[i][0]+"_"+under_bar_usiro_split[i][1] == "me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]){
kekka1.push(under_bar_usiro_split[i]);
}
}



if(kekka1.length == 1){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}

}


//ここからCSS設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).addClass('btn btn-success btn_search '+class_delete_kekka+' '+btn_dore);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-this_td',this_retu);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-sintou',this_sintou+1);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-child',me_children_mago_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('id',btn_dore_delete+'_'+all_counter[search_counter]);
if(this_sintou == 1){
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).css('background',backcolor2);
}else if(this_sintou == 2){
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).css('background',backcolor3);
}
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).css('border',all_bordercolor);
//ここまで

if(kara1 == "空っぽ"){
$('#gyou'+(this_gyou+1)).children('.retu15').css("visibility","hidden");
$('#gyou'+(this_gyou+2)).children('.retu15').css("visibility","hidden");
}


for(var i=0; i<under_bar_usiro_split_img.length; i++){
if(Number(btn_dore_delete_kekka_split[0]) == under_bar_usiro_split_img[i][0]){
if(Number(btn_dore_delete_kekka_split[1]) < under_bar_usiro_split_img[i][1]){
kekka_img_last.push(under_bar_usiro_split_img[i]);
}
if(Number(btn_dore_delete_kekka_split[1]) > under_bar_usiro_split_img[i][1]){
kekka_img_nokori_last.push(under_bar_usiro_split_img[i]);
}
}
}

/*console.log(kekka_img_last);//クリックした要素より上を取得はこっち（クリックした要素が一番上なら[]になる）
console.log(kekka_img_nokori_last);//クリックして要素が一番下の場合はこっち（クリックした要素が一番下なら[]になる）
*/

if(this_sintou == 2){
if(kekka_img_nokori_last.length == ""){//クリックした要素が一番下だったら
$('#gyou'+(this_gyou+1)).children('.retu17').css("visibility","hidden");
$('#gyou'+(this_gyou+2)).children('.retu17').css("visibility","hidden");
}
}

$(".kekka_table tr").each(function(index){
$(this).attr("id","gyou"+index);
});

}


}





















function on_kettei(syurui,who){

//syuruiは親族追加のセレクトボックスの選択肢したvalue値
//whoは各親族ボタンをクリックした際のどのid名かを取得



///////////////////////////////////////////////
///////////////////////////////////////////////
////////////ここから死亡を選択時//////////////////
///////////////////////////////////////////////
///////////////////////////////////////////////

if(syurui == "death"){
$('#gyou'+this_gyou).children('.retu'+this_retu).append("<img src='../img/death.png' style='position:absolute; top:-10px; left:-10px;'>");

}


///////////////////////////////////////////////
///////////////////////////////////////////////
////////////ここから削除を選択時//////////////////
///////////////////////////////////////////////
///////////////////////////////////////////////

if(syurui == "delete"){

var idid=[];
var under_bar=[];
var under_bar_usiro=[];
var under_bar_usiro_split=[];
var btn_dore_delete_kekka_split=[];
var kekka_yo=[];
var hit_parent_gyou="";
var hit_parent_gyou_sort=[];
var kekka1=[];
var kekka2=[];
var kekka1_brother=[];
var kekka2_brother=[];
var kekka_last=[];
var kekka_nokori_last=[];
var html_td_tuika="";
var html_td_tuika2="";
var tete=[];
var under_bar_usiro_brother=[];
var kekka_img_nokori_last_brother=[];
var kekka_img_last_brother=[];
var under_bar_usiro_split_brother=[];


var btn_dore_delete_kekka_usiro = btn_dore.slice(8);//これでme_childより後ろを全て取得　例　）2
btn_dore_delete_kekka_split = btn_dore_delete_kekka_usiro.split('_');//これでme_childより後ろの連番数字を分割

for(var i=0;i<child_count_array.length;i++){
child_count_array = child_count_array.filter(function( item ) {//ヒットした値以外を取得
var ttt = new RegExp("^"+btn_dore);
return !item.match(ttt);
});
}


for(var i=0;i<child_count_array_brother.length;i++){
child_count_array_brother = child_count_array_brother.filter(function( item ) {//ヒットした値以外を取得
var ttt = new RegExp("^"+btn_dore);
return !item.match(ttt);
});
}




for(var i=0;i<child_count_array.length;i++){
if(this_sintou == 1){//クリックして削除したボタンが親等１の場合で
if(child_count_array[i].match(/_/g || []).length == 1){//「 _ 」が１つあれば
under_bar.push(child_count_array[i]);//「 _ 」が１つの配列を全て入れる
}
}else if(this_sintou == 2){//クリックして削除したボタンが親等２の場合で
if(child_count_array[i].match(/_/g || []).length == 2){//「 _ 」が２つあれば
under_bar.push(child_count_array[i]);//「 _ 」が２つの配列を全て入れる
}
}else if(this_sintou == 3){//クリックして削除したボタンが親等３の場合で
if(child_count_array[i].match(/_/g || []).length == 3){//「 _ 」が３つあれば
under_bar.push(child_count_array[i]);//「 _ 」が３つの配列を全て入れる
}
}
}

/*
for(var i=0;i<child_count_array_brother.length;i++){
if(this_sintou == 3){//クリックして削除したボタンが親等３の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 2){//「 _ 」が2つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が2つの配列を全て入れる
}
}else if(this_sintou == 2){//クリックして削除したボタンが親等2の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 1){//「 _ 」が1つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が1つの配列を全て入れる
}
}else if(this_sintou == 1){//クリックして削除したボタンが親等2の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 1){//「 _ 」が1つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が1つの配列を全て入れる
}
}
}
*/


if(this_sintou == 3&&btn_dore.indexOf("haigusya") <= 0 ){//クリックして削除したボタンが親等３の場合

if(btn_dore.indexOf("_brother") > -1){//クリックしたボタンのid名にbrotherが含まれていたら

for(var i=0;i<child_count_array_brother.length;i++){
if(child_count_array_brother[i].match(/_/g || []).length == 2){//「 _ 」が2つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が2つの配列を全て入れる
}
}

var btn_dore_delete_kekka_usiro = btn_dore.slice(10);//これでme_brotherより後ろを全て取得　例　）2
btn_dore_delete_kekka_split_brother = btn_dore_delete_kekka_usiro.split('_');//これでme_brotherより後ろの連番数字を分割

for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(under_bar[i].slice(10));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro_brother.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split_brother.push(under_bar_usiro_brother[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}

for(var i=0;i<under_bar_usiro_split_brother.length;i++){//[["1","1"],["2","1"]]を全てループ
if("me_brother"+under_bar_usiro_split_brother[i][0] == "me_brother"+btn_dore_delete_kekka_split_brother[0]){//例えば、"me_brother1_1"とクリックしたボタンのid名の"me_brother1_1"が同じだったら
kekka1_brother.push(under_bar_usiro_split_brother[i]);//その同じだった配列以外を取得　例）[["2","1"]];など
kekka2_brother.push(under_bar_usiro_split_brother[i][2]);//配列には["1","3"]などが入ってる
}
}




for(var i=0; i<under_bar_usiro_split_brother.length; i++){
if(Number(btn_dore_delete_kekka_split_brother[1]) < under_bar_usiro_split_brother[i][1]){
kekka_img_last_brother.push(under_bar_usiro_split_brother[i]);
}
if(Number(btn_dore_delete_kekka_split_brother[1]) > under_bar_usiro_split_brother[i][1]){
kekka_img_nokori_last_brother.push(under_bar_usiro_split_brother[i]);
}//クリックした要素が一番下が空かどうか
}



if(kekka_img_nokori_last_brother.length == ""){
$("#gyou_men_brother"+(this_gyou_brother-2)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_right_top.png");
}
if(kekka1_brother.length == 0){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$("#gyou_men_brother"+(this_gyou_brother-2)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_yoko.png");
}


}else {

for(var i=0;i<under_bar.length;i++){//「 _ 」が３つの配列を全てループさせる
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）["1_1_2"、"2_1_1"]などが取得できる
}

for(var i=0;i<under_bar_usiro.length;i++){//["1_1_2"、"2_1_1"]などループ
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1","2"],["2","1","1"]]などを取得できる
}


for(var i=0;i<under_bar_usiro_split.length;i++){//[["1","1","2"],["2","1","1"]]を全てループ
if("me_child"+under_bar_usiro_split[i][0]+"_"+under_bar_usiro_split[i][1] == "me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]){//例えば、"me_child1_1"とクリックしたボタンのid名の"me_child1_1"が同じだったら
kekka1.push(under_bar_usiro_split[i]);//その同じだった配列以外を取得　例）[["2","1","1"]];など
kekka2.push(under_bar_usiro_split[i][2]);//配列には["1","3","4,"]などが入ってる
}
}


if(kekka1.length == 0){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$("#gyou"+(this_gyou-2)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_yoko.png");
}

for(var i=0;i<kekka2.length;i++){//配列["1","3","4,"]などでループ
if(kekka2[i] < btn_dore_delete_kekka_split[2]){//btn_dore_delete_kekka_splitはクリックした自身のid名を分割した配列。例えばクリックした削除ボタンのid名が「me_child1_1_2」だったらbtn_dore_delete_kekka_splitには["1","1","2"]が入ってる
kekka_last.push(Number(kekka2[i]));
}
if(kekka2[i] > btn_dore_delete_kekka_split[2]){
kekka_nokori_last.push(Number(kekka2[i]));
}
}

/*console.log(kekka_last);//クリックして削除した要素が一番下の場合はこっち（クリックした要素が一番下なら[]になる）
console.log(kekka_nokori_last);//クリックして削除した要素より上を取得はこっち（クリックした要素が一番上なら[]になる）
*/

if(kekka_last.length == ""){//クリックした要素が一番下だったら
/*Math.min.apply(null,kekka_nokori_last);*///同じ孫階級で一番若い数字を取得
var one_number = $("#me_child"+btn_dore_delete_kekka_split[0]+"_"+btn_dore_delete_kekka_split[1]+"_"+Math.min.apply(null,kekka_nokori_last)).parent().attr("id");
if(one_number){
var zan = (this_gyou-2) - Number(one_number.replace("gyou",""));//（クリックした行−２）ー（一番若い数字の行番号）結果例 3
$("#gyou"+((this_gyou-2)-zan)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_right_top.png");
}

}

}

}else if(this_sintou == 2&&btn_dore.indexOf("haigusya") <= 0 ){//クリックして削除したボタンが親等２の場合＆押したボタンのid名に「haigusya」が含まれていなくて

if(btn_dore.indexOf("_brother") > -1){//クリックしたボタンのid名にbrotherが含まれていたら

alert("koko");

var btn_dore_delete_kekka_usiro = btn_dore.slice(10);//これでme_brotherより後ろを全て取得　例　）2
btn_dore_delete_kekka_split_brother = btn_dore_delete_kekka_usiro.split('_');//これでme_brotherより後ろの連番数字を分割

for(var i=0;i<child_count_array_brother.length;i++){
if(child_count_array_brother[i].match(/_/g || []).length == 1){//「 _ 」が1つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が1つの配列を全て入れる
}
}

console.log(child_count_array);
console.log(child_count_array_brother);
console.log(under_bar);

for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(under_bar[i].slice(10));//これでme_brotherより後ろを全て取得　例　）["1_1_1"、"2_1_1"]などが取得できる
}



for(var i=0; i<under_bar_usiro_brother.length; i++){
if(Number(btn_dore_delete_kekka_split_brother[0]) < under_bar_usiro_brother[i][0]){
kekka_img_last_brother.push(under_bar_usiro_brother[i]);
}
if(Number(btn_dore_delete_kekka_split_brother[0]) > under_bar_usiro_brother[i][0]){
kekka_img_nokori_last_brother.push(under_bar_usiro_brother[i]);
}//クリックした要素が一番下が空かどうか
}

/*console.log(kekka_img_nokori_last_brother);*/

if(kekka_img_nokori_last_brother.length == ""){
/*console.log(Math.min.apply(null,kekka_img_last_brother));//同じ孫階級で一番若い数字を取得*/
var one_number = $("#me_brother"+Math.min.apply(null,kekka_img_last_brother)).parent().attr("id");//同じ孫階級で一番若い数字の親のid名取得
if(one_number){//クリックした要素の数字より上の要差があれば
var zan = (this_gyou_brother-2) - Number(one_number.replace("gyou_men_brother",""));//（クリックした行−２）ー（一番若い数字の行番号）結果例 3

for(var i=0;i<zan;i++){
$("#gyou_men_brother"+((this_gyou_brother-2)-i)).children(".retu"+(this_retu-1)).css("visibility","hidden");
}
$("#gyou_men_brother"+((this_gyou_brother-2)-zan)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_right_top.png");
}
}

}else {


for(var i=0;i<under_bar.length;i++){
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）2
}

for(var i=0;i<under_bar_usiro.length;i++){
under_bar_usiro_split.push(under_bar_usiro[i].split('_'));//これでme_childより後ろの連番数字を分割
}

for(var i=0;i<under_bar_usiro_split.length;i++){
if("me_child"+under_bar_usiro_split[i][0] == "me_child"+btn_dore_delete_kekka_split[0]){
kekka1.push(under_bar_usiro_split[i][0]);
kekka2.push(under_bar_usiro_split[i][1]);
}
}

if(kekka1.length == 0){//同じ孫階級を全て削除したら配偶者の横の画像を横ラインに戻す
$("#gyou"+(this_gyou-2)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_yoko.png");
}

for(var i=0;i<kekka2.length;i++){
if(kekka2[i] < btn_dore_delete_kekka_split[1]){
kekka_last.push(Number(kekka2[i]));
}
if(kekka2[i] > btn_dore_delete_kekka_split[1]){
kekka_nokori_last.push(Number(kekka2[i]));
}
}

/*console.log(kekka_last);//クリックして削除した要素が一番下の場合はこっち（クリックした要素が一番下なら[]になる）
console.log(kekka_nokori_last);//クリックして削除した要素より上を取得はこっち（クリックした要素が一番上なら[]になる）
*/

if(kekka_last.length == ""){//クリックした要素が一番下だったら
Math.min.apply(null,kekka_nokori_last);//同じ孫階級で一番若い数字を取得
var one_number = $("#me_child"+kekka1[0]+"_"+Math.min.apply(null,kekka_nokori_last)).parent().attr("id");//kekka1のindexは0でいい
if(one_number){
var zan = (this_gyou-2) - Number(one_number.replace("gyou",""));//（クリックした行−２）ー（一番若い数字の行番号）結果例 3
for(var i=0;i<zan;i++){
$("#gyou"+((this_gyou-2)-i)).children(".retu"+(this_retu-1)).css("visibility","hidden");
}
$("#gyou"+((this_gyou-2)-zan)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_right_top.png");
}

}

}

}else if(this_sintou == 1){//クリックして削除したボタンが親等１の場合


for(var i=0;i<under_bar.length;i++){
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）2
}



for(var i=0;i<under_bar_usiro.length;i++){
if(under_bar_usiro[i] < btn_dore_delete_kekka_usiro){
kekka_last.push(Number(under_bar_usiro[i]));
}
if(under_bar_usiro[i] > btn_dore_delete_kekka_usiro){
kekka_nokori_last.push(Number(under_bar_usiro[i]));
}
}

/*console.log(kekka_last);//クリックして削除した要素が一番下の場合はこっち（クリックした要素が一番下なら[]になる）
console.log(kekka_nokori_last);//クリックして削除した要素より上を取得はこっち（クリックした要素が一番上なら[]になる）
*/

if(under_bar_usiro.length == 0){//同じ子供階級を全て削除したら配偶者の横の画像を横ラインに戻す
$("#gyou"+(this_gyou-2)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_yoko.png");
}

if(kekka_last.length == ""){//クリックした要素が一番下だったら
Math.min.apply(null,kekka_nokori_last);//同じ孫階級で一番若い数字を取得
var one_number = $("#me_child"+Math.min.apply(null,kekka_nokori_last)).parent().attr("id");//同じ孫階級で一番若い数字の親のid名取得
if(one_number){//クリックした要素の数字より上の要差があれば
var zan = (this_gyou-2) - Number(one_number.replace("gyou",""));//（クリックした行−２）ー（一番若い数字の行番号）結果例 3
for(var i=0;i<zan;i++){
$("#gyou"+((this_gyou-2)-i)).children(".retu"+(this_retu-1)).css("visibility","hidden");
}
$("#gyou"+((this_gyou-2)-zan)).children(".retu"+(this_retu-1)).children("img").attr("src", "../img/line_right_top.png");
}

}

}

//ここから単体で配偶者を削除した場合のそのtdを保存しとく
if(btn_dore.indexOf("_haigusya") > -1){//削除したボタンのid名にhaigusyaが含まれていて
if(btn_dore != "me_haigusya"){//かつボタン名のidが「me_haigusya」以外で
var delete_oya_id = this_id;//クリックした親の行のid取得
var delete_retu = this_retu;//クリックした列の列番号を取得
}else if(btn_dore == "me_haigusya"){
var me_haigusya_delete_oya_id = this_id;//クリックした親の行のid取得
var me_haigusya_delete_retu = this_retu;//クリックした親の行のid取得
}
}
//ここまで




$("."+btn_dore).each(function(index){
idid.push($(this).parent().attr("id"));
});

$("."+btn_dore).remove();
$("#"+btn_dore).remove();


if(btn_dore == "me_haigusya"){//削除ボタンが「me_haigusya」で削除した行に何も残ってなければ親のtrも削除する
for(var i=0;i<idid.length;i++){
if($("#"+idid[i]).children("td").text() == ""){
$("#"+idid[i]).remove();
}
}

for(var i=0; i<child_count_array.length;i++){
if(child_count_array[i].match(/^me_child/g)){
tete.push(child_count_array[i]);
}
}
for(var i=0; i<tete.length;i++){//ヒットした値以外を取得
child_count_array = child_count_array.filter(function( item ) {
return item != tete[i];
});
}

}else if(btn_dore != "me_haigusya"){//削除ボタンが「me_haigusya」以外で削除した行に何も残ってなければ親のtrも削除する
for(var i=0;i<idid.length;i++){
if($("#"+idid[i]).children("td").text() == ""){
$("#"+idid[i]).remove();
}else {//削除してその行に何か残っていればどこかの配偶者ボタンを削除したと判断できるので、その行−２マス戻ったid名を取得
var delete_search = $("#"+idid[i]).children(".retu"+(this_retu-2)).attr("id");
}
}


//ここから私より下の世代の配偶者ボタンを削除した場合の処理
for(var i=0; i<child_count_array.length;i++){
var ttt="";
ttt = new RegExp("^"+delete_search+"_");
if(delete_search+"_" == child_count_array[i].match(ttt)){
tete.push(child_count_array[i]);
}
}

for(var i=0; i<tete.length;i++){
child_count_array = child_count_array.filter(function( item ) {//ヒットした値以外を取得
return item != tete[i];
});
}
//ここまで

//ここから兄弟分の配偶者ボタンを削除した場合の処理
for(var i=0; i<child_count_array_brother.length;i++){
var ttt="";
ttt = new RegExp("^"+delete_search+"_");
if(delete_search+"_" == child_count_array_brother[i].match(ttt)){
tete.push(child_count_array_brother[i]);
}
}
for(var i=0; i<tete.length;i++){
child_count_array_brother = child_count_array_brother.filter(function( item ) {//ヒットした値以外を取得
return item != tete[i];
});
}
//ここまで

}



//ここから単体で配偶者を削除した場合のそのtdを復活させる
if(btn_dore == "me_haigusya"){
for(var i=0; i<16;i++){
html_td_tuika+="<td class='retu"+(me_haigusya_delete_retu+i)+"'></td>";
}
$(html_td_tuika).insertAfter($("#"+me_haigusya_delete_oya_id).children(".retu"+(me_haigusya_delete_retu-1)));
}

if(btn_dore != "me_haigusya"){
html_td_tuika+="<td class='retu"+(delete_retu-1)+"'></td>";
$(html_td_tuika).insertAfter($("#"+delete_oya_id).children(".retu"+(delete_retu-2)));
html_td_tuika2+="<td class='retu"+delete_retu+"'></td>";
$(html_td_tuika2).insertAfter($("#"+delete_oya_id).children(".retu"+(delete_retu-1)));
}
//ここまで

if(btn_dore.indexOf("_brother") > -1){//クリックしたボタンのid名にbrotherが含まれていたら
$(".kekka_table2 tr").each(function(index){//id連番振り直し
$(this).attr("id","gyou_men_brother"+index);
});
}else {
$(".kekka_table tr").each(function(index){//id連番振り直し
$(this).attr("id","gyou"+index);
});
}


if(child_count_array_brother.length == ""){
$("#gyou6").children(".retu25").html("");
$("#gyou5").children(".retu25").html("");
$("#gyou5").children(".retu24").children("img").attr("src", "../img/line_tate.png");
}

}



















///////////////////////////////////////////////
////////////ここから両親を選択時/////////////////
///////////////////////////////////////////////

if(syurui == "oya"){

var woman=0;
var men=0;


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
var woman_id_settei = "me_mother_sousobo";
var woman_name = "曽祖母";
var men_id_settei = "me_mother_sousohu";
var men_name = "曽祖父";

}else if(who == "me_mother_sohu"){
var woman_id_settei = "me_father_sousobo";
var woman_name = "曽祖母";
var men_id_settei = "me_father_sousohu";
var men_name = "曽祖父";

}else if(who == "me_father"){///ここから父方の血族一式
var woman_id_settei = "me_father_sobo";
var woman_name = "祖母";
var men_id_settei = "me_father_sohu";
var men_name = "祖父";

}else if(who == "me_father_sobo"){
var woman_id_settei = "me_father_sousobo";
var woman_name = "曽祖母";
var men_id_settei = "me_father_sousohu";
var men_name = "曽祖父";

}else if(who == "me_father_sohu"){
var woman_id_settei = "me_father_sousobo";
var woman_name = "曽祖母";
var men_id_settei = "me_father_sousohu";
var men_name = "曽祖父";

}



if(who == "me"){

woman = this_retu-1;
men = this_retu+1;

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_tate.png' class='max-img'>");
$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu)).html("<img src='../img/line_huuhu.png' class='max-img'>");

}

if(who == "me_mother"){///ここから母方の家系図表示一式
woman = this_retu-2;
men = this_retu;

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");

}


if(who == "me_mother_sohu"){///ここから母方の家系図表示一式

woman = this_retu-2;
men = this_retu;

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
}


if(who == "me_mother_sobo"){

woman = this_retu-4;//17
men = this_retu-2;//19

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_yoko.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_yoko.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu-3)).html("<img src='../img/line_right_top.png' class='max-img'>");
$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu-3)).html("<img src='../img/line_huuhu.png' class='max-img'>");

}


if(who == "me_father"){///ここから父方の家系図表示一式
woman = this_retu+2;
men = this_retu;

$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
$('#gyou'+(this_gyou-1)).children('.retu'+(this_retu+1)).html("<img src='../img/line_left_top.png' class='max-img'>");
$('#gyou'+(this_gyou-2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
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

$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).html("<span></span>"+woman_name+"<div class='fff text_tuika'></div>");
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).html("<span></span>"+men_name+"<div class='fff text_tuika'></div>");

//ここから父方設定
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('data-toggle','modal');
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('data-this_td',(men));
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('data-sintou',this_sintou+1);
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('id',men_id_settei);
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).attr('data-sex','men');

if(this_sintou == 0){
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('background',backcolor1);
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('position','relative');
}else if(this_sintou == 1){
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('background',backcolor2);
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('position','relative');
}else if(this_sintou == 2){
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('background',backcolor3);
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('position','relative');
}
$('#gyou'+(this_gyou-2)).children('.retu'+(men)).css('border',all_bordercolor);

//ここから母方設定
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('data-toggle','modal');
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('data-this_td',(woman));
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('data-sintou',this_sintou+1);
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('id',woman_id_settei);
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).attr('data-sex','woman');
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css("border-radius","100px");

if(this_sintou == 0){
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('background',backcolor1);
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('position','relative');
}else if(this_sintou == 1){
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('background',backcolor2);
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('position','relative');
}else if(this_sintou == 2){
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('background',backcolor3);
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('position','relative');
}
$('#gyou'+(this_gyou-2)).children('.retu'+(woman)).css('border',all_bordercolor);



}





///////////////////////////////////////////////
////////////ここから子供を選択時/////////////////
///////////////////////////////////////////////

if(syurui == "children"){

if(this_all_class.indexOf("me_children") > -1){//クリックしたボタンのclass名にme_childrenが含まれていたら
if(this_sintou == "1"){
all_count++;
children_tuika("me_children","孫","5px solid #FFFF00",all_count);
}else if(this_sintou == "2"){
all_mago_count++;
children_tuika("me_mago","ひ孫","5px solid #FF0000",all_mago_count);
}

}



if(who == "me_father"){


me_father_brother_count++;
var men_name = "兄弟"+me_father_brother_count;
var men_id_settei="me_brother"+me_father_brother_count;
var bordercolor = "5px solid #ffff00";//2親等
var me_haigusya_brother_table="";
var html2="";
var under_bar=[];
var under_bar_usiro_brother=[];


if(me_father_brother_count == 1){

var me_brother_retu25 = $("#gyou6").children(".retu25").offset();

me_haigusya_brother_table+="<table id='kekka_table2' class='kekka_table2' style='position:absolute; top:"+(me_brother_retu25.top+80)+"px; left:"+me_brother_retu25.left+"px;'>";
for(var i=0;i<2;i++){
me_haigusya_brother_table+="<tr id='men_gyou_brother"+i+"' data-this_tr='"+i+"'>";
for(var z=0;z<4;z++){
if(i==0&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+btn_dore+" me_mother "+men_id_settei+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==1&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+btn_dore+" me_mother "+men_id_settei+"'><img src='../img/line_right_top.png' class='max-img'></td>";
}else if(i==1&&z==1){
me_haigusya_brother_table+="<td class='retu"+z+" btn btn-success btn_search "+btn_dore+" me_mother' style='background:"+backcolor2+"; color:#fff; border:1px solid #000;' data-toggle='modal' data-target='#modal_hyouzi' data-this_td='"+z+"' data-sintou='2' id='"+men_id_settei+"'>兄弟１</td>";
}else {
me_haigusya_brother_table+="<td class='retu"+z+"'></td>";
}
}
me_haigusya_brother_table+="</tr>";
}
me_haigusya_brother_table+="</table>";


$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_men_brother.png' class='max-img'>");
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu)).html("<img src='../img/line_left_bottom.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).html("<img src='../img/line_tate.png' class='max-img'>");

$("body").append(me_haigusya_brother_table);




}else {

html2+="<tr id='gyou_men_brother0' data-this_tr='0'>";
for(var i=0;i<4;i++){
if(i==0){
html2+="<td class='retu"+i+" "+btn_dore+" me_mother "+men_id_settei+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

html2+="<tr id='men_gyou_brother1' data-this_tr='1'>";
for(var i=0;i<4;i++){
if(i==1){
html2+="<td class='retu"+i+" btn btn-success btn_search "+btn_dore+" me_mother "+men_id_settei+"' style='background:"+backcolor2+"; color:#fff; border:1px solid #000;' data-toggle='modal' data-target='#modal_hyouzi' data-this_td='"+i+"' data-sintou='2' id='"+men_id_settei+"'>"+men_name+"</td>";
}else if(i==0){
html2+="<td class='retu"+i+" "+btn_dore+" me_mother "+men_id_settei+"'><img src='../img/line_men_brother.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

$(".kekka_table2").prepend(html2);//kekka_table2の先頭に追加していく



}

child_count_array_brother.push(men_id_settei);

for(var i=0;i<child_count_array_brother.length;i++){
if(this_sintou == 1){//クリックしたボタンが親等1の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 1){//「 _ 」が1つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が1つの配列を全て入れる
}
}
}

for(var i=0;i<under_bar.length;i++){//「 _ 」が1つの配列を全てループさせる
under_bar_usiro_brother.push(under_bar[i].slice(10));//これでme_brotherより後ろを全て取得　例　）["1"、"2"]などが取得できる
}

$(".kekka_table2 tr").each(function(index){
$(this).attr("id","gyou_men_brother"+index);
});

if(under_bar_usiro_brother.length == 1){//カウント１の時は画像を夫婦に変更
$('#gyou5').children('.retu24').html("<img src='../img/line_men_brother.png' class='max-img'>");
$('#gyou5').children('.retu25').html("<img src='../img/line_left_bottom.png' class='max-img'>");
$('#gyou6').children('.retu25').html("<img src='../img/line_tate.png' class='max-img'>");
$('#gyou_men_brother1').children('.retu0').html("<img src='../img/line_right_top.png' class='max-img'>");
}

}else if(who == "me"){

me_children_count++;
var men_name = "子供"+me_children_count;
var men_id_settei="me_child"+me_children_count;
line_img_henkou = "";
var bordercolor = "5px solid #000";//1親等
var on="";
var html2="";
var under_bar=[];
var under_bar_usiro=[];

child_count_array.push(men_id_settei);


html2+="<tr id='gyou"+(this_gyou+1)+"' data-this_tr='"+(this_gyou+1)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu-9){
html2+="<td class='retu"+(this_retu-9)+" me_haigusya me_children "+men_id_settei+"' data-this_td="+(this_retu-9)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

html2+="<tr id='gyou"+(this_gyou+2)+"' data-this_tr='"+(this_gyou+2)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu-8){
html2+="<td class='retu"+(this_retu-8)+"'>子供"+me_children_count+"</td>";
}else if(i==this_retu-9){
html2+="<td class='retu"+(this_retu-9)+" me_haigusya me_children "+men_id_settei+"' data-this_td="+(this_retu-9)+"><img src='../img/line_men_brother.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

$(html2).insertAfter("#gyou"+this_gyou);

for(var i=0;i<child_count_array.length;i++){
if(child_count_array[i].match(/_/g || []).length == 1){
under_bar.push(child_count_array[i]);
}
}
for(var i=0;i<under_bar.length;i++){
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）2
}

if(under_bar_usiro.length == 1){//同じ子供階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-9)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-9)).html("<img src='../img/line_right_top.png' class='max-img'>");
}



//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).addClass('btn btn-success btn_search me_haigusya me_children me_child'+me_children_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('data-this_td',this_retu-8);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('data-sintou','1');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('data-child',me_children_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).css('background',backcolor1);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-8)).css('border',all_bordercolor);
//ここまで最初設置

$(".kekka_table tr").each(function(index){
$(this).attr("id","gyou"+index);
});

}else if(who == "me_haigusya"){

me_children_count++;
var men_name = "子供"+me_children_count;
var men_id_settei="me_child"+me_children_count;
line_img_henkou = "";
var bordercolor = "5px solid #000";//1親等
var on="";
var html2="";
var under_bar=[];
var under_bar_usiro=[];

child_count_array.push(men_id_settei);


html2+="<tr id='gyou"+(this_gyou+1)+"' data-this_tr='"+(this_gyou+1)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu+7){
html2+="<td class='retu"+(this_retu+7)+" me_haigusya me_children "+men_id_settei+"' data-this_td="+(this_retu+7)+"><img src='../img/line_tate.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

html2+="<tr id='gyou"+(this_gyou+2)+"' data-this_tr='"+(this_gyou+2)+"'>";
for(var i=0;i<33;i++){
if(i==this_retu+8){
html2+="<td class='retu"+(this_retu+8)+"'>子供"+me_children_count+"</td>";
}else if(i==this_retu+7){
html2+="<td class='retu"+(this_retu+7)+" me_haigusya me_children "+men_id_settei+"' data-this_td="+(this_retu+7)+"><img src='../img/line_men_brother.png' class='max-img'></td>";
}else {
html2+="<td class='retu"+i+"'></td>";
}
}
html2+="</tr>";

$(html2).insertAfter("#gyou"+this_gyou);

for(var i=0;i<child_count_array.length;i++){
if(child_count_array[i].match(/_/g || []).length == 1){
under_bar.push(child_count_array[i]);
}
}
for(var i=0;i<under_bar.length;i++){
under_bar_usiro.push(under_bar[i].slice(8));//これでme_childより後ろを全て取得　例　）2
}

if(under_bar_usiro.length == 1){//同じ子供階級を全て削除したら配偶者の横の画像を横ラインに戻す
$('#gyou'+(this_gyou)).children('.retu'+(this_retu+7)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+7)).html("<img src='../img/line_right_top.png' class='max-img'>");
}



//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).addClass('btn btn-success btn_search me_haigusya me_children me_child'+me_children_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('data-this_td',this_retu+8);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('data-sintou','1');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('data-child',me_children_count);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).css('background',backcolor1);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+8)).css('border',all_bordercolor);
//ここまで最初設置

$(".kekka_table tr").each(function(index){
$(this).attr("id","gyou"+index);
});

}



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここから叔父叔母の追加////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/*
if(who == "me_father_sohu"||who == "me_father_sobo"){

me_father_brother_count++;
var men_name = "兄弟"+me_father_brother_count;
var men_id_settei="me_father_brother"+me_father_brother_count;
var td_susumu=0;
line_img_henkou = "";
var bordercolor = "5px solid #ff0000";//3親等

if(this_sex == "men"){
this_retu+=2;
}


if(me_father_brother_count == 1){

$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).html(men_name);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_t1.png' class='max-img'>");
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu)).html("<img src='../img/line_men_brother_kettei.png' class='max-img'>");
//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('data-this_td',this_retu);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).css('background','#6679ff');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu)).css('border',bordercolor);
//ここまで最初設置
}else {

if($('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+(me_father_brother_count-1)*2)).text() == ""){
td_susumu=(me_father_brother_count-1)*2;
line_img_henkou = "<img src='../img/line_huuhu.png' class='max-img'>"
}


$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).html(men_name);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-2+td_susumu)).html(line_img_henkou);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1+td_susumu)).html("<img src='../img/line_yoko.png' class='max-img'>");
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu+td_susumu)).html("<img src='../img/line_men_brother_kettei.png' class='max-img'>");

//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).attr('data-this_td',this_retu+td_susumu);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).css('background','#6679ff');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu+td_susumu)).css('border',bordercolor);


}

}else if(who == "me_mother_sohu"||who == "me_mother_sobo"){

me_mother_brother_count++;
var men_name = "兄弟"+me_mother_brother_count;
var men_id_settei="me_mother_brother"+me_mother_brother_count;
var td_susumu=0;
line_img_henkou = "";
var bordercolor = "5px solid #ff0000";//3親等

if(this_sex == "woman"){
this_retu+=2;
}

if(me_mother_brother_count == 1){
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).html(men_name);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1)).html("<img src='../img/line_t1.png' class='max-img'>");
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-2)).html("<img src='../img/line_right_bottom.png' class='max-img'>");
//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).attr('data-this_td',this_retu-2);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).css('background','#6679ff');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2)).css('border',bordercolor);
//ここまで最初設置
}else {


td_susumu=(me_mother_brother_count-1)*2;
line_img_henkou = "<img src='../img/line_huuhu.png' class='max-img'>";

$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).html(men_name);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-td_susumu)).html(line_img_henkou);
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-1-td_susumu)).html("<img src='../img/line_yoko.png' class='max-img'>");
$('#gyou'+(this_gyou+1)).children('.retu'+(this_retu-2-td_susumu)).html("<img src='../img/line_right_bottom.png' class='max-img'>");

//ここから父方設定
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).addClass('btn btn-success btn_search');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).attr('data-toggle','modal');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).attr('data-this_td',this_retu-2-td_susumu);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).attr('id',men_id_settei);
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).css('background','#6679ff');
$('#gyou'+(this_gyou+2)).children('.retu'+(this_retu-2-td_susumu)).css('border',bordercolor);

}

}
*/
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここまで////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////











////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/////ここから甥姪の追加////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

if(btn_dore.indexOf("_brother") > -1){//クリックした配偶者ボタンのid名にbrotherが含まれていたら

me_oimei_count++;
var men_id_settei=btn_dore+"_";
var me_haigusya_brother_table="";
var btn_dore_delete_kekka_split_brother="";
var child_count_array_brother_img=[];
var child_count_array_brother_img_split=[];
var kekka_img_last_brother=[];
var kekka_img_nokori_last_brother=[];
var under_bar_usiro_brother=[];
var moto_btn_dore="";
var kekka1_brother=[];
var under_bar_usiro_split_brother=[];
var under_bar=[];
var search_counter="";

if($('#gyou'+(this_gyou_brother+1)).children('.retu0').html() == undefined){
var kara1 ="空っぽ";
}
if($('#gyou'+(this_gyou_brother+1)).children('.retu0').css("visibility") == "hidden"){
var kara1 ="空っぽ";
}


if(btn_dore.indexOf("_haigusya") > -1){//クリックしたボタンのid名にbrotherとhaisusyaが含まれていたら
search_counter = btn_dore.replace("_haigusya","");
}else {
search_counter = btn_dore;
}

//all_counter_brotherは連想配列、search_counterはボタンクリックした時のid名、all_counter_brother[btn_dore]はカウントアップ用の数字（初期値は下記にそれぞれ１に設定）
if(search_counter in all_counter_brother == true){//既にid名のkeyがあれば数字を１追加していく
all_counter_brother[search_counter]++;
men_name = "甥姪"+all_counter_brother[search_counter];
}else {//初めてなら数字を１の初期値に設定する
all_counter_brother[search_counter] = 1;
men_name = "甥姪"+all_counter_brother[search_counter];
}


//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//クリックしたボタンのid名にbrotherとhaisusyaが含まれていたら
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////

if(btn_dore.indexOf("_haigusya") > -1){//クリックしたボタンのid名にbrotherとhaisusyaが含まれていたら
var btn_dore_delete_sakuzyo = btn_dore.replace("_haigusya","");

if(all_counter_brother[search_counter] == 1){//カウント１の時は画像を夫婦に変更
$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
var img_henkou2 = "right_top";
}else {
var img_henkou2 = "men_brother";
}

var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");

for(var i=0;i<2;i++){
me_haigusya_brother_table+="<tr id='gyou_men_brother"+i+"' data-this_tr='"+i+"'>";
for(var z=0;z<4;z++){
if(i==0&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==1&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==0&&z==2){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==1&&z==2){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_"+img_henkou2+".png' class='max-img'></td>";
}else if(i==1&&z==3){
me_haigusya_brother_table+="<td class='retu"+z+" btn btn-success btn_search "+class_delete_kekka+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"' style='background:"+backcolor3+"; color:#fff; border:1px solid #000;' data-toggle='modal' data-target='#modal_hyouzi' data-this_td='"+z+"' data-sintou='3' id='"+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'>"+men_name+"</td>";
}else {
me_haigusya_brother_table+="<td class='retu"+z+"'></td>";
}
}
me_haigusya_brother_table+="</tr>";
}

$(me_haigusya_brother_table).insertAfter("#gyou_men_brother"+this_gyou_brother);

child_count_array_brother.push(btn_dore.replace("_haigusya","")+"_"+all_counter_brother[search_counter]);

var btn_dore_delete_kekka_usiro = btn_dore.replace("_haigusya","").slice(10);//これでme_brotherより後ろを全て取得　例　）2
btn_dore_delete_kekka_split_brother = btn_dore_delete_kekka_usiro.split('_');//これでme_brotherより後ろの連番数字を分割

for(var i=0;i<child_count_array_brother.length;i++){
if(this_sintou == 2){//クリックして削除したボタンが親等2の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 2){//「 _ 」が2つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が2つの配列を全て入れる
}
}
}



for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(under_bar[i].slice(10));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}
for(var i=0;i<under_bar_usiro_brother.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split_brother.push(under_bar_usiro_brother[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}

for(var i=0;i<under_bar_usiro_split_brother.length;i++){//[["1","1"],["2","1"]]を全てループ
if("me_brother"+under_bar_usiro_split_brother[i][0] == "me_brother"+btn_dore_delete_kekka_split_brother[0]){//例えば、"me_brother1_1"とクリックしたボタンのid名の"me_brother1_1"が同じだったら
kekka1_brother.push(under_bar_usiro_split_brother[i]);//その同じだった配列以外を取得　例）[["2","1"]];など
}
}


for(var i=0;i<child_count_array_brother.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(child_count_array_brother[i].slice(10));//これでme_brotherより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

for(var i=0; i<under_bar_usiro_brother.length; i++){
if(Number(btn_dore_delete_kekka_split_brother[0]) < under_bar_usiro_brother[i][0]){
kekka_img_last_brother.push(under_bar_usiro_brother[i]);
}
if(Number(btn_dore_delete_kekka_split_brother[0]) > under_bar_usiro_brother[i][0]){
kekka_img_nokori_last_brother.push(under_bar_usiro_brother[i]);
}//クリックした要素が一番下が空かどうか
}

$(".kekka_table2 tr").each(function(index){
$(this).attr("id","gyou_men_brother"+index);
});

if(kekka1_brother.length == 1){//カウント１の時は画像を夫婦に変更
$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu-1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou_men_brother'+(this_gyou_brother+2)).children('.retu'+(this_retu-1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}


if(kekka_img_nokori_last_brother.length == ""){
$('#gyou_men_brother'+(this_gyou_brother+1)).children('.retu0').css("visibility","hidden");
$('#gyou_men_brother'+(this_gyou_brother+2)).children('.retu0').css("visibility","hidden");
}



}else {

//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//クリックしたボタンのid名にbrotherとhaisusyaが含まれていなかったら
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////


var btn_dore_delete_sakuzyo = btn_dore;

if(all_counter_brother[search_counter] == 1){//カウント１の時は画像を夫婦に変更
$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
var img_henkou2 = "right_top";
}else {
var img_henkou2 = "men_brother";
}


var class_delete_kekka = $("#"+btn_dore).attr('class').replace("btn btn-success btn_search ","");
class_delete_kekka = class_delete_kekka.replace("retu"+this_retu+" ","");

for(var i=0;i<2;i++){
me_haigusya_brother_table+="<tr id='gyou_men_brother"+i+"' data-this_tr='"+i+"'>";
for(var z=0;z<4;z++){
if(i==0&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==1&&z==0){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==0&&z==2){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_tate.png' class='max-img'></td>";
}else if(i==1&&z==2){
me_haigusya_brother_table+="<td class='retu"+z+" "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'><img src='../img/line_"+img_henkou2+".png' class='max-img'></td>";
}else if(i==1&&z==3){
me_haigusya_brother_table+="<td class='retu"+z+" btn btn-success btn_search "+class_delete_kekka+" "+btn_dore+" "+btn_dore_delete_sakuzyo+"_haigusya "+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"' style='background:"+backcolor3+"; color:#fff; border:1px solid #000;' data-toggle='modal' data-target='#modal_hyouzi' data-this_td='"+z+"' data-sintou='3' id='"+btn_dore_delete_sakuzyo+"_"+all_counter_brother[search_counter]+"'>"+men_name+"</td>";
}else {
me_haigusya_brother_table+="<td class='retu"+z+"'></td>";
}
}
me_haigusya_brother_table+="</tr>";
}

$(me_haigusya_brother_table).insertAfter("#gyou_men_brother"+this_gyou_brother);

child_count_array_brother.push(btn_dore+"_"+all_counter_brother[search_counter]);

var btn_dore_delete_kekka_usiro = btn_dore.slice(10);//これでme_brotherより後ろを全て取得　例　）2
btn_dore_delete_kekka_split_brother = btn_dore_delete_kekka_usiro.split('_');//これでme_brotherより後ろの連番数字を分割

for(var i=0;i<child_count_array_brother.length;i++){
if(this_sintou == 2){//クリックして削除したボタンが親等2の場合で
if(child_count_array_brother[i].match(/_/g || []).length == 2){//「 _ 」が2つあれば
under_bar.push(child_count_array_brother[i]);//「 _ 」が2つの配列を全て入れる
}
}
}



for(var i=0;i<under_bar.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(under_bar[i].slice(10));//これでme_childより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}
for(var i=0;i<under_bar_usiro_brother.length;i++){//["1_1"、"2_1"]などループ
under_bar_usiro_split_brother.push(under_bar_usiro_brother[i].split('_'));//これでme_childより後ろの「 _ 」で連番数字を分割　[["1","1"],["2","1"]]などを取得できる
}

for(var i=0;i<under_bar_usiro_split_brother.length;i++){//[["1","1"],["2","1"]]を全てループ
if("me_brother"+under_bar_usiro_split_brother[i][0] == "me_brother"+btn_dore_delete_kekka_split_brother[0]){//例えば、"me_brother1_1"とクリックしたボタンのid名の"me_brother1_1"が同じだったら
kekka1_brother.push(under_bar_usiro_split_brother[i]);//その同じだった配列以外を取得　例）[["2","1"]];など
}
}


for(var i=0;i<child_count_array_brother.length;i++){//「 _ 」が2つの配列を全てループさせる
under_bar_usiro_brother.push(child_count_array_brother[i].slice(10));//これでme_brotherより後ろを全て取得　例　）["1_1"、"2_1"]などが取得できる
}

for(var i=0; i<under_bar_usiro_brother.length; i++){
if(Number(btn_dore_delete_kekka_split_brother[0]) < under_bar_usiro_brother[i][0]){
kekka_img_last_brother.push(under_bar_usiro_brother[i]);
}
if(Number(btn_dore_delete_kekka_split_brother[0]) > under_bar_usiro_brother[i][0]){
kekka_img_nokori_last_brother.push(under_bar_usiro_brother[i]);
}//クリックした要素が一番下が空かどうか
}

$(".kekka_table2 tr").each(function(index){
$(this).attr("id","gyou_men_brother"+index);
});

if(kekka1_brother.length == 1){//カウント１の時は画像を夫婦に変更
$('#gyou_men_brother'+(this_gyou_brother)).children('.retu'+(this_retu+1)).html("<img src='../img/line_huuhu.png' class='max-img'>");
$('#gyou_men_brother'+(this_gyou_brother+2)).children('.retu'+(this_retu+1)).html("<img src='../img/line_right_top.png' class='max-img'>");
}


if(kekka_img_nokori_last_brother.length == ""){
$('#gyou_men_brother'+(this_gyou_brother+1)).children('.retu0').css("visibility","hidden");
$('#gyou_men_brother'+(this_gyou_brother+2)).children('.retu0').css("visibility","hidden");
}


}

















}


}












///////////////////////////////////////////////
////////////ここから配偶者を選択時/////////////////
///////////////////////////////////////////////

if(syurui == "haigusya"){

if($("#"+btn_dore).hasClass('me_haigusya')){
if(this_sintou == "0"){
haigusya_tuika("me_haigusya");
}else if(this_sintou == "1"){
haigusya_tuika("me_children","5px solid #FFFF00");
}else if(this_sintou == "2"){
haigusya_tuika("me_mago","5px solid #FF0000");
}
}

if($("#"+btn_dore).hasClass('me_father')){
if(this_sintou == "2"){
haigusya_tuika("me_brother_mago","5px solid #FF0000");
}
}


var men_name = "配偶者";
var men_id_settei="me_brother1_haigusya";
var td_susumu=0;
line_img_henkou = "";
var bordercolor = "5px solid #ffff00";//2親等



if(who == "me"){

men_id_settei="me_haigusya";

for(var i=1;i<16;i++){
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).html("<img src='../img/line_yoko.png' class='max-img'>");
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).attr('data-this_td',this_retu-i);
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-i)).addClass(men_id_settei);
}

$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).html(men_name);
//ここからCSS設定
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).addClass('btn btn-success btn_search '+men_id_settei);
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-toggle','modal');
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-target','#modal_hyouzi');
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('data-this_td',this_retu-16);
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).attr('id',men_id_settei);
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).css('background',backcolor0);
$('#gyou'+(this_gyou)).children('.retu'+(this_retu-16)).css('border',all_bordercolor);
//ここまで

}
}

}



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
$('#gyou'+this_gyou).children('.retu'+this_retu).css("background-color","#000");
$("#gyou"+this_gyou).children(".retu"+this_retu).children('.fff').append(text_go);
$("#gyou"+this_gyou).children(".retu"+this_retu).children('.text_tuika').css("display","block");
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




/*
$(function () {
$(document).on('click', '#diagnose', function() {
var cancer_init=[];
var cancer_old_init=[];
var cancer_sintou=[];

$(".cancer_init").each(function(){
cancer_init.push($(this).text());
cancer_sintou.push($(this).parent().parent().data("sintou"));
});
$(".cancer_old_init").each(function(){
cancer_old_init.push($(this).text());
});

console.log(cancer_init);
console.log(cancer_old_init);
console.log(cancer_sintou);


})
});
*/




/*
$(function () {
$(document).on('mouseover', '.btn-success .fff', function() {
$(this).css('height','auto');
})
});
$(function () {
$(document).on('mouseout', '.btn-success .fff', function() {
$(this).css('height','35px');
})
});
*/


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

//下記２つのデータを送信する
$("#now_time").val(now_time);
$("#canvas_img").val(canvas.toDataURL("image/jpeg",0.75));

document.send_form.submit();

});

});
});



</script>

</body>
</html>