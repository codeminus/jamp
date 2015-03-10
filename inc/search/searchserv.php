<?php
@header("Content-Type: text/html; charset=ISO-8859-1",true);
require("../../sys/_dbconn.php");
require("../../css/_colors.php");	


if($_GET["table"] == "servicos"){
		$sql = "SELECT 
					serv_id, 
					serv_nome,
					serv_desc,
					serv_valor,
					serv_unid_medida
				FROM 
					servico				
				WHERE 
					serv_status = 'A'
				AND (
					serv_id LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					serv_id LIKE '%".$_GET["searchkey"]."%' OR
					serv_nome LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					serv_nome LIKE '%".$_GET["searchkey"]."%' OR
					serv_desc LIKE '%".utf8_decode($_GET["searchkey"])."%' OR 
					serv_desc LIKE '%".$_GET["searchkey"]."%'
					)
				ORDER BY serv_nome";
					
		$sqlQuery = mysql_query($sql) or die(mysql_error());
				
		if(mysql_num_rows($sqlQuery) > 0){
						
?>			
			<table width="100%" cellpadding="2" cellspacing="0">
			<tr style="background-color: <?php echo $colors[2]?>">
                    	<th align="center">Cod.</th>                        
						<th>Servi&ccedil;o</th>
                        <th>Descri&ccedil;&atilde;o</th>
                        <th>Valor</th>
                    </tr>
<?php			
			$isColor = false;		
			while($rs = mysql_fetch_object($sqlQuery)){	
			
				($isColor)? $color = $colors[3] : $color = $colors[1];
				$descricao = '<b>C&oacute;digo: </b>'.$rs->serv_id." <b>Descri&ccedil;&atilde;o: </b> ".substr($rs->serv_desc,0,100);
?>			
				                
                <tr bgcolor="<?php echo $color?>"
                	onclick="addItem('servResult','servicos','quantServ','serv_','<?php echo $rs->serv_id?>','<?php echo $rs->serv_nome?>','<?php echo $descricao?>','99999999999','<?php echo number_format($rs->serv_valor,2,",",".")?>','<?php echo $rs->serv_unid_medida?>', '<?php echo $colors[1]?>', '<?php echo $colors[3]?>', '<?php echo $colors[5]?>')"
                    onmouseover="trFocus(this,'<?php echo $colors[5]?>'),this.style.cursor='pointer'" onmouseout="trBlur(this,'<?php echo $color?>')">                   
                
					
                    <td align="center"><?php echo $rs->serv_id?></td>                    
                    <td>
                    	<span title="<?php echo $rs->serv_nome?>">
							<?php 
                                echo substr($rs->serv_nome,0,30);
                                if(strlen($rs->serv_nome) > 30){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>
                    <td>
                    	<span title="<?php echo $rs->serv_desc?>">
							<?php 
                                echo substr($rs->serv_desc,0,60);
                                if(strlen($rs->serv_desc) > 60){
                                    echo "...";
                                }
                            ?>
                        </span>						
                    </td>
                    <td>R$ <?php echo number_format($rs->serv_valor,2,",",".")." por ".$rs->serv_unid_medida?></td>                    
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