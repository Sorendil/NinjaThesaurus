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
					goto redirection;
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
					goto redirection;
					 exit();
				   }
				   catch (AlreadyExisting $e) {
					 echo '<center><b><font color="red" size="2"> Déscripteur déjà existant.</font></b></center>';
					goto redirection;
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
					exit();
				  }
				  if (isset($_POST['rel']) && isset($_POST['libelle']))
				  {
					/* ajout relation */
					if ($desc->addRel(Tools::parse_libelle($_POST['libelle']), $_POST['rel'])){
						echo '<center><b><font color="green" size="2"> F&eacute;licitations, votre relation a bien &eacute;t&eacute; ajout&eacute;e &agrave; la base de donn&eacute;es</font></b></center>';
					goto redirection;
						exit();
					}
					else{
						echo '<center><b><font color="red" size="2"> Erreur, votre relation n\' a pas pu &ecirc;tre ajout&eacute;e &agrave; la base de donn&eacute;es </font></b></center>';
					goto redirection;
						exit();
					}
				  }
					/* affichage page descripteur */
					echo '<div id="descripteur">';
					echo "<pre>";
					echo "<h1>Page du mot : ".$desc->getLibelle()."</h1>";
				  
					$relations=$desc->getRel();
					echo "\n".print_r($desc->getRel());
					$tab_rel=array();
					echo "1";
					for($i=0; $i<count($relations['LIBELLE_REL']); $i++){
						echo "2";
						echo "taille".count($tab_rel);
						if(!(array_search($relations['LIBELLE_REL'][$i], $tab_rel))){
							$tab_rel[$relations['LIBELLE_REL'][$i]]=array();
							break;
						}
						/*for($j=0; $j<(count($tab_rel)+1); $j++){
						echo "3";
							if((count($tab_rel)==0) ||($relations['LIBELLE_REL'][$i]!=$tab_rel[$j])){
								echo "4";
								$tab_rel[$relations['LIBELLE_REL'][$i]]=array();
								break;
							}
						
						}*/								
					array_push($tab_rel[$relations['LIBELLE_REL'][$i]], $i);		
					}
					echo "\n".print_r($tab_rel);
					echo "5";
					for($i=0; $i<count($tab_rel); $i++){
						echo "<h3>".$tab_rel[$i]."</h3>";
						for($j=0; $j<count($tab_rel[$i]); $j++){
							echo '<a href="http://cvidal.org:81/'.$relations['LIBELLE_DESC'][$tab_rel[$i][$j]].'/" '.$relations['LIBELLE_DESC'][$tab_rel[$i][$j]].'</a> ';//attention affiche juste l'indice pas les mots de libelle_desc
						}
						echo '<form class=\"form-search\" action=\"\" method=\"post\">
								<input type=hidden name=rel value='.$tab_rel[$i].' />
								<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\">
								<button type=\"submit\" class=\"btn\" name=\"add\">Ajout</button>
							</form>';
					}
					
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
<?php
include("footer.php");
?>
</body>
</html>
