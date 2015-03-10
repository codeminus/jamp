<?php
###CLASSE DE CONEXÃO###

/**
 * @author Wilson Santos
 * @version 1.0.0
 * @name Connection
 */ 
class Connection{
	private $conn;
	private $db;
	private $resultObject;
	private $sqlQuery;
	
	//construtor
	function Connection($hostname, $user, $pass, $dbName){
		$this->conn = mysql_connect($hostname, $user, $pass);
		$this->selectDb($dbName);
	}
	
	### PUBLIC METHODS ###
	
	
	public function getConnection(){
		return $this->conn;
	}
	
	//seleciona um banco de dados
	public function selectDb($dbName){
		$this->db = mysql_select_db($dbName);
	}
	
	//executa a query, retorna um erro mysql caso nao tenha sido executada com sucesso
	public function execQuery($sql){
		$this->sqlQuery = mysql_query($sql) or die(mysql_error());		
	}
	
	//retorna o resultado da consulta no tipo fetch_object
	public function getResultObject(){
		return mysql_fetch_object($this->sqlQuery);
	}
	
	//n&uacute;mero de linhas de resultado
	public function getNumRows(){
		return mysql_num_rows($this->sqlQuery);
	}
}

###CLASSE DE PAGINAÇÃO###

/**
 * @author Wilson Santos
 * @version 1.0.0
 * @name PagingMaker
 */
class PagingMaker{
	private $page_limit;
	private $start_page;
	private $current_page;
	private $next_page;
	private $prev_page;
	private $total_pages;
	private $sqlQuery;
	private $num_rows;
	
	//contrutor
	function __construct($sql, $limit, $pg){
		
		if($pg == ""){
			$this->current_page = 1;
		}else{
			$this->current_page = $pg;
		}
		
		$this->page_limit = $limit;		
		$this->start_page = ($this->current_page-1)*$this->page_limit;
		$this->next_page = $this->current_page+1;
		$this->prev_page = $this->current_page-1;
		$this->total_pages = $this->getTotalPages($sql,$limit);
		$this->num_rows = mysql_num_rows(mysql_query($sql));
		$this->queryExec($sql);
	}
	
	
	###PRIVATE METHODS### 
	
	//total de paginas
	private function getTotalPages($sql,$limit){
		$this->sqlQuery = mysql_query($sql);
		return ceil(mysql_num_rows($this->sqlQuery)/$limit);
	}
	
	//Executa a query completa	
	private  function queryExec($sql){
		$this->sqlQuery = mysql_query($sql." LIMIT ".$this->start_page.",".$this->page_limit) or die(mysql_error());		
	}
	
		
	###PUBLIC METHODS###
	
	//retorna o resultado da consulta no tipo fetch_object
	public function getResultObject(){
		return	mysql_fetch_object($this->sqlQuery);
	}	
	
	//retorna o total de linhas do resultado
	public function getNumRows(){
		return $this->num_rows;
	}
	
	//retonar a pagina atual
	public function getCp(){
		return $this->current_page;
	}
	//retorna a proxima pagina
	public function getNp(){
		return $this->next_page;
	}
	//retorna a pagina anterior
	public function getPp(){
		return $this->prev_page;
	}
	//retorna o total de paginas
	public function getTp(){
		return $this->total_pages;
	}
	
	/*
	* retorna um controle da pagina&ccedil;&atilde;o.
	* $param: parametros a serem passados via GET quando os controles forem clicados
	* $tlc: string com o valor que representar&aacute; o bot&atilde;o para voltar para a primeira p&aacute;gina
	* $lc: string com o valor que representar&aacute; o bot&atilde;o para voltar uma p&aacute;gina
	* $rc: string com o valor que representar&aacute; o bot&atilde;o para avan&ccedil;ar uma p&aacute;gina
	* $trc: string com o valor que representar&aacute; o bot&atilde;o para avan&ccedil;ar para a &uacute;ltima p&aacute;gina
	*
	*exemplo: getControls("index.php?nome=<?php echo $nome?>")
	*/
	public function getControls($param, $tlc = "|<<", $lc = "<", $rc = ">", $trc = ">>|"){
		if($this->getTp() > 1){
			if($this->getCp() < $this->getTp() && $this->getCp() > 1){
				return "
					<a href=\"".$param."&pg=1\"
                       title=\"Primeira p&aacute;gina\">
                    	".$tlc."
                    </a>		
                    <a href=\"".$param."&pg=".$this->getPp()."\" 
                       title=\"P&aacute;gina anterior\">
                    	".$lc."
                    </a>
                    <b>".$this->getCp()."</b>
                    <a href=\"".$param."&pg=".$this->getNp()."\"
                       title=\"Pr&oacute;xima p&aacute;gina\">
                    	".$rc."
                    </a>
                    <a href=\"".$param."&pg=".$this->getTp()."\"
                       title=\"&Uacute;ltima &aacute;gina\">
                    	".$trc."
                    </a>
				";
			}elseif ($this->getTp() == $this->getCp()){
				return "
					<a href=\"".$param."&pg=1\"
                       title=\"Primeira p&aacute;gina\">
                    	".$tlc."
                    </a>		
                    <a href=\"".$param."&pg=".$this->getPp()."\" 
                       title=\"P&aacute;gina anterior\">
                    	".$lc."
                    </a>
                    <b>".$this->getCp()."</b>                    
				";
			}elseif ($this->getCp() == 1){
				return "
                    <b>".$this->getCp()."</b>
                    <a href=\"".$param."&pg=".$this->getNp()."\"
                       title=\"Pr&oacute;xima p&aacute;gina\">
                    	".$rc."
                    </a>
                    <a href=\"".$param."&pg=".$this->getTp()."\"
                       title=\"&Uacute;ltima &aacute;gina\">
                    	".$trc."
                    </a>
				";
			}
				
		}else{
			return "";
		}
			
	}
}


