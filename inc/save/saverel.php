<?php
require("../../sys/_config.php");
require("../../sys/_dbconn.php");
require("../../css/_colors.php");
require("../_lib.php");

$sql = "INSERT INTO os_relatorio(
				rel_tec_id,
				rel_os_id,
				rel_cont,
				rel_cont_adm,
				rel_data_criacao
			) VALUES(
				'" . $_GET["tec_id"] . "',
				'" . $_GET["os_id"] . "',
				'" . $_GET["obrg_rel_cont"] . "',
				'" . $_GET["rel_cont_adm"] . "',
				'" . (time() - (date('I') * 3600)) . "'
			)";

$sqlQuery = mysql_query($sql);
$rel_id = mysql_insert_id();

//verificando se o cliente deve ser notificado por email
$sql = "SELECT cli_not_cad_rel,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=" . $_GET["cli_id"];
$sqlQuery = mysql_query($sql);
$rs_cli = mysql_fetch_object($sqlQuery);

if ($rs_cli->cli_not_cad_rel == "S") {

    $email = new Email($config["emp_email"]);
    $email->addTo($rs_cli->usu_email);
    $email->setSubject("OSID:#" . $_GET["os_id"] . " Novo relat�rio gerado");

    $message = new Notification("<b>OSID:#" . $_GET["os_id"] . "<br/>Novo relat�rio gerado.</b>");
    $message->setConfig($config["emp_site"], $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($_GET["os_id"]);
    $message->setRel($rel_id);
    $email->setMessage($message->create());
    $email->send();
}

if ($_GET["rel_cont_adm"] != "") {

    //notificando equipe tecnica
    $email = new Email($config["emp_email"]);

    $sql = "SELECT usu_email FROM usuario WHERE usu_not_rel_adm='S' AND usu_status<>'I'";
    $not_sqlQuery = mysql_query($sql);
    while ($not_rs = mysql_fetch_object($not_sqlQuery)) {
        $email->addTo($not_rs->usu_email);
    }

    $email->setSubject("OSID:#" . $_GET["os_id"] . " Novo relat�rio administrativo gerado");

    $message = new Notification("<b>OSID:#" . $_GET["os_id"] . "<br/>Novo relat�rio administrativo gerado.</b>");
    $message->setConfig($config["emp_site"], $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($_GET["os_id"]);
    $message->setRel($rel_id);
    $message->setRelAdm(true);
    $email->setMessage($message->create());
    $email->send();
}
?>
<?php

mysql_close()?>