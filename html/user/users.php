<?php
@include "../../VarMap.php";
@include "../../Functions.php";

if ( WhiteListCheck( $_SERVER[ 'HTTP_REFERER' ] ) !== true ) {
	echo "<script>alert(\"" . WhiteListCheck( $_SERVER[ 'HTTP_REFERER' ] ) . "\");location.href=\"./\"</script>";
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

if ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && $_POST[ 'action' ] == "adduser" ) {
	if ($UserData['level'] == 0 || $UserData['level'] == "Aurora211"){
		AddUser( $_POST[ 'uid' ], $_POST[ 'name' ], $_POST[ 'email' ], $_POST[ 'level' ], $_POST[ 'password' ] );
	}else{
		echo "<script>alert(\"你无权创建超级管理员账户\");</script>";
	}
}
if ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && $_POST[ 'action' ] == "changepwd" ) {
	if ($UserData['level'] == 0 || $UserData['level'] == "Aurora211" ){
		ChangePassword( $_POST[ 'uid' ], $_POST[ 'password' ] );
	}else{
		echo "<script>alert(\"你无权修改账户密码\");</script>";
	}
}
if ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && $_POST[ 'action' ] == "removeuser" ) {
	if ($UserData['level'] == 0 || $UserData['level'] == "Aurora211" ){
		RemoveUser( $_POST[ 'uid' ] );
	}else{
		echo "<script>alert(\"你无权删除账户\");</script>";
	}
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
										<font size="4px" color="#0070E0">麦庐大礼堂管理系统&nbsp;</font>
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
					<h3 class="breadcrumb-header"><strong>管理管理员用户</strong></h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading">
									<h4 class="panel-title">管理员列表</h4>
								</div>
								<div class="panel-body">
									<button type="button" class="btn btn-success m-b-sm" data-toggle="modal" data-target="#AddUser">添加管理员</button>
									<!-- Modal -->
									<form id="AddUserForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
										<div class="modal fade" id="AddUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">添加管理员</h4>
													</div>
													<div class="modal-body">
														<div class="form-group">
															<input type="text" id="uid" name="uid" class="form-control" placeholder="用户ID" required>
														</div>
														<div class="form-group">
															<input type="text" id="name" name="name" class="form-control" placeholder="用户昵称" required>
														</div>
														<div class="form-group">
															<input type="text" id="email" name="email" class="form-control" placeholder="用户邮箱">
														</div>
														<div class="form-group">
															<input type="number" id="level" name="level" class="form-control" placeholder="用户等级 默认请输入1，超级管理员请输入0" required>
														</div>
														<div class="form-group">
															<input type="text" id="password" name="password" class="form-control" placeholder="用户密码" required>
														</div>
													</div>
													<div class="modal-footer">
														<button type="reset" class="btn btn-danger">清空</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
														<button type="submit" name="action" value="adduser" class="btn btn-success">添加</button>
													</div>
												</div>
											</div>
										</div>
									</form>
									<div class="table-responsive">
										<table id="example3" class="display table" style="width: 100%; cellspacing: 0;">
											<thead>
												<tr>
													<th>UID</th>
													<th>昵称</th>
													<th>最后登陆时间</th>
													<th>可用操作</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>UID</th>
													<th>昵称</th>
													<th>最后登陆时间</th>
													<th>可用操作</th>
												</tr>
											</tfoot>
											<tbody>
												<?php
												$UserList = GetUserList();
												foreach ( $UserList as $UserInfo ) {
													echo "<tr><td>" . $UserInfo[ 'uid' ] . "</td><td>";
													if ($UserInfo['level'] === "0")
														echo "<font color=\"red\">[超管]</font>";
													echo $UserInfo[ 'name' ] . "</td><td>";
													echo date( $TimeFormat, $UserInfo[ 'latestlogin' ] );
													echo "</td><td>";
													echo "<a onClick=\"PwdInit('" . $UserInfo[ 'uid' ] . "')\" class=\"label label-info\" data-toggle=\"modal\" data-target=\"#ChangePwd\">修改密码</a>";
													echo "&nbsp;<a onClick=\"RemoveUSR('" . $UserInfo[ 'uid' ] . "')\" class=\"label label-danger\">移除</a>";
													echo "</td></tr>";
												}
												?>
											</tbody>
										</table>
									</div>
									<form id="ChangePwdForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
										<div class="modal fade" id="ChangePwd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">修改账户密码</h4>
													</div>
													<div class="modal-body">
														<div class="form-group">
															<input type="text" id="ChangePwduid" name="uid" class="form-control" placeholder="用户ID" required readonly>
														</div>
														<div class="form-group">
															<input type="text" name="password" class="form-control" placeholder="用户密码" required>
														</div>
													</div>
													<div class="modal-footer">
														<button type="reset" class="btn btn-danger">清空</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
														<button type="submit" name="action" value="changepwd" class="btn btn-success">确定修改</button>
													</div>
												</div>
											</div>
										</div>
									</form>
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
		function RemoveUSR( ID ) {
			var url = "<?php echo $_SERVER[ 'REQUEST_URI' ]; ?>";

			var turnForm = document.createElement( "form" );
			document.body.appendChild( turnForm );

			turnForm.method = "POST";
			turnForm.target = '_self';
			turnForm.action = url;

			var newElement0 = document.createElement( "input" );
			newElement0.setAttribute( "type", "hidden" );
			newElement0.setAttribute( "name", "action" ); // 标签名
			turnForm.appendChild( newElement0 );
			newElement0.setAttribute( "value", "removeuser" ); // 赋值

			var newElement1 = document.createElement( "input" );
			newElement1.setAttribute( "type", "hidden" );
			newElement1.setAttribute( "name", "uid" ); // 标签名
			turnForm.appendChild( newElement1 );
			newElement1.setAttribute( "value", ID ); // 赋值

			turnForm.submit();
		}
		
		function PwdInit( UID ) {
			document.getElementById("ChangePwduid").value = UID;
		}
	</script>
</body>
</html>