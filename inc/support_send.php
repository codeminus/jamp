<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);


require("../sys/_config.php");
require("../css/_colors.php");
require("_lib.php");

if($_GET["cmd"] == "tecSup"){
	$email = new Email($_GET["obrg_email"]);
	$email->addTo("suporte@jamp.onlinemanager.com.br");
	#$email->addTo("cathaldallan@gmail.com");
	$email->setSubject("Solicitação de suporte");
	
	$message = new Notification("<b>Solicita&ccedil;&atilde;o de suporte</b>");
	$message->setConfig($config["emp_site"],"http://jamp.onlinemanager.com.br/img/jamp-logo.jpg");
	$message->setColors($colors);
	$message->setSupportMsg(
		$_GET["reg_nome"],
		$_GET["obrg_nome"],
		$_GET["obrg_email"],
		$_GET["obrg_email"],
		$_GET["obrg_msg"]);
	$email->setMessage($message->create());
	$email->send();	
}elseif($_GET["cmd"] == "cliSup"){
	$email = new Email($_GET["obrg_email"]);
	$email->addTo($config["emp_email"]);
	$email->setSubject("Solicitação de suporte");
	
	$message = new Notification("<b>Solicita&ccedil;&atilde;o de suporte</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);
	$message->setSupportMsg(
		$_GET["reg_nome"],
		$_GET["obrg_nome"],
		$_GET["obrg_email"],
		$_GET["obrg_email"],
		$_GET["obrg_msg"]);
	$email->setMessage($message->create());
	$email->send();	
}

?>