###CLASSE DE EMAIL###

/**
 * @author Wilson Santos
 * @version 1.0.0
 * @name Email
 */
class Email{	
	private $from;
	private $to;
	private $subject;
	private $message;	
	
	
	function Email($from, $to = "", $subject = "", $message = ""){
		
		$this->setFrom($from);
		$this->addTo($to);
		$this->setSubject($subject);
		$this->setMessage($message);
	}
		
	
	###MÉTODOS GET###
	
	public function getMailheaders(){
		return $this->mailheaders;
	}
	
	public function getFrom(){
		return $this->from;
	}
	
	public function getTo(){
		return $this->to;
	}
	
	public function getSubject(){
		return $this->subject;
	}
	
	public function getMessage(){
		return $this->message;
	}
	
	###METODOS SET###	
		
	public function setFrom($from){
		$this->from = "From: ";
		$this->from .= $from;		
		$this->from .= " \r\nContent-Type: text/html; charset=iso-8859-1\r\n";
		
		
	}		
	
	public function addTo($to){
		
		if($this->getTo() != ""){
			$this->to .=",".$to;
		}else{
			$this->to = $to;
		}
	}
	
	public function setSubject($subject){		
		$this->subject = $subject;
	}
	
	public function setMessage($message){		
		$this->message = $message;
	}
	
	###ENVIAR EMAIL###
	
	public function send(){		
		@mail($this->getTo(),$this->getSubject(),$this->getMessage(),$this->getFrom());
	}
}

###CLASSE DE NOTIFICACAO###

/**
 * @author Wilson Santos
 * @version 1.0.0
 * @name Notificacao
 */
class Notification{
	//vari&aacute;vel de montagem
	private $not;
	//titulo da notifica&ccedil;&atilde;o
	private $title;	
	//Cores para formata&ccedil;&atilde;o
	private $colors;
	//Formata&ccedil;&atilde;o 
	private $style = "";
	//website da empresa
	private $emp_site;
	//logo da empresa
	private $emp_logo;
	
	### Complementos das notifica&ccedil;&otilde;es ###
	
	//Link para o painel de controle
	private $sysLink = false;
	
	### Informa&ccedil;&otilde;es de usu&aacute;rio ###
	
	// ID do usu&aacute;rio
	private $usid = "";
	// ID do usu&aacute;rio para Notifica&ccedil;&atilde;o de login
	private $userLoginId = "";
	
	### Informa&ccedil;&otilde;es de fornecedor ###
	
	//ID do fornecedor
	private $fornid = "";
	
	### Informa&ccedil;&otilde;es de classifica&ccedil;&atilde;o de produto ###
	
	//ID da classifica&ccedil;&atilde;o
	private $classprodid = "";
	
	### Informa&ccedil;&otilde;es de produto ###
	
	//ID do produto
	private $prodid = "";
	
	### Informa&ccedil;&otilde;es de movimenta&ccedil;&atilde;o de entrada ###
	
	//ID da movimenta&ccedil;&atilde;o
	private $moventrid = "";
		
	### Informa&ccedil;&otilde;es de movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno ###
	
	//ID da movimenta&ccedil;&atilde;o	
	private $movsusointid = "";
	
	### Informa&ccedil;&otilde;es de serviço ###
	
	//ID do serviço	
	private $servid = "";
	
	### Informa&ccedil;&otilde;es de cliente ###
	
	//ID do cliente
	private $clid = "";
	
	### Informa&ccedil;&otilde;es de equipamento ###
	
	//ID do equipamento
	private $eqid = "";	
	
	### Informa&ccedil;&otilde;es de ordem de servi&ccedil;o ###
	
	//ID da ordem de servi&ccedil;o
	private $osid = "";
	//ID do relat&oacute;rio
	private $relid = "";
	//ID da pend&ecirc;ncia
	private $pendid = "";
	
	### Informa&ccedil;&otilde;es de atribui&ccedil;&atilde;o t&eacute;cnica ###
	
	//ID do t&eacute;cnico antigo
	private $oldTecid = "";	
	//ID do novo t&eacute;cnico
	private $newTecid = "";	
	//Jusitificativa
	private $atribJust = "";
	
	
	### Solicita&ccedil;&atilde;o de suporte ###
	
	private $supportFlds = "";	
	
	### Informa&ccedil;&otilde;es avan&ccedil;adas ###
	
	//Usu&aacute;rio
	private $usuAdvinfo = false;	
	//Cliente
	private $cliAdvinfo = false;
	//Relat&oacute;rio administrativo
	private $relAdm = false;
	
	
	//construtor			
	function Notification($title){
		$this->setTitle($title);
	}

