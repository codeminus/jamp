<?php
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);	
	 
	$sql = "SELECT * FROM usuario
			WHERE usu_id=".$_SESSION["tec_id"];
	$sqlQuery = mysql_query($sql);
	$rs_tec = mysql_fetch_object($sqlQuery);
	
	if($_SESSION["tec_alter_info"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}
?>
<form name="info_form">
	<input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Informa&ccedil;&otilde;es pessoais</b></td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Nome:</td>
            <td>E-mail:</td>
        </tr>
        <tr class="supField">
            <td>
				<input type="hidden" name="tec_nome" value="<?php echo $rs_tec->usu_nome?>" />
				<?php if($_SESSION["tec_tipo"] != "C"){?>
                <input type="text" name="tec_nome" value="<?php echo $rs_tec->usu_nome?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
                <?php }else{ echo $rs_tec->usu_nome;}?>
            </td>
            <td>
                <input type="text" name="tec_email" value="<?php echo $rs_tec->usu_email?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Telefones para contato:</td>
            <td>Endere&ccedil;o:</td>
        </tr>
        <tr class="supField">
            <td  valign="top">
                <input type="text" name="tec_telefone" value="<?php echo $rs_tec->usu_tel?>" 
                       style="width: 200px;" <?php echo $disabled?> />
            </td>
            <td rowspan="3" valign="top">
                <?php if($rs_tec->usu_end_logradouro != ""){ echo $rs_tec->usu_end_logradouro.'<br/>';}?>
                <?php if($rs_tec->usu_end_complemento != ""){ echo $rs_tec->usu_end_complemento.'<br/>';}?>
                <?php if($rs_tec->usu_end_bairro != ""){ echo $rs_tec->usu_end_bairro.", ";}?>
				<?php if($rs_tec->usu_end_cep != ""){ echo 'CEP: '.$rs_tec->usu_end_cep.'<br/>';}?>
                <?php if($rs_tec->usu_end_cidade != ""){ echo $rs_tec->usu_end_cidade.' - ';}?>
				<?php if($rs_tec->usu_end_estado != ""){ echo $rs_tec->usu_end_estado.' / ';}?>
				<?php if($rs_tec->usu_end_pais != ""){ echo $rs_tec->usu_end_pais;}?>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px" valign="top">Usu&aacute;rio do sistema:</td>	            
        </tr>
        <tr class="supField">
            <td valign="top"><?php echo $rs_tec->usu_login?></td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Nova senha:</td>
            <td>Confirmar nova senha:</td>
        </tr>
        <tr class="supField">
            <td><input type="password" name="obrg_tec_npass" value="000000" style="width: 200px;" <?php echo $disabled?> /></td>
            <td><input type="password" name="obrg_tec_rnpass" value="000000" style="width: 200px;" <?php echo $disabled?> /></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>; ">
            <td style="padding: 2px" colspan="2"><span><b>Configura&ccedil;&otilde;es pessoais:</b></span></td>
        </tr>
        <tr>
            <td><b>Registros por página:*</b> <input type="text" name="obrg_usu_reg_per_page" value="<?php echo $rs_tec->usu_reg_per_page?>" 
                	   style="width: 50px; text-align:right" maxlength="2" /></td>
        </tr>
        <tr>
            <td>
            	<?php if($rs_tec->usu_view_alertas == "S"){ $checked = 'checked="checked"'; }?>
                <label><input type="checkbox" name="usu_view_alertas" value="S" style="border: none" <?php echo $checked?>/>
                <img src="img/btn/alerta.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Mostrar alertas ao iniciar</label>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('info_form','inc/save/saveinfo.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','altinfo')" 
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px" <?php echo $disabled?> /></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<?php if($_SESSION["tec_adv_info"] == "S"){?>
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Cadastrado em:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_tec->usu_dt_criacao)?></font>&nbsp;
                          <?php echo date("d-m-y",$rs_tec->usu_dt_criacao)?>&nbsp;
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">&Uacute;ltimas altera&ccedil;&otilde;es:</b>
                    <?php 
						if($rs_tec->usu_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$rs_tec->usu_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_tec->usu_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$rs_tec->usu_dt_alter)?>
                          <b style="font-size: 7pt">por:</b> 
                          <?php echo $tec_rs->usu_nome;?>
                    <?php }else{?>
                        Nunca
                    <?php }?>      
                    </div> 
                    <div align="center">
                    <b style="font-size: 7pt">&Uacute;ltimo acesso:</b>
                    <?php if($_SESSION["tec_dt_login"] != NULL){?>
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$_SESSION["tec_dt_login"])?></font>&nbsp;
                          <?php echo date("d-m-y",$_SESSION["tec_dt_login"])?>&nbsp;
                    <b style="font-size: 7pt">atrav&eacute;z do IP:</b> <?php echo $_SESSION["tec_last_ip"]?>      
                    <?php }else{?>
                    	Nunca
                    <?php }?>                    
                    </div>                   
                    <div style="clear:both"></div>
                </div>
                <?php }else{ echo "&nbsp;";}?>                
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close();?>