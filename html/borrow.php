<?php
@include "../VarMap.php";
@include "../Functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['action'] == "submitrequest" ) {
	$Time = (String)$_POST['timehour'] . ":" . (String)$_POST['timeminute'];
	$Check = SubmitRequest($_POST['room'],$_POST['name'],$_POST['cardNum'],$_POST['tel'],$_POST['orgName'],$_POST['reason'],$_POST['date'],$Time,$_POST['length']);
	if ( $Check != false ) {
		echo "<script>alert(\"请求已成功提交 请记录ID方便查询 请求ID:$Check\");</script>";
	}else{
		echo "<script>alert(\"请求提交失败: Unknow ERROR Please Report To Administrator!\");</script>";
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
					<h3 class="breadcrumb-header"><strong>教室外借申请填写</strong></h3>
				</div>
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-white">
								<div class="panel-heading clearfix">
									<h4 class="panel-title">教室外借申请表 <strong><font color="red">填写前请先查询借用时间段是否空闲</font></strong></h4>
								</div>
								<div class="panel-body">
									<form class="form-horizontal" onSubmit="return form_Check()" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
										<div class="form-group">
											<button type="reset" class="btn btn-danger btn-rounded">清空申请表格</button>
											<button type="submit" class="btn btn-info btn-rounded">提交申请</button>
										</div>
										<div class="form-group">
											<label for="name" class="col-sm-2 control-label">申请人</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="name" name="name" required>
											</div>
										</div>
										<div class="form-group">
											<label for="cardNum" class="col-sm-2 control-label">一卡通号</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="cardNum" name="cardNum" required>
											</div>
											<label for="tel" class="col-sm-2 control-label">手机号</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="tel" name="tel" required>
											</div>
										</div>
										<div class="form-group">
											<label for="eula" class="col-sm-2 control-label">借用协议</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="eula" name="eula" placeholder="请在此输入 “我已阅读并同意借用协议”" required>
												<p class="help-block"><a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">查看借用协议</a></p>
											</div>
										</div>
										<div class="form-group">
											<label for="orgName" class="col-sm-2 control-label">借用单位</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="orgName" name="orgName" required>
											</div>
										</div>
										<div class="form-group">
											<label for="reason" class="col-sm-2 control-label">借用原因</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="reason" name="reason" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">借用教室</label>
											<div class="col-sm-4">
												<select style="margin-bottom: 15px;" name="room" id="room" class="form-control" required>
													<?php
													if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['selroom'])){
														echo "<option value\"".$_POST['selroom']."\">".$_POST['selroom']."</option>";
													}else{
														echo "<option>请选择</option>";
													}
													$RoomData = GetRoomInfoUser();
													foreach($RoomData as $RoomInfo){
														if ($RoomInfo['status'] === true){
															echo "<option value=\"".$RoomInfo['name']."\">".$RoomInfo['name']."</option>";
														}
													}
													?>
												</select>
											</div>
											<label class="col-sm-2 control-label">借用日期</label>
											<div class="col-sm-4">
												<input type="text" name="date" id="date" class="form-control date-picker" placeholder="点击以选择日期" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">借用开始时间</label>
											<div class="col-sm-2">
												<input name="timehour" type="number" min="0" max="23" class="form-control" placeholder="小时（24小时制）">
												<p class="help-block">小时（24小时制）</p>
											</div>
											<div class="col-sm-2">
												<input name="timeminute" type="number" min="0" max="59" class="form-control" >
												<p class="help-block">分钟</p>
											</div>
											<label class="col-sm-2 control-label">借用时长</label>
											<div class="col-sm-4">
												<select style="margin-bottom:15px;" name="length" class="form-control" required>
													<option value=1800>0小时30分钟</option>
													<option value=3600>1小时00分钟</option>
													<option value=5400>1小时30分钟</option>
													<option value=7200>2小时00分钟</option>
													<option value=9000>2小时30分钟</option>
													<option value=10800>3小时00分钟</option>
													<option value=12600>3小时30分钟</option>
													<option value=14400>4小时00分钟</option>
													<option value=16200>4小时30分钟</option>
													<option value=18000>5小时00分钟</option>
													<option value=19800>5小时30分钟</option>
													<option value=21600>6小时00分钟</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<button type="reset" class="btn btn-danger btn-rounded">清空申请表格</button>
											<button type="submit" name="action" value="submitrequest" class="btn btn-info btn-rounded">提交申请</button>
										</div>
									</form>
									<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="myModalLabel">教室借用协议</h4>
												</div>
												<div class="modal-body">
													<?php echo file_get_contents("./eula.html"); ?>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
												</div>
											</div>
										</div>
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
	<script src="assets/plugins/summernote-master/summernote.min.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/ecaps.min.js"></script>
	<script src="assets/js/pages/form-elements.js"></script>
	<script>
		function form_Check(){
			if ($('#room').find('option:selected').val() == "请选择") {
				alert("借用教室为空！");
				return false;
			}
			if ($('#date').val() == "") {
				alert("借用日期为空!");
				return false;
			}
			if ($('#time').val() == "") {
				alert("借用时间为空");
				return false;
			}
			if ($('#eula').val() !== "我已阅读并同意借用协议"){
				alert("请先同意借用协议");
				return false;
			}
			return true;
		}
	</script>
</body>
</html>