<?php	
	session_start();	
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);	
?>
<form name="user_form">
	<input type="hidden" name="cmd" value="cadastrar" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" style="width: 500px">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">
                            <b>Cadastro de usu&aacute;rio</b>
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
            	<input type="text" name="obrg_tec_nome" style="width: 200px;" tabindex="1"/>
            </td>
            <td rowspan="3" style="padding-left: 14px" valign="top">
            	<table width="100%" cellpadding="0" cellspacing="0" border="0">                	
                    <tr>
                    	<td>
                        	<a href="javascript:selectAll('user_form','usu_cad','checkbox','cad_shield')" title="Marcar todos">
                                <img id="cad_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                        	<input type="checkbox" name="usu_cad_user" style="border:none"/>
                            <img src="img/btn/add_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar usu&aacute;rios"/>
                            <input type="checkbox" name="usu_cad_prod" style="border:none"/>
                            <img src="img/btn/add_prod.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar produtos"/>
                            <input type="checkbox" name="usu_cad_cli" style="border:none">
                            <img src="img/btn/add_cli.png" width="16px" height="16px" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar clientes"/>
                            <input type="checkbox" name="usu_cad_equ" style="border:none"/>
                            <img src="img/btn/equipamento_a.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar equipamentos"/>
                            <input type="checkbox" name="usu_cad_os" style="border:none"/>
                            <img src="img/btn/new_os.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para cadastrar ordens de servi&ccedil;o"/>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>
                        	<a href="javascript:selectAll('user_form','usu_view','checkbox','view_shield')" title="Marcar todos">
                                <img id="view_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                        	<input type="checkbox" name="usu_view_user" style="border:none"/>
                            <img src="img/btn/user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar usu&aacute;rios"/>
                            <input type="checkbox" name="usu_view_cli" style="border:none"/>
                            <img src="img/btn/cliente_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar clientes"/>                            
                            <input type="checkbox" name="usu_view_os" style="border:none"/>
                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para visualizar todas ordens de servi&ccedil;o"/>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>
                        	<a href="javascript:selectAll('user_form','otr_usu','checkbox','otr_shield')" title="Marcar todos">
                                <img id="otr_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                            <input type="checkbox" name="otr_usu_aprov_os" style="border:none"/>
                            <img src="img/btn/orcamento.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para autorizar ordem de servi&ccedil;o"/>
                        	<input type="checkbox" name="otr_usu_atrib_os" style="border:none"/>
                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para atribuir respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                            <input type="checkbox" name="otr_usu_alter_info" style="border:none"/>
                            <img src="img/btn/edit_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para alterar suas pr&oacute;prias informa&ccedil;&otilde;es pessoais"/>
                            <input type="checkbox" name="otr_usu_block" style="border:none"/>
                            <img src="img/btn/lock.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Permiss&atilde;o para bloquear cadastros"/>
                            <input type="checkbox" name="otr_usu_del_os" style="border:none"/>
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
            <td><input type="text" name="usu_email" style="width: 200px;"  tabindex="2" /></td>
            
        </tr>
        <tr class="supLabel">
            <td width="225px">Telefones para contato:</td>
            <td>Notifica&ccedil;&otilde;es:</td>           
        </tr>
        <tr class="supField">
            <td>
            	<input type="text" name="usu_tel" style="width: 200px;" tabindex="3" />
            </td> 
            <td rowspan="2" style="padding-left: 4px" valign="top">
            	<table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    	<td>
                        	<a href="javascript:selectAll('user_form','usu_not','checkbox','not_shield')" title="Marcar todos">
                                <img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                        	<input type="checkbox" name="usu_not_user" style="border:none"/>
                            <img src="img/btn/user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/alteração de usu&aacute;rios"/>
                            <input type="checkbox" name="usu_not_prod" style="border:none"/>
                            <img src="img/btn/prod_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/alteração de produtos"/>
                            <input type="checkbox" name="usu_not_cli" style="border:none"/>
                            <img src="img/btn/cliente_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/alteração de clientes"/>
                            <input type="checkbox" name="usu_not_equ" style="border:none"/>
                            <img src="img/btn/equipamento_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/alteração de equipamentos"/>
                            <input type="checkbox" name="usu_not_os" style="border:none"/>
                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de cadastro/alteração de ordens de servi&ccedil;o"/>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<a href="javascript:selectAll('user_form','otr_not','checkbox','otr_not_shield')" title="Marcar todos">
                                <img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                     style="margin-bottom: -2px; margin-right: 10px" />
                            </a>
                        	<input type="checkbox" name="otr_not_atrib_os" style="border:none"/>
                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                            	 title="Notifica&ccedil;&atilde;o de atribui&ccedil;&atilde;o de respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                            <input type="checkbox" name="otr_not_rel_adm" style="border:none"/>
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
            	<input type="text" name="obrg_usu_login" style="width: 200px;" tabindex="5" />
            </td>
            <td style="font-weight: bold; padding-left: 0px">Observa&ccedil;&otilde;es:</td>
        </tr>
        <tr class="supLabel">            
            <td>Nova senha:*</td>
            <td rowspan="4" valign="top" style="padding-left: 10px"><textarea name="usu_obs" style="width: 240px; height: 70px"></textarea></td>
        </tr>
        <tr class="supField">
        	<td><input type="password" name="obrg_usu_npass" style="width: 200px;" tabindex="6"  /></td>
        </tr>
        <tr class="supLabel">            
            <td>Confirmar nova senha:*</td>
        </tr>            
        <tr class="supField">
            <td><input type="password" name="obrg_usu_rnpass" style="width: 200px;" tabindex="7"  /></td>
        </tr>        
        <tr>
            <td colspan="2">
                <label><input type="checkbox" name="usu_status" value="S" checked="checked" style="border:none" />
                O usu&aacute;rio dever&aacute; alterar a senha no pr&oacute;ximo acesso.</label>
            </td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('user_form','inc/save/saveuser.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','auser')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>