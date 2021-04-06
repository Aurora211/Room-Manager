<?php
@include "../../VarMap.php";
@include "../../Functions.php";

if (WhiteListCheck($_SERVER['HTTP_REFERER']) !== true){
	echo "<script>alert(\"". WhiteListCheck($_SERVER['HTTP_REFERER']). "\");location.href=\"./\"</script>";
}

if ( isset( $_COOKIE[ 'User' ] ) && !empty( $_COOKIE[ 'User' ] ) ) {
	ClearOutDatedCookieFile();
	$Check = CheckCookie();
	if ( $Check !== false && $Check !== "banned" ) {
		$UserData = GetUserInfoByID( $Check );
	}elseif ($Check == "banned" ){
		"<script>alert(\"账户已被封禁\");location.href=\"../\"</script>";
		exit;
	}else{
		echo "<script>location.href=\"./login.php\";</script>";
		exit;
	}
}else{
	echo "<script>location.href=\"./login.php\";</script>";
	exit;
}

$RequestList = array();
if ( $_SERVER['REQUEST_METHOD'] == "GET" && isset( $_GET[ 'type' ] ) ) {
	$RequestList = GetRequestInfo($_GET['type']);
}else{
	$RequestList = GetRequestInfo();
}
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<!-- Title -->
	<title>麦庐大礼堂管理系统</title>

	<!-- Favicons -->
	<link href="assets/images/favicon.png" rel="icon">
	<link href="assets/images/apple-touch-icon.png" rel="apple-touch-icon">

	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/plugins/icomoon/style.css" rel="stylesheet">
	<link href="assets/plugins/uniform/css/default.css" rel="stylesheet"/>
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet"/>
	<link href="assets/plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>

	<!-- Theme Styles -->
	<link href="assets/css/ecaps.min.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="page-header-fixed page-sidebar-fixed" onLoad="StartCheck()">

	<!-- Page Container -->
	<div class="page-container">
		<!-- Page Sidebar -->
		<div class="page-sidebar">
			<a class="logo-box" href="index.html">
				<span><?php echo $UserData['name']; ?></span>
				<i class="icon-radio_button_unchecked" id="fixed-sidebar-toggle-button"></i>
				<i class="icon-close" id="sidebar-toggle-button-close"></i>
			</a>
		
			<div class="page-sidebar-inner">
				<?php echo file_get_contents("./page-sidebar.html"); ?>
			</div>
		</div>
		<!-- /Page Sidebar -->

		<!-- Page Content -->
		<div class="page-content">
			<!-- Page Header -->
			<div class="page-header">
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<div class="logo-sm">
								<a href="javascript:void(0)" id="sidebar-toggle-button">
									<i class="fa fa-bars"></i>
								</a>
								<a class="logo-box" href="index.html">
									<span><?php echo $UserData['name']; ?></span>
								</a>
							</div>
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<i class="fa fa-angle-down"></i>
							</button>
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li>
									<a href="javascript:void(0)" id="collapsed-sidebar-toggle-button">
										<i class="fa fa-bars"></i>
									</a>
								</li>
								<li>
									<a href="javascript:void(0)" id="toggle-fullscreen">
										<i class="fa fa-expand"></i>
									</a>
								</li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown user-dropdown">
									<a href="javascript:void(0)" class="dropdown-toggle">
										<font size="4px" color="#0070E0" >麦庐大礼堂管理系统&nbsp;</font>
										<img src="./assets/images/favicon.png" alt="" class="img-circle">
									</a>
								</li>
							</ul>
						</div>
						<!-- /.navbar-collapse -->
					</div>
					<!-- /.container-fluid -->
				</nav>
			</div>
			<!-- /Page Header -->
			<!-- Page Inner -->
			<div class="page-inner">
				<div class="page-title">
					<h3 class="breadcrumb-header"><strong>管理已提交申请</strong></h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading">
									<h4 class="panel-title">申请列表</h4>
								</div>
								<div class="panel-body">
									<a href="javascript:location.reload()" class="btn btn-default m-b-sm"><strong>刷新页面</strong></a>
									<a href="./requests.php" class="btn btn-default m-b-sm"><strong>查看 所有</strong></a>
									<a href="./requests.php?type=processing" class="btn btn-warning m-b-sm"><strong>查看 处理中</strong></a>
									<a href="./requests.php?type=unviewed" class="btn btn-warning m-b-sm"><strong>查看 待审核</strong></a>
									<a href="./requests.php?type=proceed" class="btn btn-success m-b-sm"><strong>查看 待归还</strong></a>
									<a href="./requests.php?type=denied" class="btn btn-danger m-b-sm"><strong>查看 已驳回</strong></a>
									<a href="./requests.php?type=completed" class="btn btn-default m-b-sm"><strong>查看 已处理</strong></a>
									<div class="table-responsive">
										<table id="example3" class="display table" style="width: 100%; cellspacing: 0;">
											<thead>
												<tr>
													<th>申请ID</th>
													<th>申请人</th>
													<th>申请人一卡通</th>
													<th>申请时间</th>
													<th>申请时长(秒)</th>
													<th>申请状态</th>
													<th>可用操作</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>申请ID</th>
													<th>申请人</th>
													<th>申请人一卡通</th>
													<th>申请时间</th>
													<th>申请时长(秒)</th>
													<th>申请状态</th>
													<th>可用操作</th>
												</tr>
											</tfoot>
											<tbody>
												<?php
												foreach($RequestList as $ID => $Data){
													echo "<tr><td>".$ID."</td><td>".$Data['name']."</td><td>".$Data['idcard']."</td><td data-name=\"time\">".date($TimeFormat,GetTimeStamp($Data['date'],$Data['time']." AM"))."</td><td data-name=\"length\">".$Data['timelength']."</td><td data-name=\"status\">";
													switch($Data['status']){
														case null:
															echo "<label class=\"label label-warning\">等待审核</label>";
															break;
														case "denied":
															echo "<label class=\"label label-default\">已驳回</label>";
															break;
														case "returned":
															echo "<label class=\"label label-default\">已归还</label>";
															break;
														case "proceed":
															echo "<label class=\"label label-success\">等待使用</label>";
															break;
														default:
															echo "<label class=\"label label-danger\">状态错误</label>";
															break;
													}
													echo "</td><td>";
													switch($Data['status']){
														case null:
														case "proceed":
															echo "<a href=\"javascript:GetDetail('".$ID."','processing')\" class=\"label label-info\">查看详情</label>";
															break;
														case "returned":
														case "denied":
															echo "<a href=\"javascript:GetDetail('".$ID."','completed')\" class=\"label label-info\">查看详情</label>";
															break;
													}
													echo "</td></tr>";
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Row -->
				</div>
				<!-- Main Wrapper -->
				<?php echo file_get_contents("../copyright.html"); ?>
			</div>
			<!-- /Page Inner -->
		</div>
		<!-- /Page Content -->
	</div>
	<!-- /Page Container -->


	<!-- Javascripts -->
	<script src="assets/plugins/jquery/jquery-3.1.0.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/uniform/js/jquery.uniform.standalone.js"></script>
	<script src="assets/plugins/switchery/switchery.min.js"></script>
	<script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/ecaps.min.js"></script>
	<script src="assets/js/pages/table-data.js"></script>
	<script>
		function GetDetail( FileID, Type ) {
			var url = "./requestsdetail.php";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "GET";
			turnForm.target = '_self';
			turnForm.action = url;

			var newElement0 = document.createElement( "input" );
			newElement0.setAttribute( "type", "hidden" );
			newElement0.setAttribute( "name", "FileID" ); // 标签名
			turnForm.appendChild( newElement0 );
			newElement0.setAttribute( "value", FileID ); // 赋值

			var newElement1 = document.createElement( "input" );
			newElement1.setAttribute( "type", "hidden" );
			newElement1.setAttribute( "name", "Type" ); // 标签名
			turnForm.appendChild( newElement1 );
			newElement1.setAttribute( "value", Type ); // 赋值

			turnForm.submit();
		}
	</script>
	<script>
		function StartCheck() {
			setInterval( CheckRequestTime(), 500 );
			setTimeout( function () { location.reload(); }, 10000 );
		}
		
		function GetTimeStamp( DateTimeString ) {
			alert
			var DateString = DateTimeString.substr( 0, DateTimeString.indexOf( " " ) );
			var TimeString = DateTimeString.substr( DateTimeString.indexOf( " " ) + 1 );
			
			var Year = Number( DateString.substr( 0, DateString.indexOf( "年" ) ) );
			var Month = Number( DateString.substr( DateString.indexOf( "年" ) + 1, DateString.indexOf( "月" ) - DateString.indexOf( "年" ) - 1 ) ) - 1;
			var Day = Number( DateString.substr( DateString.lastIndexOf( "月" ) + 1 , DateString.indexOf( "日" ) - DateString.indexOf( "月" ) - 1 ) );
			
			var Hour = Number( TimeString.substr( 0, TimeString.indexOf( ":" ) ) );
			var Minute = Number( TimeString.substr( TimeString.indexOf( ":" ) + 1, TimeString.lastIndexOf( ":" ) - TimeString.indexOf( ":" ) - 1 ) );
			var Second = Number( TimeString.substr( TimeString.lastIndexOf( ":" ) + 1 ) );
			
			return ( ( new Date( Year, Month, Day, Hour, Minute, Second, 0 ) ).getTime() );
		}
		
		function CheckRequestTime() {
			var DataTRs = $('#example3').children('tbody').children('tr');
			if ($(DataTRs).text().indexOf("无记录") != -1)
				return;
			var currentTime = (new Date).getTime();
			for ( var i = 0; i < DataTRs.length; i++ ) {
				var DataTDs = $(DataTRs[i]).children('td[data-name]');
				switch($(DataTDs[2]).text()){
					case "等待使用":
						var ReqTime = GetTimeStamp($(DataTDs[0]).text());
						if ( ReqTime <= currentTime) {
							$(DataTDs[2]).children(0).attr('class','label label-success');
							$(DataTDs[2]).children(0).text("正在使用");
						}else{
							break;
						}
					case "正在使用":
						var ReqTime = GetTimeStamp($(DataTDs[0]).text());
						var ReqLength = Number($(DataTDs[1]).text());
						if ( ReqTime + ReqLength * 1000 <= currentTime) {
							$(DataTDs[2]).children(0).attr('class','label label-danger');
							$(DataTDs[2]).children(0).text('超时未还');
						}
						break;
					case "超时未还":
					case "等待审核":
					case "已驳回":
					case "已归还":
					case "状态错误":
					default:
						continue;
						break;
				}
			}
		}
	</script>
</body>
</html>