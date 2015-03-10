<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");
	
?>
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Usu&aacute;rio: </b>#<?php echo $_GET["usu_id"]?></td>
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
        	<td align="center" valign="middle">
            	<?php
					$sql = "SELECT os_id FROM os WHERE os_sta_id=4 AND os_historico='N' AND os_tec_id=".$_GET["usu_id"];
					$sqlQuery = mysql_query($sql);
					$manutencao = mysql_num_rows($sqlQuery);
					
					$sql = "SELECT os_id FROM os WHERE os_sta_id=5 AND os_historico='N' AND os_tec_id=".$_GET["usu_id"];
					$sqlQuery = mysql_query($sql);
					$concluida = mysql_num_rows($sqlQuery);
					if($manutencao > 0 || $concluida > 0){
				?>                
                <img src="inc/dinimg/img_user.php?usu_id=<?php echo $_GET["usu_id"]?>" />
                <?php
                	}else{
				?>
						Informa&ccedil;&otilde;es insuficientes.
                 <?php
					}
				?>
            </td>
        </tr>
        <tr>
        	<td align="center">            
            <?php
            	$sql = "SELECT os_id, os_data_conclusao FROM os WHERE os_tec_id='".$_GET["usu_id"]."' AND os_sta_id=5";
				$sql_query = mysql_query($sql);
				$os_num = mysql_num_rows($sql_query);	
				if($os_num > 0){
					echo "<b>M&eacute;dia de tempo para resolu&ccedil;&atilde;o de uma OS:</b> ";					
					$i = 0;																
					while($rs_os = mysql_fetch_object($sql_query)){
					
						$os[$i]["id"] = $rs_os->os_id;
						$os[$i]["fim"] = $rs_os->os_data_conclusao;
					
						$sql = "SELECT ot_dt_inicio FROM osetec WHERE ot_os_id='".$rs_os->os_id."' AND ot_dt_fim is null ";
						$ot_sql_query = mysql_query($sql);
						
						$rs = mysql_fetch_object($ot_sql_query);					
						$os[$i]["inicio"] = $rs->ot_dt_inicio;
						$i++;
					}				
					
					$tmp_total = 0;
					
					for($i = 0; $i < $os_num; $i++){
						$tmp_total += $os[$i]["fim"]-$os[$i]["inicio"];
					}				
					$media = $tmp_total/$os_num; #segundos
					
					if($media <60){
						echo $media." segundo(s)";
					}elseif($media <= 3600){
						echo round($media/60,2)." minuto(s)";
					}elseif($media < 24*3600){					
						if(round($media/3600,2)-floor($media/3600) > 0 && round($media/3600,2)-floor($media/3600) < 1){
							if(round((round($media/3600,2)-floor($media/3600))*60) == 60){
								echo 1+floor($media/3600)." hora(s)";
							}else{
								echo floor($media/3600)." hora(s) e ".round((round($media/3600,2)-floor($media/3600))*60)." minuto(s)";
							}						
						}else{
							echo round($media/3600)." hora(s)";
						}
					}elseif($media < 7*24*3600){					
						if(round($media/(24*3600),2) - floor($media/(24*3600)) > 0 && round($media/(24*3600),2) - floor($media/(24*3600)) < 1){
							if(round((round($media/(24*3600),2) - floor($media/(24*3600)))*24) == 24){
								echo 1+floor($media/(24*3600))." dia(s)";
							}else{
								echo floor($media/(24*3600))." dia(s) e ".round((round($media/(24*3600),2) - floor($media/(24*3600)))*24)." hora(s)";
							}						
						}else{
							echo round($media/(24*3600))." dia(s)";
						}
					}elseif($media < 4*7*24*3600){					
						if((round($media/(7*24*3600),2)-floor($media/(7*24*3600))) > 0 && (round($media/(7*24*3600),2)-floor($media/(7*24*3600))) < 1){
							if(round((round($media/(7*24*3600),2)-floor($media/(7*24*3600)))*7) == 7){
								echo 1+floor($media/(7*24*3600))." semana(s)";
							}else{
								echo floor($media/(7*24*3600))." semana(s) e ".round((round($media/(7*24*3600),2)-floor($media/(7*24*3600)))*7)." dia(s)";
							}					
						}else{
							echo round($media/(7*24*3600))." semana(s)";
						}
					}else{
						if((round($media/(4*7*24*3600),2)-floor($media/(4*7*24*3600))) > 0 && (round($media/(4*7*24*3600),2)-floor($media/(4*7*24*3600))) < 1){
							if(round((round($media/(4*7*24*3600),2)-floor($media/(4*7*24*3600)))*4) == 4){
								echo 1+floor($media/(4*7*24*3600))." mes(es)";
							}else{
								echo floor($media/(4*7*24*3600))." mes(es) e ".round((round($media/(4*7*24*3600),2)-floor($media/(4*7*24*3600)))*4)." semana(s)";
							}					
						}else{
							echo round($media/(4*7*24*3600))." mes(es)";
						}
					}
				}			
			?>
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    	&nbsp;
                    </div>
                    <div align="center">                    
                    </div>
                    <div style="clear:both"></div>
                </div>
            </td>
        </tr>       
     </table>   
</form>