<?php

include './class/Descripteur.php';
include './class/Tools.php';
include './class/Exceptions.php';

$db = Tools::connect_db();
if (!$db)
  die('connexion sgbd impossible');

if (isset ($_GET['libelle']))
{
  if ($_GET['libelle'] == '__ajout')
  {
    /* from nouveau libelle */
    $libelle = Tools::parse_libelle ($_GET['libelle']);
    try {
      $desc = new Descripteur ($db, $libelle);
      die ('descripteur ajoute');
    }
    catch (AlreadyExisting $e) {
      die ('descripteur deja existant');
    }
  }
  else
  {
    $adr_libelle = Tools::parse_libelle ($_GET['libelle']);
    try {
      $desc = new Descripteur ($db, $adr_libelle);
    }
    catch (NotFoundException $e) {
      die ('descripteur non existant');
    }
    if (isset($_POST['add']) && isset($_POST['rel']) && isset($_POST['libelle']))
    {
      /* ajout relation */
      if ($desc->addRel(Tools::parse_libelle($_POST['rel']), $_POST['rel']))
        die ('relation ajoute');
      else
        die ('err ajout');
    }
    else
    {
      /* affichage page descripteur */
      echo "<pre>";
      echo $desc->getLibelle();
      echo "\n".print_r($desc->getRel());
      echo "</pre>";
    }
  }
}
else if (isset ($_POST['libelle']) && isset($_POST['vedette']))
{
  /* ajout descripteur exec */
}
else
{
  /* page par défault accueil */
  echo 'accueil';
}

Tools::disconnect_db($db);
