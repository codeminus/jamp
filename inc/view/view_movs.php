<?php	
	session_start();
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	$sql = "SELECT * FROM produto
			JOIN classprod ON prod_classprod_id=classprod_id
			WHERE prod_id=".$_GET["prod_id"];		
	$sqlQuery = mysql_query($sql);
	$prod_rs = mysql_fetch_object($sqlQuery);
	
	$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
	
				
	$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_sos = mysql_num_rows($mov_sqlQuery);		
	
	
	$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$prod_rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
	
		
	if($quant_mov_entr > 0){
		$lmoventr = "index.php?inc=lmoventr&amp;ot=0&amp;ord=1&amp;cmd=srch&amp;prod_id=".$prod_rs->prod_id."&amp;prod_nome=".$prod_rs->prod_nome."&amp;pg=1";
	}else{
		$lmoventr = "javascript:void(0)";
		$lmoventr_color = "color: ".$colors[4];
	}
	
	if($quant_mov_sos > 0){
		$lmovsos = "index.php?inc=lmovsos&amp;ot=0&amp;ord=1&amp;cmd=srch&amp;prod_id=".$prod_rs->prod_id."&amp;prod_nome=".$prod_rs->prod_nome."&amp;pg=1";
	}else{
		$lmovsos =	"javascript:void(0)";
		$lmovsos_color = "color: ".$colors[4];
	}
	
	if($quant_mov_susoint > 0){
		$lmovsusoint = "index.php?inc=lmovsusoint&amp;ot=0&amp;ord=1&amp;cmd=srch&amp;prod_id=".$prod_rs->prod_id."&amp;prod_nome=".$prod_rs->prod_nome."&amp;pg=1";
	}else{
		$lmovsusoint = "javascript:void(0)";
		$lmovsusoint_color = "color: ".$colors[4];
	}
	
?>
<form name="prod_form">    		
	<input type="hidden" name="prod_id" value="<?php echo $prod_rs->prod_id?>" />    
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Movimenta&ccedil;&otilde;es do produto:</b> #<?php echo $prod_rs->prod_id?></td>
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
            	<a href="<?php echo $lmoventr?>" 
                   title="Clique para listar estas movimenta&ccedil;&otilde;es" tabindex="1" class="ancorButton"
                   style="background-image: url(img/btn/mov_in.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $lmoventr_color?>">
                	Movimenta&ccedil;&otilde;es de entrada (<?php echo $quant_mov_entr?>)
                </a>
                <a href="<?php echo $lmovsusoint?>" 
                   title="Clique para listar estas movimenta&ccedil;&otilde;es" tabindex="2" class="ancorButton"
                   style="background-image: url(img/btn/mov_out.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $lmovsusoint_color?>">
					Movimenta&ccedil;&otilde;es de sa&iacute;da por uso interno (<?php echo $quant_mov_susoint?>)
                </a>
                <a href="<?php echo $lmovsos?>" 
                	title="Clique para listar estas movimenta&ccedil;&otilde;es" tabindex="3" class="ancorButton"
                   style="background-image: url(img/btn/mov_out_os.png); background-repeat: no-repeat; background-position: 4px 2px; <?php echo $lmovsos_color?>">
                	Movimenta&ccedil;&otilde;es de sa&iacute;da por ordem de servi&ccedil;o (<?php echo $quant_mov_sos?>)
                </a>
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    	<b>C&oacute;digo:</b> #<?php echo $prod_rs->prod_id?>
                    	<b>Classifica&ccedil;&atilde;o:</b> <?php echo $prod_rs->classprod_nome?>
                        <b>Produto:</b> <?php echo $prod_rs->prod_nome?>                        
                    </div>
                    <div align="center">
                    	<b>Modelo:</b> <?php echo $prod_rs->prod_modelo?>
                    	<b>Fabricante:</b> <?php echo $prod_rs->prod_fabricante?>                        
                    </div>
                    <div style="clear:both"></div>
                </div>
            </td>
        </tr>       
     </table>   
</form>