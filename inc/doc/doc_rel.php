<?
$files = opendir("../../doc");
while(($file = readdir($files)) !== false){
	$tmp = split("_",$file);
	if($tmp[1] == $_GET["tid"]){
		unlink("../../doc/".$file);
		echo $file."<br/>";
	}
}

require("../../sys/_dbconn.php");
require("../../css/_colors.php");
require("../../sys/_config.php");
require("../_lib.php");

if($_GET["cmd"] == "sd"){
	
	$sql = "SELECT * FROM os_relatorio			
			JOIN usuario ON rel_tec_id=usu_id 			
			JOIN os ON rel_os_id=os_id
			JOIN equipamento ON os_equ_id=equ_id
			WHERE rel_id=".$_GET["id"];		
	$sqlQuery = mysql_query($sql);
	
	$rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT * FROM usuario			
			WHERE usu_id=".$rs->os_cli_id;		
	$cli_sqlQuery = mysql_query($sql);
	
	$rs_cli = mysql_fetch_object($cli_sqlQuery);
	
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$rs->os_tec_id;
	$tec_sqlQuery = mysql_query($sql);
	
	$tec_rs = mysql_fetch_object($tec_sqlQuery);	
	
	
	$html = '
		<body style="font-family: Arial">
				<table width="120%" align="center" style="font-family: Arial; font-size: 10pt">
					<tr>
						<td width="50%" align="center" valign="center" style="font-size: 14pt">
							<b>OSID:</b> #'.$rs->os_id.'<br/> Relat&oacute;rio</td>
						<td align="right"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
					</tr>					
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>Informa&ccedil;&otilde;es do cliente</b></td></tr>
					<tr>
						<td><b>Cliente:</b> '.$rs_cli->usu_nome.'</td>
						<td><b>CLID:</b> #'.$rs_cli->usu_id.'</td>
					</tr>
					<tr>
						<td><b>E-mail:</b> '.$rs_cli->usu_email.'</td>
						<td><b>tel.:</b> '.$rs_cli->usu_tel.'</td>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
					<tr><td colspan="2"><b>Informa&ccedil;&otilde;es do equipamento</b></td></tr>
					<tr>
						<td><b>Equipamento:</b> '.$rs->equ_nome.'</td>
						<td><b>EQID:</b> #'.$rs->usu_id.'</td>
					</tr>
					<tr>
						<td><b>N. Patrim&ocirc;nio:</b> '.$rs->equ_num_patrimonio.'</td>
						<td><b>N. S&eacute;rie:</b> '.$rs->equ_num_serie.'</td>
					</tr>
					<tr>
						<td><b>Fabricante:</b> '.$rs->equ_fabricante.'</td>
						<td><b>Modelo:</b> #'.$rs->equ_modelo.'</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
					<tr>
						<td><b>Relat&oacute;rio Geral:<b></td>
						<td align="right">
							<b>Data de cria&ccedil;&atilde;o:</b> 
								<font style="font-size: 8pt">'.date("H:i:s",$rs->rel_data_criacao).'</font>
								'.date("d/m/Y",$rs->rel_data_criacao).'
						</td>
					</tr>
					<tr><td colspan="2">'.nl2br($rs->rel_cont).'</td></tr>
					<tr>						
						<td align="right" colspan="2"><b>T&eacute;cnico:</b> '.$rs->usu_nome.'</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="center" valign="top" style="height: 60px">
							________________________<br/>
							'.$tec_rs->usu_nome.'
							
						</td>
						<td align="center" valign="top">
							________________________<br/>
							Cliente
						</td>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
					<tr>
						<td style="font-size: 8pt" colspan="2" align="center">
							Acesse o painel de controle para mais informa&ccedil;&otilde;es 
							sobre seus equipamentos ou entre em contato com a equipe de suporte.
						</td>
					</tr>
					<tr style="font-size: 8pt">
						<td style="padding-left: 48px">'.$config["emp_site"].'</td>
						<td style="padding-right: 48px" align="right">'.$config["emp_email"].'</td>
					</tr>
				</table>
		</body>
	';
	
	$htmltodoc= new HtmlToDoc();
	$htmltodoc->setDir("../../doc/");
	$htmltodoc->createDoc($html,$_GET["fn"]);
			
}
?>