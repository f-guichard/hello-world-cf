<html>
<head>
	<title>Formation : Portail DFY</title>
	<link rel="stylesheet" type="text/css" href="css/jquery.suggest.css" />
	<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.23.custom.css" />
	<link rel="stylesheet" type="text/css" href="css/jMenu.jquery.css" />
	<link rel="stylesheet" type="text/css" href="css/formation-custom.css" />
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
</head>
<body>
	<table>
		<tr>
			<td>
				<div id="logodfy" align="center" style="margin-left:0%;" >
					<img src="media/logoDFY.png" width="375" height="250" />
				</div>
				<div id="killbuton" align="center" >
				<form id="killapache" method="post" action="phpexec.php">
					<!-- html5/CSS3 in progress -->
					<button id="mybutton" type="button" onclick="document.getElementById('killapache').submit();">kill the apache !!!</button>
				</div>	
			</td>
			<td>
	<div id="cours_orange" style="margin-right=5%;">
		<table>
			<tr>
				<th>Date</th>
				<th>Cours</th>
				<th>%Var</th>
			</tr>
			<?php
				$NotBindingMessage = "<br>No service binding detected : <b>no database available</b><br>Please watch logs for more details<br>";
				$stdout = fopen('php://stdout', 'w');
				$stderr = fopen('php://stderr', 'w');
				//Log aggregator !!!
				$jtab = json_decode($_ENV["VCAP_APPLICATION"]);
				//APP GENERATED stdout
				fwrite($stdout, 'Jai encore lu VCAP_APPLICATION\n');
				//Manipulation des env -> changer de vhost (buildpacks)
				$jservices = json_decode($_ENV["VCAP_SERVICES"]);
				echo "<br>Nom de l'application courante : <b>" . $jtab->application_name . "</b><br><br>";
				//print_r($jservices);
				if(!isset($jservices->{'p-mysql'}))
				{
					echo $NotBindingMessage;
				}
				else
				{
					echo "<br>service binding   : <b>" . $jservices->{'p-mysql'}[0]->{'name'} . "</b> basee sur le service du marketplace <b>" . $jservices->{'p-mysql'}[0]->{'label'} . "</b><br>";
					echo " Connexion via PDO    : <b>" . $jservices->{'p-mysql'}[0]->{'credentials'}->{'jdbcUrl'} . "</b><br>";
					echo " Connexion via mysqli : <b>" . $jservices->{'p-mysql'}[0]->{'credentials'}->{'uri'} . "</b><br>";
				}
				foreach ($jservices as $js)
				{
						echo "<br>Service detected : <b>" . $js[0]->{'name'} . "</b>";
				}
				try
				{
					$dbconnctx = mysqli_connect($jservices->{'p-mysql'}[0]->{'credentials'}->{'uri'}); //die("Impossible de se connecter : " . mysqli_error($dbconnctx));
					$preparedstmt = "SELECT cours FROM orangebourse LIMITS 1";
					$query = mysqli_query($dbconnctx, $preparedstmt);
					while($rez = mysqli_fetch_array($query))
					{
						echo $rez;
					}
					mysqli_close($con);
				}
				catch(Exception $e)
				{
					//APP GENERATED stderr
					fwrite($stderr, "CATCH => Erreur  : " . $e->getMessage());
					error_log("CATCH => Erreur  : " . $e->getMessage());
				}	
				$maxline = 5;
				$cpt = 0;
				if($fileres = fopen("../data/cours_bourse_last10.txt",'r'))
				{
					while($entree = fgets($fileres))
					{
						echo '<tr align = "center">';
						$buff = explode("|",$entree);
						$compteur = count($buff);
						foreach ($buff as $data)
						{
							echo '<td>' . $data . '</td>';
						}
						echo '</tr>';
						$cpt++;
					}
					fclose($fileres);
				}
				else 
				{
					echo '<tr align = "center">';
					echo '<td>Aucun cours lu</td>';
					echo '</tr>';
				}
				fclose($stderr);
				fclose($stdout);
			?>
		</table>
	</div>
	</td>
	</tr></table>
 <div id="page" class="ui-widget">
   <div id="formulaire">
   <!-- html5/CSS3 in progress -->
   <input type="text" id="Eq" name="Equipement" size="30" placeholder="Pattern de recherche la base de données..." />
   <img id="chargement" src="media/ajax-loader.gif" width="30" height="30" />
	<script type="text/javascript">
	$(function() {
		$("#Eq").autocomplete({
			source: "doSomething.php",
			minLength: 1,
			disabled : false,
			delay : 100
		});
	});
	</script>
   </div>
   </div>
 </div>
</body>
</html>