	//Configurando website e logo da empresa
	function setConfig($emp_site, $emp_logo){
		$this->emp_site = $emp_site;
		$this->emp_logo = $emp_logo;
	}

	//Titulo da notifica&ccedil;&atilde;o
	function setTitle($title){
		$this->title = $title; 
	}	
	function getTitle(){
		return $this->title;
	}
	
	// Configurando as cores para a formata&ccedil;&atilde;o
	function setColors($colors){
		$this->colors = $colors;
	}
	
	//Formata&ccedil;&atilde;o
	function setStyle($style){
		$this->style = $style;
	}
	function getStyle(){
		if($this->style != ""){
			return $this->style;
		}else{
			return '<style type="text/css">
					<!--
						*{
							font-family: tahoma, Sans;
							font-size: 8pt;
							color: '.$this->colors[0].';
						}
						
						.shadow tr{
							background-image: url('.$this->emp_site.'img/not_middle.jpg);
							background-repeat: repeat-y;
							
						}
						
						.noshadow tr{
							background: none;
						}
						.noshadow tr td{
							border-bottom: dashed 1px '.$this->colors[2].';
						}
						
						.titulo{
							color: '.$this->colors[0].';
							font-weight: bold;
							background-color: '.$this->colors[2].';
							padding:2px;
						}
						.disabledLine *{
							color: '.$this->colors[4].';
						}
												
					-->
					</style>';
		}
	}
	
	//Configurando link para o painel de controle
	function setSysLink($sysLink){
		$this->sysLink = $sysLink;
	}
	function getSysLink(){
		if($this->sysLink){
			return '<tr><td colspan="2"><b>Link para acesso ao sistema:</b> '.$this->emp_site.'</td></tr>';
		}
	}
	
	//informa&ccedil;&otilde;es do usu&aacute;rio
	function setUser($usid){
		$this->usid = $usid;
	}
	function setUserLogin($userLoginId){
		$this->userLoginId = $userLoginId;
	}
	function setUserAdvinfo($Advinfo){
		$this->usuAdvinfo = $Advinfo;
	}
	
	//informa&ccedil;&otilde;es do fornecedor	
	function setFornecedor($fornid){
		$this->fornid = $fornid;
	}
	
	//informa&ccedil;&otilde;es da classifica&ccedil;&atilde;o de produto
	function setClassProduto($classprodid){
		$this->classprodid = $classprodid;	
	}	
	
	
	//informa&ccedil;&otilde;es do produto
	function setProduto($prodid){
		$this->prodid = $prodid;	
	}
	
	//informa&ccedil;&otilde;es da movimenta&ccedil;&atilde;o de entrada
	function setMovEntr($moventrid){
		$this->moventrid = $moventrid;	
	}
	
	//informa&ccedil;&otilde;es da movimenta&ccedil;&atilde;o de sa&iacute;da
	function setMovSusoint($movsusointid){
		$this->movsusointid = $movsusointid;	
	}
	
	//informa&ccedil;&otilde;es da movimenta&ccedil;&atilde;o de sa&iacute;da
	function setServico($servid){
		$this->servid = $servid;	
	}
	
	//informa&ccedil;&otilde;es do cliente	
	function setCliente($clid){
		$this->clid = $clid;
	}
	function setClienteAdvinfo($Advinfo){
		$this->cliAdvinfo = $Advinfo;
	}
	
	//Informa&ccedil;&otilde;es do equipamento
	function setEquipamento($eqid){
		$this->eqid = $eqid;
	}
	
	//Informa&ccedil;&otilde;es da ordem de servi&ccedil;o
	function setOs($osid){
		$this->osid = $osid;
	}
	function setRel($relid){
		$this->relid = $relid;
	}
	function setRelAdm($relAdm){
		$this->relAdm = $relAdm;
	}
	function setPend($pendid){
		$this->pendid = $pendid;
	}
	
	//Atribui&ccedil;&atilde;o t&eacute;cnica
	function setAtribTec($oldTecid, $newTecid, $atribJust){
		$this->oldTecid = $oldTecid;
		$this->newTecid = $newTecid;
		$this->atribJust = $atribJust;
	}
	
	//Solicita&ccedil;&atilde;o de suporte t&eacute;cnico
	
	function setSupportMsg($nome, $cont_nome, $email, $assunto, $msg){
		$this->supportFlds[0] = $nome;
		$this->supportFlds[1] = $cont_nome;
		$this->supportFlds[2] = $email;
		$this->supportFlds[3] = $assunto;
		$this->supportFlds[4] = $msg;
	}
	
	function getUser(){
		if($this->usid != ""){
			$sql = "SELECT * FROM usuario WHERE usu_id=".$this->usid;
			$usu_sqlQuery = mysql_query($sql);
			$rs_usu = mysql_fetch_object($usu_sqlQuery);
			
			$usuario =  '<tr><td colspan="2"class="titulo">Informa&ccedil;&otilde;es do Usu&aacute;rio</td></tr>
					  	<tr><td colspan="2"><b>Usu&aacute;rio:</b> '.$rs_usu->usu_nome.'</td></tr>';
						
			if($this->usuAdvinfo){
				$usuario .= '<tr><td colspan="2"><b>E-mails:</b> '.$rs_usu->usu_email.'</td></tr>
							<tr><td colspan="2"><b>Telefones para contato:</b> '.$rs_usu->usu_tel.'</td></tr>';
			}
			
			$usuario .= '<tr><td colspan="2">&nbsp;</td></tr>';
			return $usuario;
			
		}else{
			return '';
		}
	}
	
