<?php	
	session_start();	
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);	
?>
<form name="cli_form">
	<input type="hidden" name="cmd" value="cadastrar" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" style="margin-top: 10px">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">
                            <b>Cadastro de cliente</b>
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
            <td colspan="2">Cliente:*</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><input type="text" name="obrg_cli_nome" style="width: 427px;" maxlength="100" tabindex="1" /></td>            
        </tr>
        <tr class="supLabel">            
            <td>CPF/CNPJ:</td>
            <td>Inscri&ccedil;&atilde;o estadual:</td>
        </tr>
        <tr class="supField">            
            <td><input type="text" name="cli_cp" style="width: 200px;" maxlength="20" tabindex="2" /></td>
            <td><input type="text" name="cli_inscricao" style="width: 200px;" maxlength="20" tabindex="3"/></td>
        </tr>
        <tr class="supLabel">
            <td >Telefones:</td>
            <td >E-mails (separados por v&iacute;rgula):</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="cli_telefone" style="width: 200px;" maxlength="100" tabindex="4"/></td>
            <td><input type="text" name="cli_email" style="width: 200px;" maxlength="100" tabindex="5"/></td>            
        </tr>
        <tr class="supLabel">
            <td colspan="2">Observa&ccedil;&otilde;es:</td>            
        </tr>
        <tr class="supField">
            <td colspan="2"><textarea name="usu_obs" style="width: 427px; height: 50px" tabindex="6"></textarea></td>            
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
                            <td colspan="2"><input type="text" name="cli_end_logradouro" style="width: 423px;" maxlength="100" tabindex="7"/></td>                            
                        </tr>
                        <tr class="supLabel">
                        	<td>Complemento:</td>
                            <td>Bairro:</td>                                        
                        </tr>
                        <tr class="supField">
                        	<td width="210px"><input type="text" name="cli_end_complemento" style="width: 200px;" maxlength="100" tabindex="8"/></td>
                            <td width="210px"><input type="text" name="cli_end_bairro" style="width: 200px;" maxlength="100" tabindex="9"/></td>
                        </tr> 
                        <tr class="supLabel">
                            <td>CEP:</td>
                            <td>Cidade:</td>                            
                        </tr>
                        <tr class="supField">
                            <td><input type="text" name="cli_end_cep" style="width: 200px;" maxlength="9" tabindex="10"/></td>
                            <td><input type="text" name="cli_end_cidade" style="width: 200px;" maxlength="100" tabindex="11"/></td>
                        </tr>
                        <tr class="supLabel">
                        	<td>Estado:</td>
                            <td>Pa&iacute;s:</td>                            
                        </tr>
                        <tr class="supField">
                        	<td><input type="text" name="cli_end_estado" style="width: 200px;" maxlength="100" tabindex="12"/></td>
                            <td><input type="text" name="cli_end_pais" style="width: 200px;" maxlength="100" tabindex="13"/></td>                            
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
                            <td valign="top"><input type="text" name="obrg_cli_user" style="width: 200px;" maxlength="100" tabindex="14" /></td>
                            <td rowspan="2" style=" padding-left: 0px" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>                                
                                            <a href="javascript:selectAll('cli_form','cli_not','checkbox','not_shield')" title="Notifica&ccedil;&atilde;o de todos">
                                                <img id="not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            </a>                                
                                            <label><input type="checkbox" name="cli_not_info" style="border:none"/>
                                            <img src="img/btn/edit_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de altera&ccedil;&atilde;o das informa��es pessoais"/></label>
                                            <label><input type="checkbox" name="cli_not_cad_equ" style="border:none"/>
                                            <img src="img/btn/equipamento_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de equipamentos"/></label>
                                            <label><input type="checkbox" name="cli_not_cad_os" style="border:none"/>
                                            <img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro/altera&ccedil;&atilde;o de ordens de servi&ccedil;o"/></label>
                                            <label>
                                            <input type="checkbox" name="cli_not_atrib_os" style="border:none"/>
                                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de atribui&ccedil;&atilde;o de respons&aacute;vel a uma ordem de servi&ccedil;o"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>                                
                                            <a href="javascript:selectAll('cli_form','otr_not','checkbox','otr_not_shield')" title="Notifica&ccedil;&atilde;o de todas as outras">
                                                <img id="otr_not_shield" src="img/btn/shield_d.png" width="16" height="16" border="0" 
                                                     style="margin-bottom: -2px; margin-right: 10px" />
                                            </a>                                
                                            <label><input type="checkbox" name="otr_not_rel" style="border:none"/>
                                            <img src="img/btn/relatorio.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro de relat&oacute;rio"/></label>
                                            <label><input type="checkbox" name="otr_not_pend" style="border:none"/>
                                            <img src="img/btn/pend.png" width="16" height="16" border="0" style="margin-bottom: -2px" 
                                                 title="Notifica&ccedil;&atilde;o de cadastro de pend&ecirc;ncia"/></label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="supLabel">
                            <td>Senha:*</td>                                            
                        </tr>
                        <tr class="supField">
                            <td><input type="password" name="obrg_cli_npass" style="width: 200px;" maxlength="100" tabindex="15" /></td>                            
                        </tr>
                        <tr class="supLabel">
                            <td>Confirmar senha:*</td>                             
                        </tr>
                        <tr class="supField">
                            <td><input type="password" name="obrg_cli_rnpass" style="width: 200px;" tabindex="16" /></td>
                        </tr>	           
                    </table>
                </div>
            </td>
        </tr>        
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('cli_form','inc/save/savecli.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','acli')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>       
     </table>   
</form>