<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM classprod	WHERE classprod_id=".$_GET["classprod_id"];		
	$sqlQuery = mysql_query($sql);
	$classprod_rs = mysql_fetch_object($sqlQuery);
	
	if($_SESSION["tec_cad_prod"] == "N"){
		$disabled = "disabled=\"disabled\"";
	}	
?>
<form name="classprod_form">    		
	<input type="hidden" name="classprod_id" value="<?php echo $classprod_rs->classprod_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Classifica&ccedil;&atilde;o:</b> #<?php echo $classprod_rs->classprod_id?></td>
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
            <td>Classifica&ccedil;&atilde;o:*</td>            
        </tr>
        <tr class="supField">
            <td>
               	<input type="text" name="obrg_classprod_nome" value="<?php echo $classprod_rs->classprod_nome?>" style="width: 430px;" maxlength="80" <?php echo $disabled?>/>		
            </td>
        </tr>
        <tr class="supLabel">
            <td>Descri&ccedil;&atilde;o:</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="classprod_desc" value="<?php echo $classprod_rs->classprod_desc?>" style="width: 430px;" maxlength="80" <?php echo $disabled?>/></td>            
        </tr>        
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('classprod_form','inc/save/saveclassprod.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lclassprod','altclassprod')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px; <?php echo $display?>"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$classprod_rs->classprod_dt_cad)?></font>&nbsp;
                          <?php echo date("d-m-y",$classprod_rs->classprod_dt_cad)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                          		<b style="font-size: 7pt">por:</b>
                          <?php 
								$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$classprod_rs->classprod_cad_usu_id;
								$sqlQuery3 = mysql_query($sql) or die(mysql_error());
								$teccad_rs = mysql_fetch_object($sqlQuery3);
								
								echo $teccad_rs->usu_nome;
							}
						  ?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($classprod_rs->classprod_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$classprod_rs->classprod_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$classprod_rs->classprod_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$classprod_rs->classprod_dt_alter)?>
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