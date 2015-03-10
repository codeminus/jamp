<?php 

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}

						
$orderType[0][0] = "os_id";
$orderType[1][0] = "usu_nome";
$orderType[2][0] = "equ_nome";
$orderType[3][0] = "equ_num_patrimonio";
$orderType[4][0] = "equ_num_serie";
$orderType[5][0] = "os_data_abertura";
$orderType[6][0] = "sta_nome";
$orderType[7][0] = "os_com_remocao";
$orderType[8][0] = "usu_nome";
$orderType[9][0] = "equ_id";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}

$limitation = " AND os_historico='N'";

if($_SESSION["tec_view_os"] == "N"){
	$limitation .= " AND os_tec_id=".$_SESSION["tec_id"];					
}
if($_SESSION["tec_tipo"] == "C"){
	$limitation .= " AND os_cli_id=".$_SESSION["tec_id"];					
}



$sql = "SELECT * FROM os 
				LEFT JOIN os_serv ON os_serv_os_id=os_id
				LEFT JOIN mov_saida_os ON mov_sos_os_id=os_id
				JOIN usuario ON os_cli_id=usu_id
				JOIN equipamento ON os_equ_id=equ_id
				JOIN os_status ON os_sta_id=sta_id";

$searchStr = "";
if($_POST["cmd"] == "srch"){
	
	$fields = array_keys($_POST);
	$values = array_values($_POST);
	
	for($i = 1; $i < (count($_POST)); $i++){
		
		if($_POST[$fields[$i]] != ""){
			$sql .= " WHERE ";
			break;			
		}
	}

	
	$isFirst = true;
	$searchStr = "&cmd=".$_POST["cmd"];
	for($i =1; $i < (count($_POST)); $i++){
		if($_POST[$fields[$i]] !=""){
			$notEmpty = true;
			if($isFirst){
				if($fields[$i] == "os_dia_abertura"){
					
					$dt = split("/",$values[$i]);
					
					$sql .= $fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "os_dia_abertura"){
					$dt = split("/",$values[$i]);
					
					$sql .= " AND ".$fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}elseif($_GET["cmd"] == "srch"){	
	
	$fields = array_keys($_GET);
	$values = array_values($_GET);
	(strpos($_SERVER['REQUEST_URI'],"ord") > -1)? $begin = 4: $begin = 3;
	for($i =$begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !=""){
			$sql .= " WHERE ";
			break;
		}
	}
	
	$isFirst = true;
	$searchStr = "&cmd=".$_GET["cmd"];
	for($i =$begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg" ){			
			if($isFirst){
				if($fields[$i] == "os_dia_abertura"){
					
					$dt = split("/",$values[$i]);
					
					$sql .= $fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "os_dia_abertura"){
					$dt = split("/",$values[$i]);
					
					$sql .= " AND ".$fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}


$_SESSION["srch"] = $searchStr;

$sql .= $limitation;

$sql .= "  GROUP BY os_id";

$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_SESSION[$orderType[$_GET["ot"]][0]];



if(isset($_GET["pg"])){
	$pg = $_GET["pg"];
}else{
	$pg = 1;
}
$usu_sql = "SELECT usu_reg_per_page FROM usuario WHERE usu_id=".$_SESSION["tec_id"];
$sqlQuery = mysql_query($usu_sql);
$usu_rs = mysql_fetch_object($sqlQuery);

$pm = new PagingMaker($sql, $usu_rs->usu_reg_per_page, $_GET["pg"]);
	
?>
<div id="conteudo" align="center">
	<div>
    	<div align="left" style=" float:left">
            <?php require("inc/_menu.php")?>
        </div>    	
    	<div align="right" style="padding: 2px; float: right">
        	<?php require("inc/_pmenu.php")?>        	
        </div>
        <div id="sform" style="display:none; clear:both">
    	<form name="search_form" action="index.php?inc=los&amp;ot=5"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	OSID:<br/>
                    <input type="text" name="os_id" value="<?php echo $_POST["os_id"].$_GET["os_id"]?>" style="width:120px" />
				</td>
                <td>
                	Status:<br/>
                    <select name="sta_id" style="width: 124px">
                    	<option value=""></option>
                        <option value="1">Aguardando autoriza&ccedil;&atilde;o</option>
                        <option value="8">N&atilde;o autorizada</option>
                    	<option value="6">Encaminhada ao suporte</option>                        
                        <option value="4">Em manuten&ccedil;&atilde;o</option>
                        <option value="5">Conclu&iacute;da</option>
                    </select>
				</td>
                <td>
                	Local:<br/>
                    <select name="os_com_remocao" style="width: 124px" >
                    	<option value=""></option>
                    	<option value="N">No local</option>
                        <option value="S">Removido do local</option>
                    </select>
				</td>
                <td>
                	Data de abertura:<br/>
                    <input type="text" name="os_dia_abertura" value="<?php echo $_POST["os_dia_abertura"].$_GET["os_dia_abertura"]?>" 
                    	   style="width:120px; text-align:center"  />
                    <a href="javascript:void(0)" onClick="displayCalendar(document.forms['search_form'].os_dia_abertura,'dd/mm/yyyy',this)" title="Adicionar data">
                        <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                    </a>
				</td>
                <td>                	
                    <?php
						if($_SESSION["tec_tipo"] == "T"){
							$sql = "SELECT usu_id,usu_nome FROM usuario WHERE usu_tipo='T' ORDER BY usu_nome ASC";
							$sqlQuery = mysql_query($sql);
					?>	
                    	T&eacute;cnico:<br/>
						<select name="os_tec_id" style="width: 124px">
                        	<option value=""></option>
					<?php    
							while($teclist_rs = mysql_fetch_object($sqlQuery)){
								echo("<option value=\"".$teclist_rs->usu_id."\">".$teclist_rs->usu_nome."</option>");
							}
						}
					?>
						</select>
				</td>
                <td>
                	Cod. Servi&ccedil;o:<br/>
                    <input type="text" name="os_serv_serv_id" value="<?php echo $_POST["os_serv_serv_id"].$_GET["os_serv_serv_id"]?>" style="width:120px" />
				</td>
                <td>
                	Cod. Produto:<br/>
                    <input type="text" name="mov_sos_prod_id" value="<?php echo $_POST["mov_sos_prod_id"].$_GET["mov_sos_prod_id"]?>" style="width:120px" />
				</td>
           </tr>
           <tr class="fldField">
           		<?php if($_SESSION["tec_tipo"] == "T"){?>
           		<td>
                	Cliente:<br/>
                    <input type="text" name="usu_nome" value="<?php echo $_POST["usu_nome"].$_GET["usu_nome"]?>" style="width:120px" />
				</td>
                <?php } ?>
                <td>
                	EQUID:<br/>
                    <input type="text" name="equ_id" value="<?php echo $_POST["equ_id"].$_GET["equ_id"]?>" style="width:120px" />
				</td>
                <td>
                	Nome do equipameto:<br/>
                    <input type="text" name="equ_nome" value="<?php echo $_POST["equ_nome"].$_GET["equ_nome"]?>" style="width:120px" />
				</td>
				<td>
                	Num. Patrim&ocirc;nio:<br/>
                    <input type="text" name="equ_num_patrimonio" value="<?php echo $_POST["equ_num_patrimonio"].$_GET["equ_num_patrimonio"]?>" style="width:120px" />
				</td>
				<td>
                	Num. S&eacute;rie:<br/>
                    <input type="text" name="equ_num_serie" value="<?php echo $_POST["equ_num_serie"].$_GET["equ_num_serie"]?>" style="width:120px" />
				</td>
                <td>
                	Observa&ccedil;&otilde;es:<br/>
                    <input type="text" name="os_tec_obs" value="<?php echo $_POST["os_tec_obs"].$_GET["os_tec_obs"]?>" style="width:120px" />
				</td>
                <td valign="bottom" style="text-align:right">
                	<input type="submit" value="pesquisar" 
                    	   style="background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>" />
                </td>                
			</tr>
		</table>
        </form>
        </div>
        <div style="clear:both"></div>
	</div>
    <div id="session">
    	<div id="sessionlabel">Ordens de Servi&ccedil;o</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=los&amp;ot=5" style="color: <?php echo $colors[5]?>;">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <a href="javascript:ajaxRequest('inc/stat/stat_os.php?os_id=<?php echo $rs->os_id?>','ajaxContent')" style="color: <?php echo $colors[5]?>;">
            	<img src="img/btn/grafico.png" title="Estat&iacute;sticas" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_os.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','os.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php } ?>
        </div>
        <div style="clear:both"></div>
    </div>
    <div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> ordens de servi&ccedil;o em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=los&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>	
    <table id="listing" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
    	<tr style="background-image:url(img/bar.jpg);" height="27">
        	<td style="background-image:url(img/bar_side_l.jpg); background-repeat: no-repeat;" width="15px">&nbsp;</td>
        	<td align="center" width="45px">
	            <a href="index.php?inc=los&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo id da ordem de servi&ccedil;o">
                   OSID
                   <?php 
						if($_GET["ot"] == 0 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 0 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="105px">
            	<a href="index.php?inc=los&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar pela data de abertura da ordem de servi&ccedil;o">
                   Abertura
                   <?php 
						if($_GET["ot"] == 5 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 5 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>       
            <td align="left">
	            <a href="index.php?inc=los&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo nome do cliente">
                   Cliente
                   <?php 
						if($_GET["ot"] == 1 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 1 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
                   
            </td>
            <td align="center" width="50px">
            	<a href="index.php?inc=los&amp;ot=9&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo id do equipamento">
                   EQUID
                   <?php 
						if($_GET["ot"] == 9 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 9 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="left">
            	<a href="index.php?inc=los&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo nome de equipamento">
                   Equipamento
                   <?php 
						if($_GET["ot"] == 2 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 2 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
        	<td align="center">
	            <a href="index.php?inc=los&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo n&uacute;mero de patrim&ocirc;nio">
                   Num. Patrim&ocirc;nio
                   <?php 
						if($_GET["ot"] == 3 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 3 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
                   
            </td>
        	<td align="center">
	            <a href="index.php?inc=los&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar pelo n&uacute;mero de s&eacute;rie">
                   Num. S&eacute;rie
                   <?php 
						if($_GET["ot"] == 4 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 4 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td align="left">
            	<!--<a href="index.php?inc=los&ot=8<?php echo $searchStr?>&pg=<?php echo $pm->getCp()?>" 
                   title="Organizar pelo nome do t&eacute;cnico">-->T&eacute;cnico
            </td>
            <td align="center" width="50px">
            	<a href="index.php?inc=los&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar pelo status da ordem de servi&ccedil;o">
                   Status
                   <?php 
						if($_GET["ot"] == 6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="50px">
            	<a href="index.php?inc=los&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar pela localiza&ccedil;&atilde;o do equipamento">
                   Local
                   <?php 
						if($_GET["ot"] == 7 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 7 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"13\" style=\"padding: 30px\" align=\"center\">Nenhuma ordem de servi&ccedil;o encontrada.</td></tr>";
		}else{
			
			$isColor = true;
			while($rs = $pm->getResultObject()){
				if($isColor){
					$color = $colors[3];
				}else{
					$color = $colors[1];
				}
			?>                
			<tr bgcolor="<?php echo $color?>" onmouseover="trFocus(this,'#FFFF99')" 
				onmouseout="trBlur(this,'<?php echo $color?>')">
				<td>&nbsp;</td>
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->os_id?></font></td>
                <td align="center">										
					<font style="font-size: 6pt"><?php echo date("H:i:s",$rs->os_data_abertura)?></font>&nbsp;
                    <?php echo date("d-m-y",$rs->os_data_abertura)?>
				</td>           
                <td align="left">
                	<span title="<?php echo $rs->usu_nome?>">
						<?php 
							echo substr($rs->usu_nome,0,35);
							if(strlen($rs->usu_nome) > 35){
								echo "...";
							}
						?>
                    </span>
                </td>
                <td align="center"><?php echo $rs->equ_id?></td>
                <td align="left">
					<span title="<?php echo $rs->equ_nome?>">
						<?php 
							echo substr($rs->equ_nome,0,25);
							if(strlen($rs->equ_nome) > 25){
								echo "...";
							}
						?>
                    </span>
                </td>
				<td align="center">
                	<span style="font-size: 7pt" title="<?php echo $rs->equ_num_patrimonio?>">
						<?php 
							echo substr($rs->equ_num_patrimonio,0,20);
							if(strlen($rs->equ_num_patrimonio) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>
				<td align="center">
					<span style="font-size: 7pt" title="<?php echo $rs->equ_num_serie?>">
						<?php 
							echo substr($rs->equ_num_serie,0,20);
							if(strlen($rs->equ_num_serie) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>
                <td align="left">
                	<?php 						
						$sql = "SELECT ot_tec_id FROM osetec 
								WHERE ot_os_id=".$rs->os_id." 
								AND ot_dt_inicio=(SELECT MAX(ot_dt_inicio) FROM osetec WHERE ot_os_id=".$rs->os_id.")";					
					
						$ot_sqlQuery = mysql_query($sql);
						$ot_rs = mysql_fetch_object($ot_sqlQuery);
						$tec_atual_id = $ot_rs->ot_tec_id;
					
						$sql = "SELECT usu_nome FROM usuario WHERE usu_id='".$tec_atual_id."'";
						$tec_sqlQuery = mysql_query($sql) or die(mysql_error());
						$tec_rs = mysql_fetch_object($tec_sqlQuery);
						if($tec_rs->usu_nome != ""){
					?>
                	<span title="<?php echo $tec_rs->usu_nome?>">
						<?php 
                            $usu_nome =  split(" ",$tec_rs->usu_nome);
                            echo $usu_nome[0];
                            echo " ".substr($usu_nome[1],0,1);
							echo " ".substr($usu_nome[2],0,1);
                        ?>
                    </span>
                    <?php } ?>
                </td>
                <td align="center">
					<?php 
						switch($rs->sta_id){
							case 1 : echo '<img src="img/btn/orcamento.png" title="Aguardando autoriza&ccedil;&atilde;o do cliente"
												width="16" height="16" border="0" />';
													break;
													
							case 4 : echo '<img src="img/btn/manutencao.png" title="em manuten&ccedil;&atilde;o"
												width="16" height="16" border="0" />';
													break;
							
							case 5 : echo '<img src="img/btn/manutencao_d.png" title="conclu&iacute;da"
												width="16" height="16" border="0" />';
													break;
							
							case 6 : echo '<img src="img/btn/encaminhado.png" title="encaminhada ao suporte"
												width="16" height="16" border="0" />';
													break;
							case 8 : echo '<img src="img/btn/orcamentoj.png" title="Ordem de servi&ccedil;o n&atilde;o autorizada"
												width="16" height="16" border="0" />';
													break;
						}
					?>
                </td>
                <td align="center">
					<?php 
						switch($rs->os_com_remocao){
							case "N": echo '<img src="img/btn/local.png" title="Equipamento no local"
												width="16" height="16" border="0" />';
													break;
													
							case "S": echo '<img src="img/btn/rlocal.png" title="Equipamento removido do local"
												width="16" height="16" border="0" />';
													break;
						}
					?>
                </td>
				<td align="center">
                <?php if($_SESSION["tec_tipo"] == "T"){?>
					<a href="javascript:ajaxRequest('inc/view/view_os_tec.php?os_id=<?php echo $rs->os_id?>&inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                <?php }else{?>
                	<a href="javascript:ajaxRequest('inc/view/view_os_cli.php?os_id=<?php echo $rs->os_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                <?php } ?>
                    <?php 
					//verificando a existência de relatório sobre esta os					
					$sql = "SELECT * FROM os_relatorio WHERE rel_os_id=".$rs->os_id;
					$sqlquery2 = mysql_query($sql);
					$rs_tec = mysql_fetch_object($sqlquery2);
					$rel_nums = mysql_num_rows($sqlquery2);
					
					if($rel_nums > 0){
                    	if($rs_tec->rel_tec_id == $_SESSION["tec_id"] && $rs->os_data_conclusao == ""){?>
                            <a href="javascript:ajaxRequest('inc/osReport.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" 
                               title="Adicionar/Visualizar relat&oacute;rios">
                                <img src="img/btn/relatorio_a.png" width="16" height="16" border="0" />
                            </a>	    
                        <?php }else{?>
                            <a href="javascript:ajaxRequest('inc/osReport.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" 
                               title="<?php echo $rel_nums?> relat&oacute;rio(s) gerado(s)">
                                <img src="img/btn/relatorio.png" width="16" height="16" border="0" />
                            </a>
                    <?php
						 	  }							  
					}else{
						$sql = "SELECT os_tec_id FROM os WHERE os_id=".$rs->os_id;
						$sqlquery3 = mysql_query($sql);
						$rs_tec = mysql_fetch_object($sqlquery3);
                    	if($rs_tec->os_tec_id == $_SESSION["tec_id"] && $rs->os_data_conclusao == ""){?>
                            <a href="javascript:ajaxRequest('inc/osReport.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" 
                               title="Adicionar/Visualizar relat&oacute;rios">
                                <img src="img/btn/relatorio_da.png" width="16" height="16" border="0" />
                            </a>	    
                    <?php }else{?>
                 		   <img src="img/btn/relatorio_d.png" width="16" height="16" border="0" title="Nenhum relat&oacute;rio criado" />
                    <?php 	}
					} ?>
					<?php 
					
					//se existe alguma pendência
					$sql = "SELECT pend_id FROM os_pendencia 
							WHERE pend_os_id=".$rs->os_id;
					$pend = mysql_num_rows(mysql_query($sql));
					
					if($pend > 0){
						//se existe pendência em aberto
						$sql = "SELECT pend_id FROM os_pendencia 
								WHERE pend_os_id=".$rs->os_id." AND (pend_status='A' OR pend_status='ACT' OR pend_status='RNS')";
						$pend_a = mysql_num_rows(mysql_query($sql));
						
						if($pend_a > 0){?>
                            <a href="javascript:ajaxRequest('inc/osPending.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" title="Existem pelos menos uma pend&ecirc;ncia n&atilde;o atendida">
                                <img src="img/btn/pend_a.png" width="16" height="16" border="0" />
                            </a> 					
					<?php
						}elseif($_SESSION["tec_id"] == $rs->os_tec_id && $rs->os_data_conclusao ==""){
					?>
                    		<a href="javascript:ajaxRequest('inc/osPending.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" title="Adicionar/Visualizar pend&ecirc;ncias">
                                <img src="img/btn/pend_add.png" width="16" height="16" border="0" />
                            </a>
					<?php	
						}else{
					?>
                    		<a href="javascript:ajaxRequest('inc/osPending.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" title="Todas as pend&ecirc;ncias foram atendidas">
                                <img src="img/btn/pend.png" width="16" height="16" border="0" />
                            </a>
                    <?php	
						}
					}elseif($_SESSION["tec_id"] == $rs->os_tec_id && $rs->os_data_conclusao ==""){
					?>
                    	<a href="javascript:ajaxRequest('inc/osPending.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>&tec_id=<?php echo $rs->os_tec_id?>','ajaxContent')" title="Adicionar/Visualizar pend&ecirc;ncias">
                            <img src="img/btn/pend_add_d.png" width="16" height="16" border="0" />
                        </a>
                    <?php 
					}else{
					?>
                    	<img src="img/btn/pend_d.png" width="16" height="16" border="0" title="Sem pend&ecirc;ncias" />
                    <?php } ?>
                    <?php 
						$sql = "SELECT ot_tec_id FROM osetec 
								WHERE ot_tec_id=".$_SESSION["tec_id"]." 
								AND ot_os_id=".$rs->os_id;
						$ot_sqlQuery = mysql_query($sql);
						$aot_num = mysql_num_rows($ot_sqlQuery);
						
						$sql = "SELECT ot_tec_id FROM osetec 								
								WHERE ot_os_id=".$rs->os_id."
								AND ot_tec_id<>''";
						$ot_sqlQuery = mysql_query($sql);
						$ot_num = mysql_num_rows($ot_sqlQuery);
						
						if($tec_atual_id == $_SESSION["tec_id"]){
					?>
                        <a href="javascript:ajaxRequest('inc/ostec.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&tec_id=<?php echo $rs->os_tec_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
                           title="Sua responsabilidade">
                            <img src="img/btn/responsavel.png" width="16" height="16" border="0" />
                        </a>
                    <?php 
						}elseif ($aot_num > 0 && $tec_atual_id <> $_SESSION["tec_id"]){						
					?>
                    	<a href="javascript:ajaxRequest('inc/ostec.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&tec_id=<?php echo $rs->os_tec_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
                           title="Voc&ecirc; j&aacute; foi respons&aacute;vel">
                            <img src="img/btn/aresponsavel.png" width="16" height="16" border="0" />
                        </a>
                    <?php 
						}elseif($ot_num == 0 ){
							if($_SESSION["tec_atrib_os"] == "S"){
								if($rs->os_orc_usu_id != "" && $rs->os_orc_usu_id != "0"){
					?>
                                <a href="javascript:ajaxRequest('inc/ostec.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&tec_id=<?php echo $rs->os_tec_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
                                   title="Atribuir respons&aacute;vel">
                                    <img src="img/btn/tresponsavel.png" width="16" height="16" border="0" />
                                </a>
					<?php
								}else{
					?>
                    				<img src="img/btn/sresponsavel.png" width="16" height="16" border="0"
                                     title="A ordem de servi&ccedil;o precisa ser autorizada primeiro" />
                    <?php				
								}
							}else{
					?>
                                <img src="img/btn/sresponsavel.png" width="16" height="16" border="0"
                                     title="Sem permiss&atilde;o para atribuir respons&aacute;vel" />
					<?php		
							}
					?>
                    <?php }else{
						($_SESSION["tec_tipo"] == "T")? $resp_title = "Responsabilidade de outro t&eacute;cnico": $resp_title = "Visualizar responsabilidades t&eacute;cnicas";
					?>
                    		   <a href="javascript:ajaxRequest('inc/ostec.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&tec_id=<?php echo $rs->os_tec_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
                                   title="<?php echo $resp_title?>">
                                    <img src="img/btn/oresponsavel.png" width="16" height="16" border="0" />
                                </a>
                    <?php } ?>
                    <?php  if($_SESSION["tec_tipo"] == "T"){?>
                         <a href="inc/pdf/pdf_os.php?id=<?php echo $rs->os_id?>" target="_blank">
                            <img src="img/btn/create_rel.png" title="Gerar PDF" border="0" />
                        </a>
                    <?php } ?>
                    <?php
                        //Verificando a existência de pendências
                        $sql = "SELECT pend_id FROM os_pendencia WHERE pend_os_id=".$rs->os_id." AND pend_status<>'I'";
                        $pend_sqlQuery = mysql_query($sql);
                        
                        //Verificando a existência de relatórios						
                        $sql = "SELECT rel_id FROM os_relatorio WHERE rel_os_id=".$rs->os_id;
                        $rel_sqlQuery = mysql_query($sql);
                        
						if($_SESSION["tec_tipo"] == "T"){
                        if($rs->os_data_conclusao == ""){
							if(mysql_num_rows($pend_sqlQuery) < 1 && mysql_num_rows($rel_sqlQuery) > 0 && $rs->os_sta_id != 1 && $rs->os_sta_id != 8){
								if($_SESSION["tec_id"] == $rs->os_tec_id){
					?>
                                <a href="javascript:ajaxRequest('inc/osConclusao.php?os_id=<?php echo $rs->os_id?>&cli_id=<?php echo $rs->os_cli_id?>&equ_id=<?php echo $rs->equ_id?>&tec_id=<?php echo $rs->os_tec_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Concluir ordem de servi&ccedil;o">
                                    <img src="img/btn/ok.png" width="16" height="16" border="0" />
                                </a>
                    <?php  
								}else{
					?>
                    				<img src="img/btn/ok_d.png" width="16" height="16" border="0" 
                                	 title="Voc&ecirc; n&atilde;o &eacute; o respons&aacute;vel desta ordem de servi&ccedil;o" />
					<?php			
								}                  		
							}else{
								if(mysql_num_rows($pend_sqlQuery) > 0){
					?>							
                        			<img src="img/btn/ok_d.png" width="16" height="16" border="0" 
                                	 title="Existe pelo menos uma pend&ecirc;ncia que precisa ser atendida" />
					<?php	
								}elseif(mysql_num_rows($rel_sqlQuery) < 1){
					?>
									<img src="img/btn/ok_d.png" width="16" height="16" border="0" 
                                	 title="Gere pelo menos um relat&oacute;rio para esta ordem de servi&ccedil;o" />
					<?php			
								}else{
					?>
                    				<img src="img/btn/ok_d.png" width="16" height="16" border="0" 
                                	 title="A ordem de servi&ccedil;o precisa ser aprovada" />
                    <?php				
								}
							}
						}else{
					?>
							<img src="img/btn/ok_d.png" width="16" height="16" border="0" title="Ordem de servi&ccedil;o conclu&iacute;da" />
					<?php	
						}
						}
						
						$sql = "SELECT mov_sos_id FROM mov_saida_os WHERE mov_sos_os_id=".$rs->os_id;
						$mov_sqlQuery = mysql_query($sql);
						$mov_num = mysql_num_rows($mov_sqlQuery);
                    ?>                    
                    <?php if($_SESSION["tec_del_os"] == "S"){?>
                    <?php  if($rs->os_data_conclusao != ""){?>
                    	<?php  if($mov_num == 0){?>
                    	<a href="javascript:excluirRec('inc/save/saveos.php?cmd=excluir&os_id=<?php echo $rs->os_id?>&equ_id=<?php echo $rs->os_equ_id?>&cli_id=<?php echo $rs->os_cli_id?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $pg?>,'los','exos',true)" title="Deletar ordem de servi&ccedil;o">
                            <img src="img/btn/del.png" width="16" height="16" border="0" />
                        </a>
                        	<?php }else{?>
                            	<img src="img/btn/del_d.png" width="16" height="16" border="0" title="Exclus&atilde;o n&atilde;o permitida. Esta ordem de servi&ccedil;o est&aacute; ligada a movimenta&ccedil;&otilde;es do estoque" />
                        	</a>
                    <?php } ?>
                    <?php }else{?>
                            <img src="img/btn/del_d.png" width="16" height="16" border="0" title="Conclua a ordem de servi&ccedil;o antes de exclu&iacute;-la" />
                        </a>
                    <?php } ?>
                    <?php } ?>
				</td>
				<td>&nbsp;</td>
			</tr>	
			<?php					
				$isColor = !$isColor;					
			}
		}
	?>
    </table>
    <div id="bottomline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> ordens de servi&ccedil;o em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=los&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>