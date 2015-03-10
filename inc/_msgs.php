<?php

//login
$msgs["lerror"] = "Login ou senha incorreta.";
$msgs["lpass"] = "Para sua maior segurança crie uma nova senha.";
$msgs["lforget"] = "Por favor digite o seu endere&ccedil;o de e-mail cadastrado em nosso sistema.";
$msgs["lfinfo"] = "Um e-mail foi enviado a sua caixa de correio com suas informa&ccedil;&otilde;es de acesso.";
$msgs["llocked"] = "Usu&aacute;rio bloqueado. Favor contatar a equipe de suporte.";
$msgs["lquit"] = "Sess&atilde;o finalizada com sucesso.";


//informacoes
$msgs["altinfo"] = "Suas informa&ccedil;&otilde;es foram salvas com sucesso.";

//configuracoes
$msgs["altconf"] = "Configura&ccedil;&otilde;es alteradas com sucesso.";
$msgs["erfconf"] = "Formato de arquivo inv&aacute;lido! A imagem deve ser jpg, jpeg, bmp, gif ou png.";
$msgs["ertconf"] = "O tamanho da imagem n&atilde;o pode exceder " .($config["tamanho"]/1024). " Kb.";
$msgs["erlconf"] = "A largura da imagem n&atilde;o deve exceder " . $config["largura"] . " pixels.";
$msgs["eraconf"] = "A altura da imagem n&atilde;o deve exceder " . $config["altura"] . " pixels.";
$msgs["erpconf"] = "Verifique as permiss&otile;es de escrita para os arquivos dos diret&oacute;rios ".$config["emp_site"]."/jamp/sys e ".$config["emp_site"]."/jamp/img.";

//usuario
$msgs["auser"] = "Novo usu&aacute;rio cadastrado com sucesso.";
$msgs["altuser"] = "Informa&ccedil;&otilde;es do usu&aacute;rio alteradas com sucesso.";
$msgs["puser"] = "Acesso negado.";
$msgs["eruser"] = "Acesso negado.";
$msgs["dupuser"] = "N&atilde;o foi poss&iacute;vel cadastrar o usu&aacute;rio. J&aacute; existe um registro com o mesmo nome de usu&aacute;rio.";

//Fornecedor
$msgs["aforn"] = "Novo fornecedor cadastrado com sucesso.";
$msgs["altforn"] = "Informa&ccedil;&otilde;es do fornecedor alteradas com sucesso.";
$msgs["pforn"] = "Acesso negado.";
$msgs["erforn"] = "Erro na altera&ccedil;&atilde;o das informa&ccedil;&otilde;es do fornecedor, uma ou mais informa&ccedil;&otilde;es nao foram salvas.";
$msgs["exforn"] = "Fornecedor exclu&iacute;do com sucesso.";

//Classificacao de produto
$msgs["aclassprod"] = "Nova classifica&ccedil;&atilde;o cadastrada com sucesso.";
$msgs["altclassprod"] = "Informa&ccedil;&otilde;es da classifica&ccedil;&atilde;o alteradas com sucesso.";
$msgs["pclassprod"] = "Acesso negado.";
$msgs["erclassprod"] = "Erro na altera&ccedil;&atilde;o das informa&ccedil;&otilde;es da classifica&ccedil;&atilde;o, uma ou mais informa&ccedil;&otilde;es nao foram salvas.";
$msgs["exclassprod"] = "Classifica&ccedil;&atilde;o exclu&iacute;da com sucesso.";


//produto
$msgs["aprod"] = "Novo produto cadastrado com sucesso.";
$msgs["altprod"] = "Informa&ccedil;&otilde;es do produto alteradas com sucesso.";
$msgs["pprod"] = "Acesso negado.";
$msgs["erprod"] = "Erro na altera&ccedil;&atilde;o das informa&ccedil;&otilde;es do produto, uma ou mais informa&ccedil;&otilde;es nao foram salvas.";
$msgs["exprod"] = "Produto exclu&iacute;do com sucesso.";

//Movimentacao de entrada
$msgs["amoventr"] = "Nova movimenta&ccedil;&atilde;o de entrada cadastrada com sucesso.";
$msgs["ermoventr"] = "J&aacute; existe uma movimenta&ccedil;&otilde;o de entrada com o mesmo produto, n&uacute;mero de nota fiscal, quantidade e fornecedor.";

//Movimentacao de sa�da por uso interno
$msgs["amovsusoint"] = "Nova movimenta&ccedil;&atilde;o de sa&iacute;da cadastrada com sucesso.";
$msgs["ermovsusoint"] = "Quantidade solicitada do produto n&atilde;o dispon&iacute;vel";

