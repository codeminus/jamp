<?php
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);	
	require("../../sys/_dbconn.php");
	require("../../sys/_config.php");
	require("../../css/_colors.php");
	
?>
<form name="conf_form" enctype="multipart/form-data" method="post" action="inc/saveconf.php" >
	<input type="hidden" name="inc" value="<?php echo $_GET["inc"]?>" />
    <input type="hidden" name="old_emp_logo" value="<?php echo $config["emp_logo"]?>" />
    <input type="hidden" name="srch" value="<?php echo $_SESSION["srch"]?>" />
    <input type="hidden" name="ot" value="<?php echo $_GET["ot"]?>" />
    <input type="hidden" name="pg" value="<?php echo $_GET["pg"]?>" />
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Configura&ccedil;&otilde;es</b></td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="supLabel">
            <td width="225px">Nome da empresa:*</td>
            <td>Mensagem de boas-vindas:</td>            
        </tr>
        <tr class="supField">
            <td>
				<input type="text" name="obrg_emp_nome" value="<?php echo $config["emp_nome"]?>" 
                	   style="width: 200px;"/>
            </td>
            <td rowspan="3">
                <textarea name="emp_welcome" <?php echo $disabled?> 
                          style="width: 200px; height: 54px"><?php echo str_replace("<br/>","\r\n",$config["emp_welcome"])?></textarea>
            </td>
        </tr>
        <tr class="supLabel">
            <td>E-mail da equipe de suporte:*</td>            
        </tr>
        <tr class="supField">
            <td>
                <input type="text" name="obrg_emp_email" value="<?php echo $config["emp_email"]?>" 
                	   style="width: 200px;"/>
            </td>
        </tr>
        <tr class="supLabel">
            <td>Endere&ccedil;o do sistema:*</td>
            <td>Logotipo: <font style="font-size: 7pt; color: <?php echo $colors[4]?>">(M&aacute;ximo em pixels: 550x75)</font></td>
        </tr>
        <tr class="supField">
            <td valign="top">
                <input type="text" name="obrg_emp_site" value="<?php echo $config["emp_site"]?>" 
                	   style="width: 200px;"/>
            </td>
            <td>
            	<input type="file" style="width: 200px" name="emp_logo"   />
            </td>
        </tr>        
        <tr class="supLabel">
            <td align="center" colspan="2">
            	<img src="img/<?php echo $config["emp_logo"]?>" />
           	</td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>">
        	<td colspan="2" style="padding: 2px"><b>Ordem de servi&ccedil;o</b></td>
        </tr>
        <tr>
        	<td colspan="2" class="supLabel">
            	Termo de remo&ccedil;&atilde;o de equipamento:
            </td>
        </tr>
        <tr>
        	<td align="center" colspan="2">
            	<textarea name="termo_remocao" <?php echo $disabled?> 
                          style="width: 440px; height: 54px"><?php echo str_replace("<br/>","\r\n",$config["termo_remocao"])?></textarea>
            </td>
        </tr>
        <tr>
        	<td colspan="2" class="supLabel">
            	Termo de devolu&ccedil;&atilde;o de equipamento:
            </td>
        </tr>
        <tr>
        	<td align="center" colspan="2">
            	<textarea name="termo_devolucao" <?php echo $disabled?> 
                          style="width: 440px; height: 54px"><?php echo str_replace("<br/>","\r\n",$config["termo_devolucao"])?></textarea>
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            	Use o c&oacute;digo <b>%cliente%</b> para adicionar o nome do cliente. 
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">            	
                <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                	   onclick="sendForm('conf_form')" 
                       style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                              margin-top: 2px; margin-bottom: 4px" <?php echo $disabled?> /></td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">* Preenchimento de campo obrigat&oacute;rio.</td>
        </tr>        
     </table>   
</form>
<?php mysql_close();?>