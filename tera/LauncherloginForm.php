﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge;" />
<title>TERA Classic test</title>
<link rel="stylesheet" href="../launcher/css/login_new.css">

<!-- <link rel="stylesheet" href="//landing.mangot5.com/template/tera/launcher/css/login_new.css"> -->
<script type="text/javascript" src="../launcher/js/jquery-latest.min.js"></script>
<script type="text/javascript" src="../launcher/js/login.js?version=2"></script>
<script type="text/javascript" src="../launcher/js/jquery.cookie.js"></script>
<script type="text/javascript" src="../launcher/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../launcher/js/jquery-migrate-1.2.1.min.js"></script>
<script>


	var ACCOUNT_NAME = '';
	//if(isNaN(ACCOUNT_NAME)) $('#loginScreen').show();
	var ACCOUNT_KEY = '';
	var PERMISSION = '';
	var CHARCOUNTSTR = '';
	var VIP	= '';
	var PASS = '';

	function GetTimestamp() {
		return new Date().getTime();
	}
	function GetAccountID(account_name) {
		//alert('GetAccountID:'+ACCOUNT_NAME);
		return ACCOUNT_NAME;
	}
	function getTicket() {
		//alert('getTicket:'+ACCOUNT_KEY);
		return ACCOUNT_KEY;
	}
	function getCharCnt() {
		$.ajax({
			url : 'http://127.0.0.1:8080/tera/GetAccountInfoByUserNo',
			method : 'post',
			data : {id:ACCOUNT_NAME},
			async: false,
			success : function (data){
				CHARCOUNTSTR = data.charcountstr;
				PERMISSION= data.charcountstr;
				//alert('getCharCnt()__success_ACCOUNT_NAME:'+ACCOUNT_NAME+'__CHARCOUNTSTR:'+CHARCOUNTSTR+'__PERMISSION:'+PERMISSION);
			},
			error:function(jqXHR, textStatus, errorThrown){
				alert(jqXHR.responseText);
				alert(jqXHR.url);
				alert(jqXHR.status);
				alert(jqXHR.readyState);
				alert(jqXHR.statusText);
			}
		});

		return CHARCOUNTSTR;
	}
	function getPermission() {
		return PERMISSION;
	}
	function DoLogin() {
		//alert("dologin1");
		//return;
		$('#playButton').focus();

		var idObj = document.getElementById("userID");
		var pwObj = document.getElementById("userPW");
		if( !idRegCheck(idObj) || !pwRegCheck(pwObj) ) {
			/*
			$('.g-recaptcha').hide();

			if (typeof grecaptcha == "object" && typeof grecaptcha.reset == 'function') {
				grecaptcha.reset();
			}
			*/
			return;
		}

		$('.fullBtnBlue').hide();
		jQuery.support.cors = true;
		//window.alert($('#userloginForm').serialize()+'abcdef122222');
		$.ajax({
			url : 'http://127.0.0.1:8080/tera/LauncherLoginAction',
			method : 'post',
			data : $('#userloginForm').serialize(),
			success : function (data){
				//alert('success:'+data);
				if(data.Return){
					//$(parent).find('#msg_welcome').text('welcome '+$('#userID'));
					ACCOUNT_NAME = data.UserNo;
					ACCOUNT_KEY = data.AuthKey;
					PERMISSION = data.Permission;
					if(data.Permission == '') PERMISSION = 0;
					CHARCOUNTSTR = data.CharacterCount;

					//alert('DoLogin_ACCOUNT_KEY:'+ACCOUNT_KEY+'__ACCOUNT_NAME:'+ACCOUNT_NAME+'__PERMISSION:'+PERMISSION+'__CHARCOUNTSTR:'+CHARCOUNTSTR);
					PASS = data.PassitemInfo;
					VIP= data.VipitemInfo;

					if($.cookie('idSave') == 'true'){
						var date = new Date();
				 		var m = 60*24*7;	//7 day
				 		date.setTime(date.getTime() + (m * 60 * 1000));
						$.cookie('idValue', $('#userID').val(), { expires: date });
					}

					//launcherMemberOTP
					if(data.isUsedOtp){
						if(data.isOtpUser ==  false){
							if(!confirm('必須要申請OTP才能進入遊戲，現在移動到申請頁。')) alert('必須要申請OTP才能進入遊戲，現在移動到申請頁。');
							else {window.open('//www.mangot5.com/Index/Security/OTP');}
						}
						parent.ShowOTPUser(true, ACCOUNT_NAME);
						parent.displayMarkCheck(VIP, PASS);
					//}else if(data.result.phoneLock == false){	parent.ShowPhonelock(true);
					}else{	parent.LoginSuccess();	}
				}else{
					alert('登入失敗，請重新輸入帳號密碼。');
					/*
					$('.g-recaptcha').hide();

					if (typeof grecaptcha == "object" && typeof grecaptcha.reset == 'function') {
						grecaptcha.reset();
					}
					*/

					$('.fullBtnBlue').show();
				}

			},
			error:function(jqXHR, textStatus, errorThrown){
			    alert('responseText:'+jqXHR.responseText);
			    alert('url:'+jqXHR.url);
				alert('status:'+jqXHR.status);
				alert('readyState:'+jqXHR.readyState);
				alert('statusText:'+jqXHR.statusText);
			}

		});

	}
</script>
</head>
<body oncontextmenu="return false" ondragstart="return false" onselectstart="return false" style="overflow: hidden;">
<div class="wrap">
  <form class="form-horizontal" name="form1"  method="post" action="/tera/LauncherLoginAction" id="userloginForm">
  	<input type="hidden" name="r" value="478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb"/>
    <div class="form-group">
      <div class="col">
        <input type="text" class="form-control form-uid" id="userID" name="userID" placeholder="username" maxlength="30"  onblur="idRegCheck(this)" tabindex="1">
      </div>
    </div>
    <div class="form-group">
      <div class="col">
        <input type="password" class="form-control form-password" id="userPW" name="password" placeholder="password" maxlength="30" tabindex="2" onblur="pwRegCheck(this)" onKeypress="if(event.keyCode ==13 && pwRegCheck(this)) DoLogin();/*$('.g-recaptcha').toggle();*/">
      </div>
    </div>
    <div class="form-group">
      <div class="col">
        <div class="checkbox">
          <label >
            <input type="checkbox" id="checkboxIDSave"> remember </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col">
        <button type="button" onclick="DoLogin();" tabindex="3" class="btn-submit">login</button>
        <a target="_blank" href="//127.0.0.1:81/reg/" class="btn-join">register</a> </div>
    </div>
    <div class="g-recaptcha" data-callback="DoLogin" data-sitekey="6LcZ2f0SAAAAAD0eUdEP0YdkRZLYrdf8rg2qjsdj" data-size="normal" data-theme="dark" style="display:none;margin:3px 0px 0px 73px;position: absolute;"></div>
  </form>
  <button class="btn-close" onclick="javascript:parent.SendCommand('command:close')">close</button>
</div>
<!--/wrap-->
</body>
</html>