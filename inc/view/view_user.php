<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM usuario
			WHERE usu_id=".$_GET["usu_id"];		
	$sqlQuery = mysql_query($sql);	
	$usu_rs = mysql_fetch_object($sqlQuery);
	
	$checked = "checked=\"checked\"";
	
	($usu_rs->usu_cad_user == "S")? $usu_cad_user = checked : $usu_cad_user = "";
	($usu_rs->usu_cad_prod == "S")? $usu_cad_prod = checked : $usu_cad_prod = "";
	($usu_rs->usu_cad_cli == "S")? $usu_cad_cli = checked : $usu_cad_cli = "";
	($usu_rs->usu_cad_equ == "S")? $usu_cad_equ = checked : $usu_cad_equ = "";
	($usu_rs->usu_cad_os == "S")? $usu_cad_os = checked : $usu_cad_os = "";
	
	($usu_rs->usu_view_user == "S")? $usu_view_user = checked : $usu_view_user = "";
	($usu_rs->usu_view_cli == "S")? $usu_view_cli = checked : $usu_view_cli = "";	
	($usu_rs->usu_view_os == "S")? $usu_view_os = checked : $usu_view_os = "";	
	
	($usu_rs->usu_aprov_os == "S")? $usu_aprov_os = checked : $usu_aprov_os = "";
	($usu_rs->usu_atrib_os == "S")? $usu_atrib_os = checked : $usu_atrib_os = "";
	($usu_rs->usu_alter_info == "S")? $usu_alter_info = checked : $usu_alter_info = "";
	($usu_rs->usu_block == "S")? $usu_block = checked : $usu_block = "";
	($usu_rs->usu_del_os == "S")? $usu_del_os = checked : $usu_del_os = "";
	
	($usu_rs->usu_not_user == "S")? $usu_not_user = checked : $usu_not_user = "";
	($usu_rs->usu_not_prod == "S")? $usu_not_prod = checked : $usu_not_prod = "";
	($usu_rs->usu_not_cli == "S")? $usu_not_cli = checked : $usu_not_cli = "";
	($usu_rs->usu_not_equ == "S")? $usu_not_equ = checked : $usu_not_equ = "";
	($usu_rs->usu_not_os == "S")? $usu_not_os = checked : $usu_not_os = "";
	($usu_rs->usu_not_atrib_os == "S")? $usu_not_atrib_os = checked : $usu_not_atrib_os = "";
	($usu_rs->usu_not_rel_adm == "S")? $usu_not_rel_adm = checked : $usu_not_rel_adm = "";
	
	($usu_rs->usu_notificacao == "S")? $usu_notificacao = checked : $usu_notificacao = "";
	
	($usu_rs->usu_status == "AS")? $usu_status = checked : $usu_status = "";
	
	
	if($_SESSION["tec_cad_user"] == "N" || ($_SESSION["tec_alter_info"] == "N" && $_SESSION["tec_id"] == $usu_rs->usu_id)){
		$disabled = "disabled=\"disabled\"";
	}