	function getUserLogin(){
		if($this->userLoginId != ""){
			$sql = "SELECT usu_login, usu_pass FROM usuario WHERE usu_id=".$this->userLoginId;
			$usuLogin_sqlQuery = mysql_query($sql);
			$rs_usuLogin = mysql_fetch_object($usuLogin_sqlQuery);
			
			return '<tr><td colspan="2"class="titulo">Informa&ccedil;&otilde;es de acesso</td></tr>
					  	<tr><td colspan="2"><b>Login:</b> '.$rs_usuLogin->usu_login.'</td></tr>
					  	<tr><td colspan="2"><b>Senha:</b> '.$rs_usuLogin->usu_pass.'</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getFornecedor(){
		if($this->fornid != ""){
			$sql = "SELECT * FROM fornecedor WHERE forn_id=".$this->fornid;
			$forn_sqlQuery = mysql_query($sql);
			$rs_forn = mysql_fetch_object($forn_sqlQuery);
			
			return '<tr><td colspan="2"class="titulo">Informa&ccedil;&otilde;es do fornecedor</td></tr>
					<tr><td colspan="2"><b>Fornecedor:</b> '.$rs_forn->forn_nome.'</td></tr>
					<tr><td colspan="2"><b>E-mails:</b> '.$rs_forn->forn_email.'</td></tr>
					<tr><td colspan="2"><b>CPF/CNPJ:</b> '.$rs_forn->forn_cp.'</td></tr>
					<tr><td colspan="2"><b>Inscri&ccedil;&atilde;o Estadual:</b> '.$rs_forn->forn_inscricao.'</td></tr>
					<tr><td colspan="2"><b>Telefones para contato:</b> '.$rs_forn->forn_tel.'</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
			
		}else{
			return '';
		}
	}
	
	function getClassProduto(){
		if($this->classprodid != ""){
									
			$sql = "SELECT * FROM classprod WHERE classprod_id=".$this->classprodid;
			$classprod_sqlQuery = mysql_query($sql);
			$rs_classprod = mysql_fetch_object($classprod_sqlQuery);			
			
			if($rs_classprod->classprod_status == "I"){
				$status	= '<tr><td colspan="2"><b>Status: </b><i>Classifica&ccedil;&atilde;o bloqueada.</i></td></tr>';
			}else{
				$status = '<tr><td colspan="2"><b>Status: </b><i>Classifica&ccedil;&atilde;o desbloqueada.</i></td></tr>';	
			}
			
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es da Classifica&ccedil;&atilde;o</td></tr>
					<tr>
						<td colspan="2">
							<b>Classifica&ccedil;&atilde;o:</b> '.$rs_classprod->classprod_nome.'
						</td>
					</tr>				
					<tr>
						<td colspan="2">
							<b>Descri&ccedil;&atilde;o da Classifica&ccedil;&atilde;o:</b>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							'.$rs_classprod->classprod_desc.'
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					'.$status.'
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getProduto(){
		if($this->prodid != ""){
									
			$sql = "SELECT * FROM produto WHERE prod_id=".$this->prodid;
			$prod_sqlQuery = mysql_query($sql);
			$rs_prod = mysql_fetch_object($prod_sqlQuery);
			
			$sql = "SELECT classprod_nome FROM classprod WHERE classprod_id='".$rs_prod->prod_classprod_id."'";
			$classprod_sqlQuery = mysql_query($sql);
			$rs_classprod = mysql_fetch_object($classprod_sqlQuery);
			
			if($rs_prod->prod_status == "B"){
				$status	= '<tr><td colspan="2"><b>Status: </b><i>Produto bloqueado.</i></td></tr>';
			}else{
				$status = '<tr><td colspan="2"><b>Status: </b><i>Produto desbloqueado.</i></td></tr>';	
			}
			
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es do produto</td></tr>
					<tr>
						<td colspan="2">
							<b>Classifica&ccedil;&atilde;o:</b> '.$rs_classprod->classprod_nome.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>C&oacute;digo do produto:</b> #'.$rs_prod->prod_id.'&nbsp;&nbsp;
							<b>Produto:</b> '.$rs_prod->prod_nome.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Modelo:</b> '.$rs_prod->prod_modelo.'&nbsp;&nbsp;
							<b>Fabricante:</b> '.$rs_prod->prod_fabricante.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Localiza&ccedil;&atilde;o:</b> '.$rs_prod->prod_localizacao.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Descri&ccedil;&atilde;o do produto:</b>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							'.$rs_prod->prod_desc.'
						</td>
					</tr>
					<tr>
						<td colspan="2">						
							<b>Valor de venda:</b> R$ '.number_format($rs_prod->prod_vvalor,2,",",".").'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Unidade de medida:</b> '.$rs_prod->prod_unid_medida.'						
						</td>
					</tr>
					<tr>
						<td colspan="2">						
							<b>Quantidade m&iacute;nima para o estoque:</b> '.$rs_prod->prod_min_quant.'
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					'.$status.'
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getMovEntr(){
		if($this->moventrid != ""){
									
			$sql = "SELECT * FROM mov_entrada WHERE mov_entr_id=".$this->moventrid;
			$moventr_sqlQuery = mysql_query($sql);
			$rs_moventr = mysql_fetch_object($moventr_sqlQuery);			
			
						
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es da movimenta&ccedil;&atilde;o de entrada</td></tr>
					<tr>
						<td>
							<b>N&uacute;mero da nota fiscal:</b> '.$rs_moventr->mov_entr_num_nf.'
						</td>
						<td>
							<b>Data de emiss&atilde;o da nota fiscal:</b> '.date("d-m-y",$rs_moventr->mov_entr_nf_dt_emissao).'
						</td>
					</tr>				
					<tr>
						<td>
							<b>Valor de custo por unidade:</b> R$ '.number_format($rs_moventr->mov_entr_vcusto,2,",",".").'
						</td>
						<td>
							<b>Valor de custo total:</b> '.number_format($rs_moventr->mov_entr_vcusto*$rs_moventr->mov_entr_quant_entrada,2,",",".").'
						</td>
					</tr>
					<tr>						
						<td colspan="2">
							<b>Quantidade de entrada:</b> '.$rs_moventr->mov_entr_quant_entrada.'
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getMovSusoint(){
		if($this->movsusointid != ""){
									
			$sql = "SELECT * FROM mov_saida_usointerno WHERE mov_susoint_id=".$this->movsusointid;
			$movsusoint_sqlQuery = mysql_query($sql);
			$rs_movsusoint = mysql_fetch_object($movsusoint_sqlQuery);			
			
						
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es da movimenta&ccedil;&atilde;o de sa&iacute;da</td></tr>
					<tr>
						<td>
							<b>Solicitado por:</b> '.$rs_movsusoint->mov_susoint_solicitado_por.'
						</td>
						<td>
							<b>Autorizado por:</b> '.$rs_movsusoint->mov_susoint_autorizado_por.'
						</td>
					</tr>				
					<tr>
						<td colspan="2">
							<b>Finalidade:</b> '.$rs_movsusoint->mov_susoint_finalidade.'
						</td>						
					</tr>
					<tr>
						<td>
							<b>Valor por unidade:</b> R$ '.number_format($rs_movsusoint->mov_susoint_svalor,2,",",".").'
						</td>
						<td>							
							<b>Valor total:</b> R$ '.number_format($rs_movsusoint->mov_susoint_svalor*$rs_movsusoint->mov_susoint_quant_saida,2,",",".").'
						</td>						
					</tr>
					<tr>
						<td colspan="2">
							<b>Quantidade de sa&iacute;da:</b> '.$rs_movsusoint->mov_susoint_quant_saida.'
						</td>						
					</tr>
					<tr>
						<td colspan="2">
							<b>Data de sa&iacute;da:</b> '.date("d-m-y",$rs_movsusoint->mov_susoint_dt_saida).'
						</td>						
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getServico(){
		if($this->servid != ""){
									
			$sql = "SELECT * FROM servico WHERE serv_id=".$this->servid;
			$serv_sqlQuery = mysql_query($sql);
			$rs_serv = mysql_fetch_object($serv_sqlQuery);			
			
			if($rs_serv->serv_status == "I"){
				$status	= '<tr><td colspan="2"><b>Status: </b><i>Servi&ccedil;o bloqueado.</i></td></tr>';
			}else{
				$status = '<tr><td colspan="2"><b>Status: </b><i>Servi&ccedil;o desbloqueado.</i></td></tr>';	
			}
			
						
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es do servi&ccedil;o</td></tr>
					<tr>
						<td colspan="2">
							<b>Nome:</b> '.$rs_serv->serv_nome.'
						</td>						
					</tr>				
					<tr>
						<td colspan="2">
							<b>Descri&ccedil;&atilde;o:</b> '.$rs_serv->serv_desc.'
						</td>						
					</tr>
					<tr>						
						<td colspan="2">
							<b>Valor por unidade:</b> R$ '.number_format($rs_serv->serv_valor,2,",",".").' por '.$rs_serv->serv_unid_medida.'
						</td>						
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					'.$status.'
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	function getCliente(){
		if($this->clid != ""){
			$sql = "SELECT * FROM usuario WHERE usu_id=".$this->clid;
			$cli_sqlQuery = mysql_query($sql);
			$rs_cli = mysql_fetch_object($cli_sqlQuery);
			
			$cliente = '<tr><td colspan="2"class="titulo">Informa&ccedil;&otilde;es do cliente</td></tr>
						<tr><td colspan="2"><b>Cliente:</b> '.$rs_cli->usu_nome.'</td></tr>';
			if($this->cliAdvinfo){
		
				$sql = "SELECT * FROM cliente WHERE cli_usu_id=".$this->clid;
				$cliadv_sqlQuery = mysql_query($sql);
				$rs_cliadv = mysql_fetch_object($cliadv_sqlQuery);
				
				$cliente .= '<tr><td colspan="2"><b>E-mails:</b> '.$rs_cli->usu_email.'</td></tr>
							<tr><td colspan="2"><b>CPF/CNPJ:</b> '.$rs_cliadv->cli_cp.'</td></tr>
							<tr><td colspan="2"><b>Inscri&ccedil;&atilde;o Estadual:</b> '.$rs_cliadv->cli_inscricao.'</td></tr>
							<tr><td colspan="2"><b>Telefones para contato:</b> '.$rs_cli->usu_tel.'</td></tr>';
			}
			
			$cliente .= '<tr><td colspan="2">&nbsp;</td></tr>';
			return $cliente;
			
		}else{
			return '';
		}
	}
	
	function getEquipamento(){
		if($this->eqid != ""){
			$sql = "SELECT * FROM equipamento WHERE equ_id=".$this->eqid;
			$sqlQuery = mysql_query($sql);
			$rs = mysql_fetch_object($sqlQuery);
		
		return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es do equipamento</td></tr>
				<tr>
					<td colspan="2">
						<b>Equipamento:</b> '.$rs->equ_nome.'
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Modelo:</b> '.$rs->equ_modelo.'&nbsp;&nbsp;
						<b>Fabricante:</b> '.$rs->equ_fabricante.'
					</td>
				</tr>	
				<tr>
					<td colspan="2">						
						<b>EQID:</b> #'.$rs->equ_id.'&nbsp;&nbsp;
						<b>N. Patrim&ocirc;nio:</b> #'.$rs->equ_num_patrimonio.'&nbsp;&nbsp;
						<b>N. S&eacute;rie:</b> #'.$rs->equ_num_serie.'&nbsp;&nbsp;
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}		
	}
	
	function getOs(){
		if($this->osid != ""){
			$sql = "SELECT * FROM os			
						JOIN usuario ON os_cli_id=usu_id 
						JOIN equipamento ON os_equ_id=equ_id
						JOIN os_status ON os_sta_id=sta_id
						WHERE os_id=".$this->osid;
			$cli_sqlQuery = mysql_query($sql);
			$rs_os = mysql_fetch_object($cli_sqlQuery);
			
			if($rs_os->os_tec_id){
				$sql = "SELECT usu_nome FROM usuario where usu_id=".$rs_os->os_tec_id;
				$tec_sqlQuery = mysql_query($sql);
				$rs_tec = mysql_fetch_object($tec_sqlQuery);
			}
			($rs_os->os_com_remocao == "S")? $rem_msg = "Removido das depend&ecirc;ncias do cliente": $rem_msg = "Nas depend&ecirc;ncias do cliente";
			
			return '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es da ordem de servi&ccedil;o</td></tr>
					<tr>
						<td colspan="2">
							<b>Status:</b> '.$rs_os->sta_nome.'&nbsp;&nbsp;
							<b>Localiza&ccedil;&atilde;o:</b> '.$rem_msg.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Solicitada por:</b> '.$rs_os->os_autorizado_por.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>T&eacute;cnico Respons&aacute;vel:</b> '.$rs_tec->usu_nome.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Descri&ccedil;&atilde;o:</b>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							'.$rs_os->os_pro_cli_descricao.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Observa&ccedil;&otilde;es do cliente:</b>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							'.$rs_os->os_cli_obs.'&nbsp;
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}	
	
	function getRel(){
		if($this->relid != ""){
			$sql = "SELECT * FROM os_relatorio WHERE rel_id=".$this->relid;
			$rel_sqlQuery = mysql_query($sql);
			$rs_rel = mysql_fetch_object($rel_sqlQuery);
			
			$sql = "SELECT usu_nome FROM usuario where usu_id=".$rs_rel->rel_tec_id;
			$tec_sqlQuery = mysql_query($sql);
			$rs_tec = mysql_fetch_object($tec_sqlQuery);
			
			$relatorio = '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es do relat&oacute;rio mais recente</td></tr>
							<tr>
								<td colspan="2">
									<b>Relat&oacute;rio:</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									'.$rs_rel->rel_cont.'
								</td>
							</tr>';
			
			if($this->relAdm){
				$relatorio .= '<tr>
									<td colspan="2">
										<b>Relat&oacute;rio administrativo:</b>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										'.$rs_rel->rel_cont_adm.'
									</td>
								</tr>';
			}
			
			$relatorio .= '<tr>
								<td colspan="2" align="right">
									<b>T&eacute;cnico:</b> '.$rs_tec->usu_nome.'
								</td>
							</tr>';
			$relatorio .= '<tr><td colspan="2">&nbsp;</td></tr>';
			return $relatorio;
		}
	}
	
	function getPend(){
		if($this->pendid != ""){
		$sql = "SELECT * FROM os_pendencia WHERE pend_id=".$this->pendid;
		$pend_sqlQuery = mysql_query($sql);
		$rs_pend = mysql_fetch_object($pend_sqlQuery);
		
		$sql = "SELECT usu_nome FROM usuario where usu_id=".$rs_pend->pend_tec_id;
		$tec_sqlQuery = mysql_query($sql);
		$rs_tec = mysql_fetch_object($tec_sqlQuery);
		
		$sql = "SELECT * FROM os_pend_compl 
				WHERE os_pend_compl_pend_id=".$this->pendid." 
				ORDER BY os_pend_compl_desc_dt";
		$sqlQuery2 = mysql_query($sql);
		$num_rows = mysql_num_rows($sqlQuery2);
		
		if($num_rows > 0){
			$disabledLine = 'class="disabledLine"';
		}else{ 
			$disabledLine = "";
		}	
		$pendencia = '<tr><td colspan="2" class="titulo">Informa&ccedil;&otilde;es da pend&ecirc;ncia</td></tr>
						<tr '.$disabledLine.'>
							<td colspan="2">
								<b>Pend&ecirc;ncia:</b>
								<font style="font-size: 6pt">'.date("H:i:s",$rs_pend->pend_data_criacao).'</font>
						&nbsp;'.date("d-m-y",$rs_pend->pend_data_criacao).'
							</td>
						</tr>
						<tr '.$disabledLine.'>
							<td colspan="2">
								'.$rs_pend->pend_desc.'
							</td>
						</tr>
						<tr '.$disabledLine.'">
							<td colspan="2">
								<b>Resposta:</b> ';
						
						if($rs_pend->pend_data_resp != 0){
							$pendencia .= '<font style="font-size: 6pt">'.date("H:i:s",$rs_pend->pend_data_resp).'</font>
							&nbsp;'.date("d-m-y",$rs_pend->pend_data_resp);
						}
						
						$pendencia .= '&nbsp;</td>
						</tr>
						<tr '.$disabledLine.'>
							<td colspan="2">'.$rs_pend->pend_resp.'</td>
						</tr>
						';
		
		if($num_rows > 0){
			$cont = 1;			
			while($rs_pend_compl = mysql_fetch_object($sqlQuery2)){
				if($rs_pend_compl->os_pend_compl_resp != "" && $cont < $num_rows){
					$disabledLine = 'class="disabledLine"';
				}else{ 
					$disabledLine = "";
				}	
			
			$pendencia .= '<tr '.$disabledLine.'">
				<td colspan="2">
					<b>Complemento da pergunta:</b>
					<font style="font-size: 6pt">'.date("H:i:s",$rs_pend_compl->os_pend_compl_desc_dt).'</font>
						&nbsp;'.date("d-m-y",$rs_pend_compl->os_pend_compl_desc_dt).'
				</td>
			</tr>
			<tr '.$disabledLine.'>
				<td colspan="2">'.$rs_pend_compl->os_pend_compl_desc.'</td>
			</tr>
			<tr '.$disabledLine.'">
				<td colspan="2">
					<b>Resposta:</b> ';
			
			if($rs_pend_compl->os_pend_compl_resp_dt != 0){
				$pendencia .= '<font style="font-size: 6pt">'.date("H:i:s",$rs_pend_compl->os_pend_compl_resp_dt).'</font>
				&nbsp;'.date("d-m-y",$rs_pend_compl->os_pend_compl_resp_dt);
			}
			
			$pendencia .= '&nbsp;</td>
			</tr>
			<tr '.$disabledLine.'>
				<td colspan="2">'.$rs_pend_compl->os_pend_compl_resp.'</td>
			</tr>
			';
				$cont++;
			}
		}
		
			$pendencia .= '<tr><td colspan="2">&nbsp;</td></tr>';
			return $pendencia;
		}
	}
	
	function getAtribTec(){
		if($this->oldTecid != ""){
			$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$this->oldTecid;
			$old_tec_sqlQuery = mysql_query($sql);
			$old_tec_rs = mysql_fetch_object($old_tec_sqlQuery);
			
			$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$this->newTecid;
			$new_tec_sqlQuery = mysql_query($sql);
			$new_tec_rs = mysql_fetch_object($new_tec_sqlQuery);
			
			return '<tr><td colspan="2"class="titulo">Atribui&ccedil;&atilde;o t&eacute;cnica</td></tr>
					<tr><td colspan="2"><b>T&eacute;cnico Anterior:</b> '.$old_tec_rs->usu_nome.'</td></tr>
					<tr><td colspan="2"><b>Novo T&eacute;cnico:</b> '.$new_tec_rs->usu_nome.'</td></tr>
					<tr><td colspan="2"><b>Justificativa:</b></td></tr>
					<tr>
						<td colspan="2">
							'.$this->atribJust.'
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
		}
	}	
	
	function getSupportMsg(){
		if($this->supportFlds != ""){
			return '<tr><td colspan="2"class="titulo">Informa&ccedil;&otilde;es</td></tr>
					<tr><td colspan="2"><b>Nome:</b> '.$this->supportFlds[0].'</td></tr>
					<tr><td colspan="2"><b>Nome do contato:</b> '.$this->supportFlds[1].'</td></tr>
					<tr><td colspan="2"><b>E-mail para contato:</b> '.$this->supportFlds[2].'</td></tr>
					<tr><td colspan="2"><b>Assunto:</b> '.$this->supportFlds[3].'</td></tr>
					<tr><td colspan="2"><b>Mensagem:</b></td></tr>
					<tr>
						<td colspan="2">
							'.$this->supportFlds[4].'
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';
		}else{
			return '';
		}
	}
	
	//Montando notifica&ccedil;&atilde;o
	function create(){
		$this->not = '<html>';
		$this->not .= '<head>';
		$this->not .= $this->getStyle();
		$this->not .= '</head>';
		$this->not .= '<body>
							<table cellpadding="0" cellspacing="0" class="shadow" align="center" border="0" width="600px">
								<tr style="background: none;">
									<td>
										<img src="'.$this->emp_site.'img/not_top.jpg" style="margin-bottom: -2px"
											 border="0" width="600px" height="10px" />
									</td>
								</tr>
								<tr>
									<td align="center">
										<table width="98%" cellpadding="0" cellspacing="0" class="noshadow">
											<tr>
												<td align="center" valign="middle" width="50%" style="border: none">';
		$this->not .= $this->getTitle();		
		$this->not .= '</td>
						<td style="border: none" align="center">
							<a href="'.$this->emp_site.'" title="'.$this->emp_site.'">
								<img src="'.$this->emp_logo.'" border="0" />
							</a>
						</td>
					</tr>';
		$this->not .= $this->getUser();
		$this->not .= $this->getFornecedor();
		$this->not .= $this->getClassProduto();
		$this->not .= $this->getProduto();
		$this->not .= $this->getMovEntr();
		$this->not .= $this->getMovSusoint();
		$this->not .= $this->getServico();
		$this->not .= $this->getOs();
		$this->not .= $this->getCliente();
		$this->not .= $this->getUserLogin();
		$this->not .= $this->getEquipamento();
		$this->not .= $this->getAtribTec();
		$this->not .= $this->getRel();
		$this->not .= $this->getPend();
		$this->not .= $this->getSysLink();
		$this->not .= $this->getSupportMsg();
		$this->not .= '<tr><td colspan="2" style="border: none">&nbsp;</td></tr>
									<tr>
										<td colspan="2" align="center" style="border: none; background-color: '.$this->colors[2].'; padding: 2px">
											Acesse seu painel de controle para mais detalhes
											<a href="'.$this->emp_site.'">clicando aqui</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="background-position: top; background-repeat: no-repeat">
							<td>
								<img src="'.$this->emp_site.'img/not_bottom.jpg" border="0" width="600px" height="10px"/>
							</td>
						</tr>
					</table>
				</body>
				</html>';
				
		return $this->not;	
	}	
}

###CLASSE DE CONVERÇÃO DE HTML PARA DOC###

/**
 * @author Harish Chauhan
 * @version 1.0.0
 * @name HtmlToDoc
 */

class HtmlToDoc{
	var $docFile="";
	var $title="";
	var $htmlHead="";
	var $htmlBody="";
	var $diretorio = "";
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	function HtmlToDoc(){
		$this->title="Untitled Document";
		$this->htmlHead="";
		$this->htmlBody="";
	}
	
	/**
	 * Set the document file name
	 *
	 * @param String $docfile 
	 */
	
	function setDocFileName($docfile){			
		$this->docFile=$docfile;
	}
	
	function setTitle($title){			
		$this->title=$title;
	}
	
	/**
	 * Return header of MS Doc
	 *
	 * @return String
	 */
	function getHeader(){			
		$return  = <<<EOH
		 <html xmlns:v="urn:schemas-microsoft-com:vml"
		xmlns:o="urn:schemas-microsoft-com:office:office"
		xmlns:w="urn:schemas-microsoft-com:office:word"
		xmlns="http://www.w3.org/TR/REC-html40">
		
		<head>
		<meta http-equiv=Content-Type content="text/html; charset=ISO-8859-1">
		<meta name=ProgId content=Word.Document>
		<meta name=Generator content="Microsoft Word 9">
		<meta name=Originator content="Microsoft Word 9">
		<!--[if !mso]>
		<style>
		v\:* {behavior:url(#default#VML);}
		o\:* {behavior:url(#default#VML);}
		w\:* {behavior:url(#default#VML);}
		.shape {behavior:url(#default#VML);}
		</style>
		<![endif]-->
		<title>$this->title</title>
		<!--[if gte mso 9]><xml>
		 <w:WordDocument>
		  <w:View>Print</w:View>
		  <w:DoNotHyphenateCaps/>
		  <w:PunctuationKerning/>
		  <w:DrawingGridHorizontalSpacing>9.35 pt</w:DrawingGridHorizontalSpacing>
		  <w:DrawingGridVerticalSpacing>9.35 pt</w:DrawingGridVerticalSpacing>
		 </w:WordDocument>
		</xml><![endif]-->
		
		<!--[if gte mso 9]><xml>
		 <o:shapedefaults v:ext="edit" spidmax="1032">
		  <o:colormenu v:ext="edit" strokecolor="none"/>
		 </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>
		 <o:shapelayout v:ext="edit">
		  <o:idmap v:ext="edit" data="1"/>
		 </o:shapelayout></xml><![endif]-->
		 $this->htmlHead
		</head>			
EOH;
	return $return;
	}
	
	/**
	 * Create The MS Word Document from given HTML
	 *
	 * @param String $html
	 * @param String $file
	 * @param Boolean $download
	 * @return boolean 
	 */
	
	function createDoc($html,$file){			
		$this->setDocFileName($file);
		$doc=$this->getHeader();
		$doc.=$html;
		
		return $this->write_file($this->docFile,$doc);
		
	}

	
	/**
	 * @param String $file :: File name to be save
	 * @param String $content :: Content to be write
	 * @param [Optional] String $mode :: Write Mode
	 * @return void
	 * @access boolean True on success else false
	 */
	
	function setDir($diretorio){
		$this->diretorio = $diretorio;
	}
	
	function write_file($file,$content,$mode="w"){	
		$fp=fopen($this->diretorio.$file,$mode);
		
		fwrite($fp,$content);
		fclose($fp);
		return true;
	}
}
?>