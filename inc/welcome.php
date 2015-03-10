<?php
	@header("Content-Type: text/html; charset=ISO-8859-1",true);
	session_start();	
	require("../css/_colors.php");
	require("../sys/_dbconn.php");
	require("../sys/_config.php");		
	$_SESSION["flag"] = "1";
?>
<table id="cliview" class="alignleft" align="center" width="550px">
    <tr>
        <td colspan="2">
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px">
                        <b>Bem-vindo, <?php echo $_SESSION["tec_nome"]?>!</b> 
                    </td>
                    <td align="right">
                        <a href="javascript:hideRequests()" title="Fechar">
                            <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php if($_SESSION["tec_tipo"] == "T"){?>
    <tr>
    	<td width="50%">        
        <p align="justify">Hoje &eacute; seu primeiro acesso ao painel de controle do <b>Sistema de Gerenciamento de Ordens de Servi&ccedil;o on-line - JAMP</b> .</p>
        <p align="justify">Em caso de d&uacute;vidas sobre a utiliza&ccedil;&atilde;o ou sugest&otilde;es para melhoramentos do sistema entre em contato clicando em "suporte", no canto esquerdo inferior ou atrav&eacute;s do e-mail:<br> <b>suporte@jamp.onlinemanager.com.br</b> <br/>Pelos telefones:<br/> <b>(79) 8121-4741</b>, <b>(79) 8111-1692</b><br/>.</p>
        </td>
        <td align="center"><a href="http://jamp.onlinemanager.com.br" target="_blank"><img src="img/jamp-logo.jpg" border="0" /></a> </td>
    </tr>	
    <?php }else{?>
    <tr>
    	<td width="50%" valign="top">
        <p style="padding-left: 5px;"><?php echo $config["emp_welcome"]?></p>
        </td>
        <td align="center">
        	<a href="<?php echo $config["emp_site"]?>" title="<?php echo $config["emp_site"]?>" target="_blank">
                <img src="img/<?php echo $config["emp_logo"]?>" border="0" />
            </a>
        </td>
    </tr>	
    <?php }?>    
    <tr style="background-color: <?php echo $colors[2]?>;">
        <td colspan="2" align="right">&nbsp;</td>
    </tr>       
 </table>