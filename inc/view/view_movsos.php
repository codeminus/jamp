<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	
	$sql = "SELECT * FROM mov_saida_os WHERE mov_sos_id=".$_GET["mov_saida_id"];
	$sqlQuery = mysql_query($sql);
	$mov_sos_rs = mysql_fetch_object($sqlQuery);
?>
    <table id="cliview" class="alignleft" align="center" border="0">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Movimenta&ccedil;&atilde;o de sa&iacute;da por ordem de servi&ccedil;o:</b> #<?php echo $mov_sos_rs->mov_sos_id?>
                        </td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px"/>
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="supLabel">        	
            <td width="225px">Classifica&ccedil;&atilde;o:</td>
            <td>Nome do produto:</td>
        </tr>
        <tr class="supField">
            <td>
                <?php 
					$sql = "SELECT prod_nome,prod_fabricante,prod_modelo,prod_classprod_id,prod_unid_medida FROM produto WHERE prod_id=".$mov_sos_rs->mov_sos_prod_id;
					$sqlQuery = mysql_query($sql);
					$prod_rs = mysql_fetch_object($sqlQuery);
					
					$sql = "SELECT classprod_nome FROM classprod WHERE classprod_id=".$prod_rs->prod_classprod_id;
					$sqlQuery = mysql_query($sql);
					$classprod_rs = mysql_fetch_object($sqlQuery);
					echo $classprod_rs->classprod_nome;
				?>
            </td>            
            <td>
                <?php
					echo $prod_rs->prod_nome;
				?>
            </td>            
        </tr>
        <tr class="supLabel">            
            <td width="225px">Data de sa&iacute;da:</td>
            <td rowspan="3" valign="bottom">
            	<table  style="width: 212px" cellspacing="0" border="0">                            	
                    <tr>
                    	<td>Quant. de sa&iacute;da:</td>
                        <td align="right" style="font-weight: normal"><?php echo $mov_sos_rs->mov_sos_quant_saida." ".$prod_rs->prod_unid_medida?></td>
                    </tr>
                    <tr>
                        <td>Valor por unidade:</td>
                        <td align="right" style="font-weight: normal">R$ <?php echo number_format($mov_sos_rs->mov_sos_vvalor,"2",",",".")?></td>
                    </tr>
                     <tr>
                        <td>Valor total:</td>
                        <td align="right" style="font-weight: normal">
                        	R$ <?php echo number_format($mov_sos_rs->mov_sos_vvalor*$mov_sos_rs->mov_sos_quant_saida,"2",",",".")?>
                        </td>
                    </tr>
                </table>

        	</td>
            
        </tr>
        <tr class="supField">            
            <td><?php echo date("d/m/y",$mov_sos_rs->mov_sos_dt_saida)?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>    	
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="center">
            	<div>
                    <b>C&oacute;digo:</b> #<?php echo $mov_sos_rs->mov_sos_prod_id?>
                    <b>Modelo:</b> <?php echo $prod_rs->prod_modelo?>                    
                    <b>Fabricante:</b> <?php echo $prod_rs->prod_fabricante?>                    
                </div>
                <div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>
                    <?php 
						$sql = "SELECT os_cad_usu_id,os_data_abertura FROM os WHERE os_id=".$mov_sos_rs->mov_sos_os_id;
						$sqlQuery = mysql_query($sql);
						$os_rs = mysql_fetch_object($sqlQuery);
					?>
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$os_rs->os_data_abertura)?></font>&nbsp;
                          <?php echo date("d-m-y",$os_rs->os_data_abertura)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                          		<b style="font-size: 7pt">por:</b>
                          <?php 
								$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$os_rs->os_cad_usu_id;
								$sqlQuery3 = mysql_query($sql) or die(mysql_error());
								$teccad_rs = mysql_fetch_object($sqlQuery3);
								
								echo $teccad_rs->usu_nome;
							}
						  ?>
                </div>
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>