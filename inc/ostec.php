<?php
	session_start();	
	require("../sys/_dbconn.php");
	require("../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM osetec
			WHERE ot_os_id=".$_GET["os_id"]." ORDER BY ot_dt_inicio DESC";		
	$ot_sqlQuery = mysql_query($sql);		
	$ot_num = mysql_num_rows($ot_sqlQuery);
	
	$sql = "SELECT os_data_conclusao,os_equ_id,os_cli_id, os_sta_id FROM os
			WHERE os_id=".$_GET["os_id"];
	$os_sqlQuery = mysql_query($sql) or die(mysql_error());
	
	$os_dt = mysql_fetch_object($os_sqlQuery);
	
	$sql = "SELECT * FROM equipamento WHERE equ_id='".$os_dt->os_equ_id."'";
	$equ_sqlQuery = mysql_query($sql);
	$rs_equ = mysql_fetch_object($equ_sqlQuery);
	
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id='".$os_dt->os_cli_id."'";
	$cli_sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($cli_sqlQuery);
	
	if($_SESSION["tec_atrib_os"] == "N" || $os_dt->os_data_conclusao != "" || $os_dt->os_sta_id == 1 || $os_dt->os_sta_id == 8){
		$display = "display: none";
	}
?>

<table id="cliview" class="alignleft" align="center" style="width: 610px">
    <tr>
        <td colspan="4">
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px"><b>Responsabilidade T&eacute;cnica</b></td>
                    <td align="right">
                        <a href="javascript:hideRequests()" title="Fechar">
                            <img src="img/btn/close.png" width="16" height="16" border="0" />
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php 		
		if($ot_num == 0){
	?>
    <tr style="<?php echo $display?>">
   		<td colspan="4">        	
        	<form name="tec_form"  style="display:inline">
            	<input type="hidden" name="cmd" value="atrib_tec" />
                <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
                <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
                <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
            	<input type="hidden" name="atrib_tec_id" value="<?php echo $_SESSION["tec_id"]?>" />                
                <table align="center">
                	<tr>
                    	<td colspan="2"><b>Atribuir t&eacute;cnico:</b></td>                        
                    </tr>
                    <tr>
                    	<td>
                        	<select name="obrg_tec_id" style="width: 253px;<?php echo $display?>">
                                <option value=""></option>
                                <?php 
                                    $sql = "SELECT usu_id,usu_nome FROM usuario
                                            WHERE usu_tipo='T'";
                                    $sqlQuery = mysql_query($sql);
                                    
                                    while($tec_rs = mysql_fetch_object($sqlQuery)){
                                ?>
                                        <option value="<?php echo $tec_rs->usu_id?>"><?php echo $tec_rs->usu_nome?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                        	<span id="os_loader" style="display:none">
                            	<img src="img/loader.gif" width="16" height="16" style="margin-bottom: -4px" /> Aguarde...
                            </span>
                            <input type="button" name="s" value="Atribuir" 
                                   onclick="ajaxSender('tec_form','inc/save/saveostec.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','atribtec')"
                                   style="width: 50px; background-color: <?php echo $colors[2]?>; 
                                          margin-top: 2px; margin-bottom: 4px"/>
                        </td>
                    </tr>                    
                </table>
            </form>
        </td> 
   	</tr>
    <?php }else{?>
    <tr style="<?php echo $display?>">
   		<td colspan="4">        	
        	<form name="tec_form">
            	<input type="hidden" name="cmd" value="atrib_ntec" />
                <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />
                <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
                <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
                <input type="hidden" name="tec_id" value="<?php echo $_GET["tec_id"]?>" />                
            	<input type="hidden" name="atrib_tec_id" value="<?php echo $_SESSION["tec_id"]?>" />                
                <table align="center">
                	<tr>
                    	<td colspan="2"><b>Atribuir novo t&eacute;cnico:</b></td>                        
                    </tr>
                    <tr>
                    	<td>
                        	<select name="obrg_tec_id" <?php echo $disabled?> style="width: 253px;">
                                <option value=""></option>
                                <?php 
                                    $sql = "SELECT usu_id,usu_nome FROM usuario
                                            WHERE usu_tipo='T'";
                                    $sqlQuery = mysql_query($sql);
                                    
                                    while($tec_list_rs = mysql_fetch_object($sqlQuery)){
										if($tec_list_rs->usu_id == $_GET["tec_id"]){
											continue;
										}
                                ?>
                                        <option value="<?php echo $tec_list_rs->usu_id?>"><?php echo $tec_list_rs->usu_nome?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>                        
                    </tr>
                    <tr>
                    	<td colspan="2"><b>Justificativa:</b></td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                        	<textarea name="obrg_atrib_just" style="width: 250px; height: 50px" <?php echo $disabled?>></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="right">
                        	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" style="margin-bottom: -4px" /> Aguarde...</span>
                            <input type="button" name="s" value="Atribuir" 
                                   onclick="ajaxSender('tec_form','inc/save/saveostec.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>, 'los','atribntec')"
                                   style="width: 50px; background-color: <?php echo $colors[2]?>; 
                                          margin-top: 2px; margin-bottom: 4px; <?php echo $display?>"/>
                        </td>
                    </tr>
                </table>
            </form>
        </td> 
   	</tr>
    <tr style="background-color: <?php echo $colors[2]?>; <?php echo $display?>">        	
        <td colspan="4">
        	<b>Hist&oacute;rico técnico:</b>
            <span id="sign" style="display:none"></span>
        </td>
    </tr>
    <?php		
		$notAtual[0] = "";
		$notAtual[1] = '<img src="img/btn/responsavel.png" title="Respons&aacute;vel t&eacute;cnico atual" border="0" />';
		while($ot_rs = mysql_fetch_object($ot_sqlQuery)){
			$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$ot_rs->ot_tec_id;
			$tec_sqlQuery = mysql_query($sql);
			$tec_rs = mysql_fetch_object($tec_sqlQuery);
	?>    
    <tr <?php echo $notAtual[0]?>>
    	<td width="16px">
        	<a href="javascript:showHide('<?php echo $ot_rs->ot_id?>Content','sign')">
				<?php echo $notAtual[1]?>
            </a>
        </td>
    	<td class="dashedBottom0">
        	<a href="javascript:showHide('<?php echo $ot_rs->ot_id?>Content','sign')">
        		<b>Nome:</b> <?php echo $tec_rs->usu_nome?>
            </a>
        </td>
        <td class="dashedBottom0">
        	<a href="javascript:showHide('<?php echo $ot_rs->ot_id?>Content','sign')">
        	<b>Data inicial:</b>
            <font style="font-size: 6pt"><?php echo date("H:i:s",$ot_rs->ot_dt_inicio)?></font>
                  &nbsp;<?php echo date("d-m-y",$ot_rs->ot_dt_inicio)?>
            </a>
        </td>
        <td class="dashedBottom0">
        	<a href="javascript:showHide('<?php echo $ot_rs->ot_id?>Content','sign')">
        	<b>Data final:</b>
            <?php if($ot_rs->ot_dt_fim <> ""){?>
                <font style="font-size: 6pt"><?php echo date("H:i:s",$ot_rs->ot_dt_fim)?></font>
                      &nbsp;<?php echo date("d-m-y",$ot_rs->ot_dt_fim)?>
            <?php }elseif($os_dt->os_data_conclusao != ""){?>
				<font style="font-size: 6pt"><?php echo date("H:i:s",$os_dt->os_data_conclusao)?></font>
                  &nbsp;<?php echo date("d-m-y",$os_dt->os_data_conclusao)?>
			<?php }?>      
            </a>
        </td>        
    </tr>
    <?php 
				$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$ot_rs->ot_atrib_tec_id;
				$tec_sqlQuery = mysql_query($sql);
				$tec_atrib_rs = mysql_fetch_object($tec_sqlQuery);
	?>
    <tr <?php echo $notAtual[0]?>>
    	<td></td>
    	<td colspan="3">        
        	<table id="<?php echo $ot_rs->ot_id?>Content" cellspacing="0" style="display:none">
            	<tr>
                	<td class="dashedBottom2" width="610px"><b>Atribu&iacute;do por:</b> <?php echo $tec_atrib_rs->usu_nome?></td>
                </tr>
                <tr>
                	<td class="dashedBottom2"><b>Justificativa:</b></td>
                </tr>
                <tr>
                	<td class="dashedBottom2"><?php echo $ot_rs->ot_atrib_just?></td>
                </tr>	
            </table>
        </td>        
    </tr>    
    <?php 
			$notAtual[0] = 'class="disabledLine"';
			$notAtual[1] = '<img src="img/btn/aresponsavel_d.png" title="Antigo respons&aacute;vel t&eacute;cnico" border="0" />';
		}
	 }
	 ?>    
    <tr style="background-color: <?php echo $colors[2]?>;">        	
        <td colspan="4">
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