?>
<form name="user_form">
	<input type="hidden" name="cmd" value="alterar" />
    <input type="hidden" name="usu_id" value="<?php echo $_GET["usu_id"]?>" />
    <input type="hidden" name="tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" style="width: 500px">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">
                            <b>Usu&aacute;rio:</b> #<?php echo $_GET["usu_id"]?>
                        </td>
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
            <td width="225px">Nome:*</td>
            <td>Permiss&otilde;es:</td>
        </tr>        
        <tr>
            <td style="padding-left: 10px">
            	<input type="text" name="obrg_tec_nome" style="width: 200px;" tabindex="1" 
                		value="<?php echo $usu_rs->usu_nome?>" <?php echo $disabled?> />
            </td>
            <td rowspan="3" style="border-left: solid 2px <?php echo $colors[2]?>; padding-left: 14px" valign="top">
            	<table width="100%" cellpadding="0" cellspacing="0" border="0">                	
                    <tr>
                    	<td>
                        	<?php if($_SESSION["tec_cad_user"] == "S"){?>
                        	<a href="javascript:selectAll('user_form','usu_cad','checkbox','cad_shield')" title="Cadastrar todos">
                                <img id="cad_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <?php }else{?>
                            	<img id="cad_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            <?php }?>
                        	<input type="checkbox" name="usu_cad_user" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_cad_user?> />
                            <img src="img/btn/add_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar usu&aacute;rios"/>
                            <input type="checkbox" name="usu_cad_prod" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_cad_prod?> />
                            <img src="img/btn/add_prod.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar produtos"/>
                            <input type="checkbox" name="usu_cad_cli" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_cad_cli?>/>
                            <img src="img/btn/add_cli.png" width="16px" height="16px" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar clientes"/>
                            <input type="checkbox" name="usu_cad_equ" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_cad_equ?>/>
                            <img src="img/btn/equipamento_a.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar equipamentos"/>
                            <input type="checkbox" name="usu_cad_os" style="border:none;" 
									<?php echo $disabled?> <?php echo $usu_cad_os?>/>
                            <img src="img/btn/new_os.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar ordens de servi&ccedil;o"/>
                        </td>
                    </tr>                    
                    <tr>
                    	<td>
                        	<?php if($_SESSION["tec_cad_user"] == "S"){?>
                        	<a href="javascript:selectAll('user_form','usu_view','checkbox','view_shield')" title="Visualizar todos">
                                <img id="view_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <?php }else{?>
                            	<img id="view_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            <?php }?>
                        	<input type="checkbox" name="usu_view_user" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_view_user?>/>
                            <img src="img/btn/user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar usu&aacute;rios"/>
                            <input type="checkbox" name="usu_view_cli" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_view_cli?>/>
                            <img src="img/btn/cliente_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar clientes"/>                            <input type="checkbox" name="usu_view_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_view_os?>/>
                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar todas ordens de servi&ccedil;o"/>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>
                        	<?php if($_SESSION["tec_cad_user"] == "S"){?>
                        	<a href="javascript:selectAll('user_form','otr_usu','checkbox','otr_shield')" title="Todas outras">
                                <img id="otr_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <?php }else{?>
                            	<img id="otr_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            <?php }?>
                            <input type="checkbox" name="otr_usu_aprov_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_aprov_os?>/>
                            <img src="img/btn/orcamento.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para autorizar ordem de servi&ccedil;o"/>
                        	<input type="checkbox" name="otr_usu_atrib_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_atrib_os?>/>
                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para atribuir respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                            <input type="checkbox" name="otr_usu_alter_info" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_alter_info?>/>
                            <img src="img/btn/edit_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para alterar suas pr&oacute;prias informa&ccedil;&otilde;es pessoais"/>
                            <input type="checkbox" name="otr_usu_block" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_block?>/>
                            <img src="img/btn/lock.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para bloquear cadastros"/>
                            <input type="checkbox" name="otr_usu_del_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_del_os?>/>
                            <img src="img/btn/new_os_dd.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para apagar ordens de servi&ccedil;o"/>     
                        </td>
                    </tr>
                </table>
            </td>            
        </tr>        
        <tr class="supLabel">
            <td width="225px">E-mails (separados por v&iacute;rgula):</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="usu_email" style="width: 200px;"  tabindex="2" <?php echo $disabled?>
            			value="<?php echo $usu_rs->usu_email?>"/></td>
            
        </tr>
        <tr class="supLabel">
            <td width="225px">Telefones para contato:</td>
            <td>Notifica&ccedil;&otilde;es:</td>
        </tr>
        <tr class="supField">
            <td>
            	<input type="text" name="usu_tel" style="width: 200px;" tabindex="3"  <?php echo $disabled?>
                		value="<?php echo $usu_rs->usu_tel?>"/>
            </td> 
            <td rowspan="2" style="border-left: solid 2px <?php echo $colors[2]?>; padding-left: 4px" valign="top">
            	<table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    	<td>
                        	<?php if($_SESSION["tec_cad_user"] == "S"){?>
                        	<a href="javascript:selectAll('user_form','usu_not','checkbox','not_shield')" title="Notifica&ccedil;&atilde;o de todos">
                                <img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <?php }else{?>
                            	<img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            <?php }?>
                        	<input type="checkbox" name="usu_not_user" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_user?>/>
                            <img src="img/btn/user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de usu&aacute;rios"/>
                            <input type="checkbox" name="usu_not_prod" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_prod?>/>
                            <img src="img/btn/prod_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de produtos"/>
                            <input type="checkbox" name="usu_not_cli" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_cli?>/>
                            <img src="img/btn/cliente_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de clientes"/>
                            <input type="checkbox" name="usu_not_equ" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_equ?>/>
                            <img src="img/btn/equipamento_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de equipamentos"/>
                            <input type="checkbox" name="usu_not_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_os?>/>
                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de ordens de servi&ccedil;o"/>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<?php if($_SESSION["tec_cad_user"] == "S"){?>
                        	<a href="javascript:selectAll('user_form','otr_not','checkbox','otr_not_shield')" title="Notifica&ccedil;&atilde;o de todas as outras">
                                <img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <?php }else{?>
                            	<img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            <?php }?>
                        	<input type="checkbox" name="otr_not_atrib_os" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_atrib_os?>/>
                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de atribui&ccedil;&atilde;o de respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                            <input type="checkbox" name="otr_not_rel_adm" style="border:none" 
									<?php echo $disabled?> <?php echo $usu_not_rel_adm?>/>
                            <img src="img/btn/relatorio.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro de relat&oacute;rio administrativo"/>
                        </td>
                    </tr>
        		</table>
        	</td>
        </tr>        
        <tr class="supLabel">            
            <td>Usu&aacute;rio do sistema:*</td>
        </tr>
        <tr class="supField">
        	<td>
            	<input type="text" name="obrg_usu_login" style="width: 200px;" tabindex="5" <?php echo $disabled?>
                		value="<?php echo $usu_rs->usu_login?>"/>
            </td>
            <td style="font-weight: bold; padding-left: 0px">Observa&ccedil;&otilde;es:</td>
        </tr>
        <tr class="supLabel">            
            <td>Nova senha:*</td>
            <td rowspan="4" valign="top" style="padding-left: 10px"><textarea name="usu_obs" style="width: 240px; height: 70px" <?php echo $disabled?> ><?php echo str_replace("<br/>","\r\n",$usu_rs->usu_obs)?></textarea></td>
        </tr>
        <tr class="supField">
        	<td><input type="password" name="obrg_usu_npass" style="width: 200px;" tabindex="6" value="000000" <?php echo $disabled?> /></td>
        </tr>
        <tr class="supLabel">            
            <td>Confirmar nova senha:*</td>
        </tr>            
        <tr class="supField">
            <td><input type="password" name="obrg_usu_rnpass" style="width: 200px;" tabindex="7" value="000000" <?php echo $disabled?> /></td>
        </tr>        
        <tr>
            <td colspan="2">
                <label><input type="checkbox" name="usu_status" value="S" <?php echo $disabled?>
                		style="border: none" <?php echo $usu_status?> />
                O usu&aacute;rio dever&aacute; alterar a senha no pr&oacute;ximo acesso.</label>
            </td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('user_form','inc/save/saveuser.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'luser','altuser')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>
                     <?php 						
						$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$usu_rs->usu_criacao_usu_id;
						$cri_sqlQuery = mysql_query($sql) or die(mysql_error());
						$cri_tec_rs = mysql_fetch_object($cri_sqlQuery);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$usu_rs->usu_dt_criacao)?></font>&nbsp;
                          <?php echo date("d-m-y",$usu_rs->usu_dt_criacao)?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $cri_tec_rs->usu_nome;?>                    
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($usu_rs->usu_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$usu_rs->usu_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$usu_rs->usu_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$usu_rs->usu_dt_alter)?>
                          <b style="font-size: 7pt">por:</b>
						  <?php echo $tec_rs->usu_nome;?>
                    <?php }else{?>
                        Nunca
                    <?php }?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">&Uacute;ltimo acesso:</b>
                    <?php if($usu_rs->usu_dt_login != NULL){?>
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$usu_rs->usu_dt_login)?></font>&nbsp;
                          <?php echo date("d-m-y",$usu_rs->usu_dt_login)?>&nbsp;
                    <b style="font-size: 7pt">atrav&eacute;z do IP:</b> <?php echo $usu_rs->usu_last_ip?>      
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