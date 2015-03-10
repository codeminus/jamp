<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM produto
			JOIN classprod ON prod_classprod_id=classprod_id
			WHERE prod_id=".$_GET["prod_id"];		
	$sqlQuery = mysql_query($sql);
	$prod_rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
	$quant_entrada = 0;
	while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
		$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
	}
	
	$quant_saida = 0;
				
	$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
	
	while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
	}					
	
	$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
	while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
	}
	
	$quant_disponivel = $quant_entrada - $quant_saida;
	
	
	if($_SESSION["tec_cad_prod"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}
	
?>
<form name="prod_form">    		
	<input type="hidden" name="prod_id" value="<?php echo $prod_rs->prod_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Produto:</b> #<?php echo $prod_rs->prod_id?></td>
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
            <td width="225px">Classifica&ccedil;&atilde;o:*</td>
            <td>Nome do produto:*</td>
        </tr>
        <tr class="supField">
            <td>
                <?php 
					$sql = "SELECT * FROM classprod WHERE classprod_status='A' ORDER BY classprod_nome ASC";
					$sqlQuery = mysql_query($sql);
				?>	
					<select name="obrg_classprod" style="width: 204px; padding: 1px" <?php echo $disabled?>  tabindex="1">
                <?php    
					while($rs = mysql_fetch_object($sqlQuery)){
						if($rs->classprod_id == $prod_rs->prod_classprod_id){
							echo("<option value=\"".$rs->classprod_id."\" selected=\"selected\">".$rs->classprod_nome."</option>");
						}else{
							echo("<option value=\"".$rs->classprod_id."\">".$rs->classprod_nome."</option>");
						}
					}
				?>
					</select>				
            </td>
            <td><input type="text" name="obrg_prod_nome" value="<?php echo $prod_rs->prod_nome?>" maxlength="100" style="width: 200px;" <?php echo $disabled?> tabindex="2"/></td>
        </tr>
        <tr class="supLabel">        	
            <td>Modelo:</td>
            <td width="225px">Fabricante:</td>
        </tr>
        <tr class="supField">
        	<td><input type="text" name="prod_modelo" value="<?php echo $prod_rs->prod_modelo?>" style="width: 200px;" maxlength="80" <?php echo $disabled?> tabindex="3"/></td>
        	<td><input type="text" name="prod_fabricante" value="<?php echo $prod_rs->prod_fabricante?>" style="width: 200px;" maxlength="80" <?php echo $disabled?> tabindex="4"/></td>                        
        </tr>
        <tr class="supLabel">            
            <td colspan="2">Localiza&ccedil;&atilde;o:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><input type="text" name="prod_localizacao" style="width: 427px;" value="<?php echo $prod_rs->prod_localizacao?>" maxlength="160" tabindex="5" /></td>            
        </tr>
        <tr class="supLabel">
        	<td>Descri&ccedil;&atilde;o:</td>
            <td width="225px" rowspan="2">
            	<table  style="width: 212px" cellspacing="0">                	
                    <tr>
                    	<td>Valor de custo padr&atilde;o:</td>
                        <td align="right" style="font-weight: normal">R$ <input type="text" name="prod_cvalor" value="<?php echo number_format($prod_rs->prod_cvalor,2,",",".")?>" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" tabindex="7" /></td>
                    </tr>
                    <tr>
                    	<td>Valor de venda:*</td>
                        <td align="right" style="font-weight: normal">R$ <input type="text" name="obrg_prod_vvalor" value="<?php echo number_format($prod_rs->prod_vvalor,2,",",".")?>" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" tabindex="7" <?php echo $disabled?>/></td>
                    </tr>
                    <tr>
                        <td>Unidade de medida:*</td>
                        <td align="right"><input type="text" name="obrg_prod_unid_medida" value="<?php echo $prod_rs->prod_unid_medida?>" maxlength="4" style="width: 50px; text-align:right" tabindex="8" <?php echo $disabled?>/></td>
                    </tr>
                    <tr>
                        <td>Quantidade dispon&iacute;vel:</td>
                        <td align="right"><input type="text" name="quant_disponivel" value="<?php echo $quant_disponivel?>" style="width: 50px; background-color: <?php echo $colors[5]?>; text-align:right" tabindex="9" disabled="disabled"/></td>
                    </tr>
                    <tr>
                        <td>Quantidade m&iacute;nima:*</td>
                        <td align="right"><input type="text" name="obrg_prod_min_quant" value="<?php echo $prod_rs->prod_min_quant?>" onkeypress="return isNum(event,'int')"  style="width: 50px; text-align:right" tabindex="9" <?php echo $disabled?>/></td>
                    </tr>
                </table>
        	</td>
        </tr>
        <tr class="supField">            
            <td rowspan="">
                <textarea name="prod_desc" style="width: 200px; height: 50px" <?php echo $disabled?> tabindex="6"><?php echo str_replace("<br/>","\r\n",$prod_rs->prod_desc)?></textarea>
            </td>           
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('prod_form','inc/save/saveprod.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lprod','altprod')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px; <?php echo $display?>"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$prod_rs->prod_dt_cad)?></font>&nbsp;
                          <?php echo date("d-m-y",$prod_rs->prod_dt_cad)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                          		<b style="font-size: 7pt">por:</b>
                          <?php 
								$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$prod_rs->prod_cad_usu_id;
								$sqlQuery3 = mysql_query($sql) or die(mysql_error());
								$teccad_rs = mysql_fetch_object($sqlQuery3);
								
								echo $teccad_rs->usu_nome;
							}
						  ?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($prod_rs->prod_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$prod_rs->prod_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$prod_rs->prod_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$prod_rs->prod_dt_alter)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                              <b style="font-size: 7pt">por:</b>
                              <?php echo $tec_rs->usu_nome;?>
                          <?php }?>
                    <?php }else{?>
                        Nunca
                    <?php }?>      
                    </div>
                    <div style="clear:both"></div>
                </div>
            </td>
        </tr>       
     </table>   
</form>