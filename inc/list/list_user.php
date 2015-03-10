<?php
if($_SESSION["tec_view_user"] != "S"){
?>
	<script>document.location = "index.php?inc=los&ot=5&msg=puser";</script>
<?php
	exit;
}

function showPermission($usid, $pid, $userPermission, $permissionState){
	if($userPermission == "S" && ($_SESSION["tec_alter_info"] == "S" || $_SESSION["tec_id"] != $usid)){
		if($permissionState == "S"){
			echo '
				<a id="'.$usid.$pid.'" href="javascript:setPemission(\'inc/save/saveuser.php?cmd=ap&tec_id='.$_SESSION["tec_id"].'&usu_id='.$usid.'&p='.$pid.'\', '.$usid.$pid.')">
				<img src="img/btn/ok.png" width="16" height="16" border="0" title="Clique para alterar a permiss&atilde;o" />
				</a>
			';
		}else{
			echo '
				<a id="'.$usid.$pid.'" href="javascript:setPemission(\'inc/save/saveuser.php?cmd=ap&tec_id='.$_SESSION["tec_id"].'&usu_id='.$usid.'&p='.$pid.'\', '.$usid.$pid.')">
				<img src="img/btn/ok_d.png" width="16" height="16" border="0" title="Clique para alterar a permiss&atilde;o" />
				</a>
			';
		}	
	}else{
		if($permissionState == "S"){
			echo '
				<img src="img/btn/ok.png" width="16" height="16" border="0" title="Permitido" />
			';
		}else{
			echo '
				<img src="img/btn/ok_d.png" width="16" height="16" border="0" title="N&atilde;o permitido" />
			';
		}
	}
}
						
$orderType[0][0] = "usu_id";
$orderType[1][0] = "usu_nome";
$orderType[2][0] = "usu_email";
$orderType[3][0] = "usu_dt_criacao";
$orderType[4][0] = "usu_dt_alter";
$orderType[5][0] = "usu_dt_login";

$orderType[6][0] = "usu_cad_user";
$orderType[7][0] = "usu_cad_prod";
$orderType[8][0] = "usu_cad_cli";
$orderType[9][0] = "usu_cad_equ";
$orderType[10][0] = "usu_cad_os";

$orderType[11][0] = "usu_view_user";
$orderType[12][0] = "usu_view_cli";
$orderType[13][0] = "usu_view_doc";
$orderType[14][0] = "usu_view_os";

$orderType[15][0] = "usu_aprov_os";
$orderType[16][0] = "usu_atrib_os";
$orderType[17][0] = "usu_alter_info";
$orderType[18][0] = "usu_block";
$orderType[19][0] = "usu_del_os";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}

		
$sql = "SELECT * FROM usuario WHERE usu_tipo='T' AND usu_id<>1";

