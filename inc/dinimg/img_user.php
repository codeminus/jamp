<?php
session_start();
include("../../sys/phplot/phplot.php");
include("../../sys/_dbconn.php");

$sql = "SELECT os_id FROM os WHERE os_sta_id=4 AND os_historico='N' AND os_tec_id=".$_GET["usu_id"];
$sqlQuery = mysql_query($sql);
$manutencao = mysql_num_rows($sqlQuery);

$sql = "SELECT os_id FROM os WHERE os_sta_id=5 AND os_historico='N' AND os_tec_id=".$_GET["usu_id"];
$sqlQuery = mysql_query($sql);
$concluida = mysql_num_rows($sqlQuery);

$data = array(
	array('Em manutencao', $manutencao),
	array('Concluida', $concluida)
);

$plot = new PHPlot(400,250);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($data);
$plot->SetMarginsPixels(-90, 10, 30, 10);

$sql = "SELECT usu_nome FROM usuario WHERE usu_id=".$_GET["usu_id"];
$sqlQuery = mysql_query($sql);
$rs = mysql_fetch_object($sqlQuery);

$plot->SetTitle("Usuario: #".$_GET["usu_id"]." (".$rs->usu_nome.")");



# Set enough different colors;
$plot->SetDataColors(array('cyan','blue'));

# Build a legend from our data array.
# Each call to SetLegend makes one line as "label: value".
foreach ($data as $row)
  $plot->SetLegend(implode(': ', $row));

#$plot->SetLegendWorld(10,0);
$plot->DrawGraph();
?>
