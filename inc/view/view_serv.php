<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM servico WHERE serv_id=".$_GET["serv_id"];		
	$sqlQuery = mysql_query($sql);	
	$serv_rs = mysql_fetch_object($sqlQuery);
	
	if($_SESSION["tec_cad_prod"] != "S"){
		$disabled = "disabled=\"disabled\"";
	}	
?>
<form name="serv_form">
	<input type="hidden" name="cmd" value="alterar" />
    <input type="hidden" name="serv_id" value="<?php echo $serv_rs->serv_id?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Servi&ccedil;o: #<?php echo $serv_rs->serv_id?></b>
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
            <td colspan="2">Servi&ccedil;o:*</td>
        </tr>
        <tr class="supField">
            <td colspan="2">
                <input type="text" name="obrg_serv_nome" value="<?php echo $serv_rs->serv_nome?>" style="width: 431px;" <?php echo $disabled?> />
            </td>            
        </tr>
        <tr class="supLabel">
            <td valign="top" colspan="2">Descri&ccedil;&atilde;o:</td>	            
        </tr>
        <tr class="supField">
            <td colspan="2">
                <textarea name="serv_desc" style="width: 431px; height: 50px" <?php echo $disabled?>><?php echo str_replace("<br/>","\r\n",$serv_rs->serv_desc)?></textarea>
            </td>
        </tr>
        <td><b>Unidade de cobran&ccedil;a:</b> (ex.: hora) <input type="text" name="serv_unid_medida" value="<?php echo $serv_rs->serv_unid_medida?>" maxlength="10" style="width: 50px; text-align:right" /></td>
        <td align="right" style="padding-right: 9px"><b>Valor:</b> R$ <input type="text" name="obrg_serv_valor" value="<?php echo number_format($serv_rs->serv_valor,2,",",".")?>" onkeypress="return isNum(event,'real')" style="width: 50px; text-align:right" /></td>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" <?php echo $disabled?>
                	   onclick="ajaxSender('serv_form','inc/save/saveserv.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'lserv','altserv')"
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px"/></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    <b style="font-size: 7pt">Data do cadastro:</b>                
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$serv_rs->serv_dt_cad)?></font>&nbsp;
                          <?php echo date("d-m-y",$serv_rs->serv_dt_cad)?>
                          <?php if($_SESSION["tec_tipo"] != "C"){?>
                          		<b style="font-size: 7pt">por:</b>
                          <?php 
								$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$serv_rs->serv_cad_usu_id;
								$sqlQuery3 = mysql_query($sql) or die(mysql_error());
								$teccad_rs = mysql_fetch_object($sqlQuery3);
								
								echo $teccad_rs->usu_nome;
							}
						  ?>
                    </div>
                    <div align="center">
                    <b style="font-size: 7pt">Data das altera&ccedil;&otilde;es:</b>
                    <?php 
						if($serv_rs->serv_dt_alter != NULL){
							$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$serv_rs->serv_alter_usu_id;
							$sqlQuery2 = mysql_query($sql) or die(mysql_error());
							$tec_rs = mysql_fetch_object($sqlQuery2);
					?>				
                    <font style="font-size: 6pt"><?php echo date("H:i:s",$serv_rs->serv_dt_alter)?></font>&nbsp;
                          <?php echo date("d-m-y",$serv_rs->serv_dt_alter)?>
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
<?php mysql_close()?>