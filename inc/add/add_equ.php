<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$_GET["cli_id"];		
	$sqlQuery = mysql_query($sql);	
	$cli_rs = mysql_fetch_object($sqlQuery);
?>
<form name="equ_form">
	<input type="hidden" name="cmd" value="cadastrar" />
	<input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>CLID:</b> #<?php echo $_GET["cli_id"]?> - <b>Cadastro de equipamento</b>
                        </td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px"/>
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Cliente:</td>
            <td>Nome do equipamento:*</td>
        </tr>
        <tr class="supField">
            <td>
                <?php echo $cli_rs->usu_nome?>
            </td>
            <td><input type="text" name="obrg_equ_nome" style="width: 200px;" /></td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Modelo:</td>
            <td>Fabricante:</td>
        </tr>
        <tr class="supField">
            <td><input type="text" name="equ_modelo" style="width: 200px;" /></td>
            <td><input type="text" name="equ_fabricante" style="width: 200px;" /></td>
        </tr>
        <tr class="supLabel">
            <td width="225px">N&uacute;mero de s&eacute;rie:</td>
            <td>Observa&ccedil;&otilde;es:</td>
        </tr>
        <tr class="supField">
            <td  valign="top">
                <input type="text" name="equ_num_serie" style="width: 200px;" />
            </td>
            <td rowspan="3">
                <textarea name="equ_descricao" style="width: 200px; height: 50px"></textarea>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px" valign="top">N&uacute;mero de patrim&ocirc;nio:</td>	            
        </tr>
        <tr class="supField">
            <td valign="top"><input type="text" name="equ_num_patrimonio" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td valign="top"><label><input type="checkbox" name="equ_status" style="border: none"/>Adicionar bloqueado.</label></td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('equ_form','inc/save/saveequ.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lcli','aequ')" class="saveInfoBtn" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>