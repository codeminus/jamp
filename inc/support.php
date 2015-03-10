<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	session_start();
	require("../sys/_dbconn.php");
	require("../sys/_config.php");
	require("../sys/_version.php");
	require("../css/_colors.php");
	
	if($_SESSION["tec_tipo"] == "T"){
		$cmd = "tecSup";
		$nome = $_SESSION["tec_nome"];
	}else{
		$cmd = "cliSup";
		$nome = "";
	}
	
?>
<form name="sup_form">
	<input type="hidden" name="cmd" value="<?php echo $cmd?>" />
    <input type="hidden" name="reg_nome" value="<?php echo $_SESSION["tec_nome"]?>" />
    <input type="hidden" name="usu_id" value="<?php echo $_SESSION["tec_id"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px">                        	
                        	<b>Suporte</b>
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
            <td width="225px">Seu nome:*</td>
            <td rowspan="8" valign="top">
            	<table width="100%">
            	<?php if($_SESSION["tec_tipo"] == "T"){?>                
                	<tr>
                    	<td align="center">
                        <a href="http://jamp.onlinemanager.com.br" target="_blank" title="Gerenciamento On-line">
                            <img src="img/jamp-logo.jpg" border="0" />
                        </a>
                        </td>
                    </tr>
                    <tr>
                    	<td style="font-weight:normal" align="center">
                        	suporte@jamp.onlinemanager.com.br<br/><br/>
                            versão do sistema:<br/>
                            <?php echo $version?>
                        </td>
                    </tr>                
                <?php }else{?>
                	<tr>
                    	<td>
                        <a href="<?php echo $config["emp_site"]?>" target="_blank" title="<?php echo $config["emp_site"]?>">
                            <img src="img/<?php echo $config["emp_logo"]?>" border="0" />
                        </a>
                        </td>
                    </tr>
                    <tr>
                    	<td style="font-weight:normal" align="center"><?php echo $config["emp_email"]?></td>
                    </tr>
                <?php }?>
                </table>
            </td>
        </tr>
        <tr class="supField">
            <td><input type="text" name="obrg_nome" value="<?php echo $nome?>" style="width: 200px;" /></td>            
        </tr>
        <tr class="supLabel">
            <td>E-mail para contato:*</td>            
        </tr>
        <tr class="supField">
            <td><input type="text" name="obrg_email" value="<?php echo $_SESSION["tec_email"]?>" style="width: 200px;" /></td>
        </tr>
        <tr class="supLabel">
            <td>Assunto:*</td>            
        </tr>
        <tr class="supField">
            <td ><input type="text" name="obrg_assunto" style="width: 200px;" /></td>
        </tr>
        <tr class="supLabel">
            <td>Mensagem:*</td>	            
        </tr>
        <tr class="supField">
            <td><textarea name="obrg_msg" rows="3" style="width: 200px; "></textarea></td>
        </tr>
    	<tr>
            <td align="center" colspan="2">
            	<span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" /></span>
                <input type="button" name="s" value="Enviar mensagem" 
                	   onclick="ajaxSender('sup_form','inc/support_send.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'<?php echo $_GET["inc"]?>','asup')" class="saveInfoBtn" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="center">
            	Utilize este formul&aacute;rio em caso de d&uacute;vida sobre a utiliza&ccedil;&atilde;o do sistema ou para solicita&ccedil;&atilde;o de informa&ccedil;&otilde;es em geral.<br>                
            </td>
        </tr>       
     </table>   
</form>
<?php mysql_close()?>