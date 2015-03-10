<?php
	require("../../sys/_dbconn.php");
	require_once("../../sys/fpdf/fpdf.php");	
	require("../../css/_colors.php");
	require("../../sys/_config.php");
	
	define('FPDF_FONTPATH','../../sys/fpdf/font/');
	
	
	$sql = "SELECT * FROM os
			JOIN cliente ON os_cli_id=cli_usu_id			
			JOIN usuario ON os_cli_id=usu_id 
			JOIN equipamento ON os_equ_id=equ_id
			JOIN os_status ON os_sta_id=sta_id
			WHERE os_id=".$_GET["id"];		
	$sqlQuery = mysql_query($sql);
	
	$rs = mysql_fetch_object($sqlQuery);
	
	if($rs->os_data_abertura != "" && $rs->os_data_abertura != "0" ){		
		$dt_abertura = date("d/m/Y",$rs->os_data_abertura);
	}
	
	if($rs->os_data_orcamento != "" && $rs->os_data_orcamento != "0" ){		
		$dt_autorizacao = date("d/m/Y",$rs->os_data_orcamento);
	}
	
	if($rs->os_data_inicio_manutencao != "" && $rs->os_data_inicio_manutencao != "0" ){		
		$dt_manutencao = date("d/m/Y",$rs->os_data_inicio_manutencao);
	}
	
	if($rs->os_com_remocao_dt != "" && $rs->os_com_remocao_dt != "0" ){		
		$dt_remocao = date("d/m/Y",$rs->os_com_remocao_dt);
	}
	
	if($rs->os_data_prazo != "" && $rs->os_data_prazo != "0"){		
		$dt_prazo = date("d/m/Y",$rs->os_data_prazo);		
	}
	
	if($rs->os_data_conclusao != ""){		
		$dt_conclusao = date("d/m/Y",$rs->os_data_conclusao);		
	}
	
	$cellHeight = 4;
	
	$pdf= new FPDF("L","mm","A4");
	
	$pdf->SetTopMargin(5);
	$pdf->SetAutoPageBreak(true, 5);
	
	$pdf->SetFont('arial','',10);
	
	$pdf->SetTitle("OS n. ".$rs->os_id);
	$pdf->SetSubject("OS n. ".$rs->os_id);
	
	$pdf->SetY("-1"); 
	$titulo="Ordem de serviço";
	$pdf->Cell(0,5,$titulo,0,0,'L');
	
	$pdf->SetFont('arial','',20);
	$pdf->Cell(0,5,'OSID: #'.$rs->os_id,0,1,'R');
	$pdf->Ln(2);
	$pdf->Cell(0,0,'',1,1,'L');
	
	//endereco da imagem,posicao X(horizontal),posicao Y(vertical), tamanho largura, tamanho altura
	//$pdf->Image($config["emp_site"]."img/".$config["emp_logo"], 8,15);	
	$pdf->Image("../../img/".$config["emp_logo"], 8,15);
	
	$pdf->SetFont('arial','',8);
	
	$novo="Rua José Jesus Lima, 12, Getúlio Vargas Aracaju/SE\nCNPJ 07.888.160/0001-68  I.M. 548228-7  Tel: (79) 3222-0404/ 8837-1767\nRegistro no CREA/SE 3580";
	
	$pdf->SetY("15");
	
	$pdf->MultiCell(0,5,$novo,0,'R');	

	$pdf->SetFillColor(240);
	
	
	$pdf->SetY("45");
	$pdf->SetX("10");
	$pdf->Cell(0,$cellHeight,"Detalhes da ordem de serviço",1,1,'L', true);	
	
	$pdf->Cell(90,$cellHeight,"Data de abertura: ".$dt_abertura,1,0,'L');	
	$pdf->Cell(90,$cellHeight,"Data da autorização: ".$dt_autorizacao,1,0,'L');	
	$pdf->Cell(0,$cellHeight,"Data de inicío da manutenção: ".$dt_manutencao,1,1,'L');	
	
	$pdf->Cell(90,$cellHeight,"Data de remoção: ".$dt_remocao,1,0,'L');	
	$pdf->Cell(90,$cellHeight,"Data de devolução:",1,0,'L');	
	$pdf->Cell(0,$cellHeight,"Data de conclusão: ".$dt_conclusao,1,1,'L');		

	/*$pdf->Cell(180,$cellHeight,"",1,0,'L');
	$pdf->Cell(0,$cellHeight,"Prazo de execução: ".$dt_prazo,1,1,'L');	*/
	
	$pdf->Cell(0,$cellHeight,"Informações do cliente",1,1,'L',true);	

	$pdf->Cell(90,$cellHeight,"CLID: #".$rs->usu_id,1,0,'L');
	$pdf->Cell(90,$cellHeight,"CPF/CNPJ: ".$rs->cli_cp,1,0,'L');
	$pdf->Cell(0,$cellHeight,"Inscrição estadual: ".$rs->cli_inscricao,1,1,'L');

	$pdf->Cell(0,$cellHeight,"Nome: ".$rs->usu_nome,1,1,'L');
	
	$pdf->Cell(90,$cellHeight,"Telefones: ".$rs->usu_tel,1,0,'L');
	$pdf->Cell(0,$cellHeight,"E-mail: ".$rs->usu_email,1,1,'L');
	
	$pdf->Cell(90,$cellHeight,"Endereço: ".$rs->usu_end_logradouro,1,0,'L');
	$pdf->Cell(90,$cellHeight,"Bairro: ".$rs->usu_end_bairro,1,0,'L');
	$pdf->Cell(0,$cellHeight,"Complemento: ".$rs->usu_end_complemento,1,1,'L');
	
	$pdf->Cell(90,$cellHeight,"Cidade: ".$rs->usu_end_cidade,1,0,'L');
	$pdf->Cell(90,$cellHeight,"Estado: ".$rs->usu_end_estado,1,0,'L');
	$pdf->Cell(0,$cellHeight,"CEP: ".$rs->usu_end_cep,1,1,'L');		
	
	$pdf->Cell(0,$cellHeight,"Informações do equipamento",1,1,'L',true);
	
	$pdf->Cell(90,$cellHeight,"EQUID: #".$rs->equ_id,1,0,'L');
	$pdf->Cell(90,$cellHeight,"Número de patrimônio: ".$rs->equ_num_patrimonio,1,0,'L');
	$pdf->Cell(0,$cellHeight,"Número de série: ".$rs->equ_num_serie,1,1,'L');
	
	$pdf->Cell(0,$cellHeight,"Equipamento: ".$rs->equ_nome,1,1,'L');
	
	$pdf->Cell(90,$cellHeight,"Modelo: #".$rs->equ_modelo,1,0,'L');
	$pdf->Cell(90,$cellHeight,"Fabricante: ".$rs->equ_fabricante,1,0,'L');
	$pdf->Cell(0,$cellHeight,"Observações: ".$rs->equ_descricao,1,1,'L');
	
	$pdf->Cell(0,$cellHeight,"",1,1,'L',true);
	
	$pdf->Cell(0,$cellHeight,"Descrição do cliente para o problema:",1,1,'L');
	$pdf->Cell(0,$cellHeight,$rs->os_pro_cli_descricao,1,1,'L');	

	$pdf->Cell(0,$cellHeight,"Observações técnicas:",1,1,'L');
	$pdf->Cell(0,$cellHeight,$rs->os_tec_obs,1,1,'L');
	
	$pdf->Cell(90,$cellHeight,"Contrato: ( ) SIM    ( ) NÃO",1,0,'L');
	$pdf->Cell(0,$cellHeight,"Garantia: ( ) SIM    ( ) NÃO",1,1,'L');
	
	//SERVIÇOS
	
	$pdf->Cell(0,5,"Serviços: ",1,1,'L',true);
		
	$pdf->Cell(20,$cellHeight,"Código",1,0,'L');
	$pdf->Cell(177,$cellHeight,"Serviço",1,0,'L');
	$pdf->Cell(20,$cellHeight,"Quantidade",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Unidade",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Valor (Unid.)",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Valor",1,1,'C');
	
	$sql = "SELECT * FROM os_serv WHERE os_serv_os_id=".$_GET["id"];
	$sqlQuery = mysql_query($sql);
	$serv_num = mysql_num_rows($sqlQuery);	
	
	
	if($serv_num > 0){
		
		$serv_total = 0;
		
		while($os_serv_rs = mysql_fetch_object($sqlQuery)){
			$sql = "SELECT * FROM servico WHERE serv_id=".$os_serv_rs->os_serv_serv_id;
			$serv_sqlQuery = mysql_query($sql);
			$serv_rs = mysql_fetch_object($serv_sqlQuery);
			
			
			$serv_total += $os_serv_rs->os_serv_valor*$os_serv_rs->os_serv_quant;
			
			if($rs->os_mostrar_valor == "S"){
				$valor = number_format($os_serv_rs->os_serv_valor,2,",",".");
				$serv_subtotal = number_format($os_serv_rs->os_serv_valor*$os_serv_rs->os_serv_quant,2,",",".");
			}else{
				$valor = "";
				$serv_subtotal = "";
			}
			
			$pdf->Cell(20,$cellHeight,$serv_rs->serv_id,1,0,'L');
			$pdf->Cell(177,$cellHeight,$serv_rs->serv_nome,1,0,'L');
			$pdf->Cell(20,$cellHeight,$os_serv_rs->os_serv_quant,1,0,'C');
			$pdf->Cell(20,$cellHeight,$serv_rs->serv_unid_medida,1,0,'C');
			$pdf->Cell(20,$cellHeight,$valor,1,0,'C');
			$pdf->Cell(20,$cellHeight,$serv_subtotal,1,1,'C');			
			
		}
		
		($rs->os_mostrar_valor == "S")? $valor = number_format($serv_total,2,",","."): $valor = "";
		
		$pdf->Cell(257,$cellHeight,"subtotal:",1,0,'R');
		$pdf->Cell(0,$cellHeight,$valor,1,1,'C');
		
	}else{		
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(257,$cellHeight,"subtotal:",1,0,'R');
		$pdf->Cell(0,$cellHeight,"",1,1,'C');	
	}
	
	//PRODUTOS
	
	$pdf->Cell(0,$cellHeight,"Produtos: ",1,1,'L',true);
		
	$pdf->Cell(20,$cellHeight,"Código",1,0,'L');
	$pdf->Cell(59,$cellHeight,"Produto",1,0,'L');
	$pdf->Cell(59,$cellHeight,"Modelo",1,0,'L');
	$pdf->Cell(59,$cellHeight,"Fabricante",1,0,'L');
	$pdf->Cell(20,$cellHeight,"Quantidade",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Unidade",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Valor (Unid.)",1,0,'C');
	$pdf->Cell(20,$cellHeight,"Valor",1,1,'C');
	
	$sql = "SELECT * FROM mov_saida_os WHERE mov_sos_os_id=".$_GET["id"];
	$sqlQuery = mysql_query($sql);
	$prod_num = mysql_num_rows($sqlQuery);	
	
	if($prod_num > 0){
		
		$prod_total = 0;
		
		while($mov_rs = mysql_fetch_object($sqlQuery)){
			$sql = "SELECT * FROM produto JOIN classprod ON classprod_id=prod_classprod_id WHERE prod_id=".$mov_rs->mov_sos_prod_id;
			$prod_sqlQuery = mysql_query($sql);
			$prod_rs = mysql_fetch_object($prod_sqlQuery);
			
			$prod_total += $mov_rs->mov_sos_vvalor*$mov_rs->mov_sos_quant_saida;
			
			if($rs->os_mostrar_valor == "S"){
				$valor = number_format($mov_rs->mov_sos_vvalor,2,",",".");
				$prod_subtotal = number_format($mov_rs->mov_sos_vvalor*$mov_rs->mov_sos_quant_saida,2,",",".");
			}else{
				$valor = "";
				$prod_subtotal = "";
			}
			
			$pdf->Cell(20,$cellHeight,$prod_rs->prod_id,1,0,'L');
			$pdf->Cell(59,$cellHeight,$prod_rs->prod_nome,1,0,'L');
			$pdf->Cell(59,$cellHeight,$prod_rs->prod_modelo,1,0,'L');
			$pdf->Cell(59,$cellHeight,$prod_rs->prod_fabricante,1,0,'L');
			$pdf->Cell(20,$cellHeight,$mov_rs->mov_sos_quant_saida,1,0,'C');
			$pdf->Cell(20,$cellHeight,$prod_rs->prod_unid_medida,1,0,'C');
			$pdf->Cell(20,$cellHeight,$valor,1,0,'C');
			$pdf->Cell(20,$cellHeight,$prod_subtotal,1,1,'C');
		}
		
		($rs->os_mostrar_valor == "S")? $valor = number_format($prod_total,2,",","."): $valor = "";
		
		$pdf->Cell(257,$cellHeight,"subtotal:",1,0,'R');
		$pdf->Cell(0,$cellHeight,$valor,1,1,'C');		
		
	}else{
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(20,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(59,$cellHeight,"",1,0,'L');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,0,'C');
		$pdf->Cell(20,$cellHeight,"",1,1,'C');
		
		$pdf->Cell(257,$cellHeight,"subtotal:",1,0,'R');
		$pdf->Cell(0,$cellHeight,"",1,1,'C');
	}
	
	
	$pdf->Cell(257,$cellHeight,"",1,0,'R');
	$pdf->Cell(0,$cellHeight,"",1,1,'C');
	
	if($rs->os_mostrar_valor == "S"){
		$total = number_format($serv_total+$prod_total,2,",",".");		
	}else{
		$total = "";
	}
	
	$pdf->Cell(257,$cellHeight,"total:",1,0,'R');
	
	if($serv_num > 0 || $prod_num >0){		
		$pdf->Cell(0,$cellHeight,$total,1,1,'C');
	}else{		
		$pdf->Cell(0,$cellHeight,"",1,1,'C');
	}
	
	$pdf->Cell(0,$cellHeight,"",0,1,'C');
	$pdf->Cell(0,$cellHeight,"",0,1,'C');
	
	$pdf->Cell(135,5,"_____________________________________",0,0,'C');
	$pdf->Cell(0,5,"_____________________________________",0,1,'C');
	$pdf->Cell(135,5,"Assinatura do cliente",0,0,'C');	
	$pdf->Cell(0,5,"Assinatura do técnico",0,1,'C');
	
	/*
	//Rodapé
	
	$pdf->setY("195");
	$pdf->setX("10");
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(2);
	$pdf->Cell(0,5,'Página '.$pdf->PageNo()." / {nb}",0,1,'R');
	*/
	
	/*
	//NOVA PAGINA
	
	$pdf->addPage("L","A4");
		
	$titulo="Ordem de serviço";
	$pdf->Cell(0,5,$titulo,0,0,'L');
	
	$pdf->SetFont('arial','',20);
	$pdf->Cell(0,5,'OSID: #'.$rs->os_id,0,1,'R');
	$pdf->Ln(2);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);
	
	$pdf->SetFont('arial','',8);
	*/
	
	/*	
	$pdf->setY("195");
	$pdf->setX("10");
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(2);
	$pdf->Cell(0,5,'Página '.$pdf->PageNo()." / {nb}",0,1,'R');
	*/
	//imprime a saida do arquivo
	$pdf->AliasNbPages();
	$pdf->Output("arquivo","I");

?>
