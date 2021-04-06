<?php
@include "../VarMap.php";
@include "../Functions.php";
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<!-- Title -->
	<title>麦庐大礼堂外借系统</title>

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

<body class="page-sidebar-fixed page-header-fixed">
	<!-- Page Container -->
	<div class="page-container">
		<!-- Page Sidebar -->
		<div class="page-sidebar">
			<a class="logo-box" href="index.html">
				<span>教室外借系统</span>
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
									<span>教室外借系统</span>
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
										<font size="4px" color="#0070E0" >麦庐大礼堂借用系统&nbsp;</font>
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
					<h3 class="breadcrumb-header"><strong>功能面板</strong></h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<a href="borrow.php">
							<div class="col-lg-4 col-md-6">
								<div class="panel panel-white stats-widget">
									<div class="panel-body">
										<div class="pull-left">
											<span class="stats-number"><strong>教室借阅申请</strong></span>
											<p class="stats-info">Borrow Request</p>
										</div>
									</div>
								</div>
							</div>
						</a>
						<a href="search.php?type=id">
							<div class="col-lg-4 col-md-6">
								<div class="panel panel-white stats-widget">
									<div class="panel-body">
										<div class="pull-left">
											<span class="stats-number"><strong>按 ID 查询记录</strong></span>
											<p class="stats-info">Search Request By ID</p>
										</div>
									</div>
								</div>
							</div>
						</a>
						<a href="search.php?type=room">
							<div class="col-lg-4 col-md-6">
								<div class="panel panel-white stats-widget">
									<div class="panel-body">
										<div class="pull-left">
											<span class="stats-number"><strong>按 教室 查询记录</strong></span>
											<p class="stats-info">Search Request By Room</p>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- Row -->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">
										系统教室列表&nbsp;
										<a href="javascript:location.reload()" class="btn btn-default">刷新页面</a>
									</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="example" class="display table" style="width: 100%; cellspacing: 0;">
											<thead>
												<tr>
													<th>名称</th>
													<th>大小</th>
													<th>备注</th>
													<th>状态</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>名称</th>
													<th>大小</th>
													<th>备注</th>
													<th>状态</th>
												</tr>
											</tfoot>
											<tbody>
												<?php
												$RoomData = GetRoomInfoUser();
												foreach($RoomData as $RoomInfo){
													echo "<tr><td>".$RoomInfo['name']."</td><td>";
													echo ($RoomInfo['size']==null)?"管理员未填写":$RoomInfo['size'];
													echo "</td><td>".$RoomInfo['remark']."</td><td>";
													switch($RoomInfo['status']){
														case true:
															echo "<label class=\"label label-success\">可借用</label></td></tr>";
															break;
														case false:
															echo "<label class=\"label label-danger\">不可用</label></td></tr>";
															break;
														default:
															echo "<label class=\"label label-default\">状态错误</label></td></tr>";
															break;
													}
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
	<script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/pages/table-data.js"></script>
</body>
</html>