$searchStr = "";
if($_POST["cmd"] == "srch"){
	$fields = array_keys($_POST);
	$values = array_values($_POST);
	
	$isFirst = true;
	$searchStr = "&cmd=".$_POST["cmd"];
	for($i =1; $i < (count($_POST)); $i++){
		if($_POST[$fields[$i]] !=""){		
			$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";	
			$searchStr	.= "&".$fields[$i]."=".$values[$i];	
		}
	}				
}elseif($_GET["cmd"] == "srch"){
	$fields = array_keys($_GET);
	$values = array_values($_GET);
	(strpos($_SERVER['REQUEST_URI'],"ord") > -1)? $begin = 4: $begin = 3;
	$isFirst = true;
	$searchStr = "&cmd=".$_GET["cmd"];
	for($i = $begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg"){
			$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";	
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}


$_SESSION["srch"] = $searchStr;

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
    	<form name="search_form" action="index.php?inc=luser&amp;ot=1"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	USID:<br/><input type="text" name="usu_id" value="<?php echo $_POST["usu_id"].$_GET["usu_id"]?>" style="width:120px" />
				</td>				
				<td>
                	Usu&aacute;rio:<br/><input type="text" name="usu_nome" value="<?php echo $_POST["usu_nome"].$_GET["usu_nome"]?>" style="width:120px" />
				</td>                
                <td>
                	E-mail:<br/><input type="text" name="usu_email" value="<?php echo $_POST["usu_email"].$_GET["usu_email"]?>" style="width:120px" />
				</td>
                <td>
                	Observa&ccedil;&otilde;es:<br/><input type="text" name="usu_obs" value="<?php echo $_POST["usu_obs"].$_GET["usu_obs"]?>" style="width:120px" />
				</td>
                <td valign="bottom">
                	<input type="submit" value="pesquisar" 
                    	   style="background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>" />
                </td>
			</tr>
		</table>
        </form>
        </div>
        <div style="clear:both"></div>
	</div>
    <div id="session" >
    	<div id="sessionlabel">Usu&aacute;rios</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=luser&amp;ot=0" style="color: <?php echo $colors[5]?>;">
            	<span id="sinal" style="display:none"></span><img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_user.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','usuarios.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> usu&aacute;rios em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				echo $pm->getControls("index.php?inc=luser&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
    <table id="listing" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
    	<tr style="background-image:url(img/bar.jpg)" height="27">
        	<td style="background-image:url(img/bar_side_l.jpg); background-repeat: no-repeat;" width="15">&nbsp;</td>
        	<td align="center" width="45px">
            	<a href="index.php?inc=luser&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por id">
                   USID
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
            <td  width="" align="left">
            	<a href="index.php?inc=luser&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome">
                   Usu&aacute;rio
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
            <td align="left">
            	<a href="index.php?inc=luser&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por endere&ccedil;o de e-mail">
                   E-mail
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
            <td align="center" width="105px">
            	<a href="index.php?inc=luser&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data de cadastro">
                   Cadastro
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
            <td align="center" width="105px">
            	<a href="index.php?inc=luser&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data do último acesso">
                   &Uacute;ltimo acesso
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
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para cadastrar usu&aacute;rios">
                	<img src="img/btn/add_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
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
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para cadastrar produtos">
                	<img src="img/btn/add_prod.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
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
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=8<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para cadastrar clientes">
                	<img src="img/btn/add_cli.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 8 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 8 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=9<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para cadastrar equipamentos">
                	<img src="img/btn/equipamento_a.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
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
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=10<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para cadastrar ordens de servi&ccedil;o">
                	<img src="img/btn/new_os.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 10 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 10 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td>&nbsp;</td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=11<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para visualizar usu&aacute;rios">
                	<img src="img/btn/user.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 11 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 11 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=12<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para visualizar clientes">
                	<img src="img/btn/cliente_v.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 12 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 12 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=14<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para visualizar todas ordens de servi&ccedil;o">
                	<img src="img/btn/os_list.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 14 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 14 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td>&nbsp;</td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=15<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para autorizar ordem de servi&ccedil;o">
                	<img src="img/btn/orcamento.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 15 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 15 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=16<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para atribuir respons&aacute;vel a uma ordem de servi&ccedil;o">
                	<img src="img/btn/responsavel.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 16 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 16 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=17<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para alterar suas pr&oacute;prias informa&ccedil;&otilde;es pessoais">
                	<img src="img/btn/edit_user.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 17 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 17 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=18<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para bloquear cadastros">
                	<img src="img/btn/lock.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 18 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 18 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=luser&amp;ot=19<?php echo $searchStr?>&amp;ord=1&amp;pg=<?php echo $pm->getCp()?>" title="Permiss&atilde;o para excluir ordens de servi&ccedil;o">
                	<img src="img/btn/new_os_dd.png" width="16" height="16" border="0" style="margin-bottom: -2px" />
                    <?php 
						if($_GET["ot"] == 19 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 19 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td>&nbsp;</td>
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 	
		
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"24\" style=\"padding: 30px\" align=\"center\">Nenhum usu&aacute;rio encontrado.</td></tr>";
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
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->usu_id?></font></td>                
				<td align="left">
                	<span title="<?php echo $rs->usu_nome?>">
						<?php 
							echo substr($rs->usu_nome,0,30);
							if(strlen($rs->usu_nome) > 30){
								echo "...";
							}
						?>
                        
                    </span>                    
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->usu_email?>">
						<?php 
							echo substr($rs->usu_email,0,30);
							if(strlen($rs->usu_email) > 30){
								echo "...";
							}
						?>
                    </span>
                </td>
                <td align="center">
					<font style="font-size: 6pt">					
					<?php echo date("H:i:s",$rs->usu_dt_criacao)?></font>&nbsp;
					<?php echo date("d-m-y",$rs->usu_dt_criacao)?>                    
				</td>                
                <td align="center">
					<font style="font-size: 6pt">
					<?php if($rs->usu_dt_login != 0){?>
					<?php echo date("H:i:s",$rs->usu_dt_login)?></font>&nbsp;
					<?php echo date("d-m-y",$rs->usu_dt_login)?>
                    <?php }?>                        
				</td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 0, $_SESSION["tec_cad_user"], $rs->usu_cad_user) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 1, $_SESSION["tec_cad_user"], $rs->usu_cad_prod) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 2, $_SESSION["tec_cad_user"], $rs->usu_cad_cli) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 3, $_SESSION["tec_cad_user"], $rs->usu_cad_equ) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 4, $_SESSION["tec_cad_user"], $rs->usu_cad_os) ?>
                </td>
                <td>&nbsp;</td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 5, $_SESSION["tec_cad_user"], $rs->usu_view_user) ?>
                </td>                
                <td align="center">
                	<?php showPermission($rs->usu_id, 6, $_SESSION["tec_cad_user"], $rs->usu_view_cli) ?>
                </td>                
                <td align="center">
                	<?php showPermission($rs->usu_id, 8, $_SESSION["tec_cad_user"], $rs->usu_view_os) ?>
                </td>                
                <td>&nbsp;</td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 9, $_SESSION["tec_cad_user"], $rs->usu_aprov_os) ?>
                </td>                
                <td align="center">
                	<?php showPermission($rs->usu_id, 10, $_SESSION["tec_cad_user"], $rs->usu_atrib_os) ?>
                </td>                
                <td align="center">
                	<?php showPermission($rs->usu_id, 11, $_SESSION["tec_cad_user"], $rs->usu_alter_info) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 12, $_SESSION["tec_cad_user"], $rs->usu_block) ?>
                </td>
                <td align="center">
                	<?php showPermission($rs->usu_id, 13, $_SESSION["tec_cad_user"], $rs->usu_del_os) ?>
                </td>                
                <td>&nbsp;</td>
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_user.php?usu_id=<?php echo $rs->usu_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                    <?php 						
						$sql = "SELECT os_tec_id FROM os WHERE os_tec_id=".$rs->usu_id;					
					
						$ot_sqlQuery = mysql_query($sql);
						$ot_rs = mysql_fetch_object($ot_sqlQuery);						
					
						if(mysql_num_rows($ot_sqlQuery) > 0){?>
								<a href="index.php?inc=los&amp;ot=5&amp;ord=1&amp;cmd=srch&amp;os_tec_id=<?php echo $rs->usu_id?>&amp;pg=1" title="Visualizar ordens de servi&ccedil;o">
							<img src="img/btn/os_list.png" width="16" height="16" border="0" />
						</a>
						<?php }else{?>                    	
							<img src="img/btn/os_list_d.png" width="16" height="16" border="0" 
								 title="Nenhum ordem de servi&ccedil;o gerada" />
						</a>
						<?php }
					?>
                    <a href="javascript:ajaxRequest('inc/stat/stat_user.php?usu_id=<?php echo $rs->usu_id?>','ajaxContent')" title="Estat&iacute;sticas">
						<img src="img/btn/grafico.png" width="16" height="16" border="0" />
					</a>
                    <?php 
						($rs->usu_status == "A" || $rs->usu_status == "AS")? $lock = "src=\"img/btn/lock_d.png\" title=\"Clique para bloque&aacute;-lo\"": $lock = "src=\"img/btn/lock.png\" title=\"Clique para desbloque&aacute;-lo\"";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->usu_id?>lock" href="javascript:setLocker('inc/save/saveuser.php?cmd=al&tec_id=<?php echo $_SESSION["tec_id"]?>&usu_id=<?php echo $rs->usu_id?>&p=<?php echo $rs->usu_status?>', '<?php echo $rs->usu_id?>lock',false)">
						<img <?php echo $lock?> width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img <?php echo $lock?> width="16" height="16" border="0" />
                    <?php }?>
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
    	<div style="float: left"><?php echo $pm->getNumRows()?> usu&aacute;rios em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				echo $pm->getControls("index.php?inc=luser&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>