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
	
	$sql = "SELECT * FROM os			
			JOIN usuario ON os_cli_id=usu_id 
			JOIN equipamento ON os_equ_id=equ_id
			JOIN os_status ON os_sta_id=sta_id
			WHERE os_id=".$_GET["id"];		
	$sqlQuery = mysql_query($sql);
	
	$rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$_GET["tid"];
	$tec_sqlQuery = mysql_query($sql);
	
	$tec_rs = mysql_fetch_object($tec_sqlQuery);
	
	if($rs->os_data_inicio_manutencao != "" && $rs->os_data_inicio_manutencao != "0" ){
		$h_i_m = date("H:i:s",$rs->os_data_inicio_manutencao);
		$dt_i_m = date("d/m/Y",$rs->os_data_inicio_manutencao);
	}
	
	if($rs->os_data_conclusao != ""){
		$h_c = date("H:i:s",$rs->os_data_conclusao);
		$dt_c = date("d/m/Y",$rs->os_data_conclusao);
		if($rs->os_com_remocao == "S"){
			$msg = str_replace("\r\n","<br/>",str_replace("%cliente%",$rs->usu_nome,$config["termo_devolucao"]));
		}
	}else{
		if($rs->os_com_remocao == "S"){
			$h_r = date("H:i:s",$rs->os_com_remocao_dt);
			$dt_r = date("d/m/Y",$rs->os_com_remocao_dt);
			$msg = str_replace("\r\n","<br/>",$config["termo_remocao"]);
		}else{
			$msg = "&nbsp;";
			#$msg = str_replace("\r\n","<br/>",str_replace("%cliente%",$rs->usu_nome,$config["termo_remocao"]));
		}	
	}
	
	$sql = "SELECT * FROM os_serv WHERE os_serv_os_id=".$_GET["id"];
	$sqlQuery = mysql_query($sql);
	$serv_num = mysql_num_rows($sqlQuery);
	if($serv_num < 1){
		$servicos = "";	
	}else{
		($rs->os_mostrar_valor == "S")? $tmp = '<td width="12%"><b>Valor</b></td>': $tmp = "";
		$servicos = '			
			<tr>
				<td colspan="2">
					<table width="100%" style="font-size: 10pt">
						<tr><td colspan="3" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">Serviços</td></tr>
						<tr>
							<td><b>Serviço</b></td>
							<td width="15%"><b>Quantidade</b></td>
							'.$tmp.'
						</tr>
		';
		
		$serv_total = 0;
		$cont = 1;
		
		while($os_serv_rs = mysql_fetch_object($sqlQuery)){
			$sql = "SELECT * FROM servico WHERE serv_id=".$os_serv_rs->os_serv_serv_id;
			$serv_sqlQuery = mysql_query($sql);
			$serv_rs = mysql_fetch_object($serv_sqlQuery);
			
			$serv_total += $os_serv_rs->os_serv_valor*$os_serv_rs->os_serv_quant;
			
			($rs->os_mostrar_valor == "S")? $tmp = '<td> R$ '.number_format($os_serv_rs->os_serv_valor,2,",",".").'</td>': $tmp = "";
			
			($serv_num != $cont)? $dashed = "border-bottom: dashed 1px": $dashed = "";
			
			$cont++;
			
			$servicos .= '
				<tr>
					<td>'.$serv_rs->serv_nome.'</td>
					<td>'.$os_serv_rs->os_serv_quant." ".$serv_rs->serv_unid_medida.'</td>
					'.$tmp.'
				</tr>
				<tr>
					<td colspan="3" style="font-size: 8pt; text-align: justify; '.$dashed.' ">
						<b>Código:</b> '.$os_serv_rs->os_serv_serv_id.' <b>Descrição:</b> '.$serv_rs->serv_desc.'
					</td>
				</tr>
			';
			
		}
		
		($rs->os_mostrar_valor == "S")? $tmp = '<b>subtotal:</b> R$ '.number_format($serv_total,2,",","."): $tmp = "&nbsp;";
		
		$servicos .= '
				<tr>
					<td colspan="3" align="right" style="background-color: '.$colors[4].'; border: solid 1px; margin: 4px">
						'.$tmp.'
					</td>
				</tr>
				</table>
			</td>
		</tr>
		';
	}
	
	
	$sql = "SELECT * FROM mov_saida_os WHERE mov_sos_os_id=".$_GET["id"];
	$sqlQuery = mysql_query($sql);
	$prod_num = mysql_num_rows($sqlQuery);
	
	if($prod_num < 1){
		$produtos = "";	
	}else{
		($rs->os_mostrar_valor == "S")? $tmp = '<td width="12%"><b>Valor</b></td>': $tmp = "";
		$produtos = '			
			<tr>
				<td colspan="2">
					<table width="100%" style="font-size: 10pt">
						<tr><td colspan="3" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">Produtos</td></tr>
						<tr>
							<td><b>Produto</b></td>
							<td width="15%"><b>Quantidade</b></td>
							'.$tmp.'
						</tr>
		';
		
		$prod_total = 0;
		
		$cont = 1;
		
		while($mov_rs = mysql_fetch_object($sqlQuery)){
			$sql = "SELECT * FROM produto JOIN classprod ON classprod_id=prod_classprod_id WHERE prod_id=".$mov_rs->mov_sos_prod_id;
			$prod_sqlQuery = mysql_query($sql);
			$prod_rs = mysql_fetch_object($prod_sqlQuery);
			
			$prod_total += $mov_rs->mov_sos_vvalor*$mov_rs->mov_sos_quant_saida;
			
			($rs->os_mostrar_valor == "S")? $tmp = '<td> R$ '.number_format($mov_rs->mov_sos_vvalor,2,",",".").'</td>': $tmp = "";
			
			($prod_num != $cont)? $dashed = "border-bottom: dashed 1px": $dashed = "";
			
			$cont++;
			
			$produtos .= '
				<tr>
					<td>'.$prod_rs->classprod_nome." - ".$prod_rs->prod_nome.'</td>
					<td>'.$mov_rs->mov_sos_quant_saida." ".$prod_rs->prod_unid_medida.'</td>
					'.$tmp.'
				</tr>
				<tr>
					<td colspan="3" style="font-size: 8pt; '.$dashed.'">
						<b>Código:</b> '.$mov_rs->mov_sos_prod_id.' <b>Fabricante:</b> '.$prod_rs->prod_fabricante.' <b>Modelo:</b> '.$prod_rs->prod_modelo.'
					</td>
				</tr>
			';	
		}
		
		($rs->os_mostrar_valor == "S")? $tmp = '<b>subtotal:</b> R$ '.number_format($prod_total,2,",","."): $tmp = "&nbsp;";
		
		$produtos .= '
				<tr>
					<td colspan="3" align="right" style="background-color: '.$colors[4].'; border: solid 1px; margin: 4px">
						'.$tmp.'
					</td>
				</tr>
				</table>
			</td>
		</tr>
		';
	}
	
	($rs->os_mostrar_valor == "S")? $total = '
		<tr>
			<td align="right" colspan="3" style="padding-right: 10px">
				<b>total:</b> R$ <span style="font-size: 14pt">'.number_format($serv_total+$prod_total,2,",",".").'</span>
			</td>
		</tr>': $total = "";
	
	$html = '
		<body style="font-family: Arial;">
				<table width="120%" align="center" style="font-family: Arial; font-size: 10pt;">
					<tr>
						<td width="50%" align="center" valign="center" style="font-size: 14pt"><b>OSID:</b> #'.$_GET["id"].'</td>
						<td align="right"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">
							Detalhes da Ordem de serviço
						</td>
					</tr>
					<tr>
						<td>
							<b>Abertura:</b> 
							<font style="font-size: 8pt">'.date("H:i:s",$rs->os_data_abertura).'</font>
							'.date("d/m/Y",$rs->os_data_abertura).'
						</td>
						<td>
							<b>In&iacute;cio da manuten&ccedil;&atilde;o:</b>							
							<font style="font-size: 8pt">'.$h_i_m.'</font>
							'.$dt_i_m.'
						</td>
					</tr>
					<tr>
						<td>
							<b>Data da remo&ccedil;&atilde;o:</b>
							<font style="font-size: 8pt">'.$h_r.'</font>
							'.$dt_r.'
						</td>
						<td>
							<b>Devolu&ccedil;&atilde;o do equipamento:</b>&nbsp; ____  / ____  / ______ . 
						</td>
					</tr>
					<tr>
						<td>
							<b>Conclus&atilde;o:</b> 
							<font style="font-size: 8pt">'.$h_c.'</font>
							'.$dt_c.'
						</td>
					</tr>
					<tr>
						<td colspan="2" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">
							Descrição
						</td>
					</tr>
					<tr><td colspan="2">'.nl2br($rs->os_pro_cli_descricao).'</td></tr>
					<tr>
						<td colspan="2" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">
							Informa&ccedil;&otilde;es do cliente
						</td>
					</tr>
					<tr>
						<td colspan="2"><b>CLID:</b> #'.$rs->usu_id.' <b>Cliente:</b> '.$rs->usu_nome.'</td>
					</tr>
					<tr>
						<td><b>E-mail:</b> '.$rs->usu_email.'</td>
						<td><b>tel.:</b> '.$rs->usu_tel.'</td>
					</tr>
					<tr>
						<td colspan="2" style="background-color: '.$colors[4].'; border: solid 1px; font-weight: bold; margin: 4px">
							Informa&ccedil;&otilde;es do equipamento
						</td>
					</tr>
					<tr>
						<td><b>Equipamento:</b> '.$rs->equ_nome.'</td>
						<td><b>EQID:</b> #'.$rs->usu_id.'</td>
					</tr>
					<tr>
						<td><b>N. Patrim&ocirc;nio:</b> '.$rs->equ_num_patrimonio.'</td>
						<td><b>N. S&eacute;rie:</b> '.$rs->equ_num_serie.'</td>
					</tr>
					<tr>
						<td><b>Modelo:</b> '.$rs->equ_modelo.'</td>
						<td><b>Fabricante:</b> '.$rs->equ_fabricante.'</td>						
					</tr>
					'.$servicos.'
					'.$produtos.'
					'.$total.'
					<tr>
						<td style="margin-top: 100px; margin-bottom: 100px" align="center" valign="center" colspan="2">'.$msg.'</td>
					</tr>
					<tr>
						<td colspan="2" align="center" style="margin-bottom: 30px">
							___________________ , ____ de ____ de ______ .
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="height: 60px">
							________________________<br/>
							Representante da '.$config["emp_nome"].'
							
						</td>
						<td align="center" valign="top">
							________________________<br/>
							Cliente
						</td>
					</tr>
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
			
}else{	
	
	$orderType[0][0] = "os_id";
	$orderType[1][0] = "usu_nome";
	$orderType[2][0] = "equ_nome";
	$orderType[3][0] = "equ_num_patrimonio";
	$orderType[4][0] = "equ_num_serie";
	$orderType[5][0] = "os_data_abertura";
	$orderType[6][0] = "sta_nome";
	$orderType[7][0] = "os_com_remocao";
	$orderType[8][0] = "usu_nome";
	
	$limitation = " AND os_historico='N'";
	
	if($_GET["tt"] == "C"){
		$limitation = " AND os_cli_id=".$_GET["tid"];					
	}else{
		$sql = "SELECT usu_view_os FROM usuario WHERE usu_id=".$_GET["tid"];
		$tec_sqlQuery = mysql_query($sql);
		$rs_tec = mysql_fetch_object($tec_sqlQuery);
		
		if($rs_tec->usu_view_os == "N"){
			$limitation = " AND os_tec_id=".$_GET["tid"];
		}
	}
	
	
	
	$sql = "SELECT * FROM os 
					JOIN usuario ON os_cli_id=usu_id
					JOIN equipamento ON os_equ_id=equ_id				
					JOIN os_status ON os_sta_id=sta_id";
	
	
	
	if($_GET["cmd"] == "srch"){	
		
		$fields = array_keys($_GET);
		$values = array_values($_GET);	
		for($i = 7; $i < (count($_GET)-1); $i++){
			if($_GET[$fields[$i]] !=""){
				$sql .= " WHERE ";
				break;
			}
		}
		
		$isFirst = true;
		for($i = 7; $i < (count($_GET)-1); $i++){
			if($_GET[$fields[$i]] !="" && $fields[$i] != "rc"){			
				if($isFirst){				
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
					$isFirst = false;
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";				
				}		
			}
		}
	}
	
	$sql .= $limitation;
	
	$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_GET["ord"];
	$sqlQuery = mysql_query($sql);
	$num_rows = mysql_num_rows($sqlQuery);
	
	$html = '<body style="font-family: Arial; ">';
	$html .= '<table width="120%"  align="center" style="font-family: Arial;font-size: 10pt">
				<tr>
					<td colspan="2" align="right" style="margin-bottom: 10px"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
				</tr>
				<tr>
					<td>
						Relat&oacute;rio de ordens de servi&ccedil;o<br/>
						Gerado por: '.$_GET["tn"].'
					</td>
					<td align="right" valign="top">'.date("H:i:s d/m/Y",(time()-(date('I')*3600))).'</td>
				</tr>
			  </table><br/><br/>';
	$html .= '<table width="120%" style="font-family: Arial;font-size: 8pt;" align="center">';
	$html .= '<tr><td colspan="9">'.$num_rows.' registros nesta pesquisa</td></tr>';
	$html .= '</table>';
	$html .= '<table width="120%" cellspacing="0" cellpadding="4" style="font-family: Arial;font-size: 8pt; border: solid 1px" align="center">';
	$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
				<th>OSID</th>
				<th>Abertura</th>
				<th>Conclus&atilde;o</th>
				<th align="left">Equipamento</th>
				<th>Status</th>
				<th>Local</th>
			  </tr>';
	
	$isColor = false;
	while($rs = mysql_fetch_object($sqlQuery)){
		($isColor)? $color = $colors[2]: $color = $colors[1];
		
		$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$rs->os_tec_id;
		$tec_sqlQuery = mysql_query($sql);
		$rs_tec = mysql_fetch_object($tec_sqlQuery);
		
		$tec_nome_compl =  split(" ",$rs_tec->usu_nome);
		$tec_nome = $tec_nome_compl[0];
		$tec_nome .= " ".substr($tec_nome_compl[1],0,1);
		$tec_nome .= " ".substr($tec_nome_compl[2],0,1);
		
		
		switch($rs->sta_id){
			case 1 : $status = '<img src="'.$config["emp_site"].'/img/btn/orcamento.png" />';
									break;
									
			case 4 : $status = '<img src="'.$config["emp_site"].'/img/btn/manutencao.png" />';
									break;
			
			case 5 : $status = '<img src="'.$config["emp_site"].'/img/btn/manutencao_d.png" />';
									break;
			
			case 6 : $status = '<img src="'.$config["emp_site"].'/img/btn/encaminhado.png" />';
									break;
			case 8 : $status = '<img src="'.$config["emp_site"].'/img/btn/orcamentoj.png" />';
									break;
		}
	
		switch($rs->os_com_remocao){
			case "N": $remocao = '<img src="'.$config["emp_site"].'/img/btn/local.png" />';
									break;
									
			case "S": $remocao = '<img src="'.$config["emp_site"].'/img/btn/rlocal.png" />';
									break;
		}
						
		if($rs->os_data_conclusao == "" || $rs->os_data_conclusao == "0"){
			$dt_conclusao = "";
		}else{
			$dt_conclusao = date("d-m-y",$rs->os_data_conclusao);	
		}
		
		
		$html .= '<tr bgcolor="'.$color.'">
					<td align="center" >'.$rs->os_id.'</td>
					<td align="center" >'.date("d-m-y",$rs->equ_dt_cad).'</td>
					<td align="center" >'.$dt_conclusao.'</td>
					<td>
						<b>EQUID: </b>'.$rs->equ_id.'
						<b>Num. S&eacute;rie: </b>'.$rs->equ_num_serie.'
						<b>Num. Patrim&ocirc;nio: </b>'.$rs->equ_num_patrimonio.'						
						<b>Nome: </b>'.$rs->equ_nome.'
					</td>					
					
					<td align="center" rowspan="3">'.$status.'</td>
					<td align="center" rowspan="3">'.$remocao.'</td>
				  </tr>
				  <tr bgcolor="'.$color.'">
				  	<td colspan="3">&nbsp;</td>
					<td><b>Modelo: </b>'.$rs->equ_modelo.' <b>Fabricante: </b>'.$rs->equ_fabricante.'</td>
				  </tr>
				  <tr bgcolor="'.$color.'">
				  	<td colspan="3">&nbsp;</td>
					<td><b>Cliente: </b>'.$rs->usu_nome.'</td>
				  </tr>';
		
		$isColor = !$isColor;
	}
	
	$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
				<td colspan="6" align="center">'.$config["emp_site"].'</td>
			  </tr>		  
			  </table>
			  <table width="120%" style="font-family: sans; font-size: 8pt; margin-top: 10px" align="center">
				<tr><td>Legendas:</td></tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/orcamento.png" />&nbsp;&nbsp;&nbsp;Aguardando autorização					
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/orcamentoj.png" />&nbsp;&nbsp;&nbsp;N&atilde;o autorizado					
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/encaminhado.png" />&nbsp;&nbsp;&nbsp;Encaminhado ao suporte					
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/manutencao.png" />&nbsp;&nbsp;&nbsp;Em manuten&ccedil;&atilde;o					
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/manutencao_d.png" />&nbsp;&nbsp;&nbsp;Conclu&iacute;da
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/local.png" />&nbsp;&nbsp;&nbsp;Equipamento no local
					</td>
				</tr>
				<tr>
					<td>
						<img src="'.$config["emp_site"].'/img/btn/rlocal.png" />&nbsp;&nbsp;&nbsp;Equipamento removido do local
					</td>
				</tr>
			  </table>		  
			  </body>';
	
	
	$htmltodoc= new HtmlToDoc();
	$htmltodoc->setDir("../../doc/");
	$htmltodoc->createDoc($html,$_GET["fn"]);
}
?>