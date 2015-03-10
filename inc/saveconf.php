<?php
require("../sys/_logoconfig.php");
// Prepara a variável do arquivo
$arquivo = isset($_FILES["emp_logo"]) ? $_FILES["emp_logo"] : FALSE;

// Formulário postado... executa as ações
if($arquivo["name"] != ""){  

    #Verifica se o mime-type do arquivo é de imagem
    if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])){
        $msg = "erfconf";
    }else{
        // Para verificar as dimensões da imagem
        $tamanhos = getimagesize($arquivo["tmp_name"]);		
		
        if($arquivo["size"] > $config["tamanho"]){
            $msg = "ertconf";
        }elseif($tamanhos[0] > $config["largura"]){
            $msg = "erlconf";
        }elseif($tamanhos[1] > $config["altura"]){
            $msg = "eraconf";
        }
    }
	
	if(!isset($msg)){
	
		//apagando imagem antiga
		@unlink("../img/".$_POST["old_emp_logo"]);
	
        // Pega extensão do arquivo
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);

        // Gera um nome único para a imagem
        $imagem_nome = md5(uniqid(time())) . "." . $ext[1];		
		
        // Caminho de onde a imagem ficará
        $imagem_dir = "../img/" . $imagem_nome;

        // Faz o upload da imagem
        move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
		
		$msg = "altconf";
		
    }
	
}
if(!isset($imagem_nome)){
	$imagem_nome = $_POST["old_emp_logo"];
	
}

if(!isset($msg)){
	$msg = "altconf";
}

if(strpos($_POST["obrg_emp_site"],"http://") > -1){
	$emp_site = $_POST["obrg_emp_site"];
}else{	
	$emp_site = "http://".$_POST["obrg_emp_site"];
}

if(substr($emp_site,strlen($emp_site)-1) != "/"){
	$emp_site .= "/";
}

	$content = '<?php
			$config["emp_nome"] = "'.$_POST["obrg_emp_nome"].'";
			$config["emp_email"] = "'.$_POST["obrg_emp_email"].'";
			$config["emp_site"] = "'.$emp_site.'";
			$config["emp_logo"] = "'.$imagem_nome.'";
			$config["emp_welcome"] = "'.$_POST["emp_welcome"].'";
			$config["termo_remocao"] = "'.$_POST["termo_remocao"].'";
			$config["termo_devolucao"] = "'.$_POST["termo_devolucao"].'";			
		?>';
	$config_file = fopen("../sys/_config.php","w+");			
	fwrite($config_file,$content) or die(
		header("Location: ../index.php?inc=".$_POST["inc"].$_POST["srch"]."&ot=".$_POST["ot"]."&msg=erpconf&pg=".$_POST["pg"])
	);
	
	header("Location: ../index.php?inc=".$_POST["inc"].$_POST["srch"]."&ot=".$_POST["ot"]."&msg=".$msg."&pg=".$_POST["pg"]);
?>