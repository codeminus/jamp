<?php
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	require("../../sys/_config.php");
	
	$sql = "SELECT * FROM os			
			JOIN usuario ON os_cli_id=usu_id 
			JOIN equipamento ON os_equ_id=equ_id
			JOIN os_status ON os_sta_id=sta_id
			WHERE os_id=".$_GET["os_id"];		
	$sqlQuery = mysql_query($sql);	
	$os_rs = mysql_fetch_object($sqlQuery);
	
		
	if($_SESSION["tec_id"] != $os_rs->os_tec_id){
		$disabled = "disabled=\"disabled\"";
		$dontDisplay = "display: none";
	}
	
	if($os_rs->os_sta_id == 1 || $os_rs->os_sta_id == 5 || ($_SESSION["tec_id"] != $os_rs->os_tec_id && $_SESSION["tec_aprov_os"] != "S")){
		$naoAlterar = "disabled";
	}
?>
<form name="os_form" style="display: inline">    	
	<input type="hidden" name="cmd" value="altos" />
    <input type="hidden" name="os_id" value="<?php echo $os_rs->os_id?>" />
	<input type="hidden" name="equ_id" value="<?php echo $os_rs->equ_id?>" />
    <input type="hidden" name="cli_id" value="<?php echo $os_rs->usu_id?>" />    
    <input type="hidden" name="cad_tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center" border="0" style="width: 750px; margin-top: 10px">
    	<tr>
            <td colspan="3" style="border:none">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px; border:none"><b>Ordem de Servi&ccedil;o:</b> #<?php echo $os_rs->os_id?></td>
                        <td align="right" style="border:none">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            	<div id="infogDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('infog','infoSign')">
                        <span id="infoSign" title="Ampliar">
                            <img src="img/btn/menos.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Informa&ccedil;&otilde;es gerais
                    </a>                    
                    <table id="infog" border="0" style="width: 100%; display:block; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                        	<td width="65%"><b>Cliente:</b> <?php echo $os_rs->usu_nome?></td>
                            <td width="160px"><b>Data de Abertura:</b></td>
                            <td width="105px" align="right">
                            	<font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_abertura)?></font>&nbsp;
								<?php echo date("d-m-y",$os_rs->os_data_abertura)?>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Equipamento:</b> <?php echo $os_rs->equ_nome?></td>
                            <td><b>Autoriza&ccedil;&atilde;o da OS:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_orcamento != "" && $os_rs->os_data_orcamento != "0"){ ?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_orcamento)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_orcamento)?>
                                <?php }else{ echo "&nbsp;";} ?>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Status:</b> <?php echo $os_rs->sta_nome?></td>
                            <td><b>In&iacute;cio da manuten&ccedil;&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_inicio_manutencao != "" && $os_rs->os_data_inicio_manutencao != "0"){ ?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_inicio_manutencao)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_inicio_manutencao)?>
                                <?php }else{ echo "&nbsp;";} ?>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<b>Localiza&ccedil;&atilde;o:</b>
                                <?php 
									if($os_rs->os_com_remocao == "S"){ 
										echo "Depend&ecirc;ncias da ".$config["emp_nome"];
									}else{
										echo "Depend&ecirc;ncias do cliente";										
									}
								?>
                            </td>
                            
                            <td width=" 150px"><b>Data de remo&ccedil;&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_com_remocao_dt != "" && $os_rs->os_com_remocao_dt != "0"){ ?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_com_remocao_dt)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_com_remocao_dt)?>
                                <?php }else{ echo "&nbsp;";} ?>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Solicitada por:</b> <?php echo $os_rs->os_autorizado_por?></td>
                            <td><b>Data de conclus&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_conclusao != ""){ 
                                  echo date("d-m-y",$os_rs->os_data_conclusao);
                                 }else{ echo "&nbsp;";} ?>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<b>T&eacute;cnico Respons&aacute;vel:</b>
                                <?php
					
									$sql = "SELECT ot_tec_id FROM osetec 
											WHERE ot_os_id=".$os_rs->os_id." 
											AND ot_dt_inicio=(SELECT MAX(ot_dt_inicio) FROM osetec WHERE ot_os_id=".$os_rs->os_id.")";
								
									$ot_sqlQuery = mysql_query($sql);
									$ot_rs = mysql_fetch_object($ot_sqlQuery);
									$tec_atual_id = $ot_rs->ot_tec_id;
									
									$sql = "SELECT usu_nome FROM usuario WHERE usu_id='".$tec_atual_id."'";
									$tec_sqlQuery = mysql_query($sql);
									$tec_rs = mysql_fetch_object($tec_sqlQuery);
									echo $tec_rs->usu_nome;
								?>
                            </td>
                            <td><b>Prazo de execu&ccedil;&atilde;o:</b></td>
                            <td align="right">
                            	<?php 
									if($os_rs->os_data_prazo != "" && $os_rs->os_data_prazo != "0"){                                
                                    	$prazo = date("d/m/Y",$os_rs->os_data_prazo);
										
										
                                	}else{ 
										$prazo = "";
									}
								?>
                                <input type="text" name="os_data_prazo" value="<?php echo $prazo?>" style="width: 58px; text-align:center" onchange="verifyDate(this,'<?php echo date("d/m/Y") ?>')" 
                	   disabled />
                       			<?php if($os_rs->os_data_conclusao == "" && $_SESSION["tec_id"] == $os_rs->os_tec_id){ ?>
                                <a href="javascript:void(0)" onClick="displayCalendar(document.forms['os_form'].os_data_prazo,'dd/mm/yyyy',this)" title="Adicionar data" tabindex="3" >
                                    <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                                </a>
                                <a href="javascript:void(0)" onclick="document.forms['os_form'].os_data_prazo.value=''"><img src="img/btn/del.png" width="16" height="16" border="0" style="margin-bottom: -3px" title="Apagar prazo" /></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>           
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="detDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('det','detSign')">
                        <span id="detSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Detalhes
                    </a>                    
                    <table id="det" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                        	<td><b>Descri&ccedil;&atilde;o:</b></td>
                        </tr>
                        <tr>
                        	<td style="padding-left: 10px; border:none">
                                <div style="height: 60px; overflow-y: auto"><?php echo $os_rs->os_pro_cli_descricao?></div>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Observa&ccedil;&otilde;es t&eacute;cnicas:</b></td>
                        </tr>
                        <tr>
                        	<td style="padding-left: 10px; border:none">
                                <textarea name="os_tec_obs" style="width: 712px; height: 60px" <?php echo $disabled?> <?php echo $naoAlterar?> ><?php echo str_replace("<br/>","\r\n",$os_rs->os_tec_obs)?></textarea>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<b>Observa&ccedil;&otilde;es do cliente:</b>
                                <?php if($os_rs->os_cli_obs_dt != ""){ ?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_cli_obs_dt)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_cli_obs_dt)?>
                                <?php }else{ echo "&nbsp;";} ?>
                            </td>
                        </tr>
                        <tr>
                        	<td style="padding-left: 10px; border:none">
                            	<div style="height: 60px; overflow-y: auto"><?php echo $os_rs->os_cli_obs?></div>
                            </td>
                        </tr>                        
                    </table>
                </div>
            </td>
        </tr>        
        <tr>
        	<td colspan="3">
                <div id="osservDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('osserv','osservSign')">
                        <span id="osservSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Servi&ccedil;os
                    </a>                    
                    <table id="osserv" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<?php if($os_rs->os_sta_id != 1 && $os_rs->os_data_conclusao == "" && 
								 ($_SESSION["tec_aprov_os"] == "S" || $_SESSION["tec_id"] == $os_rs->os_tec_id)){ ?>
                        <tr>
                        	<td>
                            	<b>Pesquisar servi&ccedil;os:</b>
                            	<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){ ?>                            	
                                <input type="text" style="width:615px" onkeyup="doSearch('servicos', 'os_form', 'searchserv.php', this, 'servResult')" />
                            	<?php }else{ ?>
                                <input type="text" style="width:617px" onkeyup="doSearch('servicos', 'os_form', 'searchserv.php', this, 'servResult')" />
                                <?php } ?>
                            </td>
                        </tr>                        
                        <tr>
                            <td>                            	
                            	<div id="servResult" style="overflow-y: auto; overflow-x:hidden; height: 0px"></div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                        	<td>
                            	<?php
									$sql = "SELECT * FROM os_serv WHERE os_serv_os_id=".$os_rs->os_id;
									$sqlQuery = mysql_query($sql);
									$quant_serv = mysql_num_rows($sqlQuery);
								?>
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantServ"><?php echo $quant_serv?></span> servi&ccedil;os adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                            <?php (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']))? $tbwidth = "97%" : $tbwidth = "708px" ;?>
                                                <table id="servicos" width="<?php echo $tbwidth?>" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Servi&ccedil;o</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                    <?php 
														$serv_subtotal = 0;
														while($serv_rs = mysql_fetch_object($sqlQuery)){
															$serv_subtotal += $serv_rs->os_serv_valor*$serv_rs->os_serv_quant;
															
															$sql = "SELECT * FROM servico WHERE serv_id=".$serv_rs->os_serv_serv_id;
															$serv_sqlQuery = mysql_query($sql);
															$this_serv_rs = mysql_fetch_object($serv_sqlQuery);
													?>
                                                    	<tr id="serv_<?php echo $this_serv_rs->serv_id?>">
                                                        	<td>&nbsp;</td>
                                                            <td align="center" rowspan="2">
                                                            	<?php if($os_rs->os_sta_id != 1 && $os_rs->os_sta_id != 5 && 
																		 ($_SESSION["tec_id"] == $os_rs->os_tec_id || $_SESSION["tec_aprov_os"] == "S")){ ?>
                                                                <a href="javascript:void(0)" 
                                                                   onclick="removeItem('serv_','<?php echo $this_serv_rs->serv_id?>','quantServ', true, <?php echo $os_rs->os_id?>)" 
                                                                   title="Remover" style="margin-top: 0px">
                                                                   <img src="img/btn/del.png" style="margin-bottom: -5px" border="0" />
                                                                </a>
                                                                <?php }else{ ?>
                                                                	<img src="img/btn/del_d.png" style="margin-bottom: -5px" border="0" />
                                                                <?php } ?>
                                                            </td>
                                                            <td><font style="font-size: 12px"><?php echo $this_serv_rs->serv_nome?></font></td>
                                                            <td rowspan="2" align="left" style="padding-top: 4px">
																<input type="text" name="serv_quant_<?php echo $this_serv_rs->serv_id?>" 
                                                                	   value="<?php echo $serv_rs->os_serv_quant?>" 
                                                                	   onkeypress="return isNum(event,\'int\')"
                                                                	   onkeyup="checkQuant(this,99999999999,'serv_', true)" 
                                                                       onblur="checkQuantAgain(this,99999999999,'serv_')"  
                                                                       style="width: 40px; text-align:right" <?php echo $naoAlterar?>/>
																<?php echo $this_serv_rs->serv_unid_medida?>
                                                            </td>
                                                            <td rowspan="2" align="left" style="padding-top: 4px">
																<input type="hidden" name="serv_valor_<?php echo $this_serv_rs->serv_id?>" 
                                                                	   value="<?php echo $serv_rs->os_serv_valor?>"/>
                                                                R$ <?php echo number_format($serv_rs->os_serv_valor,2,",",".")?>
                                                                
                                                                <input type="hidden" name="serv_id_<?php echo $this_serv_rs->serv_id?>" 
                                                                	   value="<?php echo $this_serv_rs->serv_id?>"/>
                                                            </td>															
                                                        </tr>                                                        
                                                        <tr id="serv_desc_<?php echo $this_serv_rs->serv_id?>">
                                                        	<td colspan="2">&nbsp;</td>
                                                        	<td style="padding-left: 2px">
                                                            	<font style="font-size: 9px">
                                                                	<b>C&oacute;digo:</b> <?php echo $this_serv_rs->serv_id?>
																	<b>Descri&ccedil;&atilde;o:</b> <?php echo substr($this_serv_rs->serv_desc,0,100)?>
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        	<td id="serv_div_<?php echo $this_serv_rs->serv_id?>" colspan="6">
                                                            	<hr style="bottom-border: dashed 1px; color:<?php echo $colors[2]?>" />
                                                            </td>
                                                        </tr>
                                                    <?php		
														}
													?>
                                                </table>                                        		
                                        	</div>
                                        </td>
                                    </tr>
                                	<tr>
                                        <td align="right">
                                        	<b>Subtotal:</b> R$ <span id="serv_subtotal"><?php echo number_format($serv_subtotal,2,",",".")?></span>
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
        	<td colspan="3">
                <div id="osprodDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('osprod','osprodSign')">
                        <span id="osprodSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Produtos
                    </a>                                        
                    <table id="osprod" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<?php if($os_rs->os_sta_id != 1 && $os_rs->os_data_conclusao == "" && 
								 ($_SESSION["tec_aprov_os"] == "S" || $_SESSION["tec_id"] == $os_rs->os_tec_id)){ ?>
                        <tr>
                        	<td>
                            	<b>Pesquisar produtos:</b>
                            	<?php if(eregi("MSIE",$_SERVER['HTTP_USER_AGENT'])){ ?>                            	
                                <input type="text" style="width:611px" onkeyup="doSearch('produtos', 'os_form', 'searchprod.php', this, 'prodResult')" />
                            	<?php }else{ ?>
                                <input type="text" style="width:613px" onkeyup="doSearch('produtos', 'os_form', 'searchprod.php', this, 'prodResult')" />
                                <?php } ?>
                            </td>
                        </tr>                        
                        <tr>
                            <td>                            	
                            	<div id="prodResult" style="overflow-y: auto; overflow-x:hidden; height: 0px"></div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                        	<td>
                            	<?php
									$sql = "SELECT * FROM mov_saida_os WHERE mov_sos_os_id=".$os_rs->os_id;
									$sqlQuery = mysql_query($sql);
									$quant_prod = mysql_num_rows($sqlQuery);
								?> 
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantProd"><?php echo $quant_prod?></span> produtos adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                        		<?php (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']))? $tbwidth = "97%" : $tbwidth = "708px" ;?>
                                                <table id="produtos" width="<?php echo $tbwidth?>" cellpadding="0" cellspacing="0" border="0">
                                                	<tr bgcolor="<?php echo $colors[2]?>" style="padding: 2px">
                                                        <th colspan="2" width="30px" >&nbsp;</th>
                                                        <th  width="500px" align="left">Produto</th>
                                                        <th align="left">Quantidade</th>
                                                        <th align="left">Valor(unid.)</th>
                                                    </tr>
                                                    <?php 
														$prod_subtotal = 0;
														while($prod_rs = mysql_fetch_object($sqlQuery)){
															$prod_subtotal += $prod_rs->mov_sos_vvalor*$prod_rs->mov_sos_quant_saida;
															
															$sql = "SELECT * FROM produto WHERE prod_id=".$prod_rs->mov_sos_prod_id;
															$serv_sqlQuery = mysql_query($sql);
															$this_prod_rs = mysql_fetch_object($serv_sqlQuery);
															
															$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada 
																	WHERE mov_entr_prod_id=".$prod_rs->mov_sos_prod_id;
															$mov_sqlQuery = mysql_query($sql);
															$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
															$quant_entrada = 0;
															while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
																$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
															}
															
															$quant_saida = 0;
																		
															$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os 
																	WHERE mov_sos_prod_id=".$prod_rs->mov_sos_prod_id;
															$mov_sqlQuery = mysql_query($sql);
															$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
															
															while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
																$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
															}					
															
															$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno 
																	WHERE mov_susoint_prod_id=".$prod_rs->mov_sos_prod_id;
															$mov_sqlQuery = mysql_query($sql);
															$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
															while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
																$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
															}
															
															$quant_disponivel = $quant_entrada - $quant_saida + $prod_rs->mov_sos_quant_saida;
															
													?>
                                                    	<tr id="prod_<?php echo $this_prod_rs->prod_id?>">
                                                        	<td>&nbsp;</td>
                                                            <td align="center" rowspan="2">
                                                            	<?php if($os_rs->os_sta_id != 1 && $os_rs->os_sta_id != 5 && 
																		 ($_SESSION["tec_id"] == $os_rs->os_tec_id || $_SESSION["tec_aprov_os"] == "S")){ ?>
                                                                <a href="javascript:void(0)" 
                                                                   onclick="removeItem('prod_','<?php echo $this_prod_rs->prod_id?>','quantProd', true, <?php echo $os_rs->os_id?>)" 
                                                                   title="Remover" style="margin-top: 0px">
                                                                   <img src="img/btn/del.png" style="margin-bottom: -5px" border="0" />
                                                                </a>
                                                                <?php }else{ ?>
                                                                	<img src="img/btn/del_d.png" style="margin-bottom: -5px" border="0" />
                                                                <?php } ?>
                                                            </td>
                                                            <td><font style="font-size: 12px"><?php echo $this_prod_rs->prod_nome?></font></td>
                                                            <td rowspan="2" align="left" style="padding-top: 4px">
																<input type="text" name="prod_quant_<?php echo $this_prod_rs->prod_id?>" 
                                                                	   value="<?php echo $prod_rs->mov_sos_quant_saida?>" 
                                                                	   onkeypress="return isNum(event,\'int\')"
                                                                	   onkeyup="checkQuant(this,<?php echo $quant_disponivel?>,'prod_', true)" 
                                                                       onblur="checkQuantAgain(this,<?php echo $quant_disponivel?>,'prod_')"  
                                                                       style="width: 40px; text-align:right" <?php echo $naoAlterar?>/>
																<?php echo $this_prod_rs->prod_unid_medida?>
                                                            </td>
                                                            <td rowspan="2" align="left" style="padding-top: 4px">
																<input type="hidden" name="prod_valor_<?php echo $this_prod_rs->prod_id?>" 
                                                                	   value="<?php echo $prod_rs->mov_sos_vvalor?>"/>
                                                                R$ <?php echo number_format($prod_rs->mov_sos_vvalor,2,",",".")?>
                                                                
                                                                <input type="hidden" name="prod_id_<?php echo $this_prod_rs->prod_id?>" 
                                                                	   value="<?php echo $this_prod_rs->prod_id?>"/>
                                                            </td>															
                                                        </tr>                                                        
                                                        <tr id="prod_desc_<?php echo $this_prod_rs->prod_id?>">
                                                        	<td colspan="2">&nbsp;</td>
                                                        	<td style="padding-left: 2px">
                                                            	<font style="font-size: 9px">
                                                                	<b>C&oacute;digo:</b> <?php echo $this_prod_rs->prod_id?>
                                                                    <b>Modelo:</b> <?php echo $this_prod_rs->prod_modelo?>
                                                                    <b>Fabricante:</b> <?php echo $this_prod_rs->prod_fabricante?>
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        	<td id="prod_div_<?php echo $this_prod_rs->prod_id?>" colspan="6">
                                                            	<hr style="bottom-border: dashed 1px; color:<?php echo $colors[2]?>" />
                                                            </td>
                                                        </tr>
                                                    <?php		
														}
													?>
                                                </table>
                                        	</div>
                                        </td>
                                    </tr>                                    
                                	<tr>
                                        <td align="right">
                                        	<b>Subtotal:</b> R$ <span id="prod_subtotal"><?php echo number_format($prod_subtotal,2,",",".")?></span>
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
        	<td align="right" colspan="3">
            	<b>Total:</b> R$ <span id="total" style="font-size: 18px"><?php echo number_format($serv_subtotal+$prod_subtotal,2,",",".")?></span>                
            </td>
        </tr>
        <tr>
            <td colspan="3">            	
            	<?php if($os_rs->os_com_remocao == "S"){ ?>
                	<label><input type="checkbox" name="os_sem_remocao"
                    	   style="border:none; margin-top: -6px; margin-bottom: 0px" <?php echo $disabled?>  />
                    <img src="img/btn/local.png" width="16" height="16" border="0" style="margin-bottom: -1px">
                    Devolver equipamento ao cliente.</label>
                <?php
					  }else{
                		if($os_rs->os_data_conclusao == ""){ ?>                	
                	<label><input type="checkbox" name="os_com_remocao" 
                    	   style="border:none; margin-top: -6px; margin-bottom: 0px" <?php echo $disabled?> />
                    <img src="img/btn/rlocal.png" width="16" height="16" border="0" style="margin-bottom: -1px">       
                    Remover equipamento das depend&ecirc;ncias do cliente.</label>
                <?php 	}
					  }
				?>                
            </td>
            
        </tr>
        <?php if($os_rs->os_sta_id != "1" && ($_SESSION["tec_aprov_os"] == "S" || $_SESSION["tec_id"] == $os_rs->os_tec_id)){ ?>
        <tr>            
            <td colspan="3">
            	<label><input type="checkbox" name="os_orcamento" value="S" style="border: none" />
                <img src="img/btn/orcamento.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Solicitar autoriza&ccedil;&atilde;o.</label>
            </td>
        </tr>
        <?php } ?>
        <?php 
			if($_SESSION["tec_id"] == $os_rs->os_tec_id || $_SESSION["tec_aprov_os"] == "S"){
        		$mostrarValor = "";
       		}else{        		
				$mostrarValor = "disabled";
        	}
		?>
        <tr>            
            <td colspan="3">
            	<label><?php if($os_rs->os_mostrar_valor == "S"){ ?>
            		<input type="checkbox" name="os_mostrar_valor" style="border: none" checked="checked" <?php echo $mostrarValor?> />
                <?php }else{ ?>
                <input type="checkbox" name="os_mostrar_valor" style="border: none" <?php echo $mostrarValor?> />
                <?php } ?>
                <img src="img/btn/cifrao.png" width="16" height="16" border="0" style="margin-bottom: -1px">
            	Mostrar valores para o cliente.</label>
            </td>
        </tr>
    	<tr>
            <td colspan="3" align="center">
            	<?php if(($_SESSION["tec_id"] == $os_rs->os_tec_id) || ($os_rs->os_com_remocao == "S" && $_SESSION["tec_id"] == $os_rs->os_tec_id) || 
						  $_SESSION["tec_aprov_os"] == "S"){ ?>
            		<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>                
                    <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                           onclick="ajaxSender('os_form','inc/save/saveos.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','altos')"
                           style="width: 170px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;"/>
                
                <?php } ?>                
                
           
            <?php if($os_rs->os_sta_id == "1" && $_SESSION["tec_aprov_os"] == "S"){ ?>               
                <a href="javascript:void(0)" onclick="ajaxSenderOnly('inc/save/saveos.php?cmd=aprov_os&aprov=S&os_id=<?php echo $os_rs->os_id?>&usu_id=<?php echo $_SESSION["tec_id"]?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','aprovos')"
                   title="Autorizar ordem de serviço" style="display: inline; width: 170px; background-color: <?php echo $colors[5]?>; color: <?php echo $colors[0]?>; padding: 2px; margin-left: 10px">
                	Autorizar ordem de serviço
                </a>
                <a href="javascript:void(0)" onclick="ajaxSenderOnly('inc/save/saveos.php?cmd=aprov_os&aprov=N&os_id=<?php echo $os_rs->os_id?>&usu_id=<?php echo $_SESSION["tec_id"]?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','naprovos')"
                   title="N&Atilde;O autorizar ordem de serviço"  style="display: inline; width: 170px; background-color: <?php echo $colors[6]?>; color: <?php echo $colors[1]?>;padding: 2px; margin-left: 15px;">
                	N&Atilde;O autorizar ordem de serviço
                </a>
            <?php } ?>
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="3" align="center">
            	<span style="font-size: 7pt">                
                <b style="font-size: 7pt">EQID:</b> #<?php echo $os_rs->equ_id?>
            	<b style="font-size: 7pt">N. Patrim&ocirc;monio:</b> #<?php echo $os_rs->equ_num_patrimonio?>
                <b style="font-size: 7pt">N. S&eacute;rie:</b> #<?php echo $os_rs->equ_num_serie?>
                <b style="font-size: 7pt">Modelo:</b> <?php echo $os_rs->equ_modelo?>
                <b style="font-size: 7pt">Fabricante:</b> <?php echo $os_rs->equ_fabricante?>
                </span>
                <div align="center">
                <?php
					$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$os_rs->os_cad_usu_id;
					$sqlQuery2 = mysql_query($sql) or die(mysql_error());
					$cad_rs = mysql_fetch_object($sqlQuery2);
                ?>	
                <b style="font-size: 7pt">Data de cadastro:</b>                		
                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_abertura)?></font>&nbsp;
                      <?php echo date("d-m-y",$os_rs->os_data_abertura)?>
                      <b style="font-size: 7pt">por:</b>
                      <?php echo $cad_rs->usu_nome;?>                 
                </div>
                <div align="center">
                <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                <?php 
                    if($os_rs->os_alter_usu_id != NULL){
                        $sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$os_rs->os_alter_usu_id;
                        $sqlQuery2 = mysql_query($sql) or die(mysql_error());
                        $tec_rs = mysql_fetch_object($sqlQuery2);
                ?>				
                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_alter)?></font>&nbsp;
                      <?php echo date("d-m-y",$os_rs->os_data_alter)?>
                      <b style="font-size: 7pt">por:</b>
                      <?php echo $tec_rs->usu_nome;?>
                <?php }else{ ?>
                    Nunca
                <?php } ?>      
                </div>
                <div align="center">                
                <?php 
                    if($os_rs->os_orc_usu_id != "" && $os_rs->os_orc_usu_id != "0"){
                        $sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$os_rs->os_orc_usu_id;
                        $sqlQuery2 = mysql_query($sql) or die(mysql_error());
                        $tec_rs = mysql_fetch_object($sqlQuery2);
                ?>
                <b style="font-size: 7pt">Data de autoriza&ccedil;&atilde;o:</b>
                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_orcamento)?></font>&nbsp;
                      <?php echo date("d-m-y",$os_rs->os_data_orcamento)?>
                      <b style="font-size: 7pt">por:</b>
                      <?php echo $tec_rs->usu_nome;?>                
                <?php } ?>
            </td>
        </tr>       
     </table>
</form>
<?php mysql_close()?>