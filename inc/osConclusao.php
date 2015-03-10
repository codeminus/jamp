<?php
session_start();
require("../sys/_dbconn.php");
require("../css/_colors.php");
header("Content-Type: text/html; charset=ISO-8859-1", true);

$sql = "SELECT os_equ_id,os_cli_id, os_sta_id FROM os
			WHERE os_id=" . $_GET["os_id"];
$os_sqlQuery = mysql_query($sql) or die(mysql_error());

$os_dt = mysql_fetch_object($os_sqlQuery);

$sql = "SELECT * FROM equipamento WHERE equ_id='" . $os_dt->os_equ_id . "'";
$equ_sqlQuery = mysql_query($sql);
$rs_equ = mysql_fetch_object($equ_sqlQuery);

$sql = "SELECT usu_nome FROM usuario WHERE usu_id='" . $os_dt->os_cli_id . "'";
$cli_sqlQuery = mysql_query($sql);
$rs_cli = mysql_fetch_object($cli_sqlQuery);
?>

<table id="cliview" class="alignleft" align="center" style="width: 400px">
  <tr>
    <td colspan="4">
      <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
        <tr style="background-color: <?php echo $colors[2] ?>;">
          <td style="padding: 2px"><b>Conclus&atilde;o de OS:</b> #<?php echo $_GET["os_id"] ?></td>
          <td align="right">
            <a href="javascript:hideRequests()" title="Fechar">
              <img src="img/btn/close.png" width="16" height="16" border="0" />
            </a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <form name="os_form" style="display: inline">    	
        <input type="hidden" name="cmd" value="concluir" />
        <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
        <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"] ?>" />
        <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"] ?>" />
        <input type="hidden" name="alter_tec_id" value="<?php echo $_SESSION["tec_id"] ?>" />
        Data de conclus&atilde;o:
        <input type="text" name="obrg_dt_conclusao" style="width: 60px; text-align:center" disabled />
        <a href="javascript:void(0)" onClick="displayCalendar(document.forms['os_form'].obrg_dt_conclusao, 'dd/mm/yyyy', this)" title="Adicionar data" tabindex="3" >
          <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
        </a>
        <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>                
        <input type="button" name="s" value="Concluir" 
               onclick="ajaxSender('os_form', 'inc/save/saveos.php',<?php echo $_GET["ot"] ?>, '<?php echo $_SESSION["srch"] ?>',<?php echo $_GET["pg"] ?>, 'los', 'cos')"
               style="width: 170px; background-color: red; color: white;"/>
      </form>
    </td>
  </tr>
  <tr style="background-color: <?php echo $colors[2] ?>;">        	
    <td colspan="4">
      <div>
        <div align="center">                	
          <b style="font-size: 7pt">EQID:</b> #<?php echo $rs_equ->equ_id ?>
          <b style="font-size: 7pt">Equipamento:</b> <?php echo $rs_equ->equ_nome ?>
          <b style="font-size: 7pt">Modelo:</b> <?php echo $rs_equ->equ_modelo ?>
          <b style="font-size: 7pt">N. Patrim&ocirc;monio:</b> #<?php echo $rs_equ->equ_num_patrimonio ?>
          <b style="font-size: 7pt">N. S&eacute;rie:</b> #<?php echo $rs_equ->equ_num_serie ?><br/>
          <b style="font-size: 7pt">Cliente:</b> <?php echo $rs_cli->usu_nome ?>
        </div>
        <div style="clear:both"></div>
      </div>
    </td>
  </tr>       
</table>