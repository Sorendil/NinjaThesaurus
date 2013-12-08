<?php

include './class/Descripteur.php';
include './class/DVedette.php';
include './class/DNormal.php';
include './class/Tools.php';

$adr_libelle = NULL;
$adr_methode = NULL;

if (isset ($_GET['libelle']))
  $adr_libelle = Tools::parse_libelle ($_GET['libelle']);

if (isset ($_GET['methode']))
  $adr_methode = $_GET['methode'];

echo $adr_libelle."<br />".$adr_methode;
