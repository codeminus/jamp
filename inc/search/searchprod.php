<?php
@header("Content-Type: text/html; charset=ISO-8859-1",true);
require("../../sys/_dbconn.php");
require("../../css/_colors.php");	


if($_GET["table"] == "produtos"){
		$sql = "SELECT 
					prod_id, 
					prod_nome, 
					prod_fabricante, 
					prod_modelo,
					prod_vvalor,					
					classprod_nome,
					prod_min_quant,
					prod_unid_medida
				FROM 
					produto
				JOIN 
					classprod ON prod_classprod_id=classprod_id
				WHERE 
					prod_status = 'D'
				AND (
					prod_id LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					prod_id LIKE '%".$_GET["searchkey"]."%' OR
					prod_nome LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					prod_nome LIKE '%".$_GET["searchkey"]."%' OR
					prod_fabricante LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					prod_fabricante LIKE '%".$_GET["searchkey"]."%' OR
					prod_modelo LIKE '%".utf8_decode($_GET["searchkey"])."%' OR
					prod_modelo LIKE '%".$_GET["searchkey"]."%' OR
					classprod_nome LIKE '%".$_GET["searchkey"]."%'
					
					)
				ORDER BY prod_nome";
					
		$sqlQuery = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($sqlQuery) > 0){			
						
?>			
			<table width="100%" cellpadding="2" cellspacing="0">
			<tr style="background-color: <?php echo $colors[2]?>">
                    	<th align="center">Cod.</th>
                        <th>Classifica&ccedil;&atilde;o</th>
						<th>Produto</th>
						<th>Fabricante</th>
						<th>Modelo</th>
                        <th>Valor</th>
                        <th align="center" width="75px">Quant.</th>                        
                    </tr>
<?php			
			$isColor = false;		
			while($rs = mysql_fetch_object($sqlQuery)){	
			
				$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
				$quant_entrada = 0;
				while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
					$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
				}
				
				$quant_saida = 0;
							
				$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
				
				while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
					$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
				}					
				
				$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
				while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
					$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
				}
				
				$quant_disponivel = $quant_entrada - $quant_saida;
				
				($quant_disponivel > $rs->prod_min_quant)? $linecolor = "color: #F00" : $linecolor = "";
			
				($isColor)? $color = $colors[3] : $color = $colors[1];
				
				$nome = $rs->classprod_nome." - ".$rs->prod_nome;
				
				$descricao = "<b>C&oacute;digo:</b> ".$rs->prod_id.							 
							 " <b>Fabricante:</b> ".$rs->prod_fabricante.
							 " <b>Modelo:</b> ".$rs->prod_modelo;
?>			
				<?php if($quant_disponivel == 0){?>
                
                <tr bgcolor="<?php echo $color?>" style="color: <?php echo $colors[4]?>" 
                	onmouseover="trFocus(this,'<?php echo $colors[5]?>')" onmouseout="trBlur(this,'<?php echo $color?>')">
                    
				<?php }elseif($quant_disponivel > $rs->prod_min_quant){?>
                
                <tr bgcolor="<?php echo $color?>"
                	onclick="addItem('prodResult','produtos','quantProd','prod_','<?php echo $rs->prod_id?>','<?php echo $nome?>','<?php echo $descricao?>','<?php echo $quant_disponivel?>','<?php echo number_format($rs->prod_vvalor,2,",",".")?>', '<?php echo $rs->prod_unid_medida?>', '<?php echo $colors[1]?>', '<?php echo $colors[3]?>', '<?php echo $colors[5]?>')"
                    onmouseover="trFocus(this,'<?php echo $colors[5]?>'),this.style.cursor='pointer'" onmouseout="trBlur(this,'<?php echo $color?>')">
                    
                <?php }elseif($quant_disponivel <= $rs->prod_min_quant){?>
                
                <tr bgcolor="<?php echo $color?>" style="color: #F00"
                	onclick="addItem('prodResult','produtos','quantProd','prod_','<?php echo $rs->prod_id?>','<?php echo $nome?>','<?php echo $descricao?>','<?php echo $quant_disponivel?>','<?php echo number_format($rs->prod_vvalor,2,",",".")?>', '<?php echo $rs->prod_unid_medida?>', '<?php echo $colors[1]?>', '<?php echo $colors[3]?>', '<?php echo $colors[5]?>')"
                    onmouseover="trFocus(this,'<?php echo $colors[5]?>'),this.style.cursor='pointer'" onmouseout="trBlur(this,'<?php echo $color?>')">                   
                <?php }?>              
					
                    <td align="center"><?php echo $rs->prod_id?></td>
                    <td>
                    	<span title="<?php echo $rs->classprod_nome?>">
							<?php 
                                echo substr($rs->classprod_nome,0,20);
                                if(strlen($rs->classprod_nome) > 20){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>
                    <td>
                    	<span title="<?php echo $rs->prod_nome?>">
							<?php 
                                echo substr($rs->prod_nome,0,20);
                                if(strlen($rs->prod_nome) > 20){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>
                    <td>
                    	<span title="<?php echo $rs->prod_fabricante?>">
							<?php 
                                echo substr($rs->prod_fabricante,0,20);
                                if(strlen($rs->prod_fabricante) > 20){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>
                    <td>
                    	<span title="<?php echo $rs->prod_modelo?>">
							<?php 
                                echo substr($rs->prod_modelo,0,20);
                                if(strlen($rs->prod_modelo) > 20){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>                    
                    <td>R$ <?php echo number_format($rs->prod_vvalor,2,",",".")?></td>
                    <td align="center"><?php echo $quant_disponivel." ".$rs->prod_unid_medida?></td>
                </tr>
<?php
				$isColor = !$isColor;
				
			}
?>
			</table>
<?php
			
		}
		
}
?>