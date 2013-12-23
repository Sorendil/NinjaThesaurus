<?php		
echo "<div id=\"logo\">
			<a href= \"#\"><img src=\"/static/images/logo.png\" /></a>
		</div>
		<div id=\"form-recherche\">
			<form class=\"form-search\" action=\"index.php\" method=\"get\">
				<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\">
				<button type=\"submit\" class=\"btn\">Recherche</button>
			</form>
		</div>
		<div class=\"navbar navbar-left navbar-default menu\">
			<div class=\"navbar-collapse collapse\">
				<ul class=\"nav navbar-nav\">
					<li><a href=\"/\">Accueil</a></li>
				</ul>
			</div>
		</div>
		<div id=\"ajout\">
					<form class=\"form-search\" action=\"/\" method=\"post\">
							<input type=\"text\" name=\"libelle\" class=\"input-medium search-query\" />
							<br/>
							<input type=\"checkbox\" name=\"vedette\" id=\"vedette\" /> <label for=\"vedette\">Vedette</label>
							<button type=\"submit\" class=\"btn\" name=\"add\">Ajout Descripteur</button>
					</form>
		</div>
<div class=\"clearfix\"></div>"; 
?>
