<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Animal Thesaurus</title>
  <link rel="stylesheet" href="static/css/style.css">
  <link rel="stylesheet" href="static/css/bootstrap.css">
</head>
<body>
	<div id="global-content">
		<div id="content">
<!----   ICI LE HEADER     --->
	<header>
		<?php
		include("header.php");
		?>		
	</header>
			


<!----   ICI LE LE CORPS DE PAGE     --->

<?php

include './class/Descripteur.php';
include './class/Tools.php';
include './class/Exceptions.php';

$db = Tools::connect_db();

if (!$db){
	/* HTML TODO */
	echo '<center><b><font color="red" size="2"> La connexion avec la base de données a échoué. Réessayez ultérieurement. </font></b></center>'; /* HTML TODO */
	include 'accueil.php';
	exit();
	}

if (isset ($_GET['libelle']))
{
  $adr_libelle = Tools::parse_libelle ($_GET['libelle']);
  try {
    $desc = new Descripteur ($db, $adr_libelle);
  }
  catch (NotFoundException $e) {
    echo '<center><b><font color="red" size="2"> D&eacute;sol&eacute;, le descripteur que vous recherchez est inexistant </font></b></center>'; /* HTML TODO */
	include 'accueil.php';
	exit();
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
    echo "<h1>Page du mot ".$desc->getLibelle()."</h1>";
    echo "\n".print_r($desc->getRel());
    echo "</pre>";
	
		/* partie en test */
	$type_rel=$relations[LIBELLE_REL];
	$synonyme=array();
	$generalisation=array();
	$specialisation=array();
	$autre=array();

	for($i=0; $i<count($type_rel); $i++){

		if($type_rel=='synonyme'){
			array_push($synonyme, $i);
		}
		else if($type_rel=='est'){
			if($relations[LIBELLE_SENS][$i]==0){
				array_push($specialisation, $i);
			}
			else if($relations[LIBELLE_SENS][$i]==1){
				array_push($generalisation, $i);
			}
		}
		else{
			array_push($autre, $i);
		}
	}

	echo "Synonymes";
	for($j; $j<count($synonyme); $j++){
		echo $relations[LIBELLE_DESC][$synonyme[$j]];
	}

	echo "Spécialisations";
	for($j; $j<count($specialisation); $j++){
		echo $relations[LIBELLE_DESC][$specialisation[$j]];
	}

	echo "Spécialisations";
	for($j; $j<count($generalisation); $j++){
		echo $relations[LIBELLE_DESC][$generalisation[$j]];
	}

	echo "Autres";
	for($j; $j<count($autre); $j++){
		echo $relations[LIBELLE_DESC][$autre[$j]];
	}
	/* fin de partie en test */	 
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
include("accueil.php");
}

Tools::disconnect_db($db);
?>
			
<!----   ICI LE FOOTER     --->
	<footer>
		<?php
		include("footer.php");
		?>
	</footer>

</body>
</html>
