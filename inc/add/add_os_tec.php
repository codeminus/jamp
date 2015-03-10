<?php	
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	
	$sql = "SELECT * FROM equipamento
			JOIN usuario ON equ_cli_id=usu_id 
			WHERE equ_id=".$_GET["equ_id"];		
	$sqlQuery = mysql_query($sql);	
	$equ_rs = mysql_fetch_object($sqlQuery);
		
?>
<form name="os_form">    	
	<input type="hidden" name="cmd" value="<?php echo $cmd?>" />
	<input type="hidden" name="equ_id" value="<?php echo $equ_rs->equ_id?>" />
    <input type="hidden" name="cli_id" value="<?php echo $equ_rs->equ_cli_id?>" />
    <input type="hidden" name="cad_tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0" style="width: 750px; margin-top: 10px">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>EQID:</b> #<?php echo $equ_rs->equ_id?> - <b>Ordem de servi&ccedil;o</b> </td>
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
            <td width="225px">Cliente:</td>
            <td width="50%">Nome do equipamento:</td>
        </tr>
        <tr class="supField">
            <td><?php echo $equ_rs->usu_nome?></td>
            <td><?php echo $equ_rs->equ_nome?></td>
        </tr>
        <tr class="supLabel">            
            <td>Solicitada por:*</td>
            <td>T&eacute;cnico respons&aacute;vel:</td>
        </tr>
        <tr class="supField">            
            <td><input type="text" name="obrg_os_autorizado_por" style="width: 350px" maxlength="50" /></td>			
            <td>
                <select name="tec_id" style="width: 350px;">
                    <option value=""></option>
                    <?php 
                        $sql = "SELECT usu_id,usu_nome FROM usuario
                                WHERE usu_tipo='T' AND usu_status<>'I'";
                        
						if($_SESSION["tec_atrib_os"] == "N"){
							$sql .= " AND usu_id=".$_SESSION["tec_id"];
						}
						
						$sqlQuery = mysql_query($sql);
						
                        while($tec_rs = mysql_fetch_object($sqlQuery)){							
							
							($tec_rs->usu_id == $_SESSION["tec_id"])? $selected = "selected": $selected = "";
                    ?>
                            <option value="<?php echo $tec_rs->usu_id?>" <?php echo $selected?>><?php echo $tec_rs->usu_nome?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>            
        </tr>
        <tr class="supLabel">
            <td>
                Descri&ccedil;&atilde;o:*
            </td>            
            <td>
            	Prazo de execu&ccedil;&atilde;o:                
                <input type="text" name="os_data_prazo" value="" style="width: 203px; text-align:center" onchange="verifyDate(this,'<?php echo date("d/m/Y") ?>')" 
                	   disabled />
                <a href="javascript:void(0)" onClick="displayCalendar(document.forms['os_form'].os_data_prazo,'dd/mm/yyyy',this)" title="Adicionar data" tabindex="3" >
                	<img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                </a>
                <a href="javascript:void(0)" onclick="document.forms['os_form'].os_data_prazo.value=''"><img src="img/btn/del.png" width="16" height="16" border="0" style="margin-bottom: -3px" title="Apagar prazo" /></a>
            </td>            
        </tr>
        <tr class="supField">
            <td colspan="2">            	
                <textarea name="obrg_os_pro_cli_descricao" style="width: 720px; height: 50px;"></textarea>
            </td>            
        </tr>
        <tr class="supLabel">
            <td colspan="2">
                Observa&ccedil;&atilde;o:
            </td>
        </tr>
        <tr class="supField">
            <td colspan="2">            	
                <textarea name="os_tec_obs" style="width: 720px; height: 50px;"></textarea>
            </td>            
        </tr>        
        <tr>
        	<td colspan="2">
                <div id="osservDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('osserv','osservSign')">
                        <span id="osservSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Servi&ccedil;os
                    </a>                    
                    <table id="osserv" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                        	<td>
                            	<b>Pesquisar servi&ccedil;os:</b>
                            	<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){?>                            	
                                <input type="text" style="width:615px" onkeyup="doSearch('servicos', 'os_form', 'searchserv.php', this, 'servResult')" />
                            	<?php }else{?>
                                <input type="text" style="width:617px" onkeyup="doSearch('servicos', 'os_form', 'searchserv.php', this, 'servResult')" />
                                <?php }?>
                            </td>
                        </tr>                        
                        <tr>
                            <td>                            	
                            	<div id="servResult" style="overflow-y: auto; overflow-x:hidden;"></div>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantServ">0</span> servi&ccedil;os adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                        		<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){?>
                                                <table id="servicos" width="97.5%" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Servi&ccedil;o</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                </table>
                                                <?php }else{?>
                                                <table id="servicos" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Servi&ccedil;o</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                </table>
                                                <?php }?>
                                        	</div>
                                        </td>
                                    </tr>
                                	<tr>
                                        <td align="right">
                                        	<b>Subtotal:</b> R$ <span id="serv_subtotal">0,00</span>
                                        </td>
                                    </tr>                           
                                </table>
                            </td>
                         </tr>
                    </table>
                </div>
            </td>
        </tr>        
        <tr>
        	<td colspan="2">
                <div id="osprodDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('osprod','osprodSign')">
                        <span id="osprodSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Produtos
                    </a>                    
                    <table id="osprod" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                        	<td>
                            	<b>Pesquisar produtos:</b>
                            	<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){?>                            	
                                <input type="text" style="width:611px" onkeyup="doSearch('produtos', 'os_form', 'searchprod.php', this, 'prodResult')" />
                            	<?php }else{?>
                                <input type="text" style="width:613px" onkeyup="doSearch('produtos', 'os_form', 'searchprod.php', this, 'prodResult')" />
                                <?php }?>
                            </td>
                        </tr>                        
                        <tr>
                            <td>                            	
                            	<div id="prodResult" style="overflow-y: auto; overflow-x:hidden;"></div>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantProd">0</span> produtos adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                        		<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){?>
                                                <table id="produtos" width="97.5%" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Produto</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                </table>
                                                <?php }else{?>
                                                <table id="produtos" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Produto</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                </table>
                                                <?php }?>
                                        	</div>
                                        </td>
                                    </tr>
                                	<tr>
                                        <td align="right">
                                        	<b>Subtotal:</b> R$ <span id="prod_subtotal">0,00</span>
                                        </td>
                                    </tr>                           
                                </table>
                            </td>
                         </tr>
                    </table>
                </div>                
            </td>
        </tr>
        <tr>
        	<td colspan="2">
                <div id="osadicDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('osadic','osadicSign')">
                        <span id="osadicSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Adicionais
                    </a>
                    <table id="osadic" width="100%" style="display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                        <tr class="supLabel">
                            <td width="50%">
                                Gerar Relat&oacute;rio geral:
                            </td>
                            <td>
                                Gerar Pend&ecirc;ndia:
                            </td>
                        </tr>
                        <tr class="supField">
                            <td valign="top">
                                <textarea name="rel_cont" style="width: 344px; height: 73px"></textarea>
                            </td>
                            <td>
                                Assunto: <input type="text" name="pend_assunto" style="width: 298px;" maxlength="50" />
                                <textarea name="pend_desc" style="width: 344px; height: 50px; margin-top: 4px;"></textarea>
                            </td>
                        </tr>                        
                    </table>
                </div>                
            </td>
        </tr>
        <tr>
        	<td align="right" colspan="2"><b>Total:</b> R$ <span id="total" style="font-size: 18px">0,00</span></td>
        </tr>
        <tr>
            <td colspan="2">            	
            	<label><input type="checkbox" name="os_com_remocao" value="S" style="border: none" />
                <img src="img/btn/rlocal.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Requer remo&ccedil;&atilde;o de equipamento das depend&ecirc;ncias do cliente.</label>
            </td>
            
        </tr>
        <tr>            
            <td colspan="2">
            	<label><input type="checkbox" name="os_orcamento" value="S" style="border: none"/>
                <img src="img/btn/orcamento.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Solicitar autoriza&ccedil;&atilde;o.</label>
            </td>
        </tr>
        <tr>            
            <td colspan="2">
            	<label><input type="checkbox" name="os_mostrar_valor" value="S" style="border: none" />
                <img src="img/btn/cifrao.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Mostrar valores para o cliente.</label>
            </td>
        </tr>        
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('os_form','inc/save/saveos.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'lequ','aos')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="center">            	
            	<b>N. Patrim&ocirc;monio:</b> #<?php echo $equ_rs->equ_num_patrimonio?>
                <b>N. S&eacute;rie:</b> #<?php echo $equ_rs->equ_num_serie?>
                <b>Modelo:</b> <?php echo $equ_rs->equ_modelo?>
                <b>Fabricante:</b> <?php echo $equ_rs->equ_fabricante?>
                <br/>                
                * Preenchimento de campo obrigat&oacute;rio.
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>