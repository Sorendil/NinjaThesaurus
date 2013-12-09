<?php

include './class/Descripteur.php';
include './class/Tools.php';
include './class/Exceptions.php';

$db = Tools::connect_db();
if (!$db)
  die('connexion sgbd impossible');

$adr_libelle = NULL;
$adr_methode = NULL;

if (isset ($_GET['libelle']))
  $adr_libelle = Tools::parse_libelle ($_GET['libelle']);

if (isset ($_GET['methode']))
  $adr_methode = $_GET['methode'];

if ($adr_libelle)
{
  if ($adr_methode)
  {
  }
  else
  {
    try {
      $desc = new Descripteur ($db, $adr_libelle);
    }
    catch (NotFoundException $e) {
      die ('descripteur non existant');
    }

    echo "<pre>";
    echo $desc->getLibelle();
    echo $desc->getRel();
    echo "</pre>";
  }
}

Tools::disconnect_db($db);
