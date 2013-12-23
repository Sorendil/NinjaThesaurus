<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Animal Thesaurus</title>
  <link rel="stylesheet" href="/static/css/style.css">
  <link rel="stylesheet" href="/static/css/bootstrap.css">
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
					echo '<center><b><font color="red" size="2"> La connexion avec la base de donn�es a �chou�. R�essayez ult�rieurement. </font></b></center>'; 
					include 'accueil.php';?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php
					/*include("footer.php");*/
					exit();
					}
				if (isset ($_POST['libelle']))
				{
				   /* ajout descripteur exec */
				   $libelle = Tools::parse_libelle ($_POST['libelle']);
				   try {
					 $vedette=0;
					 if (isset($_POST['vedette']))
					   $vedette=1;
					 $desc = new Descripteur ($db, $libelle, $vedette);
					 echo '<center><b><font color="green" size="2"> Le déscripteur a bien été ajouté.</font></b></center>';
					 include("accueil.php");?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php include("footer.php");
					 exit();
				   }
				   catch (AlreadyExisting $e) {
					 echo '<center><b><font color="red" size="2"> Déscripteur déjà existant.</font></b></center>';
					 include("accueil.php");?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php
					/* include("footer.php");*/
					 exit();
				   }
				}
				if (isset ($_GET['libelle']))
				{
				  $adr_libelle = Tools::parse_libelle ($_GET['libelle']);
				  try {
					$desc = new Descripteur ($db, $adr_libelle);
				  }
				  catch (NotFoundException $e) {
					echo '<center><b><font color="red" size="2"> D&eacute;sol&eacute;, le descripteur que vous recherchez est inexistant </font></b></center>';
					goto redirection;
					include ('accueil.php');?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php
					include("footer.php");
					exit();
				  }
				  if (isset($_POST['rel']) && isset($_POST['libelle']))
				  {
					/* ajout relation */
					if ($desc->addRel(Tools::parse_libelle($_POST['libelle']), $_POST['rel'])){
						echo '<center><b><font color="green" size="2"> F&eacute;licitations, votre relation a bien &eacute;t&eacute; ajout&eacute;e &agrave; la base de donn&eacute;es</font></b></center>';
					include ('accueil.php');?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php
					/*include("footer.php");*/
						exit();
					}
					else{
						echo '<center><b><font color="red" size="2"> Erreur, votre relation n\' a pas pu &ecirc;tre ajout&eacute;e &agrave; la base de donn&eacute;es </font></b></center>';
					include ('accueil.php');?>
					 <div id="animals"></div>
		             <div id="trees"></div>
					 <?php
					/*include("footer.php");*/
						exit();
					}
				  }
					/* affichage page descripteur */
					echo '<div id="descripteur">';
					echo "<pre>";
					echo "<h1>Page du mot : ".$desc->getLibelle()."</h1>";
				  
					$relations=$desc->getRel();
					$synonyme=array();
					$generalisation=array();
					$specialisation=array();
					$autre=array();

					for($i=0; $i<count($relations['LIBELLE_REL']); $i++){

						if($relations['LIBELLE_REL'][$i]=="synonyme"){
							array_push($synonyme, $i);
							
						}
						else if($relations['LIBELLE_REL'][$i]=="est"){
							if($relations['SENS'][$i]==0){
								array_push($specialisation, $i);
								
							}
							else if($relations['SENS'][$i]==1){
								array_push($generalisation, $i);
								
							}
						}
						else{
							array_push($autre, $i);
							
						}
					}
				/*<!----   affichage synonyme     --->*/
					echo "<h3>Synonymes</h3>";
					
						for($j=0; $j<count($synonyme); $j++){
						echo '<a href="/'.$relations['LIBELLE_DESC'][$synonyme[$j]].'/">'.$relations['LIBELLE_DESC'][$synonyme[$j]]."</a> ";
					}
					echo "<form class=\"form-search\" action=\"\" method=\"post\">
								<input type=\"hidden\" name=\"rel\" value=\"\" />
								<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\"><button type=\"submit\" class=\"btn\" name=\"add\">Ajout</button>
							</form>";

				/*<!----   affichage spécialisation     --->*/
					echo "<h3>Sp&eacute;cialisations</h3>";
						for($j=0; $j<count($specialisation); $j++){
						echo '<a href="/'.$relations['LIBELLE_DESC'][$specialisation[$j]].'/">'.$relations['LIBELLE_DESC'][$specialisation[$j]]."</a> ";
					}
					echo "<form class=\"form-search\" action=\"\" method=\"post\">
								<input type=\"hidden\" name=\"rel\" value=\"\" />
								<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\"><button type=\"submit\" class=\"btn\" name=\"add\">Ajout</button>
							</form>";

				/*<!----   affichage généralisation     --->*/
					echo "<h3>G&eacute;n&eacute;ralisations</h3> ";
						for($j=0; $j<count($generalisation); $j++){
						echo '<a href="/'.$relations['LIBELLE_DESC'][$generalisation[$j]].'/">'.$relations['LIBELLE_DESC'][$generalisation[$j]]."</a> ";
					}
					echo "<form class=\"form-search\" action=\"\" method=\"post\">
								<input type=\"hidden\" name=\"rel\" value=\"\" />
								<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\"><button type=\"submit\" class=\"btn\" name=\"add\">Ajout</button>
							</form>";

					
					/*
					echo "\n <h3>Autres</h3> ";
					echo "<form class=\"form-search\" action=\"#\" method=\"post\">
								<input type=\"hidden\" name=\"rel\" value=\"\"/>
								<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\">
								<button type=\"submit\" class=\"btn\" name=\"add\">Ajout</button>
							</form>";
					echo "<hr/> \n";
					for($j=0; $j<count($autre); $j++){
						echo '<a href="http://cvidal.org:81/'.$relations['LIBELLE_DESC'][$autre[$j]].'/">'.$relations['LIBELLE_DESC'][$autre[$j]]."</a> ";
					}
					*/
					echo "</pre>";
					echo '</div>';
					/* fin affichage page descripteur */
				 }
				 else
				 {
					 redirection:
				    include("accueil.php");
				 }

				Tools::disconnect_db($db);
			?>

		</div>
		<div id="animals"></div>
		<div id="trees"></div>
	</div>
		
<!----   ICI LE FOOTER     --->
		<footer>
			<p>Site r&eacute;alis&eacute; par : Julien Deguilhem - Ma&iuml;lys Denis - S&eacute;bastien Gautheron - Johann Mitrail - Anthony Rossi - Colin Vidal <a href="http://www.univ-montp2.fr/"><img src="static/images/logo_um2.png" align="right" width="40" height="40"/></a></br> 
			Cours de base de donn&eacute;es - professeur : Th&eacute;rese Libourel</br>
			Projet : Th&eacute;saurus</p>
		</footer>

</body>
</html>
