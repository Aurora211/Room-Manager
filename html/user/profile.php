<!DOCTYPE html>

<?php
@include "../../VarMap.php";
@include "../../Functions.php";

if (WhiteListCheck($_SERVER['HTTP_REFERER']) !== true){
	echo "<script>alert(\"". WhiteListCheck($_SERVER['HTTP_REFERER']). "\");location.href=\"./\"</script>";
}

$OldPasswordError = $NewPasswordError = null;
$EmailError = null;

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

if ( !empty( $_GET[ 'action' ] ) ) {
	switch ( $_GET[ 'action' ] ) {
		case "changepassword":
			if ( empty( $_POST[ 'oldpassword' ] ) )
				$OldPasswordError = "原密码不可为空";
			if ( empty( $_POST[ 'newpassword' ] ) )
				$NewPasswordError = "新密码不可为空";
			if ( empty( $_POST[ 'oldpassword' ] ) or empty( $_POST[ 'newpassword' ] ) )
				break;
			
			$OriginPassword = GetUserInfoByID( $UserData[ 'uid' ], "password" );
			if ( $OriginPassword == $_POST[ 'oldpassword' ] ) {
				if ( ChangePassword( $UserData[ 'uid' ], $_POST[ 'newpassword' ] ) )
					echo "<script>alert('密码修改成功！');location.href='./profile.php';</script>";
				else
					echo "<script>alert('密码修改出错！后续出现账号故障请联系超级管理员！');location.href='./profile.php';</script>";
			} else
				$OldPasswordError = "密码错误";
			break;
		case "changeemail":
			if ( empty( $_POST[ 'newemail' ] ) ) {
				$EmailError = "原密码不可为空";
				break;
			}
			if ( !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $_POST[ 'newemail' ] ) ) {
				$EmailError = "非法邮箱格式";
				break;
			}
			
			if ( ChangeEmail( $UserData[ 'uid' ], $_POST[ 'newemail' ] ) )
				echo "<script>alert('邮箱修改成功！');location.href='" . htmlspecialchars( $_SERVER[ 'PHP_SELF' ] ) . "';</script>";
			else
				echo "<script>alert('邮箱修改出错！后续出现账号故障请联系管理员！');location.href='" . htmlspecialchars( $_SERVER[ 'PHP_SELF' ] ) . "';</script>";
			break;
		case "destory":
			echo "<script>alert(\"该服务当前不可用\");</script>";
			break;
			destoryUID( $UserData[ 'UID' ] );
			Logout();
			echo "<script>location.href='../';</script>";
			exit;
			break;
		default:
			echo "<script>location.href='" . htmlspecialchars( $_SERVER[ 'PHP_SELF' ] ) . "';</script>";
	}
}

?>

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
	<link href="assets/plugins/nvd3/nv.d3.min.css" rel="stylesheet">

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

<body class="page-header-fixed page-sidebar-fixed">
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
					<h3 class="breadcrumb-header"><strong>账户设置</strong>&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload()" class="btn btn-default">刷新页面</a></h3>
				</div>

				<div id="main-wrapper">

					<div class="row">

						<div class="col-md-4">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">基本信息</h4>
								</div>
								<div class="panel-body user-profile-panel">
									<img src="./assets/images/logo.jpg" class="user-profile-image img-circle" alt="默认头像">
									<h4 class="text-center m-t-lg">
										<?php echo $UserData['name']; ?>
									</h4>
									<p class="text-center">用户ID:
										<?php echo $UserData['uid']; ?>
									</p>
									<hr>
									<ul class="list-unstyled text-center">
										<li>
											<p><i class="fa fa-info-circle m-r-xs"></i>
												<?php echo "管理员等级: ".$UserData['level']; ?>
											</p>
										</li>
										<li>
											<p><i class="fa fa-paper-plane-o m-r-xs"></i>
												<?php echo (!empty($UserData['email']))?$UserData['email']:"未绑定邮箱" ?>
											</p>
										</li>
									</ul>
									<hr>
									<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'].'?action=destory') ?>" class="btn btn-danger btn-block" disabled>销毁账号</a>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">修改密码</h4>
								</div>
								<div class="panel-body">
									<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'].'?action=changepassword'); ?>" method="post">
										<div class="form-group">
											<label>原密码</label>
											<input type="text" class="form-control" id="oldpassword" name="oldpassword" placeholder="请输入原密码" required>
											<span style="color: red">
												<?php echo $OldPasswordError; ?>
											</span>
										</div>
										<div class="form-group">
											<label>新密码</label>
											<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="请输入新密码" required>
											<span style="color: red">
												<?php echo $NewPasswordError; ?>
											</span>
										</div>
										<a href="tencent://message/?uin=<?php echo $ContractQQ; ?>&Menu=yes"><label class="fa fa-qq">忘记密码-联系管理员</label></a>
										<button type="submit" class="btn btn-primary btn-block">确认修改密码</button>
									</form>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">修改邮箱</h4>
								</div>
								<div class="panel-body">
									<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'].'?action=changeemail'); ?>" method="post">
										<div class="form-group">
											<label>原邮箱</label>
											<input type="email" class="form-control" id="oldemail" placeholder="未绑定邮箱" value="<?php echo $UserData['email']; ?>" readonly>
										</div>
										<div class="form-group">
											<label>新邮箱</label>
											<input type="email" class="form-control" id="newemail" name="newemail" placeholder="请输入新邮箱" required>
											<span style="color: red">
												<?php echo $EmailError; ?>
											</span>
										</div>
										<a href="tencent://message/?uin=<?php echo $ContractQQ; ?>&Menu=yes"><label class="fa fa-qq">联系管理员</label></a>
										<button type="submit" class="btn btn-primary btn-block">确认修改邮箱</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- Row -->
					<!-- Row -->
				</div>
				<!-- Main Wrapper -->
				<div class="page-footer">
					<p>网站后台管理系统 - Aurora211</p>
				</div>
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
	<script src="assets/plugins/d3/d3.min.js"></script>
	<script src="assets/plugins/nvd3/nv.d3.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.symbol.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="assets/plugins/chartjs/chart.min.js"></script>
	<script src="assets/js/ecaps.min.js"></script>
	<script src="assets/js/pages/dashboard.js"></script>
</body>
</html>