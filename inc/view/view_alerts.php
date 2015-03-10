<?php
	@header("Content-Type: text/html; charset=ISO-8859-1",true);
	session_start();	
	require("../../css/_colors.php");
	require("../../sys/_dbconn.php");
	require("../../sys/_config.php");		
	$_SESSION["flag"] = "1";
	
	//Verificando produtos com nível baixo de estoque
	$sql = "SELECT prod_id FROM produto 
			WHERE prod_status='D'
			AND 
				(IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
			    IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
			    IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				
	$sqlQuery = mysql_query($sql);	
	$prod_num = mysql_num_rows($sqlQuery);
	
	if($prod_num > 0){
		$destino[0] = "index.php?inc=lprod&amp;ot=0&amp;ord=1&amp;cmd=srch&ating_min=A&amp;pg=1";
	}else{
		$destino[0] = "javascript:void(0)";
		$prod_color = "color: ".$colors[4];
	}
	
	//Verificando ordens de serviço aguardando autorização
	$sql = "SELECT os_id FROM os WHERE os_sta_id='1'";
	
	if($_SESSION["tec_tipo"] == "T" && $_SESSION["tec_view_os"] == "N"){
		$sql .= " AND os_tec_id=".$_SESSION["tec_id"];	
	}
	
	if($_SESSION["tec_tipo"] == "C"){
		$sql .= " AND os_cli_id=".$_SESSION["tec_id"];	
	}
	
	$sqlQuery = mysql_query($sql);
	$os_orcamento_num = mysql_num_rows($sqlQuery);
	
	if($os_orcamento_num > 0){
		$destino[1] = "index.php?inc=los&amp;ot=0&amp;ord=1&amp;cmd=srch&os_sta_id=1&amp;pg=1";
	}else{
		$destino[1] = "javascript:void(0)";
		$orcamento_color = "color: ".$colors[4];
	}
	
	
	//Verificando ordens de serviço não autorizadas
	$sql = "SELECT os_id FROM os WHERE os_sta_id='8'";
	
	if($_SESSION["tec_tipo"] == "T" && $_SESSION["tec_view_os"] == "N"){
		$sql .= " AND os_tec_id=".$_SESSION["tec_id"];	
	}
	
	if($_SESSION["tec_tipo"] == "C"){
		$sql .= " AND os_cli_id=".$_SESSION["tec_id"];	
	}
	
	$sqlQuery = mysql_query($sql);
	$os_nao_auto_num = mysql_num_rows($sqlQuery);
	
	if($os_nao_auto_num > 0){
		$destino[2] = "index.php?inc=los&amp;ot=0&amp;ord=1&amp;cmd=srch&os_sta_id=8&amp;pg=1";
	}else{
		$destino[2] = "javascript:void(0)";
		$nao_auto_color = "color: ".$colors[4];
	}
	
	
	//Verificando ordens de serviço encaminhadas ao suporte
	$sql = "SELECT os_id FROM os WHERE os_sta_id='6'";
			
	if($_SESSION["tec_tipo"] == "C"){
		$sql .= " AND os_cli_id=".$_SESSION["tec_id"];	
	}
	
	$sqlQuery = mysql_query($sql);
	$os_encaminhada_num = mysql_num_rows($sqlQuery);
	
	if($os_encaminhada_num > 0){
		$destino[3] = "index.php?inc=los&amp;ot=0&amp;ord=1&amp;cmd=srch&os_sta_id=6&amp;pg=1";
	}else{
		$destino[3] = "javascript:void(0)";
		$encaminhada_color = "color: ".$colors[4];
	}
	
?>
<table id="cliview" class="alignleft" align="center" width="550px">
    <tr>
        <td colspan="2">
            <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color: <?php echo $colors[2]?>;">
                    <td style="padding: 2px">
                        <b>Alertas</b> 
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
   <tr>
        <td colspan="2" align="center">
            <?php if($_SESSION["tec_cad_prod"] == "S"){?>
            <a href="<?php echo $destino[0]?>" 
               title="Clique para listar estes produtos" tabindex="1" class="ancorButton"
               style="background-image: url(img/btn/prod_list.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $prod_color?>">
                Produtos com n&iacute;vel baixo de estoque (<?php echo $prod_num?>)
            </a>
            <?php }?>            
            <a href="<?php echo $destino[1]?>" 
               title="Clique para listar estas ordens de servi&ccedil;o" tabindex="2" class="ancorButton"
               style="background-image: url(img/btn/orcamento.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $orcamento_color?>">
                Ordens de servi&ccedil;o aguardando autoriza&ccedil;&atilde;o (<?php echo $os_orcamento_num?>)
            </a>
            <a href="<?php echo $destino[2]?>" 
                title="Clique para listar estas ordens de servi&ccedil;o" tabindex="3" class="ancorButton"
               style="background-image: url(img/btn/orcamentoj.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $nao_auto_color?>">
                Ordens de servi&ccedil;o n&atilde;o autorizadas (<?php echo $os_nao_auto_num?>)
            </a>
            <?php if($_SESSION["tec_view_os"] == "S" || $_SESSION["tec_tipo"] == "C"){?>
            <a href="<?php echo $destino[3]?>" 
                title="Clique para listar estas ordens de servi&ccedil;o" tabindex="4" class="ancorButton"
               style="background-image: url(img/btn/encaminhado.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $encaminhada_color?>">
                Ordens de servi&ccedil;o encaminhadas ao suporte (<?php echo $os_encaminhada_num?>)
            </a>
            <?php }?>
        </td>
    </tr>
    <tr style="background-color: <?php echo $colors[2]?>;">
        <td colspan="2" align="right">&nbsp;</td>
    </tr>       
 </table>