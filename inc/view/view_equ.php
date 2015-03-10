<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM equipamento
			JOIN usuario ON equ_cli_id=usu_id
			JOIN cliente ON equ_cli_id=cli_usu_id 
			WHERE equ_id=".$_GET["equ_id"];		
	$sqlQuery = mysql_query($sql);	
	$equ_rs = mysql_fetch_object($sqlQuery);
	
	if($_SESSION["tec_cad_cli"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}
	
	if($_SESSION["tec_tipo"] == "C"){
		$display = "display: none";
	}
?>
<form name="equ_form">    	
	<input type="hidden" name="cli_id" value="<?php echo $equ_rs->equ_cli_id?>" />
	<input type="hidden" name="equ_id" value="<?php echo $equ_rs->equ_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Equipamento:</b> #<?php echo $equ_rs->equ_id?></td>
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
            <td>Nome do equipamento:*</td>
        </tr>
        <tr class="supField">
            <td>
                <?php echo $equ_rs->usu_nome?>
            </td>
            <td>
            	<input type="text" name="obrg_equ_nome" value="<?php echo $equ_rs->equ_nome?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Modelo:</td>
            <td>Fabricante:</td>
        </tr>
        <tr class="supField">
            <td>
            	<input type="text" name="equ_modelo" value="<?php echo $equ_rs->equ_modelo?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
            <td>
            	<input type="text" name="equ_fabricante" value="<?php echo $equ_rs->equ_fabricante?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">N&uacute;mero de s&eacute;rie:</td>
            <td>Observa&ccedil;&otilde;es:</td>
        </tr>
        <tr class="supField">
            <td  valign="top">
                <input type="text" name="equ_num_serie" value="<?php echo $equ_rs->equ_num_serie?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
            <td rowspan="3">
                <textarea name="equ_descricao" style="width: 200px; height: 50px" <?php echo $disabled?>><?php echo str_replace("<br/>","\r\n",$equ_rs->equ_descricao)?></textarea>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px" valign="top">N&uacute;mero de patrim&ocirc;nio:</td>	            
        </tr>
        <tr class="supField">
            <td valign="top">
            	<input type="text" name="equ_num_patrimonio" value="<?php echo $equ_rs->equ_num_patrimonio?>" 
                	   style="width: 200px;" <?php echo $disabled?> />
            </td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('equ_form','inc/save/saveequ.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lequ','altequ')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px; <?php echo $display?>"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$equ_rs->equ_dt_cad)?></font>&nbsp;
                          <?php echo date("d-m-y",$equ_rs->equ_dt_cad)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                          		<b style="font-size: 7pt">por:</b>
                          <?php 
								$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$equ_rs->equ_cad_usu_id;
								$sqlQuery3 = mysql_query($sql) or die(mysql_error());
								$teccad_rs = mysql_fetch_object($sqlQuery3);
								
								echo $teccad_rs->usu_nome;
							}
						  ?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($equ_rs->equ_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$equ_rs->equ_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$equ_rs->equ_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$equ_rs->equ_dt_alter)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                              <b style="font-size: 7pt">por:</b>
                              <?php echo $tec_rs->usu_nome;?>
                          <?php }?>
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