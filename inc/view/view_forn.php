<?php
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM fornecedor WHERE forn_id=".$_GET["forn_id"];		
	$sqlQuery = mysql_query($sql);	
	$forn_rs = mysql_fetch_object($sqlQuery);
	
	if($_SESSION["tec_cad_prod"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}	
?>
<form name="forn_form">  
	<input type="hidden" name="forn_id" value="<?php echo $forn_rs->forn_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Fornecedor:</b> #<?php echo $forn_rs->forn_id?></td>
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
            <td colspan="2">Fornecedor:*</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><input type="text" name="obrg_forn_nome" value="<?php echo $forn_rs->forn_nome?>" style="width: 427px;" maxlength="100" tabindex="1" <?php echo $disabled?>/></td>            
        </tr>
        <tr class="supLabel">            
            <td>CPF/CNPJ:</td>
            <td>Inscri&ccedil;&atilde;o estadual:</td>
        </tr>
        <tr class="supField">            
            <td><input type="text" name="forn_cp" value="<?php echo $forn_rs->forn_cp?>" style="width: 200px;" maxlength="20" tabindex="2" <?php echo $disabled?>/></td>
            <td><input type="text" name="forn_inscricao" value="<?php echo $forn_rs->forn_inscricao?>" style="width: 200px;" maxlength="20" tabindex="3" <?php echo $disabled?>/></td>
        </tr>
        <tr class="supLabel">
            <td >Telefones para contato:</td>
            <td >E-mails (separados por v&iacute;rgula):</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="forn_telefone" value="<?php echo $forn_rs->forn_tel?>" style="width: 200px;" maxlength="100" tabindex="4" <?php echo $disabled?>/></td>
            <td><input type="text" name="forn_email" value="<?php echo $forn_rs->forn_email?>" style="width: 200px;" maxlength="100" tabindex="5" <?php echo $disabled?>/></td>            
        </tr>
        <tr class="supLabel">
            <td colspan="2">Observa&ccedil;&otilde;es:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><textarea name="forn_observacao" style="width: 427px; height: 50px" maxlength="100" tabindex="6" <?php echo $disabled?>><?php echo str_replace("<br/>","\r\n",$forn_rs->forn_observacao)?></textarea></td>            
        </tr>
        <tr>
            <td colspan="2">
            	<div id="endDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('end','endSign')">
                        <span id="endSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Endere&ccedil;o
                    </a>                    
                    <table id="end" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr class="supLabel">
                            <td colspan="2">Logradouro:</td>
                        </tr>
                        <tr class="supField">
                            <td colspan="2"><input type="text" name="forn_end_logradouro" value="<?php echo $forn_rs->forn_end_logradouro?>" style="width: 423px;" maxlength="100" tabindex="7" <?php echo $disabled?>/></td>                            
                        </tr>
                        <tr class="supLabel">
                        	<td>Complemento:</td>
                            <td>Bairro:</td>                                        
                        </tr>
                        <tr class="supField">
                        	<td width="210px"><input type="text" name="forn_end_complemento" value="<?php echo $forn_rs->forn_end_complemento?>" style="width: 200px;" maxlength="100" tabindex="8" <?php echo $disabled?>/></td>
                            <td width="210px"><input type="text" name="forn_end_bairro" value="<?php echo $forn_rs->forn_end_bairro?>" style="width: 200px;" maxlength="100" tabindex="9" <?php echo $disabled?>/></td>
                        </tr> 
                        <tr class="supLabel">
                            <td>CEP:</td>
                            <td>Cidade:</td>                            

                        </tr>
                        <tr class="supField">
                            <td><input type="text" name="forn_end_cep" value="<?php echo $forn_rs->forn_end_cep?>" style="width: 200px;" maxlength="9" tabindex="10" <?php echo $disabled?>/></td>
                            <td><input type="text" name="forn_end_cidade" value="<?php echo $forn_rs->forn_end_cidade?>" style="width: 200px;" maxlength="100" tabindex="11" <?php echo $disabled?>/></td>
                        </tr>
                        <tr class="supLabel">
                        	<td>Estado:</td>
                            <td>Pa&iacute;s:</td>                            
                        </tr>
                        <tr class="supField">
                        	<td><input type="text" name="forn_end_estado" value="<?php echo $forn_rs->forn_end_estado?>" style="width: 200px;" maxlength="100" tabindex="12" <?php echo $disabled?>/></td>
                            <td><input type="text" name="forn_end_pais" value="<?php echo $forn_rs->forn_end_pais?>" style="width: 200px;" maxlength="100" tabindex="13" <?php echo $disabled?>/></td>                            
                        </tr>                       
                    </table>
                </div>           
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('forn_form','inc/save/saveforn.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lforn','altforn')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">        	
            <td colspan="2">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$forn_rs->forn_dt_cad)?></font>&nbsp;
                          <?php echo date("d-m-y",$forn_rs->forn_dt_cad)?>
                          <?php 
						  	$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$forn_rs->forn_cad_usu_id;
							$sqlQuery3 = mysql_query($sql) or die(mysql_error());
							$tec_cad = mysql_fetch_object($sqlQuery3);
						  ?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $tec_cad->usu_nome;?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($forn_rs->forn_alter_usu_id != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$forn_rs->forn_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$forn_rs->forn_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$forn_rs->forn_dt_alter)?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $tec_rs->usu_nome;?>
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
<?php mysql_close()?>