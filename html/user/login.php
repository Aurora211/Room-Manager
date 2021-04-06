<?php
@include "../../VarMap.php";
@include "../../Functions.php";

$uiderror = $pwderror = null;
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	$Check = Login($_POST['uid'],$_POST['pwd']);
	if ($Check === false){
		$pwderror = "账户或密码错误";
	}else{
		CookieLink($Check);
		echo "<script>location.href=\"./\";</script>";
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

<body>

	<!-- Page Container -->
	<div class="page-container">
		<!-- Page Inner -->
		<div class="page-inner login-page">
			<div id="main-wrapper" class="container-fluid">
				<div class="row">
					<div class="col-sm-6 col-md-3 login-box">
						<h4 class="login-title">登陆管理员账户</h4>
						<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
							<div class="form-group">
								<label for="exampleInputEmail1">账户ID</label>
								<input type="text" class="form-control" id="udi" name="uid" required>
								<p style="color: red" class="help-block"><?php echo (isset($uiderror))?$uiderror:null ?></p>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">账户密码</label>
								<input type="password" class="form-control" id="pwd" name="pwd" required>
								<p style="color: red" class="help-block"><?php echo (isset($pwderror))?$pwderror:null ?></p>
							</div>
							<button type="submit" class="btn btn-primary btn-block">管理员登陆</button>
							<a href="../" class="btn btn-default btn-block">返回</a>
							<a href="tencent://message/?Menu=yes&uin=<?php echo $ContractQQ; ?>" class="forgot-link fa fa-qq">&nbsp;忘记密码，联系超管</a>
						</form>
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
</body>
</html>