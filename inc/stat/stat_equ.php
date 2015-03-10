<?php
	require("../sys/_dbconn.php");
	require("../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	
	$sql = "SELECT * FROM equipamento
			JOIN usuario ON usu_id=equ_cli_id
			JOIN os ON os_equ_id=equ_id
			WHERE equ_id=".$_GET["equ_id"];
	
	$equ_sqlQuery = mysql_query($sql);
	$equ_rs = mysql_fetch_object($equ_sqlQuery);
?>
<table id="cliview" class="alignleft" align="center" style="width: 461px">
    <tr>
        <td colspan="2">
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px">
                        <b>EQID:</b> # <?php echo $_GET["equ_id"]?> - <b>Estat&iacute;sticas</b>
                    </td>
                    <td align="right">
                        <a href="javascript:hideRequests()" title="Fechar">
                            <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="supLabel">
        <td width="225px">Cliente:</td>
        <td>Equipamento:</td>
    </tr>
    <tr class="supField">
        <td><?php echo $equ_rs->usu_nome?></td>
        <td><?php echo $equ_rs->equ_nome?></td>
    </tr>
    <tr>        
    <tr>
        <td align="center" colspan="2">
            <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
        </td>
    </tr>
   <tr style="background-color: <?php echo $colors[2]?>;">
        <td colspan="3" align="center" style="border:none">
            <b>Modelo:</b> <?php echo $equ_rs->equ_modelo?>
            <b>N. Patrim&ocirc;monio:</b> #<?php echo $equ_rs->equ_num_patrimonio?>
            <b>N. S&eacute;rie:</b> #<?php echo $equ_rs->equ_num_serie?>            
        </td>
    </tr>         
</table>   