//Movimentacao de saida ordem de servico
$msgs["amovsos"] = "Nova movimenta&ccedil;&atilde;o de sa&iacute;da cadastrada com sucesso.";
$msgs["ermovsos"] = "Quantidade solicitada do produto n&atilde;o dispon&iacute;vel";

//Servico
$msgs["aserv"] = "Novo servi&ccedil;o cadastrado com sucesso.";
$msgs["pserv"] = "Acesso negado.";
$msgs["dupserv"] = "N&atilde;o foi poss&iacute;vel cadastrar o servi&ccedil;o. J&aacute; existe um registro com o mesmo nome.";
$msgs["altserv"] = "Informa&ccedil;&otilde;es do sevi&ccedil;o alteradas com sucesso.";


//cliente
$msgs["acli"] = "Novo cliente cadastrado com sucesso.";
$msgs["altcli"] = "Informa&ccedil;&otilde;es do cliente alteradas com sucesso.";
$msgs["pcli"] = "Acesso negado.";
$msgs["ercli"] = "Erro na altera&ccedil;&atilde;o das informa&ccedil;&otilde;es do cliente, uma ou mais informa&ccedil;&otilde;es nao foram salvas.";


//equipamento
$msgs["aequ"] = "Novo equipamento cadastrado com sucesso.";
$msgs["aequinfo"] = "Selecione um cliente e clique em seu respectivo bot&atilde;o de cadastrar equipamento.";
$msgs["pequ"] = "Acesso negado.";
$msgs["altequ"] = "Informa&ccedil;&otilde;es do equipamento alteradas com sucesso.";
$msgs["fequ"] = "Cadastre pelo menos um cliente antes de cadastrar um equipamento.";
$msgs["erequ"] = "Erro na altera&ccedil;&atilde;o das informa&ccedil;&otilde;es do equipamento, uma ou mais informa&ccedil;&otilde;es nao foram salvas.";
$msgs["exequ"] = "Equipamento exclu&iacute;do com sucesso.";

//ordem de servico
$msgs["aos"] = "Nova ordem de servi&ccedil;o gerada com sucesso.";
$msgs["aprovos"] = "Ordem de servi&ccedil;o autorizada.";
$msgs["naprovos"] = "Ordem de servi&ccedil;o n&atilde;o autorizada, aguardando posi&ccedil;&atilde;o da equipe de suporte.";
$msgs["aosinfo"] = "Selecione um equipamento e clique em seu respectivo bot&atilde;o de gerar ordem de servi&ccedil;o.";
$msgs["altos"] = "Ordem de sevi&ccedil;o alterada com sucesso.";
$msgs["cos"] = "Ordem de sevi&ccedil;o conclu&iacute;da com sucesso.";
$msgs["exos"] = "Ordem de sevi&ccedil;o exclu&iacute;da com sucesso.";
$msgs["erpos"] = "Ordem de sevi&ccedil;o n&atilde;o p&ocirc;de ser cadastrada. Data de prazo inv&aacute;lida.";
$msgs["erservos"] = "Aten&ccedil;&atilde;o, um ou mais servi&ccedil;os n&atilde;o poder&atilde;o ser adicionados devido a disponibilidade.";
$msgs["erprodos"] = "Aten&ccedil;&atilde;o, um ou mais produtos n&atilde;o poderam ser adicionados devido a disponibilidade.";


//relatorio
$msgs["arel"] = "Novo relat&oacute;rio gerado com sucesso.";

//pendencia
$msgs["apend"] = "Nova pend&ecirc;ncia gerada com sucesso.";
$msgs["rpend"] = "Complemento da pend&ecirc;ncia gerado com sucesso.";
$msgs["acpend"] = "Resposta da pend&ecirc;ncia aceita.";
$msgs["erpend"] = "Um erro ocorreu na manipula&ccedil;&atilde;o das pend&ecirc;ncias.";
$msgs["resppend"] = "Pend&ecirc;ncia respondida. Aguardando confirma&ccedil;&atilde;o t&eacute;cnica para valida&ccedil;&atilde;o de sua  resposta.";
$msgs["tecresppend"] = "Pend&ecirc;ncia respondida.";

//responsabilidade tecnica
$msgs["atribtec"] = "T&eacute;cnico atribu&iacute;do com sucesso.";
$msgs["atribntec"] = "Novo t&eacute;cnico atribu&iacute;do com sucesso.";

//Orcamento
$msgs["aorcinfo"] = "Selecione um cliente e clique em seu respectivo bot&atilde;o de gerar or&ccedil;amento.";

//Solicitacao de suporte
$msgs["asup"] = "Mensagem enviada com sucesso.";
?>