<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	
	$sql = "SELECT * FROM produto WHERE prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	$rs = mysql_fetch_object($sqlQuery);
	
	
	if($rs->prod_cvalor == "" || $rs->prod_cvalor == 0){
		$sql = "select mov_entr_vcusto from mov_entrada where mov_entr_prod_id=".$rs->prod_id." AND  mov_entr_nf_dt_emissao = 
				(SELECT MAX(mov_entr_nf_dt_emissao) FROM mov_entrada WHERE mov_entr_prod_id=".$rs->prod_id.")";
		$sqlQuery = mysql_query($sql);
		$mov_rs = mysql_fetch_object($sqlQuery);
		
		$vcusto = $mov_rs->mov_entr_vcusto;
	}else{
		$vcusto = $rs->prod_cvalor;
	}
				
	
?>
<form name="mov_susoint_form">
	<input type="hidden" name="cmd" value="cadastrar" />
    <input type="hidden" name="prod_id" value="<?php echo $rs->prod_id?>" />
    <input type="hidden" name="vcusto" value="<?php echo $vcusto?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno</b>
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
            <td width="225px">Solicitado por:*</td>
            <td>Autorizado por:*</td>
        </tr>
        <tr class="supField">            
            <td><input type="text" name="obrg_mov_susoint_solicitado_por" style="width: 200px;" maxlength="100" tabindex="1" /></td>
            <td><input type="text" name="obrg_mov_susoint_autorizado_por" style="width: 200px;" maxlength="100" tabindex="2" /></td>            
        </tr>
        <tr class="supLabel">            
            <td colspan="2">Finalidade:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2">
            	<input type="text" name="mov_susoint_finalidade" style="width:430px" maxlength="200" tabindex="3" />
            </td>
        </tr> 
        <tr class="supLabel">            
            <td width="225px">Data de sa&iacute;da:*</td>
            <td rowspan="3" valign="bottom">            	
            	<table  style="width: 212px" cellspacing="0" border="0">                            	
                    <tr>
                    	<td>Quant. de sa&iacute;da:*</td>
                        <td align="right" style="font-weight: normal">
							<?php echo $rs->prod_unid_medida?> <input type="text" name="obrg_mov_susoint_quant_saida" style="width: 50px; text-align:right" onkeypress="return isNum(event,'int')" onKeyUp="checkZeroFill(this),checkQuant(this, <?php echo $_GET["quant_disp"]?>,'', false),document.getElementById('custo_total').innerHTML = numberFormat(this.value*<?php echo $vcusto?>,2)" onblur="checkZeroFill(this)" maxlength="11" tabindex="5"  /></td>
                    </tr>
                    <tr>
                        <td>Custo por unidade:</td>
                        <td align="right" style="font-weight: normal">R$ <?php echo number_format($vcusto,"2",",",".")?></td>
                    </tr>
                     <tr>
                        <td>Custo total:</td>
                        <td align="right" style="font-weight: normal">R$ <span id="custo_total"></span></td>
                    </tr>
                </table>
        	</td>
            
        </tr>
        <tr class="supField">            
            <td>
            	<input type="text" name="obrg_mov_susoint_dt_saida" style="width: 166px; text-align:center" tabindex="4" disabled />
                <a href="javascript:void(0)" onClick="displayCalendar(document.forms['mov_susoint_form'].obrg_mov_susoint_dt_saida,'dd/mm/yyyy',this)" title="Adicionar data" >
                	<img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                </a>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
    	<tr>

            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('mov_susoint_form','inc/save/savemovsusoint.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lprod','amovsusoint')" class="saveInfoBtn" />
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