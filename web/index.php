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
					echo '<center><b><font color="red" size="2"> La connexion avec la base de données a échoué. Réessayez ultérieurement. </font></b></center>'; 
					goto redirection;
					exit();
					}
				if (isset ($_POST['libelle']) && !isset($_POST['rel']))
				{
				   /* ajout descripteur exec */
				   $libelle = Tools::parse_libelle ($_POST['libelle']);
				   try {
					 $vedette=0;
					 if (isset($_POST['vedette']))
					   $vedette=1;
					 $desc = new Descripteur ($db, $libelle, $vedette);
					 echo '<center><b><font color="green" size="2"> Le descripteur a bien été ajouté.</font></b></center>';
					goto redirection;
					 exit();
				   }
				   catch (AlreadyExisting $e) {
					 echo '<center><b><font color="red" size="2"> Le descripteur que vous tentez d\'ajouter existe déjà.</font></b></center>';
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
					echo "<div>";
					echo "<h1>Page du mot : ".$desc->getLibelle()."</h1>";
					
					if ($desc->getVedette()==null){
					$isvedette="null";}
					else if($desc->getVedette()==1){
					$isvedette="oui";}
					else if($desc->getVedette()==0){
					$isvedette="non";}
					else {
					$isvedette="autre";}
					echo "Vedette : ".$isvedette;
				
					$relations=$desc->getRel();
					 
					/*
					 echo "<pre>";
					 print_r($relations);
					 echo "</pre>";
					*/
					$all_libelle_rel=Tools::getLibelleRel($db);//récupération de toutes les types de relations de la bdd	
					$tab_rel=array();
					
     for($i=0; $i<count($relations['LIBELLE_REL']); $i++){							

       $tab_rel[$relations['LIBELLE_REL'][$i]][]=$i;		

     }
					
					foreach($tab_rel as $key => $value){
						
						echo "<h3>".ucfirst($key)."</h3>";
						
						for($j=0; $j<count($tab_rel[$key]); $j++){
							if($relations['SENS'][$tab_rel[$key][$j]]==0){
								echo '<a href="/'.$relations['LIBELLE_DESC'][$tab_rel[$key][$j]].'/" >'.$relations['LIBELLE_DESC'][$tab_rel[$key][$j]].'</a> , ';
							}
						}
						
						echo '<form class="form-search" action="/'.$desc->getLibelle().'/" method="post">
								<input type=hidden name=rel value='.$key.' />
								<input type="text" name="libelle" class="input-medium search-query">
								<button type="submit" class="btn" name="add">Ajout</button>
							</form>';
						
						
					}

					
					//affichage des types de relations vides : juste le nom et le formulaire apparaissent
					foreach ($all_libelle_rel['LIBELLE'] as $value){
							
							if(array_search($value, array_keys($tab_rel))===false){
							
								echo "<h3>".ucfirst($value)."</h3>";
								
								echo '<form class="form-search" action="/'.$desc->getLibelle().'/" method="post">
									<input type=hidden name=rel value='.$value.' />
									<input type="text" name="libelle" class="input-medium search-query">
									<button type="submit" class="btn" name="add">Ajout</button>
								</form>';
 
							}
					}
					
					echo "</div>";
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
