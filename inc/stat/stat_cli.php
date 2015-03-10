<?php	
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	
	$totalvc = 0;

	$orderType[0][0] = "usu_nome";
	$orderType[1][0] = "usu_email";
	$orderType[2][0] = "usu_end_estado";
	$orderType[3][0] = "usu_id";
	$orderType[4][0] = "cli_cp";
	$orderType[5][0] = "usu_tel";
	$orderType[6][0] = "usu_end_cidade";
	$orderType[7][0] = "usu_end_pais";
	$orderType[8][0] = "usu_end_logradouro";
	
	$sql = "SELECT usu_id FROM usuario
			JOIN cliente ON usu_id=cli_usu_id";
	
	
	if($_GET["cmd"] == "srch"){
	
		$fields = array_keys($_GET);
		$values = array_values($_GET);
		
		for($i = 2; $i < (count($_GET)-1); $i++){
			if($_GET[$fields[$i]] !=""){
				$sql .= " WHERE ";
				break;
			}
		}
		
		$isFirst = true;
		$searchStr = "&cmd=".$_GET["cmd"];
		for($i = 2; $i < (count($_GET)-1); $i++){
			if($_GET[$fields[$i]] !="" && $fields[$i] != "msg"){
				if($isFirst){
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
					$isFirst = false;
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$searchStr	.= "&".$fields[$i]."=".$values[$i];
			}
		}	
	
	}
	
	
	$cli_sqlQuery = mysql_query($sql);
	
	$totalvc = 0;
	
	while($rs = mysql_fetch_object($cli_sqlQuery)){
		
		$sql = "SELECT os_id FROM os WHERE os_cli_id=".$rs->usu_id;
		$os_sqlQuery = mysql_query($sql);		
		
		
		while($os_rs = mysql_fetch_object($os_sqlQuery)){
		
			$sql = "SELECT mov_sos_prod_id, mov_sos_vvalor,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_os_id=".$os_rs->os_id;
			$movsos_sqlQuery = mysql_query($sql);
			
			while($movsos_rs = mysql_fetch_object($movsos_sqlQuery)){
			
				$vcusto = 0;
				
				$sql = "SELECT prod_cvalor FROM produto WHERE prod_id=".$movsos_rs->mov_sos_prod_id;
				$sqlQuery = mysql_query($sql);
				$prod_rs = mysql_fetch_object($sqlQuery);
				
				if($prod_rs->prod_cvalor == 0 || $prod_rs->prod_cvalor == ""){
					$sql = "SELECT mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id." 
						AND  mov_entr_id = (SELECT MAX(mov_entr_id) FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id.")
						AND mov_entr_nf_dt_emissao = (SELECT MAX(mov_entr_nf_dt_emissao)FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id.")";
					$sqlQuery = mysql_query($sql);
					$moventr_rs = mysql_fetch_object($sqlQuery);
					
					$vcusto = $moventr_rs->mov_entr_vcusto;
					
				}else{				
					$vcusto = $prod_rs->prod_cvalor;
				}
				
				$totalvc += $vcusto*$movsos_rs->mov_sos_quant_saida;
				
			}
		}
	}
	
	
?>
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Estat&iacute;sticas </b></td>
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
            	<b>Custo total: </b>R$ <?php echo number_format($totalvc,2,",",".")?>
            </td>
        </tr>
        <tr>
        	<td>
            	<b>Custo total por per&iacute;odo: </b>
            </td>
        </tr>
        <tr>
        	<td>
            	<form name="calc_custo_form">
                <table width="100%">
                	<tr>
                    	<td>
                            Data inicial:<br/>
                            <input type="text" name="dt_inicial" style="width: 100px; text-align:center" disabled />
                            <a href="javascript:void(0)" onClick="displayCalendar(document.forms['calc_custo_form'].dt_inicial,'dd/mm/yyyy',this)" title="Adicionar data" tabindex="3" >
                                <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                            </a>
                    	</td>
                    	<td>
                            Data final:<br/>
                            <input type="text" name="dt_final" style="width: 100px; text-align:center" 
                            	   onchange="verifyDate(this,document.forms['calc_custo_form'].dt_inicial.value)" disabled />
                            <a href="javascript:void(0)" onClick="displayCalendar(document.forms['calc_custo_form'].dt_final,'dd/mm/yyyy',this)" title="Adicionar data" tabindex="3" >
                                <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                            </a>
                    	</td>
                        <td valign="bottom">
                        	<input type="button" value="calcular" style="background-color: <?php echo $colors[0]?>; color: <?php echo $colors[2]?>"
                            	   onClick="ajaxRequest('inc/stat/process_stat_cli.php?dti='+document.forms['calc_custo_form'].dt_inicial.value+'&dtf='+document.forms['calc_custo_form'].dt_final.value+'<?php echo $searchStr?>', 'calc_result')" />
                        </td>
                        <td  width="100px" align="right" valign="bottom" style="font-size: 16px">
                        	R$ <div id="calc_result" style="display:inline; font-size: 16px">0,00</div>
                        </td>
                    </tr>
                </table>    
                </form>                
            </td>            
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td align="center">
            	<div>
                    Valores obtidos a partir do resultado da pesquisa.
                </div>
            </td>
        </tr>       
     </table>   
</form>