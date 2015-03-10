<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM equipamento
			JOIN usuario ON equ_cli_id=usu_id 
			WHERE equ_id=".$_GET["equ_id"];		
	$sqlQuery = mysql_query($sql);	
	$equ_rs = mysql_fetch_object($sqlQuery);
		
?>
<form name="os_form">    	
	<input type="hidden" name="cmd" value="cad_cli_os" />
	<input type="hidden" name="equ_id" value="<?php echo $equ_rs->equ_id?>" />
    <input type="hidden" name="cli_id" value="<?php echo $equ_rs->equ_cli_id?>" />
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>EQID:</b> #<?php echo $equ_rs->equ_id?> - <b>Ordem de servi&ccedil;o</b> </td>
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
            <td colspan="2">Nome do equipamento:</td>
        </tr>
        <tr class="supField">            
            <td colspan="2"><?php echo $equ_rs->equ_nome?></td>
        </tr>
        <tr class="supLabel">            
            <td colspan="2">Solicitada por:*</td>            
        </tr>
        <tr class="supField">            
            <td colspan="2"><input type="text" name="obrg_os_autorizado_por" style="width: 427px" maxlength="50" /></td>
        </tr>
        <tr class="supLabel">
            <td colspan="2">Descri&ccedil;&atilde;o:*</td>
        </tr>
        <tr class="supField">
            <td colspan="2">            	
                <textarea name="obrg_os_pro_cli_descricao" style="width: 427px; height: 60px;"></textarea>
            </td>            
        </tr>
        <tr class="supLabel">
            <td colspan="2">Observa&ccedil;&otilde;es:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2">            	
                <textarea name="os_cli_obs" style="width: 427px; height: 60px;"></textarea>
            </td>            
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('os_form','inc/save/saveos.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'lequ','aos')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="center">            	
            	<b>N. Patrim&ocirc;monio:</b> #<?php echo $equ_rs->equ_num_patrimonio?>
                <b>N. S&eacute;rie:</b> #<?php echo $equ_rs->equ_num_serie?>
                <b>Modelo:</b> <?php echo $equ_rs->equ_modelo?>
                <b>Fabricante:</b> <?php echo $equ_rs->equ_fabricante?>
                <br/>
                * Preenchimento de campo obrigat&oacute;rio.
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>