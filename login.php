<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
    error_reporting(0);
	require("sys/_version.php");
	require("inc/_lib.php");
	require("sys/_config.php");
	require("inc/_msgs.php");
	require("css/_colors.php");
        
        
	if($_POST["cmd"] == "logar"){
            
                session_start();
                session_destroy();
            
		if(strpos($_POST["login"],"'") > -1 || strpos($_POST["pass"],"'") > -1){
			header("Location: login.php?msg=lerror");
			exit;
		}
		require("sys/_dbconn.php");
		
		$sql = "SELECT * FROM usuario 
				WHERE usu_login='".$_POST["login"]."' AND usu_pass='".$_POST["pass"]."' LIMIT 1";
		$sqlQuery = mysql_query($sql) or die(mysql_error());
		$rs = mysql_fetch_object($sqlQuery);
		if (isset($rs->usu_nome)){
			if($rs->usu_status != "I"){				
				
				/*#if(!(strpos($rs->usu_last_link,"index.php") > -1)){
                                if(!(strpos($rs->usu_last_link,"index.php?inc=los&ot=5") > -1)){
					$sql = "UPDATE usuario SET usu_last_link='index.php?inc=los&ot=5' WHERE usu_id=".$rs->usu_id . " LIMIT 0,1";
					$sqlQuery = mysql_query($sql) or die(mysql_error());
				}*/
				

				session_start();
				$_SESSION["tec_id"] = $rs->usu_id;
				$_SESSION["tec_tipo"] = $rs->usu_tipo;
				$_SESSION["tec_nome"] = $rs->usu_nome;
				$_SESSION["tec_email"] = $rs->usu_email;
				$_SESSION["tec_last_ip"] = $rs->usu_last_ip;
				$_SESSION["tec_dt_login"] = $rs->usu_dt_login;
				$_SESSION["tec_dt_logout"] = $rs->usu_dt_logout;
				$_SESSION["tec_cad_user"] = $rs->usu_cad_user;
				$_SESSION["tec_cad_prod"] = $rs->usu_cad_prod;
				$_SESSION["tec_cad_cli"] = $rs->usu_cad_cli;
				$_SESSION["tec_cad_equ"] = $rs->usu_cad_equ;
				$_SESSION["tec_gerar_os"] = $rs->usu_cad_os;
				$_SESSION["tec_cad_orc"] = $rs->usu_cad_orc;
				$_SESSION["tec_atrib_os"] = $rs->usu_atrib_os;
				$_SESSION["tec_view_user"] = $rs->usu_view_user;
				$_SESSION["tec_view_prod"] = $rs->usu_view_prod;
				$_SESSION["tec_view_cli"] = $rs->usu_view_cli;
				$_SESSION["tec_view_equ"] = $rs->usu_view_equ;
				$_SESSION["tec_view_os"] = $rs->usu_view_os;
				$_SESSION["tec_view_orc"] = $rs->usu_view_orc;
				$_SESSION["tec_del_os"] = $rs->usu_del_os;
				$_SESSION["tec_aprov_os"] = $rs->usu_aprov_os;
				$_SESSION["tec_block"] = $rs->usu_block;				
				$_SESSION["tec_alter_info"] = $rs->usu_alter_info;
				$_SESSION["tec_adv_info"] = $rs->usu_adv_info;
				$_SESSION["tec_view_alertas"] = $rs->usu_view_alertas;


				$_SESSION["order"] = "DESC";
				
				$sql = "UPDATE usuario SET 
							usu_last_ip='".$_SERVER['REMOTE_ADDR']."',
							usu_x_fwd='".$_SERVER['HTTP_X_FORWARDED_FOR']."',
							usu_dt_login='".(time()-(date('I')*3600))."'
														
						WHERE usu_id='".$rs->usu_id."'
							";
				$sqlQuery2 = mysql_query($sql);
				
				if($rs->usu_status == "AS"){
					header("Location: login.php?cmd=AS&msg=lpass");
				}else{
					header("Location: index.php");
                                        exit;
				}
			}elseif($rs->usu_status == "I"){
				header("Location: login.php?msg=llocked");
			}
		}else{
			header("Location: login.php?msg=lerror");
			exit;
		}
	}elseif($_POST["cmd"] == "alterPass"){	
		require("sys/_dbconn.php");
		session_start();
		$sql = "UPDATE usuario SET usu_pass='".$_POST["obrg_npass"]."', usu_status='A' WHERE usu_id='".$_SESSION["tec_id"]."'";
		$sqlQuery = mysql_query($sql) or die(mysql_error());
		header("Location: index.php");
		exit;
	}elseif($_POST["cmd"] == "sendPass"){
	
		if($_POST["obrg_email"] == ""){
			header("Location: login.php?cmd=forget&msg=lforget");
			exit;
		}
	
		require("sys/_dbconn.php");
		
		//enviando email ao cliente
		
		$email = new Email($config["emp_email"]);
		
		//selecionando email
		$sql = "SELECT * FROM usuario WHERE usu_email='".$_POST["obrg_email"]."'";
		$sqlQuery = mysql_query($sql);
		if($num_rows = mysql_num_rows($sqlQuery) == 0){
			header("Location: login.php?cmd=forget&msg=lforget");
			exit;
		}
		$rs = mysql_fetch_object($sqlQuery) or die(mysql_error());
		
		$email->addTo($rs->usu_email);
		$email->setSubject("Recupera��o de senha");
		
		$message = new Notification("<b>Recupera��o de senha.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."jamp/img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setUser($rs->usu_id);
		$message->setUserAdvinfo(true);
		$message->setUserLogin($rs->usu_id);
		$email->setMessage($message->create());
		$email->send();
		
		header("Location: login.php?msg=lfinfo");
		
		
	}elseif($_GET["cmd"] == "quit"){
		session_start();
		session_destroy();
		header("Location: login.php?msg=lquit");
		exit;
	}
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $config["emp_nome"]?> - Autentica&ccedil;&atilde;o de usu&aacute;rio</title>
<script src="sys/functions.js" language="javascript" type="text/javascript"></script>
<?php 
	require("css/_colors.php");
	require("css/loginCSS.php");
	require("css/generalCSS.php");
?>
</head>

<body onload="setFocus('login_form','login')">
<?php
	if(isset($_GET["msg"])){
?>
		<div id="msgLogin">
			<div id="headline" style="margin:2px">Mensagem</div>
			<?php echo $msgs[$_GET["msg"]]?>
		</div>		
<?php	
	}
?>
<div id="shadow">
<div id="divloginForm">
	<div id="headline">.:Login</div>    
    <div style="float:right; margin-top: 10px">
    	<a href="http://jamp.onlinemanager.com.br" target="_blank">
    		<img src="img/jamp-logo.jpg" width="198" height="75" border="0" />
        </a>
    </div>
	<?php if($_GET["cmd"] == ""){	?>
		<div id="loginForm" class="loginForm">
		<form name="login_form" action="<?php $_SERVER['PHP_SELF']?>" method="post" style="display:inline">
			<input type="hidden" name="cmd" value="logar" style="display:none" />
			<label>
				Usu&aacute;rio:
				<input type="text" name="login" />				
			</label>
			<label>
				Senha:
				<input type="password" name="pass" />
			</label>
			<div align="right">
				<input type="submit" class="entrar" onclick="sendForm('login_form')" value="entrar" />
			</div> 
		</form>
		</div>
	<?php }elseif($_GET["cmd"] == "AS"){?>
		
		<div id="loginForm" class="loginForm">
		<form name="login_form" action="<?php $_SERVER['PHP_SELF']?>" method="post" style="display:inline">
			<input type="hidden" name="cmd" value="alterPass" style="display:none" />
			<label>
				Nova senha:
				<input type="password" name="obrg_npass"/>								
			</label>
			<label>
				Confirmar nova senha:
				<input type="password" name="obrg_rnpass" />
			</label>
			<div align="right">
				<input type="button" class="entrar" onclick="sendForm('login_form')" value="alterar" />
			</div> 
		</form>
		</div>
	<?php }elseif($_GET["cmd"] == "forget"){?>
		<div id="loginForm" class="loginForm">
		<form name="login_form" action="<?php $_SERVER['PHP_SELF']?>" method="post" style="display:inline"
        	  onsubmit="sendForm('login_form')">
			<input type="hidden" name="cmd" value="sendPass" style="display:none" />
			<label>
				E-mail:
				<input type="text" name="obrg_email"/>
			</label>			
			<div align="right">
            	<input type="button" class="entrar" onclick="document.location='login.php'" 
                	   value="cancelar" style="display:inline" />
				<input type="button" class="entrar" onclick="sendForm('login_form')" value="receber"  style="display: inline"/>
			</div> 
		</form>
		</div>
	<?php }	?>    
    <!--login.php?cmd=forget-->
	<div id="bottomline"><a href="?cmd=forget&msg=lforget" style="color: <?php echo $colors[1]?>">esqueceu a senha?</a></div>
    <div style="clear:both" align="right">jamp <?php echo $version?> - todos os direitos reservados</div>
</div>
</div>
</body>
</html>

