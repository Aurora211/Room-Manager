<?php

function WhiteListCheck( $Site ) {

	$PROTOCOL = null; //协议
	$DOMAIN = null; //域名
	//$SERVERPORT = null;	//端口
	$WHITELIST = null; //白名单
	$SITE_PART[] = null; //URL切分后数组 0=>协议 1=>域名 1+=>路径

	//协议检测开始
	if ( stristr( $_SERVER[ 'SERVER_PROTOCOL' ], "https" ) !== false )
		$PROTOCOL = "https://";
	elseif ( stristr( $_SERVER[ 'SERVER_PROTOCOL' ], "http" ) !== false )
		$PROTOCOL = "http://";
	else
		return "PROHIBITED_PROTOCOL";
	//协议检测结束

	//域名检测开始
	$DOMAIN = $_SERVER[ 'HTTP_HOST' ];
	if ( stristr( $Site, $DOMAIN ) === false )
		return "UNAUTHORIZED_DOMAIN";
	//域名检测结束

	//URL切分开始
	$PART = strtok( $Site, "/" );
	$NUM = 0;
	while ( $PART != false ) {
		$SITE_PART[ $NUM ] = $PART;
		$PART = strtok( "/" );
		$NUM++;
	}
	//URL切分结束

	//网站路径检测开始
	if ( $SITE_PART[ 2 ] == "user" ) {
		return true;
	}
	return "PROHIBITED_SITE";
}

function CreateDefaultSuperAdmin() {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] ) )
		mkdir( $GLOBALS[ 'UserDataPath' ] );
	//if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . "Aurora211.json" ) ) {
	//	AddUser( "Aurora211", "SuperAdmin", "", "Aurora211", "Misaka20001" );	//新建隐藏超级管理员
	//}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . "0.json" ) ) {
		AddUser( "0", "超级管理员", "", "0", "123456" );	//新建默认超级管理员
	}
}

function Login( $uid, $pwd ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $_POST[ 'uid' ] ) || !isset( $_POST[ 'pwd' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4221\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4124 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		return 4312;
	}

	$UserData = json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ), true );
	if ( $uid != $UserData[ 'uid' ] ) {
		echo "<script>alert(\"系统错误! Code:42F0\");window.history.go(-1);</script>";
		exit;
	}
	if ( $UserData[ 'banned' ] == true ) {
		return "banned";
	}
	if ( $pwd === $UserData[ 'password' ] && $UserData[ 'banned' ] != true ) {
		return $UserData[ 'uid' ];
	}
	return false;
}

