<?php

include './class/Descripteur.php';
include './class/Tools.php';
include './class/Exceptions.php';

$db = Tools::connect_db();

if (!$db)
  die('connexion sgbd impossible'); /* HTML TODO */

if (isset ($_GET['libelle']))
{
  $adr_libelle = Tools::parse_libelle ($_GET['libelle']);
  try {
    $desc = new Descripteur ($db, $adr_libelle);
  }
  catch (NotFoundException $e) {
    die ('descripteur non existant'); /* HTML TODO */
  }
  if (isset($_POST['add']) && isset($_POST['rel']) && isset($_POST['libelle']))
  {
    /* ajout relation */
    if ($desc->addRel(Tools::parse_libelle($_POST['rel']), $_POST['rel']))
      die ('relation ajoute'); /* HTML TODO */
    else
      die ('err ajout'); /* HTML TODO */
  }
  else
  {
    /* affichage page descripteur */
    echo "<pre>";
    echo $desc->getLibelle();
    echo "\n".print_r($desc->getRel());
    echo "</pre>";
    /* HTML TODO */
  }
}
else if (isset($_GET['__ajout']))
{
  /* from nouveau libelle */
  /* HTML TODO */
}
else if (isset ($_POST['libelle']) && isset($_POST['vedette']))
{
  /* ajout descripteur exec */
  $libelle = Tools::parse_libelle ($_GET['libelle']);
  try {
    $desc = new Descripteur ($db, $libelle);
    die ('descripteur ajoute'); /* HTML TODO */
  }
  catch (AlreadyExisting $e) {
    die ('descripteur deja existant'); /* HTML TODO */
  }
}
else
{
  /* page par défault accueil */
  echo 'accueil'; /* HTML TODO */
}

Tools::disconnect_db($db);
