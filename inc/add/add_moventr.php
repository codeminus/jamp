<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	
	$sql = "SELECT * FROM produto WHERE prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	$rs = mysql_fetch_object($sqlQuery);
?>
<form name="mov_entr_form">
	<input type="hidden" name="cmd" value="cadastrar" />
    <input type="hidden" name="prod_id" value="<?php echo $rs->prod_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Movimenta&ccedil;&atilde;o de entrada</b>
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
            <td width="225px">Classifica&ccedil;&atilde;o:</td>
            <td>Nome do produto:</td>
        </tr>
        <tr class="supField">
            <td>
                <?php 
					$sql = "SELECT classprod_nome FROM classprod WHERE classprod_id=".$rs->prod_classprod_id;
					$sqlQuery = mysql_query($sql);
					$classprod_rs = mysql_fetch_object($sqlQuery);
					echo $classprod_rs->classprod_nome;
				?>
            </td>
            <td><?php echo $rs->prod_nome?></td>
        </tr>
        <tr class="supLabel">            
            <td colspan="2">Fornecedor:*</td>            
        </tr>
        <tr class="supField">
            <td colspan="2">
            	<?php 
					$sql = "SELECT forn_id,forn_nome FROM fornecedor WHERE forn_status<>'I' ORDER BY forn_nome";
					$sqlQuery = mysql_query($sql);
				?>
                <select name="obrg_forn_id" style="width: 433px" tabindex="1">
                	<option value=""></option>
                <?php	
					while($forn_rs = mysql_fetch_object($sqlQuery)){
				?>
                	<option value="<?php echo $forn_rs->forn_id?>"><?php echo $forn_rs->forn_nome?></option>
                <?php		
					}
				?>
                </select>
            </td>
        </tr>
        <tr class="supLabel">            
            <td width="225px">N&uacute;mero da nota fiscal:*</td>
            <td rowspan="2" valign="bottom">
            	<table  style="width: 212px" cellspacing="0">                            	
                    <tr>
                    	<td>Custo por unidade:*</td>
                        <td align="right" style="font-weight: normal">R$ <input type="text" name="obrg_mov_entr_vcusto" value="" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" tabindex="4" /></td>
                    </tr>
                    <tr>
                        <td>Quant. de entrada:*</td>
                        <td align="right" style="font-weight: normal"><?php echo $rs->prod_unid_medida?> <input type="text" name="obrg_mov_entr_quant_entrada" onkeypress="return isNum(event,'int')" onkeyup="checkZeroFill(this)" onblur="checkZeroFill(this)" style="width: 50px; text-align:right" tabindex="5"  /></td>
                    </tr>                    
                </table>
        	</td>
            
        </tr>
        <tr class="supField">            
            <td><input type="text" name="obrg_mov_entr_num_nf" style="width: 200px;" maxlength="20" tabindex="2" /></td>            
        </tr>
        <tr class="supLabel">
        	<td colspan="2">Data de emiss&atilde;o da nota fiscal:*</td>
            
        </tr>
        <tr class="supField">
            <td colspan="2">
            	<input type="text" name="obrg_mov_entr_nf_dt_emissao" style="width: 200px; text-align:center" disabled />
                <a href="javascript:void(0)" onClick="displayCalendar(document.forms['mov_entr_form'].obrg_mov_entr_nf_dt_emissao,'dd/mm/yyyy',this)" title="Adicionar data" tabindex="3" >
                	<img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                </a>
            </td>
        </tr>        
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('mov_entr_form','inc/save/savemoventr.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lprod','amoventr')" class="saveInfoBtn" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="center">
            	<span>
                    <b>C&oacute;digo:</b> #<?php echo $rs->prod_id?>
                    <b>Modelo:</b> <?php echo $rs->prod_modelo?>
                    <b>Fabricante:</b> <?php echo $rs->prod_fabricante?>                                        
                </span><br/>
            	* Preenchimento de campo obrigat&oacute;rio.
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>