function Logout() {
	if ( !isset( $GLOBALS[ 'CookiePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 CookiePath\");window.history.go(-1);</script>";
	}
	if ( file_exists( $GLOBALS[ 'CookiePath' ] . $_COOKIE[ 'User' ] . ".json" ) ) {
		unlink( $GLOBALS[ 'CookiePath' ] . $_COOKIE[ 'User' ] . ".json" );
	}
	setcookie( "User", "", time() - 3600, "/" );
}

function CookieLink( $uid ) {
	if ( !isset( $GLOBALS[ 'CookieLife' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 CookieLife\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'CookiePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 CookiePath\");window.history.go(-1);</script>";
		exit;
	}

	$CookieData = array(
		"uid" => $uid,
		"value" => md5( $uid . time() ),
		"life" => $GLOBALS[ 'CookieLife' ]
	);
	if ( !file_exists( $GLOBALS[ 'CookiePath' ] ) )
		mkdir( $GLOBALS[ 'CookiePath' ] );
	if ( file_exists( $GLOBALS[ 'CookiePath' ] . $CookieData[ 'value' ] . ".json" ) ) {
		echo "<script>alert(\"系统错误! Code:4120\");window.history.go(-1);</script>";
		exit;
	}
	$CookieFile = fopen( $GLOBALS[ 'CookiePath' ] . $CookieData[ 'value' ] . ".json", "x+" );
	fwrite( $CookieFile, json_encode( $CookieData ) );
	fclose( $CookieFile );

	setcookie( "User", $CookieData[ 'value' ], time() + $CookieData[ 'life' ], "/" );
}

function CheckCookie() {
	if ( !isset( $GLOBALS[ 'CookiePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 CookiePath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'CookiePath' ] . $_COOKIE[ 'User' ] . ".json" ) ) {
		return false;
	}
	$ServerData = json_decode( file_get_contents( $GLOBALS[ 'CookiePath' ] . $_COOKIE[ 'User' ] . ".json" ), true );
	unlink( $GLOBALS[ 'CookiePath' ] . $_COOKIE[ 'User' ] . ".json" );
	CookieLink( $ServerData[ 'uid' ] );

	$UserData = json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $ServerData[ 'uid' ] . ".json" ), true );
	$UserData[ 'latestlogin' ] = time();
	$file = fopen( $GLOBALS[ 'UserDataPath' ] . $ServerData[ 'uid' ] . ".json", "w+" );
	fwrite( $file, json_encode( $UserData ) );
	fclose( $file );

	if ( $UserData[ 'banned' ] == true ) {
		return "banned";
	}

	return $ServerData[ 'uid' ];
}

function ClearOutDatedCookieFile() {
	if ( !isset( $GLOBALS[ 'CookiePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 CookiePath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'CookiePath' ] ) )
		return true;
	$CurrentTime = time();
	$Dir = opendir( $GLOBALS[ 'CookiePath' ] );
	while ( ( $File = readdir( $Dir ) ) != false ) {
		if ( $File == "."
			or $File == ".." )
			continue;
		$CookieLife = json_decode( file_get_contents( $GLOBALS[ 'CookiePath' ] . $File ), true )[ 'life' ];
		$FileTime = filectime( $GLOBALS[ 'CookiePath' ] . $File );
		if ( $FileTime + $CookieLife < $CurrentTime )
			unlink( $GLOBALS[ 'CookiePath' ] . $File );
	}
	return true;
}

function GetUserInfoByID( $uid, $filter = "all" ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		echo "<script>alert(\"系统错误! Code:4312\");</script>";
		return false;
	}
	$UserData = json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ), true );
	if ( $filter == "all" ) {
		$UserData[ 'password' ] = null;
		return $UserData;
	} else {
		return $UserData[ $filter ];
	}
}

function ChangePassword( $uid, $pwd ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		echo "<script>alert(\"系统错误! Code:4312\");;</script>";
		return false;
	}
	$UserData = json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ), true );
	$UserData[ 'password' ] = $pwd;
	$File = fopen( $GLOBALS[ 'UserDataPath' ] . $uid . ".json", "w+" );
	fwrite( $File, json_encode( $UserData ) );
	fclose( $File );
	return true;
}

function ChangeEmail( $uid, $email ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		echo "<script>alert(\"系统错误! Code:4312\");;</script>";
		return false;
	}
	$UserData = json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ), true );
	$UserData[ 'email' ] = $email;
	$File = fopen( $GLOBALS[ 'UserDataPath' ] . $uid . ".json", "w+" );
	fwrite( $File, json_encode( $UserData ) );
	fclose( $File );
	return true;
}

function GetUserList() {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !file_exists( $GLOBALS[ 'UserDataPath' ] ) )
		return array();
	$UserList = array();
	$Dir = opendir( $GLOBALS[ 'UserDataPath' ] );
	while ( ( $File = readdir( $Dir ) ) != false ) {
		if ( $File == "."
			or $File == ".."
			or is_dir( $GLOBALS[ 'UserDataPath' ] . $File ) )
			continue;
		$temp = substr( $File, 0, strripos( $File, ".json" ) );
		$UserData = GetUserInfoByID( $temp );
		if ( $UserData[ 'level' ] === "Aurora211" ) {
			continue;
		}
		array_push( $UserList, $UserData );
	}
	return $UserList;
}

function AddUser( $uid, $name, $email, $level, $password ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		echo "<script>alert(\"用户已存在! Code:0\");window.history.go(-1);</script>";
		return false;
	}
	$UserData = array(
		"uid" => $uid,
		"name" => $name,
		"email" => $email,
		"level" => $level,
		"banned" => false,
		"password" => $password,
		"latestlogin" => 0
	);
	$file = fopen( $GLOBALS[ 'UserDataPath' ] . $uid . ".json", "x+" );
	fwrite( $file, json_encode( $UserData ) );
	fclose( $file );
	return true;
}

