<?php
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM usuario
			JOIN cliente ON usu_id=cli_usu_id
			WHERE usu_id=".$_GET["cli_id"];		
	$sqlQuery = mysql_query($sql);	
	$cli_rs = mysql_fetch_object($sqlQuery);
	
	($cli_rs->usu_notificacao == "S")? $usu_notificacao = checked : $usu_notificacao = "";
	
	($cli_rs->usu_status == "AS")? $usu_status = checked : $usu_status = "";
	
	
	if($_SESSION["tec_cad_cli"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}
	
	$checked = "checked=\"checked\"";
	
	($cli_rs->cli_not_info == "S")? $cli_not_info = checked : $cli_not_info = "";
	($cli_rs->cli_not_cad_equ == "S")? $cli_not_cad_equ = checked : $cli_not_cad_equ = "";
	($cli_rs->cli_not_cad_os == "S")? $cli_not_cad_os = checked : $cli_not_cad_os = "";
	($cli_rs->cli_not_atrib_os == "S")? $cli_not_atrib_os = checked : $cli_not_atrib_os = "";
	
	($cli_rs->cli_not_cad_pend == "S")? $cli_not_cad_pend = checked : $cli_not_cad_pend = "";
	($cli_rs->cli_not_cad_rel == "S")? $cli_not_cad_rel = checked : $cli_not_cad_rel = "";
?>
<form name="cli_form">  
	<input type="hidden" name="cli_id" value="<?php echo $cli_rs->usu_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" style="margin-top: 10px">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Cliente:</b> #<?php echo $cli_rs->usu_id?></td>
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
            <td colspan="2">Cliente:*</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><input type="text" name="obrg_cli_nome" value="<?php echo $cli_rs->usu_nome?>" style="width: 427px;" maxlength="100" tabindex="1" <?php echo $disabled?>/></td>            
        </tr>
        <tr class="supLabel">            
            <td>CPF/CNPJ:</td>
            <td>Inscri&ccedil;&atilde;o estadual:</td>
        </tr>
        <tr class="supField">            
            <td><input type="text" name="cli_cp" value="<?php echo $cli_rs->cli_cp?>" style="width: 200px;" maxlength="20" tabindex="2" <?php echo $disabled?>/></td>
            <td><input type="text" name="cli_inscricao" value="<?php echo $cli_rs->cli_inscricao?>" style="width: 200px;" maxlength="20" tabindex="3" <?php echo $disabled?>/></td>
        </tr>
        <tr class="supLabel">
            <td >Telefones para contato:</td>
            <td >E-mails (separados por v&iacute;rgula):</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="cli_telefone" value="<?php echo $cli_rs->usu_tel?>" style="width: 200px;" maxlength="100" tabindex="4" <?php echo $disabled?>/></td>
            <td><input type="text" name="cli_email" value="<?php echo $cli_rs->usu_email?>" style="width: 200px;" maxlength="100" tabindex="5" <?php echo $disabled?>/></td>            
        </tr>
        <tr class="supLabel">
            <td colspan="2">Observa&ccedil;&otilde;es:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><textarea name="usu_obs" style="width: 427px; height: 50px" tabindex="6"><?php echo str_replace("<br/>","\r\n",$cli_rs->usu_obs)?></textarea></td>            
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
                            <td colspan="2"><input type="text" name="cli_end_logradouro" value="<?php echo $cli_rs->usu_end_logradouro?>" style="width: 423px;" maxlength="100" tabindex="7" <?php echo $disabled?>/></td>                            
                        </tr>
                        <tr class="supLabel">
                        	<td>Complemento:</td>
                            <td>Bairro:</td>                                        
                        </tr>
                        <tr class="supField">
                            <td width="210px"><input type="text" name="cli_end_complemento" value="<?php echo $cli_rs->usu_end_complemento?>" style="width: 200px;" maxlength="100" tabindex="8" <?php echo $disabled?>/></td>
                            <td width="210px"><input type="text" name="cli_end_bairro" value="<?php echo $cli_rs->usu_end_bairro?>" style="width: 200px;" maxlength="100" tabindex="9" <?php echo $disabled?>/></td>
                        </tr> 
                        <tr class="supLabel">
                        	<td>CEP:</td>
                            <td>Cidade:</td>
                        </tr>
                        <tr class="supField">
                        	<td><input type="text" name="cli_end_cep" value="<?php echo $cli_rs->usu_end_cep?>" style="width: 200px;" maxlength="9" tabindex="10" <?php echo $disabled?>/></td>
                            <td><input type="text" name="cli_end_cidade" value="<?php echo $cli_rs->usu_end_cidade?>" style="width: 200px;" maxlength="100" tabindex="11" <?php echo $disabled?>/></td>                                        
                        </tr>
                        <tr class="supLabel">
                        	<td>Estado:</td>
                            <td>Pa&iacute;s:</td>                            
                        </tr>
                        <tr class="supField">
                        	<td><input type="text" name="cli_end_estado" value="<?php echo $cli_rs->usu_end_estado?>" style="width: 200px;" maxlength="100" tabindex="12" <?php echo $disabled?>/></td>
                            <td><input type="text" name="cli_end_pais" value="<?php echo $cli_rs->usu_end_pais?>" style="width: 200px;" maxlength="100" tabindex="13" <?php echo $disabled?>/></td>                            
                        </tr>                       
                    </table>
                </div>           
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            	<div id="sysDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:void(0)">
                        <span id="sysSign" title="">
                            <img src="img/btn/menos.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Informa&ccedil;&otilde;es para o sistema
                    </a>                    
                    <table id="sys" border="0" style="width: 100%; display:block; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr class="supLabel">
                            <td width="210px" valign="top">Usu&aacute;rio do sistema:*</td>
                            <td style="font-weight: bold">Notifica&ccedil;&otilde;es:</td>	            
                        </tr>
                        <tr class="supField">
                            <td valign="top"><input type="text" name="obrg_cli_user" value="<?php echo $cli_rs->usu_login?>" style="width: 200px;" maxlength="100" tabindex="14" <?php echo $disabled?> /></td>
                            <td rowspan="2" style=" padding-left: 0px" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <?php if($_SESSION["tec_cad_cli"] == "S"){?>
                                            <a href="javascript:selectAll('cli_form','cli_not','checkbox','not_shield')" title="Notifica&ccedil;&atilde;o de todos">
                                                <img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            </a>
                                            <?php }else{?>
                                                <img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            <?php }?>
                                            <input type="checkbox" name="cli_not_info" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_info?>/>
                                            <img src="img/btn/edit_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de altera&ccedil;&atilde;o das informações pessoais"/>
                                            <input type="checkbox" name="cli_not_cad_equ" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_cad_equ?>/>
                                            <img src="img/btn/equipamento_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de equipamentos"/>
                                            <input type="checkbox" name="cli_not_cad_os" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_cad_os?>/>
                                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de ordens de servi&ccedil;o"/>
                                            <input type="checkbox" name="cli_not_atrib_os" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_atrib_os?>/>
                                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de atribui&ccedil;&atilde;o de respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php if($_SESSION["tec_cad_cli"] == "S"){?>
                                            <a href="javascript:selectAll('cli_form','otr_not','checkbox','otr_not_shield')" title="Notifica&ccedil;&atilde;o de todas as outras">
                                                <img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            </a>
                                            <?php }else{?>
                                                <img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            <?php }?>
                                            <input type="checkbox" name="otr_not_rel" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_cad_rel?>/>
                                            <img src="img/btn/relatorio.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro de relat&oacute;rio"/>
                                            <input type="checkbox" name="otr_not_pend" style="border:none" 
                                                    <?php echo $disabled?> <?php echo $cli_not_cad_pend?>/>
                                            <img src="img/btn/pend.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro de pend&ecirc;ncia"/>                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="supLabel">
                            <td>Senha:*</td>                                            
                        </tr>
                        <tr class="supField">
                            <td><input type="password" name="obrg_cli_npass" value="000000" style="width: 200px;" maxlength="100" tabindex="15" <?php echo $disabled?>/></td>                            
                        </tr>
                        <tr class="supLabel">
                            <td>Confirmar senha:*</td>                             
                        </tr>
                        <tr class="supField">
                            <td><input type="password" name="obrg_cli_rnpass" value="000000" style="width: 200px;" tabindex="16" <?php echo $disabled?>/></td>
                        </tr>	           
                    </table>
                </div>
            </td>           
        <tr>
            <td colspan="2">
                <label><input type="checkbox" name="cli_status" value="S" <?php echo $disabled?>
                		style="border: none" <?php echo $usu_status?> />
                O usu&aacute;rio dever&aacute; alterar a senha no pr&oacute;ximo acesso.</label>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('cli_form','inc/save/savecli.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lcli','altcli')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">        	
            <td colspan="2">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$cli_rs->usu_dt_criacao)?></font>&nbsp;
                          <?php echo date("d-m-y",$cli_rs->usu_dt_criacao)?>
                          <?php 
						  	$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$cli_rs->usu_criacao_usu_id;
							$sqlQuery3 = mysql_query($sql) or die(mysql_error());
							$tec_cad = mysql_fetch_object($sqlQuery3);
						  ?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $tec_cad->usu_nome;?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($cli_rs->usu_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$cli_rs->usu_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$cli_rs->usu_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$cli_rs->usu_dt_alter)?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $tec_rs->usu_nome;?>
                    <?php }else{?>
                        Nunca
                    <?php }?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">&Uacute;ltimo acesso:</b>
                    <?php if($cli_rs->usu_dt_login != NULL){?>
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$cli_rs->usu_dt_login)?></font>&nbsp;
                          <?php echo date("d-m-y",$cli_rs->usu_dt_login)?>&nbsp;
                    <b style="font-size: 7pt">atrav&eacute;z do IP:</b> <?php echo $cli_rs->usu_last_ip?>      
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