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
			WHERE rel_id=".$_GET["id"];		
	$sqlQuery = mysql_query($sql);
	
	$rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT * FROM usuario WHERE usu_id=".$_GET["tid"];
	$tec_sqlQuery = mysql_query($sql);
	
	$tec_rs = mysql_fetch_object($tec_sqlQuery);
	
	if($tec_rs->usu_not_rel_adm == "S" || $rs->rel_tec_id == $tec_rs->usu_id){
		$rel_adm = '<tr><td colspan="2"><hr /></td></tr>
					<tr><td colspan="2"><b>Relat&oacute;rio Administrativo</b></td></tr>					
					<tr>
						<td colspan="2">'.nl2br($rs->rel_cont_adm).'</td>						
					</tr>';
	}
	
	$html = '
		<body style="font-family: Arial">
				<table width="120%" align="center" style="font-family: Arial; font-size: 10pt">
					<tr>
						<td width="50%" align="center" valign="center" style="font-size: 14pt">
							<b>OSID:</b> #'.$rs->os_id.'<br/> Relat&oacute;rio</td>
						<td align="right"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td>
							<b>Data de cria&ccedil;&atilde;o:</b> 
							<font style="font-size: 8pt">'.date("H:i:s",$rs->rel_data_criacao).'</font>
							'.date("d/m/Y",$rs->rel_data_criacao).'
						</td>
						<td align="right"><b>T&eacute;cnico:</b> '.$rs->usu_nome.'</td>
					</tr>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
					<tr><td colspan="2"><b>Relat&oacute;rio Geral:<b></td></tr>
					<tr><td colspan="2">'.nl2br($rs->rel_cont).'</td></tr>
					'.$rel_adm.'
					<tr><td colspan="2"><hr /></td></tr>
					<tr>
						<td style="font-size: 8pt" colspan="2" align="center">
							Acesse o painel de controle para mais informações 
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