<?php
@include "../../VarMap.php";
@include "../../Functions.php";

//if (WhiteListCheck($_SERVER['HTTP_REFERER']) !== true){
//	echo "<script>alert(\"". WhiteListCheck($_SERVER['HTTP_REFERER']). "\");location.href=\"./\"</script>";
//}

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

if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
	if ( !isset( $_POST[ 'action' ] ) ) {
		echo "<script>alert(\"系统错误! Code:212\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $_POST[ 'action' ] ) {
		case "delete":
			DeleteRequest( $_POST[ 'FileID' ], $_POST[ 'Type' ] );
			echo "<script>alert(\"".$_POST['FileID']." 已删除\");location.href=\"./requests.php\"</script>";
			exit;
			break;
		case "proceed":
			SetProceedRequest( $_POST[ 'FileID' ], $_POST[ 'Type' ] );
			break;
		case "denied":
			SetDeniedRequest( $_POST[ 'FileID' ], $_POST[ 'Type' ] );
			echo "<script>location.href=\"" . $_SERVER['PHP_SELF'] . "?FileID=" . $_POST[ 'FileID' ] . "&Type=completed\";</script>";
			exit;
			break;
		case "return":
			SetReturnedRequest( $_POST[ 'FileID' ], $_POST[ 'Type' ] );
			echo "<script>location.href=\"" . $_SERVER['PHP_SELF'] . "?FileID=" . $_POST[ 'FileID' ] . "&Type=completed\";</script>";
			exit;
			break;
		default:
	}
}

