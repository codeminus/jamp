<?php	
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	
	$sql = "SELECT usu_nome, cli_cp FROM usuario JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
	$sqlQuery = mysql_query($sql);
	$cli_rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT os_id FROM os WHERE os_cli_id=".$_GET["cli_id"];
	$os_sqlQuery = mysql_query($sql);
	
	$totalvc = 0;
	
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
	
?>
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Cliente: </b>#<?php echo $_GET["cli_id"]?></td>
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
                            	   onClick="ajaxRequest('inc/stat/process_stat_this_cli.php?cli_id=<?php echo $_GET["cli_id"]?>&dti='+document.forms['calc_custo_form'].dt_inicial.value+'&dtf='+document.forms['calc_custo_form'].dt_final.value, 'calc_result')" />
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
                    <b>Nome:</b> <?php echo $cli_rs->usu_nome?>
                    <b>CPF/CNPJ:</b> <?php echo $cli_rs->cli_cp?>
                </div>
            </td>
        </tr>       
     </table>   
</form>