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
	
	$mostrarValor = false;
	if($os_rs->os_mostrar_valor == "S"){
		$mostrarValor = true;
	}
	
	if($os_rs->os_data_conclusao != "" && $os_rs->os_com_remocao == "N"){
		$isClient = "disabled=\"disabled\"";
	}
?>
<form name="os_form" style="display: inline">    	
	<input type="hidden" name="cmd" value="altos_cli" />    
	<input type="hidden" name="os_id" value="<?php echo $os_rs->os_id?>" />
    <input type="hidden" name="cli_id" value="<?php echo $os_rs->equ_cli_id?>" />
    <input type="hidden" name="equ_id" value="<?php echo $os_rs->equ_id?>" />
    <input type="hidden" name="tec_id" value="<?php echo $os_rs->os_tec_id?>" />
    <table id="cliview" class="alignleft" align="center" border="0" style="width: 650px; margin-top: 10px">
    	<tr>
            <td colspan="3" style="border:none">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
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
                        	<td width="60%"><b>Equipamento:</b> <?php echo $os_rs->equ_nome?></td>
                            <td width=" 160px"><b>Data de Abertura:</b></td>
                            <td width="105px" align="right">
                            	<font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_abertura)?></font>&nbsp;
								<?php echo date("d-m-y",$os_rs->os_data_abertura)?>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Status:</b> <?php echo $os_rs->sta_nome?></td>
                            <td><b>Autoriza&ccedil;&atilde;o da OS:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_orcamento != "" && $os_rs->os_data_orcamento != "0"){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_orcamento)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_orcamento)?>
                                <?php }else{ echo "&nbsp;";}?>
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
                            <td width=" 150px"><b>In&iacute;cio da manuten&ccedil;&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_inicio_manutencao != "" && $os_rs->os_data_inicio_manutencao != "0"){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_inicio_manutencao)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_inicio_manutencao)?>
                                <?php }else{ echo "&nbsp;";}?>
                            </td>
                        </tr>
                        <tr>
                        	<td><b>Solicitada por:</b> <?php echo $os_rs->os_autorizado_por?></td>
                            <td><b>Prazo de execu&ccedil;&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_prazo != "" && $os_rs->os_data_prazo != "0"){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_prazo)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_prazo)?>
                                <?php }else{ echo "&nbsp;";}?>
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
									echo $tec_rs->usu_nome
								?>
                            </td>
                            <td width=" 150px"><b>Data de remo&ccedil;&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_com_remocao_dt != "" && $os_rs->os_com_remocao_dt != "0"){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_com_remocao_dt)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_com_remocao_dt)?>
                                <?php }else{ echo "&nbsp;";}?>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                            <td><b>Data de conclus&atilde;o:</b></td>
                            <td align="right">
								<?php if($os_rs->os_data_conclusao != ""){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_conclusao)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_data_conclusao)?>
                                <?php }else{ echo "&nbsp;";}?>
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
                        	<td>
                            	<b>Observa&ccedil;&otilde;es do cliente:</b>
                                <?php if($os_rs->os_cli_obs_dt != ""){?>
                                <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_cli_obs_dt)?></font>&nbsp;
                                    <?php echo date("d-m-y",$os_rs->os_cli_obs_dt)?>
                                <?php }else{ echo "&nbsp;";}?>
                            </td>
                        </tr>
                        <tr>
                        	<td style="padding-left: 10px; border:none">
                                <textarea name="obrg_os_cli_obs" style="width: 616px; height: 60px" <?php echo $isClient?>><?php echo str_replace("<br/>","\r\n",$os_rs->os_cli_obs)?></textarea>
                            </td>
                        </tr>
                        <?php if($os_rs->os_data_conclusao == "" || $os_rs->os_com_remocao == "S"){?>
                        <tr>
                        	<td align="right">
                            	
                                <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                                      
                                     <input type="button" name="s" value="Salvar observa&ccedil;&otilde;es" 
                                           onclick="ajaxSender('os_form','inc/save/saveos.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','altos')"
                                           style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                                                  margin-top: 2px; margin-bottom: 4px;"/>
                                </form>
                            </td>
                        </tr>
                        <?php }?> 
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
                    <?php
                    	$sql = "SELECT * FROM os_serv WHERE os_serv_os_id=".$os_rs->os_id;
						$sqlQuery = mysql_query($sql);
						$quant_serv = mysql_num_rows($sqlQuery);
					?>                    
                    <table id="osserv" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                        <tr>
                        	<td>
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantServ"><?php echo $quant_serv?></span> servi&ccedil;os adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                        		<?php (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']))? $tbwidth = "97%" : $tbwidth = "607px" ;?>
                                                <table id="servicos" width="<?php echo $tbwidth?>" cellpadding="0" cellspacing="0" border="0">
                                                	<tr style="padding: 2px; background-color: <?php echo $colors[2]?>">
                                                        <th align="left">Servi&ccedil;o</th>
                                                        <th align="left">Quantidade</th>                                                        
                                                       <?php if($mostrarValor){?><th align="left">Valor</th><?php }?>
                                                    </tr>
                                                    <?php 
														$serv_subtotal = 0;
														while($serv_rs = mysql_fetch_object($sqlQuery)){
															$serv_subtotal += $serv_rs->os_serv_valor*$serv_rs->os_serv_quant;
															
															$sql = "SELECT serv_nome, serv_desc, serv_unid_medida FROM servico WHERE serv_id=".$serv_rs->os_serv_serv_id;
															$serv_sqlQuery = mysql_query($sql);
															$this_serv_rs = mysql_fetch_object($serv_sqlQuery);
													?>
                                                    	<tr>
                                                        	<td style="padding: 2px"><font style="font-size: 12px"><?php echo $this_serv_rs->serv_nome?></font></td>
                                                            <td rowspan="2" style="padding-top: 4px">
																<?php echo $serv_rs->os_serv_quant." ".$this_serv_rs->serv_unid_medida?>
                                                            </td>
															<?php if($mostrarValor){?>
                                                            <td rowspan="2" style="padding-top:4px">R$ <?php echo number_format($serv_rs->os_serv_valor,2,",",".")?></td>
                                                            <?php }?>
                                                        </tr>
                                                        <tr>
                                                        	<td style="padding-left: 2px">
                                                            	<font style="font-size: 9px">
																	<?php 
                                                                        echo $this_serv_rs->serv_desc;
                                                                        
                                                                    ?>
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        	<td colspan="3">
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
                                    <?php if($mostrarValor){?>
                                	<tr>
                                        <td align="right">                                        	
                                            <b>Subtotal:</b> R$ <span id="serv_subtotal"><?php echo number_format($serv_subtotal,2,",",".")?></span>
                                        </td>
                                    </tr>
                                    <?php }?>
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
                    <?php
                    	$sql = "SELECT * FROM mov_saida_os WHERE mov_sos_os_id=".$os_rs->os_id;
						$sqlQuery = mysql_query($sql);
						$quant_prod = mysql_num_rows($sqlQuery);
					?>                     
                    <table id="osprod" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">                    	
                        <tr>
                        	<td>
                            	<table width="100%" style="background-color: <?php echo $colors[2]?>">
                                	<tr>
                                    	<td><span id="quantProd"><?php echo $quant_prod?></span> produtos adicionados</td>
                                    </tr>
                                    <tr>
                                    	<td style="background-color: <?php echo $colors[1]?>">
                                        	<div style="overflow-y: scroll; height: 150px">
                                        		<?php (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']))? $tbwidth = "97%" : $tbwidth = "607px" ;?>
                                                <table id="produtos" width="<?php echo $tbwidth?>" cellpadding="0" cellspacing="0" border="0">
                                                	<tr style="padding: 2px; background-color: <?php echo $colors[2]?>">
                                                        <th align="left">Produto</th>
                                                        <th align="left" >Quantidade</th>
                                                        <?php if($mostrarValor){?><th align="left">Valor(unid.)</th><?php }?>
                                                    </tr>
                                                    <?php 
														$prod_subtotal = 0;
														while($prod_rs = mysql_fetch_object($sqlQuery)){
															$prod_subtotal += $prod_rs->mov_sos_vvalor*$prod_rs->mov_sos_quant_saida;
															
															$sql = "SELECT * FROM produto WHERE prod_id=".$prod_rs->mov_sos_prod_id;
															$serv_sqlQuery = mysql_query($sql);
															$this_prod_rs = mysql_fetch_object($serv_sqlQuery);
													?>
                                                    	<tr>
                                                        	<td style="padding: 2px"><font style="font-size: 12px"><?php echo $this_prod_rs->prod_nome?></font></td>
                                                            <td rowspan="2" align="left" style="padding-top: 4px">
																<?php echo $prod_rs->mov_sos_quant_saida." ".$this_prod_rs->prod_unid_medida?>
                                                            </td>
                                                            <?php if($mostrarValor){?>
                                                            <td rowspan="2" style="padding-top:4px">
                                                                R$ <?php echo number_format($prod_rs->mov_sos_vvalor,2,",",".")?>
                                                            </td>
                                                            <?php }?>
                                                        </tr>
                                                        <tr>
                                                        	<td style="padding-left: 2px">
                                                            	<font style="font-size: 9px">
																	<b>C&oacute;digo:</b> <?php echo $this_prod_rs->prod_id?>
                                                                    <b>Modelo:</b> <?php echo $this_prod_rs->prod_modelo?>
                                                                    <b>Fabricante:</b> <?php echo $this_prod_rs->prod_fabricante?>
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        	<td colspan="3">
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
                                    <?php if($mostrarValor){?>
                                	<tr>
                                        <td align="right">
                                            <b>Subtotal:</b> R$ <span id="prod_subtotal"></span><?php echo number_format($prod_subtotal,2,",",".")?>
                                        </td>                                        
                                    </tr>
                                    <?php }?>
                                </table>
                            </td>
                         </tr>
                    </table>
                </div>
            </td>
        </tr>
        <?php if($mostrarValor){?>
        <tr>
        	<td align="right" colspan="3">
            	<b>Total:</b> R$ <span id="total" style="font-size: 18px">
				<?php echo number_format($serv_subtotal+$prod_subtotal,2,",",".")?></span>
            </td>
        </tr>
        <?php }?>
        <?php if($os_rs->os_sta_id == "1"){?>
        <tr>
            <td align="center" colspan="2">
            	
                <a href="javascript:void(0)" onclick="ajaxSenderOnly('inc/save/saveos.php?cmd=aprov_os&aprov=S&os_id=<?php echo $os_rs->os_id?>&usu_id=<?php echo $_SESSION["tec_id"]?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','aprovos')"
                   title="Autorizar ordem de serviço" style="display: inline; width: 170px; background-color: <?php echo $colors[5]?>; color: <?php echo $colors[0]?>; padding: 2px; margin: 10px">
                	Autorizar ordem de serviço
                </a>
                <a href="javascript:void(0)" onclick="ajaxSenderOnly('inc/save/saveos.php?cmd=aprov_os&aprov=N&os_id=<?php echo $os_rs->os_id?>&usu_id=<?php echo $_SESSION["tec_id"]?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','naprovos')"
                   title="N&Atilde;O autorizar ordem de serviço"  style="display: inline; width: 170px; background-color: <?php echo $colors[6]?>; color: <?php echo $colors[1]?>;padding: 2px; margin: 10px">
                	N&Atilde;O autorizar ordem de serviço
                </a>
            </td>
        </tr>
        <?php }?>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="3" align="center" style="border:none">
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
                <?php }else{?>
                    Nunca
                <?php }?>      
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
                <?php }?>
            </td>
        </tr>        
     </table>
<?php mysql_close()?>