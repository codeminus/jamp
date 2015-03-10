<?php

require("../../sys/_config.php");
require("../../sys/_dbconn.php");
require("../../css/_colors.php");
require("../_lib.php");


if (isset($_GET["os_com_remocao"])) {
  $remocao = "S";
  $rem_msg = "Removido do local";
  $dt_remocao = (time() - (date('I') * 3600));
} else {
  $remocao = "N";
  $rem_msg = "No local";
  $dt_remocao = "";
}


if ($_GET["cmd"] == "altos") {

  if (isset($_GET["os_orcamento"])) {
    $os_sta_id = "os_sta_id='1',";
    $os_data_orcamento = "os_data_orcamento='',";
    $os_orc_usu_id = "os_orc_usu_id='',";
  }

  if (isset($_GET["os_mostrar_valor"])) {
    $os_mostrar_valor = "S";
  } else {
    $os_mostrar_valor = "N";
  }

  if (isset($_GET["os_data_prazo"])) {
    $dt = split("/", $_GET["os_data_prazo"]);
    $dt_prazo = "os_data_prazo='" . mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]) . "',";
  } else {
    $dt_prazo = "os_data_prazo='',";
  }

  $sql = "UPDATE os SET
				" . $os_sta_id . "
				" . $os_data_orcamento . "
				" . $os_orc_usu_id . "
				" . $dt_prazo . "
				os_tec_obs='" . $_GET["os_tec_obs"] . "',
				os_mostrar_valor='" . $os_mostrar_valor . "',
				os_alter_usu_id='" . $_GET["cad_tec_id"] . "',
				os_data_alter='" . (time() - (date('I') * 3600)) . "',
				os_com_remocao='" . $remocao . "',
				os_com_remocao_dt='" . $dt_remocao . "' WHERE os_id=" . $_GET["os_id"];
  $sqlQuery = mysql_query($sql);


  $fields = array_keys($_GET);
  $values = array_values($_GET);

  $error = "";


  $os_dia_alteracao = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

  //Salvando serviços	

  for ($i = 0; $i < count($fields); $i++) {
    if (strpos($fields[$i], "serv_id_") > -1) {

      $sql = "SELECT os_serv_quant,os_serv_valor FROM os_serv WHERE os_serv_os_id='" . $_GET["os_id"] . "' AND os_serv_serv_id=" . $values[$i];
      $serv_sqlQuery = mysql_query($sql);
      $this_serv_rs = mysql_fetch_object($serv_sqlQuery);




      if ($_GET["serv_quant_" . $values[$i]] > 0) {
        if ($this_serv_rs->os_serv_quant == 0) {
          $sql = "INSERT INTO os_serv (
								os_serv_os_id,
								os_serv_serv_id,							
								os_serv_valor,
								os_serv_quant
							) VALUES (
								'" . $_GET["os_id"] . "',
								'" . $values[$i] . "',
								'" . $_GET["serv_valor_" . $values[$i]] . "',
								'" . $_GET["serv_quant_" . $values[$i]] . "'
							)";
          $serv_sqlQuery = mysql_query($sql) or die(mysql_error());
        } elseif ($_GET["serv_quant_" . $values[$i]] != $this_serv_rs->os_serv_quant || $_GET["serv_valor_" . $values[$i]] != $this_serv_rs->os_serv_valor) {
          $sql = "UPDATE os_serv SET
								os_serv_valor='" . $_GET["serv_valor_" . $values[$i]] . "',
								os_serv_quant='" . $_GET["serv_quant_" . $values[$i]] . "'
							WHERE os_serv_os_id='" . $_GET["os_id"] . "' AND os_serv_serv_id=" . $values[$i];
          $serv_sqlQuery = mysql_query($sql) or die(mysql_error());
        }
      }
    }
  }

  //Salvando produtos

  for ($i = 0; $i < count($fields); $i++) {
    if (strpos($fields[$i], "prod_id_") > -1) {

      $sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=" . $values[$i];
      $mov_sqlQuery = mysql_query($sql);
      $quant_mov_entr = mysql_num_rows($mov_sqlQuery);
      $quant_entrada = 0;
      while ($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)) {
        $quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
      }

      $sql = "SELECT mov_sos_vvalor, mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_os_id='" . $_GET["os_id"] . "' AND mov_sos_prod_id=" . $values[$i];
      $mov_sqlQuery = mysql_query($sql);
      $this_prod_rs = mysql_fetch_object($mov_sqlQuery);
      $quant_reservada = $this_prod_rs->mov_sos_quant_saida;

      $quant_saida = 0;

      $sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_os_id<>'" . $_GET["os_id"] . "' AND mov_sos_prod_id=" . $values[$i];
      $mov_sqlQuery = mysql_query($sql);
      $quant_mov_sos = mysql_num_rows($mov_sqlQuery);

      while ($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)) {
        $quant_saida += $rs_mov_sos->mov_sos_quant_saida;
      }

      $sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=" . $values[$i];
      $mov_sqlQuery = mysql_query($sql) or die(mysql_error());
      $quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
      while ($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)) {
        $quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
      }

      $quant_disponivel = $quant_entrada + $quant_reservada - $quant_saida;



      if ($quant_disponivel >= $_GET["prod_quant_" . $values[$i]]) {
        if ($_GET["prod_quant_" . $values[$i]] > 0) {
          if ($quant_reservada == 0) {
            $sql = "INSERT INTO mov_saida_os (
							mov_sos_os_id,
							mov_sos_prod_id,
							mov_sos_vvalor,
							mov_sos_quant_saida,
							mov_sos_dt_saida
						) VALUES (
							'" . $_GET["os_id"] . "',
							'" . $values[$i] . "',
							'" . $_GET["prod_valor_" . $values[$i]] . "',
							'" . $_GET["prod_quant_" . $values[$i]] . "',
							'" . $os_dia_alteracao . "'
						)";
            $prod_sqlQuery = mysql_query($sql) or die(mysql_error());
          } elseif ($_GET["prod_quant_" . $values[$i]] != $quant_reservada || $_GET["prod_valor_" . $values[$i]] != $this_prod_rs->mov_sos_vvalor) {
            $sql = "UPDATE mov_saida_os SET
									mov_sos_vvalor='" . $_GET["prod_valor_" . $values[$i]] . "',
									mov_sos_quant_saida='" . $_GET["prod_quant_" . $values[$i]] . "',
									mov_sos_dt_saida='" . $os_dia_alteracao . "'
								WHERE mov_sos_os_id='" . $_GET["os_id"] . "' AND mov_sos_prod_id=" . $values[$i];
            $prod_sqlQuery = mysql_query($sql) or die(mysql_error());
          }
        }
      } else {
        $error = "erprodos";
      }
    }
  }

  //verificando se o cliente deve ser notificado por email
  $sql = "SELECT cli_not_cad_os,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=" . $_GET["cli_id"];
  $sqlQuery = mysql_query($sql);
  $rs_cli = mysql_fetch_object($sqlQuery);

  if ($rs_cli->cli_not_cad_os == "S") {

    $email = new Email($config["emp_email"]);
    $email->addTo($rs_cli->usu_email);
    $email->setSubject("OSID: #" . $_GET["os_id"] . " Ordem de serviço alterada");

    $message = new Notification("<b>OSID: #" . $_GET["os_id"] . "<br/>Ordem de serviço alterada</b>");
    $message->setConfig($config["emp_site"],
            $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($_GET["os_id"]);
    $message->setCliente($_GET["cli_id"]);
    $message->setEquipamento($_GET["equ_id"]);

    $email->setMessage($message->create());
    $email->send();
  }
} elseif ($_GET["cmd"] == "altos_cli") {
  $sql = "UPDATE os SET
				os_alter_usu_id='" . $_GET["cli_id"] . "',
				os_data_alter='" . (time() - (date('I') * 3600)) . "',
				os_cli_obs='" . $_GET["obrg_os_cli_obs"] . "',
				os_cli_obs_dt='" . (time() - (date('I') * 3600)) . "'
			WHERE os_id=" . $_GET["os_id"];
  $sqlQuery = mysql_query($sql);

  $email = new Email($config["emp_email"]);

  //selecionando email do tecnico
  if ($_GET["tec_id"] != "") {
    $sql = "SELECT usu_email FROM usuario WHERE usu_id=" . $_GET["tec_id"];
    $tec_sqlQuery = mysql_query($sql);
    $rs_tec = mysql_fetch_object($tec_sqlQuery);
    $email->addTo($rs_tec->usu_email);
  } else {
    $sql = "SELECT usu_email FROM usuario WHERE usu_not_user='S' AND usu_status<>'I'";
    $not_sqlQuery = mysql_query($sql);
    while ($not_rs = mysql_fetch_object($not_sqlQuery)) {
      $email->addTo($not_rs->usu_email);
    }
  }

  $email->setSubject("OSID: #" . $_GET["os_id"] . " Observações da ordem de serviço alterada");
  $message = new Notification("<b>OSID: #" . $_GET["os_id"] . " <br/>Observa&ccedil;&otilde;es da ordem de serviço alteradas</b>");
  $message->setConfig($config["emp_site"],
          $config["emp_site"] . "img/" . $config["emp_logo"]);
  $message->setColors($colors);
  $message->setOs($_GET["os_id"]);
  $message->setCliente($_GET["cli_id"]);
  $message->setEquipamento($_GET["equ_id"]);

  $email->setMessage($message->create());
  $email->send();
} elseif ($_GET["cmd"] == "cad_cli_os") {
  $sql = "INSERT INTO os(
				os_cli_id,
				os_equ_id,
				os_sta_id,
				os_tip_id,
				os_cad_usu_id,
				os_data_abertura,
				os_dia_abertura,
				os_data_orcamento,
				os_orc_usu_id,
				os_autorizado_por,
				os_pro_cli_descricao,
				os_cli_obs
			) VALUES(
				'" . $_GET["cli_id"] . "',
				'" . $_GET["equ_id"] . "',
				6,
				2,
				'" . $_GET["cli_id"] . "',
				'" . (time() - (date('I') * 3600)) . "',
				'" . mktime(0, 0, 0, date('m'), date('d'), date('Y')) . "',
				'" . (time() - (date('I') * 3600)) . "',
				'" . $_GET["cli_id"] . "',
				'" . $_GET["obrg_os_autorizado_por"] . "',
				'" . $_GET["obrg_os_pro_cli_descricao"] . "',
				'" . $_GET["os_cli_obs"] . "'
			)";

  $sqlQuery = mysql_query($sql) or die(mysql_error());
  $os_id = mysql_insert_id();

  $email = new Email($config["emp_email"]);
  $email->addTo($config["emp_email"]);

  //notificando a equipe de suporte
  $sql = "SELECT * FROM usuario WHERE usu_tipo='T' AND usu_not_os='S' AND usu_status<>'I'";
  $sqlQuery = mysql_query($sql);

  while ($rs_tec = mysql_fetch_object($sqlQuery)) {
    $email->addTo($rs_tec->usu_email);
  }

  $email->setSubject("OSID: #" . $os_id . " Nova ordem de serviço gerada pelo cliente");

  $message = new Notification("<b>OSID: #" . $os_id . " <br/>Nova ordem de serviço gerada pelo cliente</b>");
  $message->setConfig($config["emp_site"],
          $config["emp_site"] . "img/" . $config["emp_logo"]);
  $message->setColors($colors);
  $message->setOs($os_id);
  $message->setCliente($_GET["cli_id"]);
  $message->setEquipamento($_GET["equ_id"]);
  $email->setMessage($message->create());
  $email->send();
} elseif ($_GET["cmd"] == "concluir") {

  $dt = split("/", $_GET["obrg_dt_conclusao"]);
  $dt_conclusao = "os_data_conclusao='" . mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]) . "'";

  $sql = "UPDATE os SET 
				os_sta_id=5,
				os_alter_usu_id='" . $_GET["alter_tec_id"] . "',
				os_data_alter='" . (time() - (date('I') * 3600)) . "',
				" . $dt_conclusao . "
			WHERE os_id=" . $_GET["os_id"];

  $sqlQuery = mysql_query($sql);

  //verificando se o cliente deve ser notificado por email
  $sql = "SELECT cli_not_cad_os,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=" . $_GET["cli_id"];
  $sqlQuery = mysql_query($sql);
  $rs_cli = mysql_fetch_object($sqlQuery);

  if ($rs_cli->cli_not_cad_os == "S") {

    $email = new Email($config["emp_email"]);
    $email->addTo($rs_cli->usu_email);
    $email->setSubject("OSID: #" . $_GET["os_id"] . " Ordem de serviço finalizada");

    $message = new Notification("<b>OSID: #" . $_GET["os_id"] . "<br/>Ordem de serviço finalizada</b>");
    $message->setConfig($config["emp_site"],
            $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($_GET["os_id"]);
    $message->setCliente($_GET["cli_id"]);
    $message->setEquipamento($_GET["equ_id"]);

    $email->setMessage($message->create());
    $email->send();
  }
} elseif ($_GET["cmd"] == "excluir") {
  $sql = "UPDATE os SET
				os_historico='S',
				os_dt_historico='" . (time() - (date('I') * 3600)) . "'
			WHERE os_id=" . $_GET["os_id"];

  $sqlQuery = mysql_query($sql);

  if ($_GET["not"] == "S") {
    //selecionando o cliente
    $sql = "SELECT usu_email FROM usuario WHERE usu_id=" . $_GET["cli_id"];
    $sqlQuery = mysql_query($sql);
    $rs_cli = mysql_fetch_object($sqlQuery);

    $email = new Email($config["emp_email"]);
    $email->addTo($rs_cli->usu_email);
    $email->setSubject("OSID: #" . $_GET["os_id"] . " Ordem de serviço excluída");

    $message = new Notification("<b>OSID: #" . $_GET["os_id"] . "<br/>Ordem de serviço excluída</b>");
    $message->setConfig($config["emp_site"],
            $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($_GET["os_id"]);
    $message->setCliente($_GET["cli_id"]);
    $message->setEquipamento($_GET["equ_id"]);

    $email->setMessage($message->create());
    $email->send();
  }
} elseif ($_GET["cmd"] == "aprov_os") {

  if ($_GET["aprov"] == "S") {
    $sql = "SELECT os_tec_id FROM os WHERE os_id=" . $_GET["os_id"];
    $sqlQuery = mysql_query($sql);
    $rs = mysql_fetch_object($sqlQuery);

    if ($rs->os_tec_id != "" && $rs->os_tec_id != "0") {
      $os_sta_id = 4;
    } else {
      $os_sta_id = 6;
    }

    $sql = "UPDATE os SET
					os_sta_id='" . $os_sta_id . "',
					os_data_orcamento='" . (time() - (date('I') * 3600)) . "',
					os_orc_usu_id='" . $_GET["usu_id"] . "',
					os_alter_usu_id='" . $_GET["usu_id"] . "',
					os_data_alter='" . (time() - (date('I') * 3600)) . "'
				WHERE os_id=" . $_GET["os_id"];
  } else {
    $sql = "UPDATE os SET
			os_sta_id='8',			
			os_alter_usu_id='" . $_GET["usu_id"] . "',
			os_data_alter='" . (time() - (date('I') * 3600)) . "'
		WHERE os_id=" . $_GET["os_id"];
  }
  $sqlQuery = mysql_query($sql);
} elseif ($_GET["cmd"] == "serv_del") {
  $sql = "DELETE FROM os_serv WHERE os_serv_os_id ='" . $_GET["os_id"] . "' AND os_serv_serv_id ='" . $_GET["item_id"] . "'";
  $sqlQuery = mysql_query($sql);
} elseif ($_GET["cmd"] == "prod_del") {
  $sql = "DELETE FROM mov_saida_os WHERE mov_sos_os_id ='" . $_GET["os_id"] . "' AND mov_sos_prod_id ='" . $_GET["item_id"] . "'";
  $sqlQuery = mysql_query($sql);
} else {

  if (isset($_GET["os_data_prazo"])) {
    $dt = split("/", $_GET["os_data_prazo"]);
    $dt_prazo = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
  } else {
    $dt_prazo = "";
  }

  $os_dia_abertura = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

  if (isset($_GET["os_orcamento"])) {
    $status = 1;
    $os_data_orcamento = "";
    $os_dt_manutencao = "";
    $os_tec_id = "";
    $os_orc_usu_id = "";
  } elseif ($_GET["tec_id"] == "") {
    $status = 6;
    $os_data_orcamento = (time() - (date('I') * 3600));
    $os_dt_manutencao = "";
    $os_tec_id = "";
    $os_orc_usu_id = $_GET["cad_tec_id"];
  } else {
    $status = 4;
    $os_data_orcamento = (time() - (date('I') * 3600));
    $os_dt_manutencao = (time() - (date('I') * 3600));
    $os_tec_id = $_GET["tec_id"];
    $os_orc_usu_id = $_GET["cad_tec_id"];
  }

  (isset($_GET["os_mostrar_valor"])) ? $mostrar_valor = "S" : $mostrar_valor = "N";

  if (!isset($error)) {
    $sql = "INSERT INTO os(
					os_cli_id,
					os_equ_id,
					os_tec_id,
					os_sta_id,
					os_tip_id,
					os_cad_usu_id,
					os_data_abertura,
					os_dia_abertura,
					os_data_orcamento,
					os_orc_usu_id,
					os_data_inicio_manutencao,
					os_data_prazo,
					os_autorizado_por,
					os_pro_cli_descricao,
					os_com_remocao,
					os_com_remocao_dt,
					os_tec_obs,
					os_mostrar_valor
				) VALUES(
					'" . $_GET["cli_id"] . "',
					'" . $_GET["equ_id"] . "',
					'" . $os_tec_id . "',
					" . $status . ",
					2,
					'" . $_GET["cad_tec_id"] . "',
					'" . (time() - (date('I') * 3600)) . "',
					'" . $os_dia_abertura . "',
					'" . $os_data_orcamento . "',
					'" . $os_orc_usu_id . "',
					'" . $os_dt_manutencao . "',
					'" . $dt_prazo . "',
					'" . $_GET["obrg_os_autorizado_por"] . "',
					'" . $_GET["obrg_os_pro_cli_descricao"] . "',
					'" . $remocao . "',
					'" . $dt_remocao . "',
					'" . $_GET["os_tec_obs"] . "',
					'" . $mostrar_valor . "'
				)";

    $sqlQuery = mysql_query($sql) or die(mysql_error());
    $os_id = mysql_insert_id();


    $fields = array_keys($_GET);
    $values = array_values($_GET);

    $error = "";


    //Salvando serviços	

    for ($i = 0; $i < count($fields); $i++) {
      if (strpos($fields[$i], "serv_id_") > -1) {

        if ($_GET["serv_quant_" . $values[$i]] > 0) {
          $sql = "INSERT INTO os_serv (
								os_serv_os_id,
								os_serv_serv_id,							
								os_serv_valor,
								os_serv_quant
							) VALUES (
								'" . $os_id . "',
								'" . $values[$i] . "',
								'" . $_GET["serv_valor_" . $values[$i]] . "',
								'" . $_GET["serv_quant_" . $values[$i]] . "'
							)";
          $serv_sqlQuery = mysql_query($sql) or die(mysql_error());
        }
      }
    }

    //Salvando produtos

    for ($i = 0; $i < count($fields); $i++) {
      if (strpos($fields[$i], "prod_id_") > -1) {

        $sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=" . $values[$i];
        $mov_sqlQuery = mysql_query($sql);
        $quant_mov_entr = mysql_num_rows($mov_sqlQuery);
        $quant_entrada = 0;
        while ($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)) {
          $quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
        }

        $quant_saida = 0;

        $sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=" . $values[$i];
        $mov_sqlQuery = mysql_query($sql);
        $quant_mov_sos = mysql_num_rows($mov_sqlQuery);

        while ($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)) {
          $quant_saida += $rs_mov_sos->mov_sos_quant_saida;
        }

        $sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=" . $values[$i];
        $mov_sqlQuery = mysql_query($sql) or die(mysql_error());
        $quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
        while ($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)) {
          $quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
        }

        $quant_disponivel = $quant_entrada - $quant_saida;

        if ($quant_disponivel >= $_GET["prod_quant_" . $values[$i]] && $_GET["prod_quant_" . $values[$i]] > 0) {
          $sql = "INSERT INTO mov_saida_os (
								mov_sos_os_id,
								mov_sos_prod_id,
								mov_sos_vvalor,
								mov_sos_quant_saida,
								mov_sos_dt_saida
							) VALUES (
								'" . $os_id . "',
								'" . $values[$i] . "',
								'" . $_GET["prod_valor_" . $values[$i]] . "',
								'" . $_GET["prod_quant_" . $values[$i]] . "',
								'" . $os_dia_abertura . "'
							)";
          $prod_sqlQuery = mysql_query($sql) or die(mysql_error());
        } else {
          $error = "erprodos";
        }
      }
    }

    if (!isset($_GET["os_orcamento"]) && $_GET["tec_id"] != "") {

      $sql = "INSERT INTO osetec(
						ot_os_id,
						ot_tec_id,
						ot_atrib_tec_id,
						ot_atrib_just,
						ot_dt_inicio
					) VALUES(
						'" . $os_id . "',
						'" . $_GET["tec_id"] . "',
						'" . $_GET["cad_tec_id"] . "',
						'Abertura de OS',
						'" . (time() - (date('I') * 3600)) . "'
					)";

      $ot_sqlQuery = mysql_query($sql);
    }

    //gerando relatorio
    if ($_GET["rel_cont"] != "") {
      $sql = "INSERT INTO os_relatorio (
						rel_tec_id,
						rel_os_id,
						rel_cont,
						rel_data_criacao
					) VALUES(
						'" . $_GET["cad_tec_id"] . "',
						'" . $os_id . "',
						'" . $_GET["rel_cont"] . "',
						'" . (time() - (date('I') * 3600)) . "'
					)";
      $sqlQuery = mysql_query($sql);
      $rel_id = mysql_insert_id();
    }

    //gerando pendencia
    if ($_GET["pend_desc"] != "") {
      $sql = "INSERT INTO os_pendencia (
						pend_os_id,
						pend_tec_id,
						pend_assunto,
						pend_desc,
						pend_status,
						pend_data_criacao
					) VALUES(
						'" . $os_id . "',
						'" . $_GET["cad_tec_id"] . "',
						'" . $_GET["pend_assunto"] . "',
						'" . $_GET["pend_desc"] . "',
						'A',
						'" . (time() - (date('I') * 3600)) . "'
					)";
      $sqlQuery = mysql_query($sql);
      $pend_id = mysql_insert_id();
    }

    //verificando se o cliente deve ser notificado por email
    $sql = "SELECT cli_not_cad_os,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=" . $_GET["cli_id"];
    $sqlQuery = mysql_query($sql);
    $rs_cli = mysql_fetch_object($sqlQuery);

    $email = new Email($config["emp_email"]);

    if ($rs_cli->cli_not_cad_os == "S") {
      $email->addTo($rs_cli->usu_email);
    }

    //notificando equipe tecnica		
    $sql = "SELECT usu_email FROM usuario WHERE usu_not_os='S' AND usu_status<>'I' AND usu_tipo='T' AND usu_id<>" . $_GET["cad_tec_id"];
    $not_sqlQuery = mysql_query($sql);
    while ($not_rs = mysql_fetch_object($not_sqlQuery)) {
      $email->addTo($not_rs->usu_email);
    }


    $email->setSubject("OSID: #" . $os_id . " Nova ordem de serviço gerada");

    $message = new Notification("<b>OSID: #" . $os_id . "<br/>Nova ordem de serviço gerada</b>");
    $message->setConfig($config["emp_site"],
            $config["emp_site"] . "img/" . $config["emp_logo"]);
    $message->setColors($colors);
    $message->setOs($os_id);
    $message->setCliente($_GET["cli_id"]);
    $message->setEquipamento($_GET["equ_id"]);
    $message->setRel($rel_id);
    $message->setPend($pend_id);
    $email->setMessage($message->create());
    $email->send();

    if ($error != "") {
      echo $error;
    }
  }
}
mysql_close();
?>