function RemoveUser( $uid ) {
	if ( !isset( $GLOBALS[ 'UserDataPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserDataPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( file_exists( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ) ) {
		if ( json_decode( file_get_contents( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" ), true )[ 'level' ] === "Aurora211" )
			return true;
		unlink( $GLOBALS[ 'UserDataPath' ] . $uid . ".json" );
	}
	return true;
}

function GetRoomInfo( $filter = "all" ) {
	if ( !isset( $GLOBALS[ 'DataAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 DataAdminPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'DataAdminPath' ] . "room.json" ) )
		return null;
	$File = json_decode( file_get_contents( $GLOBALS[ 'DataAdminPath' ] . "room.json" ), true )[ 'rooms' ];
	if ( $filter == "all" )
		return $File;
	else
		return $File[ $filter ];
}

function GetRoomInfoUser( $filter = "all" ) {
	if ( !isset( $GLOBALS[ 'DataGetPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 DataGetPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'DataGetPath' ] . "room.json" ) )
		return null;
	$File = json_decode( file_get_contents( $GLOBALS[ 'DataGetPath' ] . "room.json" ), true )[ 'rooms' ];
	if ( $filter == "all" )
		return $File;
	else
		return $File[ $filter ];
}

function AddRoom( $name, $size, $remark, $status, $start, $end ) {
	if ( !isset( $GLOBALS[ 'DataAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 DataAdminPath\");window.history.go(-1);</script>";
		exit;
	}

	$RoomInfo = array(
		"name" => $name,
		"size" => $size,
		"remark" => $remark,
		"status" => $status,
		"time" => array(
			"start" => $start,
			"end" => $end
		)
	);

	if ( !file_exists( $GLOBALS[ 'DataAdminPath' ] . "room.json" ) ) {
		$File = fopen( $GLOBALS[ 'DataAdminPath' ] . "room.json", "x+" );
		$FileData = array(
			"rooms" => array( $RoomInfo )
		);
		fwrite( $File, json_encode( $FileData ) );
		fclose( $File );
	} else {
		$FileData = json_decode( file_get_contents( $GLOBALS[ 'DataAdminPath' ] . "room.json" ), true );
		foreach ( $FileData[ 'rooms' ] as $Roomdata ) {
			if ( $Roomdata[ 'name' ] == $name ) {
				echo "<script>alert(\"房间已存在\");</script>";
				return;
			}
		}
		array_push( $FileData[ 'rooms' ], $RoomInfo );
		$File = fopen( $GLOBALS[ 'DataAdminPath' ] . "room.json", "w+" );
		fwrite( $File, json_encode( $FileData ) );
		fclose( $File );
	}
}

function EditRoom( $name, $size, $remark, $status, $start, $end ) {
	if ( !isset( $GLOBALS[ 'DataAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 DataAdminPath\");window.history.go(-1);</script>";
		exit;
	}

	$RoomInfo = array(
		"name" => $name,
		"size" => $size,
		"remark" => $remark,
		"status" => $status,
		"time" => array(
			"start" => $start,
			"end" => $end
		)
	);

	if ( !file_exists( $GLOBALS[ 'DataAdminPath' ] . "room.json" ) ) {
		$File = fopen( $GLOBALS[ 'DataAdminPath' ] . "room.json", "x+" );
		$FileData = array(
			"rooms" => array( $RoomInfo )
		);
		fwrite( $File, json_encode( $FileData ) );
		fclose( $File );
	} else {
		$FileData = json_decode( file_get_contents( $GLOBALS[ 'DataAdminPath' ] . "room.json" ), true );
		$Count = count( $FileData['rooms'] );
		for ( $index = 0; $index < $Count; $index++ ) {
			if ( $FileData[ 'rooms' ][ $index ][ 'name' ] == $name ) {
				$FileData[ 'rooms' ][ $index ] = $RoomInfo;
				break;
			}
		}
		$File = fopen( $GLOBALS[ 'DataAdminPath' ] . "room.json", "w+" );
		fwrite( $File, json_encode( $FileData ) );
		fclose( $File );
	}
}

function RemoveRoom( $name ) {
	if ( !isset( $GLOBALS[ 'DataAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 DataAdminPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( file_exists( $GLOBALS[ 'DataAdminPath' ] . "room.json" ) ) {
		$FileData = json_decode( file_get_contents( $GLOBALS[ 'DataAdminPath' ] . "room.json" ), true );
		foreach ( $FileData[ 'rooms' ] as $id => $Roomdata ) {
			if ( $Roomdata[ 'name' ] == $name ) {
				unset( $FileData[ 'rooms' ][ $id ] );
			}
		}
		$File = fopen( $GLOBALS[ 'DataAdminPath' ] . "room.json", "w+" );
		fwrite( $File, json_encode( $FileData ) );
		fclose( $File );
	}
}

function SubmitRequest( $room, $name, $idcard, $tel, $org, $reason, $date, $time, $length ) {
	if ( !isset( $GLOBALS[ 'RequestSavePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestSavePath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'TimeFormatShort' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 TimeFormatShort\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'RequestSavePath' ] ) );
	mkdir( $GLOBALS[ 'RequestSavePath' ] );

	if ( !file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) );
	mkdir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );

	$RequestData = array(
		"room" => $room,
		"name" => $name,
		"idcard" => $idcard,
		"telephone" => $tel,
		"company" => $org,
		"reason" => $reason,
		"date" => $date,
		"time" => $time,
		"timelength" => $length,
		"requesttime" => time(),
		"status" => null,
		"manager" => null
	);

	$index = 0;
	$currenttime = date( $GLOBALS[ 'TimeFormatShort' ], time() );

	while ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $currenttime . "-" . $index . ".json" ) )
		$index++;

	$ID = $currenttime . "-" . $index;
	$File = fopen( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $ID . ".json", "x+" );
	fwrite( $File, json_encode( $RequestData ) );
	fclose( $File );
	return $ID;
}

function GetRequestInfo( $filter = "all" ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}

	if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] ) )
		return array();
	$list = array();
	switch ( $filter ) {
		case "all":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) && !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "returned/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ) )
						continue;
					$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true ) );
				}
			}
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $File ) )
						continue;
					$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $File ), true ) );
				}
			}
			return $list;
			break;
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ) )
						continue;
					$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true ) );
				}
			}
			return $list;
			break;
		case "completed":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $File ) )
						continue;
					$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $File ), true ) );
				}
			}
			return $list;
			break;
		case "unviewed":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ) )
						continue;
					$data = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true );
					if ( $data[ 'status' ] == null ) {
						$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true ) );
					}
				}
			}
			return $list;
			break;
		case "proceed":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ) )
						continue;
					$data = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true );
					if ( $data[ 'status' ] == "proceed" ) {
						$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true ) );
					}
				}
			}
			return $list;
			break;
		case "denied":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) )
				return array();
			if ( file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" ) ) {
				$Dir = opendir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" );
				while ( ( $File = readdir( $Dir ) ) != false ) {
					if ( $File == "."
						or $File == ".."
						or is_dir( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ) )
						continue;
					$data = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true );
					if ( $data[ 'status' ] == "denied" ) {
						$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $File ), true ) );
					}
				}
			}
			return $list;
			break;
	}
	return $list;
}

