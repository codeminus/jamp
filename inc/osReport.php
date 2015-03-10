<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
    session_start();
	require("../sys/_dbconn.php");
	require("../css/_colors.php");
	$sql = "SELECT * FROM os_relatorio 
			JOIN os ON os_id=rel_os_id
			JOIN usuario ON usu_id=rel_tec_id
			WHERE rel_os_id=".$_GET["os_id"]." ORDER BY rel_data_criacao DESC";
	$sqlQuery = mysql_query($sql) or die(mysql_error());		
	
	$sql = "SELECT os_data_conclusao,os_equ_id,os_cli_id FROM os
			WHERE os_id=".$_GET["os_id"];
			
	$os_sqlQuery = mysql_query($sql) or die(mysql_error());
	
	$os_dt = mysql_fetch_object($os_sqlQuery);
	
	$sql = "SELECT * FROM equipamento WHERE equ_id='".$os_dt->os_equ_id."'";
	$equ_sqlQuery = mysql_query($sql);
	$rs_equ = mysql_fetch_object($equ_sqlQuery);
	
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id='".$os_dt->os_cli_id."'";
	$cli_sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($cli_sqlQuery);
	
	
?>
<table id="cliview" align="center" style="width: 610px">
	<tr>
        <td colspan="2">
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px"><b>OSID:</b> #<?php echo $_GET["os_id"]?><b> - Relat&oacute;rios t&eacute;cnicos</b></td>
                    <td align="right">
                        <a href="javascript:hideRequests()" title="Fechar">
                            <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php if($_GET["tec_id"] == $_SESSION["tec_id"] && $os_dt->os_data_conclusao == ""){?>
	<tr>
        <td>        	
        	<a href="javascript:showHide('gform','gsinal')" style="padding-left: 2px">
        	<b>
            	<span id="gsinal" style="font-size:10pt">
                	<img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                </span> 
                Gerar Relatorios:
            </b>
            </a>            
        </td>
    </tr>    
    <tr>
    	<td>
        	<span id="gform" style="display:none">
            <form name="rel_form" style="display:inline">
            <input type="hidden" name="tec_id" value="<?php echo $_SESSION["tec_id"]?>" />
            <input type="hidden" name="cli_id" value="<?php echo $_GET["cli_id"]?>" />
            <input type="hidden" name="equ_id" value="<?php echo $_GET["equ_id"]?>" />
            <input type="hidden" name="os_id" value="<?php echo $_GET["os_id"]?>" />	
        	<table width="100%">
    			<tr class="supLabel">
                    <td>Geral:</td>
                    <td>Administrativo:</td>
                </tr>
                <tr class="supField" style="border-bottom: solid 2px <?php echo $colors[2]?>">
                    <td>        	
                        <textarea name="obrg_rel_cont" style="width: 283px; height: 50px;"></textarea>
                    </td>
                    <td>
                        <textarea name="rel_cont_adm" style="width: 283px; height: 50px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <span id="os_loader" style="display:none"><img src="img/loader.gif" width="16" height="16" style="margin-bottom: -4px" /> Aguarde...</span>
                        <input type="button" name="s" value="Salvar informa&ccedil;&otilde;es" 
                               onclick="ajaxSender('rel_form','inc/save/saverel.php',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $_GET["pg"]?>,'los','arel')"
                               style="width: 150px; background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>;
                                      margin-top: 2px; margin-bottom: 4px"/>
                    </td>
                </tr>        
            </table>
            </form>
            </span>
        </td>
    </tr>    
<?php }?>
<?php 
	if(mysql_num_rows($sqlQuery) > 0){
		while($rs_rel = mysql_fetch_object($sqlQuery)){?>    
		<tr>
			<td style="border-bottom: dotted 1px <?php echo $colors[2]?>" colspan="2">
				<div>
                <div style="float:left">
                <a href="javascript:showHide('<?php echo $rs_rel->rel_id?>Content','<?php echo $rs_rel->rel_id?>Sign')" 
				   style="color: <?php echo $colors[0]?>; padding-right: 4px; padding-left: 2px; outline: none">
					<b>
                    	<span id="<?php echo $rs_rel->rel_id?>Sign" style="font-size:10pt" title="Ampliar">
                        	<img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                    </b>
				
				<b>Data:</b> <font style="font-size: 6pt"><?php echo date("H:i:s",$rs_rel->rel_data_criacao)?></font>
				&nbsp;<?php echo date("d-m-y",$rs_rel->rel_data_criacao)?>
                <span style="padding-left: 10px">
					<?php 
						echo str_replace("<br/>",";&nbsp;",substr($rs_rel->rel_cont,0,65));
						if(strlen($rs_rel->rel_cont) > 65){
							echo "...";
						}
					?>                    
                </span>
				</a>
                </div>
                <div style="float:right">
                <a href="javascript:createDoc('inc/doc/doc_rel.php','relatorio.doc','sd','<?php echo $rs_rel->rel_id?>','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
                    <img src="img/btn/create_rel.png" title="Gerar documento do relat&aacute;rio geral" border="0" />
                </a>
                <?php if($_SESSION["tec_tipo"] == "T"){?>
                <a href="javascript:createDoc('inc/doc/doc_rel_adm.php','relatorio.doc','sd','<?php echo $rs_rel->rel_id?>','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
                    <img src="img/btn/create_rel_adm.png" title="Gerar documento do relat&aacute;rio geral e administrativo" border="0" />
                </a>
                <?php }?>
                </div>
                <div style="clear:both"></div>
                </div>
			</td>
		</tr>
		<tr><td colspan="2">
		<table id="<?php echo $rs_rel->rel_id?>Content" style="display:none">
        <tr>
        	<td><b>Geral:</b></td>
        <?php if($_SESSION["tec_tipo"] == "T"){?>
        	<td style="border-left: solid 2px #FFFFFF"><b>Administrativo:</b></td></tr>
        <?php }?>
		<tr>
        	<?php ($_SESSION["tec_tipo"] == "T")? $width = "305px": $width = "610px"?>
			<td width="<?php echo $width?>" valign="top">
				<div style="height:60px;overflow-y: auto">
					<?php echo $rs_rel->rel_cont?>
                </div>
            </td>
        <?php if($_SESSION["tec_tipo"] == "T"){?>    
            <td width="305px" valign="top" style="border-left: solid 2px <?php echo $colors[0]?>">
				<div style="height:60px;overflow-y: auto">
					<?php echo $rs_rel->rel_cont_adm?>
                </div>
           	</td>
        <?php }?>
		</tr>    
		<tr>
			<td align="right" bgcolor="<?php echo $colors[3]?>" colspan="2"
				style="border-bottom: solid 2px <?php echo $colors[0]?>"><b>T&eacute;cnico:</b> <?php echo $rs_rel->usu_nome?></td>
		</tr>
		</table>
		</td></tr>
<?php 
		}
	}else if($_GET["tec_id"] != $_SESSION["tec_id"]){
?>
		<tr>
			<td align="center" colspan="2">Voc&ecirc; n&atilde;o &eacute; o t&eacute;cnico respons&aacute;vel por essa ordem de servi&ccedil;o.</td>
		</tr>    
<?php }?>
	<tr style="background-color: <?php echo $colors[2]?>;">        	
        <td colspan="2">
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
