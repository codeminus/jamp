<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
?>
<form name="classprod_form">
	<input type="hidden" name="cmd" value="cadastrar" />	
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Cadastro de classifica&ccedil;&atilde;o</b>
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
            <td>Classifica&ccedil;&atilde;o:*</td>            
        </tr>
        <tr class="supField">
            <td>
               	<input type="text" name="obrg_classprod_nome" style="width: 430px;" maxlength="80" />		
            </td>
        </tr>
        <tr class="supLabel">
            <td>Descri&ccedil;&atilde;o:</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="classprod_desc" style="width: 430px;" maxlength="80" /></td>            
        </tr>
        <tr>
            <td valign="top"><label><input type="checkbox" name="classprod_status" style="border: none"/>Adicionar bloqueado.</label></td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('classprod_form','inc/save/saveclassprod.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','aclassprod')" class="saveInfoBtn" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>