<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);	
?>
<form name="serv_form">
	<input type="hidden" name="cmd" value="cadastrar" />	
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Cadastro de servi&ccedil;o</b>
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
            <td colspan="2">Nome:*</td>
        </tr>
        <tr class="supField">
            <td colspan="2">
                <input type="text" name="obrg_serv_nome" style="width: 431px;" />
            </td>            
        </tr>
        <tr class="supLabel">
            <td valign="top" colspan="2">Descri&ccedil;&atilde;o:</td>	            
        </tr>
        <tr class="supField">
            <td colspan="2">
                <textarea name="serv_desc" style="width: 431px; height: 50px"></textarea>
            </td>
        </tr>
        <tr>
        	<td><b>Unidade de cobran&ccedil;a:</b> (ex.: hora) <input type="text" name="serv_unid_medida" maxlength="10" style="width: 50px; text-align:right" /></td>
        	 <td align="right" style="padding-right: 9px"><b>Valor:*</b> R$ <input type="text" name="obrg_serv_valor" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" /></td>
        </tr>
        <tr>
        	<td><label><input type="checkbox" name="serv_status" style="border: none"/>Adicionar bloqueado.</label></td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('serv_form','inc/save/saveserv.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','aserv')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>