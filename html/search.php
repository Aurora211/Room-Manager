<?php
@include "../VarMap.php";
@include "../Functions.php";
//echo "<script>alert(\"为防止网络爬虫使服务器崩溃，此页面不开放使用需要查询请联系管理员\");location.href=\"./\";</script>";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['filter'])){
	$list = SearchRequestGuest( $_GET['type'], $_POST['keyword'], $_POST['filter'] );
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
										<font size="4px" color="#0070E0">麦庐大礼堂借用系统&nbsp;</font>
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
					<h3 class="breadcrumb-header">
						<strong><?php
						switch($_GET['type']){
							case "id":
								echo "按一卡通检索记录";
								break;
							case "room":
								echo "按教室检索记录";
								break;
							default:
								echo "系统错误:未知检索方式";
								echo "<script>alert(\"系统错误:未知检索方式\");location.href=\"./\";</script>";
								break;
						}
						?></strong>
					</h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
                                <div class="panel-body">
                                    <form class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                                        <div class="form-group">
                                            <label for="searchtarget" class="col-sm-2 control-label">待检索内容</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="keyword" required>
                                            </div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<center>
													<?php
													switch($_GET['type']){
														case "id":
															echo "<button type=\"submit\" name=\"filter\" value=\"all\" class=\"btn btn-info\">所有项目</button> ";
															echo "<button type=\"submit\" name=\"filter\" value=\"proceed\" class=\"btn btn-success\">已通过项目</button> ";
															echo "<button type=\"submit\" name=\"filter\" value=\"denied\" class=\"btn btn-danger\">已驳回项目</button>";
															break;
														case "room":
															echo "<button type=\"submit\" name=\"filter\" value=\"proceed\" class=\"btn btn-info\">已通过项目</button>";
															break;
													}
													?>
												</center>
											</div>
                                        </div>
                                    </form>
                                </div>
                            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">查询结果</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="example" class="display table" style="width: 100%; cellspacing: 0;">
											<thead>
												<tr>
													<th>记录ID</th>
													<th>申请人</th>
													<th>申请人一卡通</th>
													<th>申请教室</th>
													<th>借用时间</th>
													<th>申请状态</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>记录ID</th>
													<th>申请人</th>
													<th>申请人一卡通</th>
													<th>申请教室</th>
													<th>借用时间</th>
													<th>申请状态</th>
												</tr>
											</tfoot>
											<tbody id="datatable">
												<?php
												foreach($list as $FileID => $RequestData){
													echo "<tr><td>";
													echo $FileID;
													echo "</td><td>";
													echo $RequestData['name'];
													echo "</td><td>";
													echo $RequestData['idcard'];
													echo "</td><td>";
													echo $RequestData['room'];
													echo "</td><td>";
													echo date($TimeFormat,GetTimeStamp($RequestData['date'],$RequestData['time']." AM"));
													echo "</td><td>";
													switch ($RequestData['status']){
														case null:
															echo "<label class=\"label label-info\">正在审核</label>";
															break;
														case "proceed":
															echo "<label class=\"label label-success\">审核通过</label>";
															break;
														case "denied":
															echo "<label class=\"label label-warning\">审核驳回</label>";
															break;
														case "returned":
															echo "<label class=\"label label-default\">归还成功</label>";
															break;
														default:
															echo "<label class=\"label label-danger\">状态错误</label>";
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
				<?php echo file_get_contents("./copyright.html"); ?>
			</div>
			<!-- /Page Inner -->
			<div class="page-right-sidebar" id="main-right-sidebar">
				<div class="page-right-sidebar-inner">
					<div class="right-sidebar-top">
						<div class="right-sidebar-tabs">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active" id="chat-tab"><a href="#chat" aria-controls="chat" role="tab" data-toggle="tab">chat</a>
								</li>
								<li role="presentation" id="settings-tab"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">settings</a>
								</li>
							</ul>
						</div>
						<a href="javascript:void(0)" class="right-sidebar-toggle right-sidebar-close" data-sidebar-id="main-right-sidebar"><i class="icon-close"></i></a>
					</div>
					<div class="right-sidebar-content">
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="chat">
								<div class="chat-list">
									<span class="chat-title">Recent</span>
									<a href="javascript:void(0);" class="right-sidebar-toggle chat-item unread" data-sidebar-id="chat-right-sidebar">
										<div class="user-avatar">
											<img src="http://via.placeholder.com/40x40" alt="">
										</div>
										<div class="chat-info">
											<span class="chat-author">David</span>
											<span class="chat-text">where u at?</span>
											<span class="chat-time">08:50</span>
										</div>
									</a>
									<a href="javascript:void(0);" class="right-sidebar-toggle chat-item unread active-user" data-sidebar-id="chat-right-sidebar">
										<div class="user-avatar">
											<img src="http://via.placeholder.com/40x40" alt="">
										</div>
										<div class="chat-info">
											<span class="chat-author">Daisy</span>
											<span class="chat-text">Daisy sent a photo.</span>
											<span class="chat-time">11:34</span>
										</div>
									</a>
								</div>
								<div class="chat-list">
									<span class="chat-title">Older</span>
									<a href="javascript:void(0);" class="right-sidebar-toggle chat-item" data-sidebar-id="chat-right-sidebar">
										<div class="user-avatar">
											<img src="http://via.placeholder.com/40x40" alt="">
										</div>
										<div class="chat-info">
											<span class="chat-author">Tom</span>
											<span class="chat-text">You: ok</span>
											<span class="chat-time">2d</span>
										</div>
									</a>
									<a href="javascript:void(0);" class="right-sidebar-toggle chat-item active-user" data-sidebar-id="chat-right-sidebar">
										<div class="user-avatar">
											<img src="http://via.placeholder.com/40x40" alt="">
										</div>
										<div class="chat-info">
											<span class="chat-author">Anna</span>
											<span class="chat-text">asdasdasd</span>
											<span class="chat-time">4d</span>
										</div>
									</a>
									<a href="javascript:void(0);" class="right-sidebar-toggle chat-item active-user" data-sidebar-id="chat-right-sidebar">
										<div class="user-avatar">
											<img src="http://via.placeholder.com/40x40" alt="">
										</div>
										<div class="chat-info">
											<span class="chat-author">Liza</span>
											<span class="chat-text">asdasdasd</span>
											<span class="chat-time">&nbsp;</span>
										</div>
									</a>
									<a href="javascript:void(0);" class="load-more-messages" data-toggle="tooltip" data-placement="bottom" title="Load More">&bull;&bull;&bull;</a>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="settings">
								<div class="right-sidebar-settings">
									<span class="settings-title">General Settings</span>
									<ul class="sidebar-setting-list list-unstyled">
										<li>
											<span class="settings-option">Notifications</span><input type="checkbox" class="js-switch" checked/>
										</li>
										<li>
											<span class="settings-option">Activity log</span><input type="checkbox" class="js-switch" checked/>
										</li>
										<li>
											<span class="settings-option">Automatic updates</span><input type="checkbox" class="js-switch"/>
										</li>
										<li>
											<span class="settings-option">Allow backups</span><input type="checkbox" class="js-switch"/>
										</li>
									</ul>
									<span class="settings-title">Account Settings</span>
									<ul class="sidebar-setting-list list-unstyled">
										<li>
											<span class="settings-option">Chat</span><input type="checkbox" class="js-switch" checked/>
										</li>
										<li>
											<span class="settings-option">Incognito mode</span><input type="checkbox" class="js-switch"/>
										</li>
										<li>
											<span class="settings-option">Public profile</span><input type="checkbox" class="js-switch"/>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="page-right-sidebar" id="chat-right-sidebar">
				<div class="page-right-sidebar-inner">
					<div class="right-sidebar-top">
						<div class="chat-top-info">
							<span class="chat-name">Noah</span>
							<span class="chat-state">2h ago</span>
						</div>
						<a href="javascript:void(0)" class="right-sidebar-toggle chat-sidebar-close pull-right" data-sidebar-id="chat-right-sidebar"><i class="icon-keyboard_arrow_right"></i></a>
					</div>
					<div class="right-sidebar-content">
						<div class="right-sidebar-chat slimscroll">
							<div class="chat-bubbles">
								<div class="chat-start-date">02/06/2017 5:58PM</div>
								<div class="chat-bubble them">
									<div class="chat-bubble-img-container">
										<img src="http://via.placeholder.com/38x38" alt="">
									</div>
									<div class="chat-bubble-text-container">
										<span class="chat-bubble-text">Hello</span>
									</div>
								</div>
								<div class="chat-bubble me">
									<div class="chat-bubble-text-container">
										<span class="chat-bubble-text">Hello!</span>
									</div>
								</div>
								<div class="chat-start-date">03/06/2017 4:22AM</div>
								<div class="chat-bubble me">
									<div class="chat-bubble-text-container">
										<span class="chat-bubble-text">lorem</span>
									</div>
								</div>
								<div class="chat-bubble them">
									<div class="chat-bubble-img-container">
										<img src="http://via.placeholder.com/38x38" alt="">
									</div>
									<div class="chat-bubble-text-container">
										<span class="chat-bubble-text">ipsum dolor sit amet</span>
									</div>
								</div>
							</div>
						</div>
						<div class="chat-write">
							<form class="form-horizontal" action="javascript:void(0);">
								<input type="text" class="form-control" placeholder="Say something">
							</form>
						</div>
					</div>
				</div>
			</div>
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
	<script src="assets/js/ecaps.min.js"></script>
	<script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/pages/table-data.js"></script>
</body>
</html>