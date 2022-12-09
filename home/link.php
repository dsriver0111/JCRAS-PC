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
			
			.container {
				display: flex;
				align-items: center;
			}
			
			.max-img{
				width:auto;
				height:auto;
				max-width:100%;
				max-height:100%;
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
			
			
		</style>
		
		<title>リスク評価判定結果</title>
		
	</head>
	<body>
		
		<h5 class="mt-5 text-center">情報リンク</h5>
		
		
		<div class="container">
			<div class="row col-md-12">
				<div class="col-md-1"></div>
				<div class="col-md-10 ml-3 mr-2">
					<div style="overflow-x:auto;">
					<ul class="nav nav-tabs mt-4" style="width:770px;">
					<li class="nav-item"><a href="#contents1" data-toggle="tab" class="nav-link pt-3 pb-3" onclick="counter('tab1');">サーベイランス案</a></li>
					<li class="nav-item"><a href="#contents2" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab2');">参考例文集</a></li>
					<li class="nav-item"><a href="#contents3" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab3');">臨床遺伝学・遺伝性腫瘍</a></li>
					<li class="nav-item"><a href="#contents4" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab4');">最寄り医療機関</a></li>
				</ul>
					</div>
				
			<!--<div class="row justify-content-center col-sm-12">
				<div style="overflow-x:scroll;">
					<ul class="nav nav-tabs mt-4 mb-4 justify-content-center" style="width:690px;">
					<li class="nav-item"><a href="#contents1" data-toggle="tab" class="nav-link pt-3 pb-3" onclick="counter('tab1');">サーベイランス案</a></li>
					<li class="nav-item"><a href="#contents2" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab2');">参考例文集</a></li>
					<li class="nav-item"><a href="#contents3" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab3');">臨床遺伝学・遺伝性腫瘍</a></li>
					<li class="nav-item"><a href="#contents4" data-toggle="tab" class="nav-link pt-3 pb-3"onclick="counter('tab4');">最寄り医療機関</a></li>
				</ul>
				</div>
				-->
				
		
				<div class="tab-content mt-5">
					<div id="contents0" class="tab-pane active">
						<div class="search_no text-center mt-5 mb-5 font-weight-bold">上記タブを選択してください</div>
					</div>
			<div id="contents1" class="tab-pane">
		病的バリアント保有者に対する癌のサーベイランス方法JCRAS-PC は遺伝性腫瘍の中でも頻度の高い遺伝性乳癌卵巣癌症候群(HBOC)とリンチ症候群(LS)に着目して作成されています。JCRAS -PC によって癌のリスク評価判定が高いと判定された場合、遺伝カウンセリングや遺伝学的検査への紹介基準を満たしていることになります。 ここでは、HBOC や LS のリスクが高いと診断された方に対して、病的バリアントを保持していると想定して、HBOC /L S に関して推奨・提案されている癌のサーベ イランス方法について示します。癌未発症で HBOC/LS の可能性が有る場合、一般集団に推奨される癌検診の推奨事項とは異なる、以下のサーベイランス方法を意 識しておく必要があります。<br><br>
						
						<img src="../img/sabe.jpg" class="max-img">
			</div>
					<div id="contents2" class="tab-pane">
							ここにテキストが入りますここにテキストが入りますここにテキストが入りますここにテキストが入りますここにテキストが入りますここにテキストが入ります
			</div>
					<div id="contents3" class="tab-pane">
							<div style="color:#aa0000;">1．GeneReviews Japan（日本語）</div>
							<a href="http://grj.umin.jp/" style="color:#ff0000;text-decoration:underline;">http://grj.umin.jp/</a><BR>
							▶️多くの遺伝性疾患の診断、管理、そして患者／クライエントやその家族のための遺伝カウンセリングについて、遺伝専門職がpoint-of-careで閲覧しているリソース。<BR>
							▶️本システム（JCRAS-PC）で焦点を当てている癌リスク評価は以下の疾患名になります。<BR>
							・「BRCA1およびBRCA2関連遺伝性乳癌卵巣癌」<BR>
							・「リンチ症候群」<BR><BR>
							
							<div style="color:#aa0000;">2．eviQ（英語）</div>
							<a href="https://www.eviq.org.au/cancer-genetics/referral-guidelines" style="color:#ff0000;text-decoration:underline;">https://www.eviq.org.au/cancer-genetics/referral-guidelines</a><BR>
							▶️オーストラリアのCancer Institute NSWによるevidence-basedな癌治療に関するリソース。<BR>
							▶️「〇〇〇(がん種名）-　referring to genetics」の項目で、プライマリ・ケア医が遺伝専門職への紹介を考慮すべき既往歴や家族歴を確認できます。<BR><BR>
							
							<div style="color:#aa0000;">3．NCCNガイドライン（日本語版）</div>
							<a href="https://www2.tri-kobe.org/nccn/guideline/index.html" style="color:#ff0000;text-decoration:underline;">https://www2.tri-kobe.org/nccn/guideline/index.html</a><BR>
							▶️National Comprehensive Cancer Network（NCCN）は、計32の米国の主要ながんセンターによる非営利団体で、癌種ごとに臨床診療ガイドラインを作成しています。<BR>
							▶️本システム（JCRAS-PC）で参照しているガイドライン名は以下になります。<BR>
							・「大腸がんにおける遺伝学的/家族性リスク評価」<BR>
							・「乳がんおよび卵巣がんにおける遺伝学的/家族性リスク評価」<BR><BR>
							
							<div style="color:#aa0000;">4．CanRisk（英語）</div>
							<a href="https://www.canrisk.org/" style="color:#ff0000;text-decoration:underline;">https://www.canrisk.org/</a><BR>
							▶️既往歴、家族歴、生活様式やホルモン関連のリスク因子などを入力することで、将来の卵巣癌または乳癌の発症リスクを数値化するリスク評価ツールです（登録したら使用可能、無料）。<BR><BR>
							
							<div style="color:#aa0000;">5．PREMM5 モデル（英語）</div>
							<a href="https://premm.dfci.harvard.edu/" style="color:#ff0000;text-decoration:underline;">https://premm.dfci.harvard.edu/</a><BR>
							▶️既往歴、家族歴の情報を入力すると、リンチ症候群の原因となる病的バリアントを保有している確率を数値化するリスク評価ツールです（無料で使用可能）。
			</div>
						
						
					<div id="contents4" class="tab-pane text-center">
							最寄りの遺伝子医療を実施している医療機関の検索リンク<BR><BR>
				<a href="http://www.idenshiiryoubumon.org/search/index.html" style="color:#ff0000;text-decoration:underline;">こちら</a>
			</div>
						
						
						<BR><BR><BR>
		</div>
			
				</div>
				
			</div>
			
		
		</div>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		
		<script>
			
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
			
		</script>
		
	</body>
</html>