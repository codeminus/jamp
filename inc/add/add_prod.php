<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
?>
<form name="prod_form">
	<input type="hidden" name="cmd" value="cadastrar" />	
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Cadastro de produto</b>
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
            <td width="225px">Classifica&ccedil;&atilde;o:*</td>
            <td>Nome do produto:*</td>
        </tr>
        <tr class="supField">
            <td>
                <?php 
					$sql = "SELECT * FROM classprod WHERE classprod_status='A' ORDER BY classprod_nome ASC";
					$sqlQuery = mysql_query($sql);
				?>	
					<select name="obrg_classprod" style="width: 204px; padding: 1px" tabindex="1">
                    	<option value=""></option>
                <?php    
					while($rs = mysql_fetch_object($sqlQuery)){
						echo("<option value=\"".$rs->classprod_id."\">".$rs->classprod_nome."</option>");
					}
				?>
					</select>				
            </td>
            <td><input type="text" name="obrg_prod_nome" style="width: 200px;" maxlength="100" tabindex="2" /></td>
        </tr>
        <tr class="supLabel">
        	<td>Modelo:</td>
            <td>Fabricante:</td>            
        </tr>
        <tr class="supField">
        	<td><input type="text" name="prod_modelo" style="width: 200px;" maxlength="80" tabindex="3" /></td>
            <td><input type="text" name="prod_fabricante" style="width: 200px;" maxlength="80" tabindex="4" /></td>            
        </tr>
        <tr class="supLabel">            
            <td colspan="2">Localiza&ccedil;&atilde;o:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><input type="text" name="prod_localizacao" style="width: 427px;" maxlength="160" tabindex="5" /></td>            
        </tr>
        <tr class="supLabel">
        	<td>Descri&ccedil;&atilde;o:</td>
            <td rowspan="2">
            	<table  style="width: 212px" cellspacing="0">
                	<tr>
                    	<td>Valor de custo padr&atilde;o:</td>
                        <td align="right" style="font-weight: normal">R$ <input type="text" name="prod_cvalor" value="" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" tabindex="7" /></td>
                    </tr>
                    <tr>
                    	<td>Valor de venda:*</td>
                        <td align="right" style="font-weight: normal">R$ <input type="text" name="obrg_prod_vvalor" value="" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" tabindex="8" /></td>
                    </tr>
                    <tr>
                        <td>Unidade de medida:*</td>
                        <td align="right"><input type="text" name="obrg_prod_unid_medida" maxlength="4" style="width: 50px; text-align:right" tabindex="9" /></td>
                    </tr>
                    <tr>
                        <td>Quantidade m&iacute;nima:*</td>
                        <td align="right"><input type="text" name="obrg_prod_min_quant" value="" onkeypress="return isNum(event,'int')"  style="width: 50px; text-align:right" tabindex="10" /></td>
                    </tr>
                </table>
        	</td>
        </tr>
        <tr class="supField">            
            <td rowspan="">
                <textarea name="prod_desc" style="width: 200px; height: 50px" tabindex="6"></textarea>
            </td>           
        </tr>  
        <tr>
            <td valign="top"><label><input type="checkbox" name="prod_status" style="border: none" tabindex="11"/>Adicionar bloqueado.</label></td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('prod_form','inc/save/saveprod.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','aprod')" class="saveInfoBtn" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>