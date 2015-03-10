<?php	
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	
	$sql = "SELECT * FROM produto
			JOIN classprod ON prod_classprod_id=classprod_id
			WHERE prod_id=".$_GET["prod_id"];		
	$sqlQuery = mysql_query($sql);
	$prod_rs = mysql_fetch_object($sqlQuery);
	
	//menor valor de custo
	$sql = "SELECT mov_entr_id,mov_entr_nf_dt_emissao,mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$menvc = 0; // menor valor de custo de entrada
	$menvc_dt = 0; // data da nota fiscal da entrada
	$menvc_id = 0; // id da movimentação de entrada
	$isFirst = true;
	while($rs = mysql_fetch_object($sqlQuery)){
		if($isFirst){
			$menvc_id = $rs->mov_entr_id;
			$menvc = $rs->mov_entr_vcusto;
			$menvc_dt = $rs->mov_entr_nf_dt_emissao;			
			$isFirst = false;
		}else{
			if($menvc > $rs->mov_entr_vcusto){
				$menvc_id = $rs->mov_entr_id;
				$menvc = $rs->mov_entr_vcusto;
				$menvc_dt = $rs->mov_entr_nf_dt_emissao;
			}elseif($menvc == $rs->mov_entr_vcusto && $menvc_dt < $rs->mov_entr_nf_dt_emissao){
				$menvc_id = $rs->mov_entr_id;
				$menvc = $rs->mov_entr_vcusto;
				$menvc_dt = $rs->mov_entr_nf_dt_emissao;
			}
		}
	}
	
	//maior valor de custo
	$sql = "SELECT mov_entr_id,mov_entr_nf_dt_emissao,mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$maivc = 0; // maior valor de custo de entrada
	$maivc_dt = 0; // data da nota fiscal da entrada
	$maivc_id = 0; // id da movimentação de entrada
	$isFirst = true;
	while($rs = mysql_fetch_object($sqlQuery)){
		if($isFirst){
			$maivc_id = $rs->mov_entr_id;
			$maivc = $rs->mov_entr_vcusto;
			$maivc_dt = $rs->mov_entr_nf_dt_emissao;			
			$isFirst = false;
		}else{
			if($maivc < $rs->mov_entr_vcusto){
				$maivc_id = $rs->mov_entr_id;
				$maivc = $rs->mov_entr_vcusto;
				$maivc_dt = $rs->mov_entr_nf_dt_emissao;
			}elseif($maivc == $rs->mov_entr_vcusto && $maivc_dt < $rs->mov_entr_nf_dt_emissao){
				$maivc_id = $rs->mov_entr_id;
				$maivc = $rs->mov_entr_vcusto;
				$maivc_dt = $rs->mov_entr_nf_dt_emissao;
			}
		}
	}
	
	//Valor de custo mais recente
	$sql = "SELECT mov_entr_id,mov_entr_nf_dt_emissao,mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$recvc = 0; // valor de custo de entrada mais recente
	$recvc_dt = 0; // data da nota fiscal da entrada
	$recvc_id = 0; // id da movimentação de entrada
	$isFirst = true;
	while($rs = mysql_fetch_object($sqlQuery)){
		if($isFirst){
			$recvc_id = $rs->mov_entr_id;
			$recvc = $rs->mov_entr_vcusto;
			$recvc_dt = $rs->mov_entr_nf_dt_emissao;			
			$isFirst = false;
		}else{
			if($recvc_dt < $rs->mov_entr_nf_dt_emissao){
				$recvc_id = $rs->mov_entr_id;
				$recvc = $rs->mov_entr_vcusto;
				$recvc_dt = $rs->mov_entr_nf_dt_emissao;
			}
		}
	}
	
	//Valor médio de custo
	$sql = "SELECT mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	$num_moventr = mysql_num_rows($sqlQuery); //quantidade de movimentações de entrada
	
	$totalvc = 0; // valor médio de custo de entrada mais recente	
	while($rs = mysql_fetch_object($sqlQuery)){
		$totalvc += $rs->mov_entr_vcusto;
	}
	
	$medvc = $totalvc/$num_moventr;
	
	
	###############################################################################
	
	//menor valor de venda
	$sql = "SELECT mov_sos_id,mov_sos_dt_saida,mov_sos_vvalor FROM mov_saida_os WHERE mov_sos_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$menvv = 0; // menor valor de venda
	$menvv_dt = 0; // data da saída
	$menvv_id = 0; // id da movimentação de saída
	$isFirst = true;
	while($rs = mysql_fetch_object($sqlQuery)){
		if($isFirst){
			$menvv_id = $rs->mov_sos_id;
			$menvv = $rs->mov_sos_vvalor;
			$menvv_dt = $rs->mov_sos_dt_saida;			
			$isFirst = false;
		}else{
			if($menvv > $rs->mov_sos_vvalor){
				$menvv_id = $rs->mov_sos_id;
				$menvv = $rs->mov_sos_vvalor;
				$menvv_dt = $rs->mov_sos_dt_saida;
			}elseif($menvv == $rs->mov_sos_vvalor && $menvv_dt < $rs->mov_sos_dt_saida){
				$menvv_id = $rs->mov_sos_id;
				$menvv = $rs->mov_sos_vvalor;
				$menvv_dt = $rs->mov_sos_dt_saida;
			}
		}
	}
	
	//maior valor de venda
	$sql = "SELECT mov_sos_id,mov_sos_dt_saida,mov_sos_vvalor FROM mov_saida_os WHERE mov_sos_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$maivv = 0; // maior valor de venda
	$maivv_dt = 0; // data da saida
	$maivv_id = 0; // id da movimentação de saída
	$isFirst = true;
	while($rs = mysql_fetch_object($sqlQuery)){
		if($isFirst){
			$maivv_id = $rs->mov_sos_id;
			$maivv = $rs->mov_sos_vvalor;
			$maivv_dt = $rs->mov_sos_dt_saida;			
			$isFirst = false;
		}else{
			if($maivv < $rs->mov_sos_vvalor){
				$maivv_id = $rs->mov_sos_id;
				$maivv = $rs->mov_sos_vvalor;
				$maivv_dt = $rs->mov_sos_dt_saida;
			}elseif($maivv == $rs->mov_sos_vvalor && $maivv_dt < $rs->mov_sos_dt_saida){
				$maivv_id = $rs->mov_sos_id;
				$maivv = $rs->mov_sos_vvalor;
				$maivv_dt = $rs->mov_sos_dt_saida;
			}
		}
	}
	
	
	//Valor médio de venda
	$sql = "SELECT mov_sos_vvalor FROM mov_saida_os WHERE mov_sos_prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	$num_movsos = mysql_num_rows($sqlQuery); //quantidade de movimentações de saída
	
	if($num_movsos > 0){
		$totalvv = 0;
		while($rs = mysql_fetch_object($sqlQuery)){
			$totalvv += $rs->mov_sos_vvalor;
		}	
		
		$medvv = $totalvv/$num_movsos;
	}
	
		
	