function GetRequestDetail( $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	$info = array();
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) )
				break;
			$info = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ), true );
			break;
		case "completed":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" ) )
				break;
			$info = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" ), true );
			break;
		default:
			echo "<script>alert(\"系统错误! Code:212 Type\");</script>";
			break;
	}
	return $info;
}

function RequestAdminOption( $Option, $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) )
				break;
	}
}

function GetTimeStamp( $Date, $Time ) {
	$Year = ( int )( substr( $Date, 0, stripos( $Date, "/" ) ) );
	$Month = ( int )( substr( $Date, stripos( $Date, "/" ) + 1, strripos( $Date, "/" ) - stripos( $Date, "/" ) - 1 ) );
	$Day = ( int )( substr( $Date, strripos( $Date, "/" ) + 1 ) );
	$TimeType = substr( $Time, stripos( $Time, " " ) + 1 );
	$Time = substr( $Time, 0, stripos( $Time, " " ) );
	if ( $TimeType == "AM" ) {
		$Hour = ( int )( substr( $Time, 0, stripos( $Time, ":" ) ) );
	} elseif ( $TimeType == "PM" ) {
		$Hour = ( int )( substr( $Time, 0, stripos( $Time, ":" ) ) ) + 12;
	} else {
		$Hour = ( int )( substr( $Time, 0, stripos( $Time, ":" ) ) );
	}
	$Minute = ( int )( substr( $Time, stripos( $Time, ":" ) + 1 ) );
	$Second = 0;
	return mktime( $Hour, $Minute, $Second, $Month, $Day, $Year );
}

function ShearFile( $src, $target, $force = false ) {
	if ( !file_exists( $src ) )
		return false;
	if ( file_exists( $target ) && $force == false )
		return false;
	if ( !copy( $src, $target ) )
		return false;
	unlink( $src );
	return true;
}

function DeleteRequest( $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) )
				break;
			unlink( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" );
			break;
		case "completed":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" ) )
				break;
			unlink( $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" );
			break;
		default:
			echo "<script>alert(\"系统错误! Code:212 Type\");</script>";
			break;
	}
}