$RequestDetail = array();
if ( isset( $_GET[ 'FileID'] ) && isset( $_GET[ 'Type' ] ) ) {
	$RequestDetail = GetRequestDetail( $_GET[ 'FileID' ], $_GET[ 'Type' ] );
}else{
	echo "<script>alert(\"系统错误! Code:212\");window.history.go(-1);</script>";
	exit;
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
	<link href="assets/plugins/summernote-master/summernote.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css"/>

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

<body class="page-sidebar-fixed page-header-fixed">

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
					<h3 class="breadcrumb-header"><strong>教室外借申请详情</strong>&nbsp;&nbsp;<font color="#FF0000">本页禁止重复提交表单</font></h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">教室外借申请表</h4>
								</div>
								<div class="panel-body">
									<form class="form-horizontal" onSubmit="return false" action="javascript:void(0)">
										<div class="form-group">
											<a href="./requests.php" class="btn btn-primary btn-rounded">返回请求管理</a>
											<?php
											switch($RequestDetail['status']){
												case null:
													echo "<a onClick=\"ProceedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-success btn-rounded\">通过申请</a>";
													echo "<a onClick=\"DeniedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-danger btn-rounded\">驳回申请</a>";
													break;
												case "proceed":
													echo "<a onClick=\"ReturnRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-warning btn-rounded\">设为已归还</a>";
													echo "<a onClick=\"DeniedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-danger btn-rounded\">修改为驳回申请</a>";
													break;
												case "returned":
												case "denied":
													echo "<a href=\"javascript:void(0)\" class=\"btn btn-default btn-rounded\" disabled>无可用操作</a>";
													break;
												default:
													echo "<a href=\"javascript:void(0)\" class=\"btn btn-danger btn-rounded\" disabled>该条记录状态参数错误</a>";
											}
											?>
											<a onClick="DeleteRequest('<?php echo $_GET['FileID']; ?>','<?php echo $_GET['Type'];?>')" class="btn btn-danger btn-rounded">删除该条记录</a>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">申请状态</label>
											<div class="col-sm-4">
												<a href="javascript:void(0)" class="btn btn-<?php
																					switch($RequestDetail['status']){
																						case null:
																							echo "info";
																							break;
																						case "proceed":
																							echo "success";
																							break;
																						case "returned":
																							echo "default";
																							break;
																						case "denied":
																							echo "default";
																							break;
																						default:
																							echo "danger";
																							break;
																					}
																					?>">
													<?php
													switch($RequestDetail['status']){
														case null:
															echo "待审核";
															break;
														case "proceed":
															echo "审核通过，待归还";
															break;
														case "returned":
															echo "已归还";
															break;
														case "denied":
															echo "已驳回";
															break;
														default:
															echo "状态参数错误";
															break;
													}
													?>
												</a>
											</div>
											<label class="col-sm-2 control-label">申请处理人</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['manager']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="name" class="col-sm-2 control-label">申请人</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['name']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="cardNum" class="col-sm-2 control-label">一卡通号</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['idcard']; ?>" readonly>
											</div>
											<label for="tel" class="col-sm-2 control-label">手机号</label>
											<div class="col-sm-4">
												<input type="tel" class="form-control" value="<?php echo $RequestDetail['telephone']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="orgName" class="col-sm-2 control-label">借用单位</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['company']; ?>" readonly>
											</div>
											<label for="reason" class="col-sm-2 control-label">借用原因</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['reason']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">借用教室</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value="<?php echo $RequestDetail['room']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">借用开始时间</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php $EndStamp = GetTimeStamp( $RequestDetail['date'], $RequestDetail['time'] . " AM" ); echo date( $TimeFormat, $EndStamp ); ?>" readonly>
											</div>
											<label class="col-sm-2 control-label">借用结束时间</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" value="<?php $EndStamp = GetTimeStamp( $RequestDetail['date'], $RequestDetail['time'] . " AM" ) + $RequestDetail['timelength']; echo date( $TimeFormat, $EndStamp ); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<a href="./requests.php" class="btn btn-primary btn-rounded">返回请求管理</a>
											<?php
											switch($RequestDetail['status']){
												case null:
													echo "<a onClick=\"ProceedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-success btn-rounded\">通过申请</a>";
													echo "<a onClick=\"DeniedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-danger btn-rounded\">驳回申请</a>";
													break;
												case "proceed":
													echo "<a onClick=\"ReturnRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-warning btn-rounded\">设为已归还</a>";
													echo "<a onClick=\"DeniedRequest('".$_GET['FileID']."','".$_GET['Type']."')\" class=\"btn btn-danger btn-rounded\">修改为驳回申请</a>";
													break;
												case "returned":
												case "denied":
													echo "<a href=\"javascript:void(0)\" class=\"btn btn-default btn-rounded\" disabled>无可用操作</a>";
													break;
												default:
													echo "<a href=\"javascript:void(0)\" class=\"btn btn-danger btn-rounded\" disabled>该条记录状态参数错误</a>";
											}
											?>
											<a onClick="DeleteRequest('<?php echo $_GET['FileID']; ?>','<?php echo $_GET['Type'];?>')" class="btn btn-danger btn-rounded">删除该条记录</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- Row -->
				</div>
				<!-- Main Wrapper -->
				<?php echo file_get_contents("./copyright.html"); ?>
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
	<script src="assets/plugins/summernote-master/summernote.min.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/ecaps.min.js"></script>
	<script src="assets/js/pages/form-elements.js"></script>
	<script>
		function DeleteRequest( FileID, Type ) {
			var url = "<?php $_SERVER['REQUEST_URI'] ?>";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "POST";
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
			
			var newElement2 = document.createElement( "input" );
			newElement2.setAttribute( "type", "hidden" );
			newElement2.setAttribute( "name", "action" ); // 标签名
			turnForm.appendChild( newElement2 );
			newElement2.setAttribute( "value", "delete" ); // 赋值

			turnForm.submit();
		}
		
		function ProceedRequest( FileID, Type ) {
			var url = "<?php $_SERVER['REQUEST_URI'] ?>";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "POST";
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
			
			var newElement2 = document.createElement( "input" );
			newElement2.setAttribute( "type", "hidden" );
			newElement2.setAttribute( "name", "action" ); // 标签名
			turnForm.appendChild( newElement2 );
			newElement2.setAttribute( "value", "proceed" ); // 赋值

			turnForm.submit();
		}
		
		function DeniedRequest( FileID, Type ) {
			var url = "<?php $_SERVER['REQUEST_URI'] ?>";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "POST";
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
			
			var newElement2 = document.createElement( "input" );
			newElement2.setAttribute( "type", "hidden" );
			newElement2.setAttribute( "name", "action" ); // 标签名
			turnForm.appendChild( newElement2 );
			newElement2.setAttribute( "value", "denied" ); // 赋值

			turnForm.submit();
		}
		
		function ReturnRequest( FileID, Type ) {
			var url = "<?php $_SERVER['REQUEST_URI'] ?>";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "POST";
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
			
			var newElement2 = document.createElement( "input" );
			newElement2.setAttribute( "type", "hidden" );
			newElement2.setAttribute( "name", "action" ); // 标签名
			turnForm.appendChild( newElement2 );
			newElement2.setAttribute( "value", "return" ); // 赋值

			turnForm.submit();
		}
	</script>
</body>
</html>