?>
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Produto: </b>#<?php echo $_GET["prod_id"]?></td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td>
            	<div id="moventrDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('moventr','moventrSign')">
                        <span id="moventrSign" title="Ampliar">
                            <img src="img/btn/menos.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Movimenta&ccedil;&otilde;es de entrada
                    </a>                    
                    <table id="moventr" border="0" style="width: 100%; display:block; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                            <td width="439px">
                                <a href="javascript:ajaxRequest('inc/view/view_moventr.php?mov_entr_id=<?php echo $menvc_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&atilde;o de entrada">
                                    <img src="img/btn/mov_in.png" width="16" height="16" border="0" />
                                </a>
                                <b>Menor valor de custo:</b> R$ <?php echo number_format($menvc,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:ajaxRequest('inc/view/view_moventr.php?mov_entr_id=<?php echo $maivc_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&atilde;o de entrada">
                                    <img src="img/btn/mov_in.png" width="16" height="16" border="0" />
                                </a>
                                <b>Maior valor de custo:</b> R$ <?php echo number_format($maivc,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:ajaxRequest('inc/view/view_moventr.php?mov_entr_id=<?php echo $recvc_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&atilde;o de entrada">
                                    <img src="img/btn/mov_in.png" width="16" height="16" border="0" />
                                </a>
                                <b>Valor de custo mais recente:</b> R$ <?php echo number_format($recvc,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr style="background-color: <?php echo $colors[5]?>;">
                            <td>            	
                                <b>Valor m&eacute;dio de custo:</b> R$ <?php echo number_format($medvc,2,",",".") ?>                
                            </td>
                        </tr>
                    </table>
                </div> 
            </td>
        </tr>
        <tr>
        	<td>
            	<div id="movsosDiv" style="background-color: <?php echo $colors[2]?>;
                			 border: solid 2px <?php echo $colors[2]?>;">
                    <a href="javascript:showHide('movsos','movsosSign')">
                        <span id="movsosSign" title="Ampliar">
                            <img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />
                        </span>
                        Movimenta&ccedil;&otilde;es de sa&iacute;da por ordem de servi&ccedil;o
                    </a>                    
                    <table id="movsos" border="0" style="width: 100%; display:none; background-color: <?php echo $colors[1]?>; margin-top: 4px">
                    	<tr>
                            <td width="439px">
                                <a href="javascript:ajaxRequest('inc/view/view_movsos.php?mov_saida_id=<?php echo $menvv_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&atilde;o de entrada">
                                    <img src="img/btn/mov_out_os.png" width="16" height="16" border="0" />
                                </a>
                                <b>Menor valor de venda:</b> R$ <?php echo number_format($menvv,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:ajaxRequest('inc/view/view_movsos.php?mov_saida_id=<?php echo $maivv_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&atilde;o de entrada">
                                    <img src="img/btn/mov_out_os.png" width="16" height="16" border="0" />
                                </a>
                                <b>Maior valor de venda:</b> R$ <?php echo number_format($maivv,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr>
                            <td>                                
                                <b>Valor de venda atual:</b> R$ <?php echo number_format($prod_rs->prod_vvalor,2,",",".") ?>                
                            </td>
                        </tr>
                        <tr style="background-color: <?php echo $colors[5]?>;">
                            <td>            	
                                <b>Valor m&eacute;dio de venda:</b> R$ <?php echo number_format($medvv,2,",",".") ?>                
                            </td>
                        </tr>
                    </table>
                </div> 
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td align="center">
            	<div>
                    <b>Nome:</b> <?php echo $prod_rs->prod_nome?>
                    <b>Modelo:</b> <?php echo $prod_rs->prod_modelo?>
                    <b>Fabricante:</b> <?php echo $prod_rs->prod_fabricante?>                                        
                </div>
            </td>
        </tr>       
     </table>   
</form>