function SetProceedRequest( $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'UserData' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserData\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) ) {
				echo "<script>alert(\"系统错误! 请求丢失 Code:4125 " . $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" . "\");</script>";
				return false;
			}
			$info = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ), true );
			$info[ 'status' ] = "proceed";
			$info[ 'manager' ] = $GLOBALS[ 'UserData' ][ 'uid' ];
			$File = fopen( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json", "w+" );
			fwrite( $File, json_encode( $info ) );
			fclose( $File );
			return true;
			break;
		case "completed":
		default:
			echo "<script>alert(\"系统错误! Code:212 Type\");</script>";
			break;
	}
	return false;
}

function SetDeniedRequest( $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'UserData' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserData\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) ) {
				echo "<script>alert(\"系统错误! 请求丢失 Code:4125 " . $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" . "\");</script>";
				return false;
			}
			$info = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ), true );
			$info[ 'status' ] = "denied";
			$info[ 'manager' ] = $GLOBALS[ 'UserData' ][ 'uid' ];
			$File = fopen( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json", "w+" );
			fwrite( $File, json_encode( $info ) );
			fclose( $File );
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" ) )
				mkdir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" );
			return ShearFile( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json", $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" );
			break;
		case "completed":
		default:
			echo "<script>alert(\"系统错误! Code:212 Type\");</script>";
			break;
	}
	return false;
}

function SetReturnedRequest( $FileID, $Type ) {
	if ( !isset( $GLOBALS[ 'RequestAdminPath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestAdminPath\");window.history.go(-1);</script>";
		exit;
	}
	if ( !isset( $GLOBALS[ 'UserData' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 UserData\");window.history.go(-1);</script>";
		exit;
	}
	switch ( $Type ) {
		case "processing":
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ) ) {
				echo "<script>alert(\"系统错误! 请求丢失 Code:4125 " . $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" . "\");</script>";
				return false;
			}
			$info = json_decode( file_get_contents( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json" ), true );
			$info[ 'status' ] = "returned";
			$info[ 'manager' ] = $GLOBALS[ 'UserData' ][ 'uid' ];
			$File = fopen( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json", "w+" );
			fwrite( $File, json_encode( $info ) );
			fclose( $File );
			if ( !file_exists( $GLOBALS[ 'RequestAdminPath' ] . "completed/" ) )
				mkdir( $GLOBALS[ 'RequestAdminPath' ] . "completed/" );
			return ShearFile( $GLOBALS[ 'RequestAdminPath' ] . "processing/" . $FileID . ".json", $GLOBALS[ 'RequestAdminPath' ] . "completed/" . $FileID . ".json" );
			break;
		case "completed":
		default:
			echo "<script>alert(\"系统错误! Code:212 Type\");</script>";
			break;
	}
	return false;
}

function SearchRequestGuest( $type, $keyword, $filter = "all" ) {
	if ( !isset( $GLOBALS[ 'RequestSavePath' ] ) ) {
		echo "<script>alert(\"系统错误! Code:4211 RequestSavePath\");window.history.go(-1);</script>";
		exit;
	}

	$list = array();
	switch ( $type ) {
		case "id":
			switch ( $filter ) {
				case "all":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'idcard' ] == $keyword )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				case "proceed":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'idcard' ] == $keyword && $FileData[ 'status' ] == "proceed" )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				case "denied":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'idcard' ] == $keyword && $FileData[ 'status' ] == "denied" )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				default:
					echo "<script>alert(\"未知检索方式 Code:4111 filter\");windows.history.go(-1);</script>";
					exit;
					break;
			}
			break;
		case "room":
			switch ( $filter ) {
				case "all":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'room' ] == $keyword )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				case "proceed":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'room' ] == $keyword && $FileData[ 'status' ] == "proceed" )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				case "denied":
					if ( file_exists( $GLOBALS[ 'RequestSavePath' ] . "processing/" ) ) {
						$Dir = opendir( $GLOBALS[ 'RequestSavePath' ] . "processing/" );
						while ( ( $File = readdir( $Dir ) ) != false ) {
							if ( $File == "."
								or $File == ".."
								or is_dir( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ) )
								continue;
							$FileData = json_decode( file_get_contents( $GLOBALS[ 'RequestSavePath' ] . "processing/" . $File ), true );
							if ( $FileData[ 'room' ] == $keyword && $FileData[ 'status' ] == "denied" )
								$list += array( substr( $File, 0, stripos( $File, ".json" ) ) => $FileData );
						}
					}
					break;
				default:
					echo "<script>alert(\"未知检索方式 Code:4111 filter\");windows.history.go(-1);</script>";
					exit;
					break;
			}
			break;
		default:
			echo "<script>alert(\"未知检索方式 Code:4111 type\");windows.history.go(-1);</script>";
			exit;
	}
	return $list;
}
?>