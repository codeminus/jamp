<?php
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../sys/_dbconn.php");
	require("../css/_colors.php");
	$sql = "SELECT * FROM os_pendencia
			JOIN os ON os_id=pend_os_id
			JOIN usuario ON usu_id=pend_tec_id
			WHERE pend_os_id=".$_GET["os_id"]." ORDER BY pend_data_criacao DESC";
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	
	$sql = "SELECT os_data_conclusao,os_equ_id,os_cli_id FROM os
			WHERE os_id=".$_GET["os_id"];
	$os_sqlQuery = mysql_query($sql) or die(mysql_error());
	
	$os_dt = mysql_fetch_object($os_sqlQuery);
	
	$sql = "SELECT * FROM equipamento WHERE equ_id='".$os_dt->os_equ_id."'";
	$equ_sqlQuery = mysql_query($sql);
	$rs_equ = mysql_fetch_object($equ_sqlQuery);
	
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id='".$os_dt->os_cli_id."'";
	$cli_sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($cli_sqlQuery);
?>
<table id="cliview" align="center" style="width: 610px">
	<tr>
        <td>
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px"><b>OSID:</b> #<?php echo $_GET["os_id"]?><b> - Pend&ecirc;ncias</b></td>
                    <td align="right">
                        <a href="javascript:hideRequests()" title="Fechar">
                            <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php if($_GET["tec_id"] == $_SESSION["tec_id"] && $os_dt->os_data_conclusao == ""){?>
	<tr>
        <td>
        	<a href="javascript:showHide('gform','gsinal')" style="padding-left: 2px">
        	<b>
            	<span id="gsinal" style="font-size:10pt">
            		<img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                </span>
                Gerar Pend&ecirc;ncia
            </b>
            </a>
        </td>
    </tr>    
	<tr>
        <td align="center" style="border-bottom: solid 2px <?php echo $colors[2]?>">
        	<span id="gform" style="display:none">
        	<form name="pend_form" style="display:inline">
            	<input type="hidden" name="cmd" value="gerar" />
            	<input type="hidden" name="tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
                <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
                <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
                <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
                <span style="margin-bottom: 5px; display:block">
                	<b>Assunto:</b>
                    <input type="text" name="obrg_pend_assunto" style="width:396px;" maxlength="30" />
                </span>
            	<textarea name="obrg_pend_desc" style="width: 450px; height: 50px;"></textarea><br/>
                <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" style="margin-bottom: -4px" /> Aguarde...</span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="ajaxSender('pend_form','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','apend')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/>
            </form>
            </span>
        </td>
    </tr>    
<?php }?>
<?php 
	if(mysql_num_rows($sqlQuery) > 0){    
		while($rs_pend = mysql_fetch_object($sqlQuery)){
		
			switch($rs_pend->pend_status){
				case "A": 
					$status = "Aguardando resposta do cliente.";
					$sta_class ="";					
					break;
				case "I": 
					$status = "Resposta aceita.";
					$sta_class ="ra";
					break;
				case "ACT": 
					$status = "Aguardando confirmação técnica.";
					$sta_class ="act";
					break;
				case "RNS": 
					$status = "Resposta não satisfatória.";
					$sta_class ="rns";
					break;	
							
			}
?>
            <tr class="<?php echo $sta_class?>">
                <td style="border-bottom: dashed 1px <?php echo $colors[2]?>">
                    <a href="javascript:showHide('<?php echo $rs_pend->pend_id?>Content','<?php echo $rs_pend->pend_id?>Sign')" 
                       style="padding-right: 4px; padding-left: 2px; outline: none" class="<?php echo $sta_class?>">
                        <b>
                        	<span id="<?php echo $rs_pend->pend_id?>Sign" style="font-size:10pt" title="Ampliar">
                        		<img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                            </span>
                        </b>
                        <b>Assunto:</b> <?php echo $rs_pend->pend_assunto?>          
                    <b style="padding-left: 10px">Data:</b> <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_pend->pend_data_criacao)?></font>
                    &nbsp;<?php echo date("d-m-y",$rs_pend->pend_data_criacao)?>
                    <b style="padding-left: 10px">Status:</b>
                    <?php echo $status?>                                
                    </a>
                </td>        
            </tr>
            <tr><td>            
            <table id="<?php echo $rs_pend->pend_id?>Content" style="display:none">
            
            	<?php ($rs_pend->pend_resp != "")? $disabledLine = 'class="disabledLine"': $disabledLine = "";?>
            
                <tr <?php echo $disabledLine?> style="background-color: <?php echo $colors[3]?>">
                    <td width="610px">
                    	<b>Pergunta:</b>
                    	<font style="font-size: 6pt"><?php echo date("H:i:s",$rs_pend->pend_data_criacao)?></font>
		                    &nbsp;<?php echo date("d-m-y",$rs_pend->pend_data_criacao)?>
                    </td>
                </tr>
                <tr <?php echo $disabledLine?>>
                    <td><?php echo $rs_pend->pend_desc?></td>
                </tr>
                <tr <?php echo $disabledLine?> style="background-color: <?php echo $colors[3]?>;">
                    <td>
                        <b>Resposta:</b>                       	
						<?php if($rs_pend->pend_data_resp != ""){?>
                        <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_pend->pend_data_resp)?></font>
                        &nbsp;<?php echo date("d-m-y",$rs_pend->pend_data_resp)?>
                        	<?php 
								if($rs_pend->pend_data_resp == $rs_pend->pend_data_aceita){
									echo "&nbsp;(Respondida pelo respons&aacute;vel t&eacute;cnico nesta data.)";
								}
							?>
                        <?php }?>
                        
                        
                    </td>
                </tr>
                <tr <?php echo $disabledLine?>>
                    <td>
                    	<?php if($_SESSION["tec_tipo"] == "C" && $rs_pend->pend_status == "A" && $os_dt->os_data_conclusao == ""){?>
                        	<form name="resp_pend_form<?php echo $rs_pend->pend_id?>" style="display:inline">
                            	<input type="hidden" name="cmd" value="resp_pend" />
                                <input type="hidden" name="pend_id" value="<?php echo $rs_pend->pend_id?>" />
                                <input type="hidden" name="os_id" value="<?php echo $rs_pend->pend_os_id?>" />
                                <input type="hidden" name="tec_id" value="<?php echo $rs_pend->pend_tec_id?>" />
                                <input type="hidden" name="cli_id" value="<?php echo $_SESSION["tec_id"]?>" />
                                <input type="hidden" name="pend_desc" value="<?php echo $rs_pend->pend_desc?>" />
                                <input type="text" name="obrg_pend_resp" style="width: 508px" />
                                <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" style="margin-bottom: -4px" /> Aguarde...</span>
                                <input type="button" name="s" <?php echo $disabled?>
                                   onclick="ajaxSender('resp_pend_form<?php echo $rs_pend->pend_id?>','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','resppend')"
                                   value="Responder"style="background-color: <?php echo $colors[3]?>"  />
                            </form>
                        <?php }else{
							  	echo $rs_pend->pend_resp;
							  }
						?>
                        <?php if($rs_pend->pend_data_resp == "" && $_GET["tec_id"] == $_SESSION["tec_id"]){?>
							<form name="resp_pend_form<?php echo $rs_pend->pend_id?>" style="display:inline">
                                <input type="hidden" name="cmd" value="tec_resp_pend" />
                                <input type="hidden" name="pend_id" value="<?php echo $rs_pend->pend_id?>" />
                                <input type="hidden" name="os_id" value="<?php echo $rs_pend->pend_os_id?>" />
                                <input type="hidden" name="cli_id" value="<?php echo $rs_pend->os_cli_id?>" />
                                <input type="hidden" name="tec_id" value="<?php echo $rs_pend->pend_tec_id?>" />
                                <input type="hidden" name="resp_tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
                                <input type="hidden" name="pend_desc" value="<?php echo $rs_pend->pend_desc?>" />
                                <input type="text" name="obrg_pend_resp" style="width: 508px" />
                                <input type="button" name="s" <?php echo $disabled?>
                                   onclick="ajaxSender('resp_pend_form<?php echo $rs_pend->pend_id?>','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','tecresppend')"
                                   value="Responder"style="background-color: <?php echo $colors[3]?>"  />
                            </form>
                            <br/>Obs: O t&eacute;cnico poder&aacute; responder a pend&ecirc;ncia somente caso o cliente n&atilde;o possa faz&ecirc;-la ou caso a mesma seja dispens&aacute;vel.
						<?php }?>
                    </td>        
                </tr>
                <?php
                	$sql = "SELECT * FROM os_pend_compl 
							WHERE os_pend_compl_pend_id=".$rs_pend->pend_id." 
							ORDER BY os_pend_compl_desc_dt";
					$sqlQuery2 = mysql_query($sql);
					$num_rows = mysql_num_rows($sqlQuery2);
					$cont = 1;
					while($rs_pend_compl = mysql_fetch_object($sqlQuery2)){
				?>
                
                	<?php ($rs_pend_compl->os_pend_compl_resp != "" && $cont< $num_rows)? $disabledLine = 'class="disabledLine"': $disabledLine = "";?>
                    
					<tr <?php echo $disabledLine?> style="background-color: <?php echo $colors[3]?>">
                        <td>
                            <b>Complemento da pergunta:</b>
                            <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_pend_compl->os_pend_compl_desc_dt)?></font>
                                &nbsp;<?php echo date("d-m-y",$rs_pend_compl->os_pend_compl_desc_dt)?>
                        </td>
                    </tr>
                    <tr <?php echo $disabledLine?>>
                        <td><?php echo $rs_pend_compl->os_pend_compl_desc?></td>
                    </tr>
                    <tr <?php echo $disabledLine?> style="background-color: <?php echo $colors[3]?>;">
                        <td>
                            <b>Resposta:</b>                            
							<?php if($rs_pend_compl->os_pend_compl_resp_dt != 0){?>
                            <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_pend_compl->os_pend_compl_resp_dt)?></font>
                            &nbsp;<?php echo date("d-m-y",$rs_pend_compl->os_pend_compl_resp_dt)?>
                            <?php }?>                            
                        </td>
                    </tr>
                    <tr <?php echo $disabledLine?>>
                        <td>
                        	<?php if($_SESSION["tec_tipo"] == "C" && $rs_pend->pend_status == "RNS"
									 && $rs_pend_compl->os_pend_compl_resp == "" && $os_dt->os_data_conclusao == ""){?>
                                <form name="resp_pend_compl_form<?php echo $rs_pend->pend_id?>" style="display:inline">
                                    <input type="hidden" name="cmd" value="resp_pend_compl" />
                                    <input type="hidden" name="pend_id" value="<?php echo $rs_pend->pend_id?>" />
                                    <input type="hidden" name="pend_compl_id" value="<?php echo $rs_pend_compl->os_pend_compl_id?>" />
                                    <input type="hidden" name="os_id" value="<?php echo $rs_pend->pend_os_id?>" />
                                    <input type="hidden" name="tec_id" value="<?php echo $rs_pend->pend_tec_id?>" />
                                    <input type="hidden" name="cli_id" value="<?php echo $_SESSION["tec_id"]?>" />
                                    <input type="hidden" name="pend_compl_desc" value="<?php echo $rs_pend_compl->os_pend_compl_desc?>" />
                                    <input type="text" name="obrg_pend_compl_resp" style="width: 508px" />
                                    <input type="button" name="s" <?php echo $disabled?>
                                       onclick="ajaxSender('resp_pend_compl_form<?php echo $rs_pend->pend_id?>','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','resppend')"
                                       value="Responder"style="background-color: <?php echo $colors[3]?>"  />
                                </form>
                            <?php }else{
                                    echo $rs_pend_compl->os_pend_compl_resp;
                                  }
                            ?>
                        </td>        
                    </tr>
				<?php	
						$cont++;
					}					
				?>
                <tr>
                    <td align="right">
                    	
                        <?php 
							if($_SESSION["tec_tipo"] == "T" && $_SESSION["tec_id"] != $rs_pend->pend_tec_id){
								$disabled = "disabled=\"disabled\"";
							}
							
							if($_SESSION["tec_tipo"] == "T" && $rs_pend->pend_status == "ACT" && $os_dt->os_data_conclusao == ""){ 
						?>
                        	<form name="pend_formr<?php echo $rs_pend->pend_id?>" style="display:inline">		
                            <input type="hidden" name="cmd" value="rejeitar" />
                            <input type="hidden" name="pend_id" value="<?php echo $rs_pend->pend_id?>" />
                            <input type="hidden" name="tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
                            <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
                            <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
                            <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
                            <b>Compl. da pergunta:</b>
                            <input type="text" name="obrg_os_pend_compl_desc" style="width: 270px" <?php echo $disabled?> />
                            <input type="button" name="s" <?php echo $disabled?>
                                   onclick="ajaxSender('pend_formr<?php echo $rs_pend->pend_id?>','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','rpend')"
                                   value="Resposta n&atilde;o satisfat&oacute;ria"style="background-color: <?php echo $colors[3]?>; width: 130px"  />
                            </form>                            
                            <form name="pend_forma<?php echo $rs_pend->pend_id?>" style="display:inline">
                            <input type="hidden" name="cmd" value="aceitar" />		
                            <input type="hidden" name="pend_id" value="<?php echo $rs_pend->pend_id?>" />
                            <input type="hidden" name="tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
                            <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
                            <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
                            <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
                            <input type="button" name="s" <?php echo $disabled?>
                                   onclick="ajaxSender('pend_forma<?php echo $rs_pend->pend_id?>','inc/save/savepend.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','acpend')"
                                   value="Aceitar"style="background-color: <?php echo $colors[3]?>"  />
                            </form>                            
                        <?php }?>
                    </td>
                </tr>    
                <tr>
                    <td style="background-color: <?php echo $colors[3]?>;border-bottom: solid 2px <?php echo $colors[0]?>" 
                    	align="right">
                        <b>T&eacute;cnico:</b> <?php echo $rs_pend->usu_nome?>
                    </td>
                </tr>            
            </table>            
            </td></tr>
<?php 
		}
	}else{
?>
		<tr>
			<td align="center" style="padding: 30px;">N&atilde;o existem pend&ecirc;ncias.</td>
		</tr>    
<?php }?>	
	<tr style="background-color: <?php echo $colors[2]?>;">        	
        <td colspan="2">
        	<div>
                <div align="center">                	
                	<b style="font-size: 7pt">EQID:</b> #<?php echo $rs_equ->equ_id?>
                	<b style="font-size: 7pt">Equipamento:</b> <?php echo $rs_equ->equ_nome?>
                    <b style="font-size: 7pt">Modelo:</b> <?php echo $rs_equ->equ_modelo?>
                    <b style="font-size: 7pt">N. Patrim&ocirc;monio:</b> #<?php echo $rs_equ->equ_num_patrimonio?>
                    <b style="font-size: 7pt">N. S&eacute;rie:</b> #<?php echo $rs_equ->equ_num_serie?><br/>
                    <b style="font-size: 7pt">Cliente:</b> <?php echo $rs_cli->usu_nome?>
                </div>                
                <div style="clear:both"></div>
            </div>
        </td>
    </tr>	 
</table>
<?php mysql_close()?>