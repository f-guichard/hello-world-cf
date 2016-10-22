<html>
<head>
	<title>Workshop : Portail DFY</title>
	<link rel="stylesheet" type="text/css" href="css/jquery.suggest.css" />
	<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.23.custom.css" />
	<link rel="stylesheet" type="text/css" href="css/jMenu.jquery.css" />
	<link rel="stylesheet" type="text/css" href="css/workshop-custom.css" />
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
</head>
<body>
	<div id="logocfydocker" align="center" style="margin-left:0%;" >
		<img src="media/cfdocker.png" width="600" height="250" />
	</div>

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
					<img id="chargement" src="media/ajax-loader.gif" width="20" height="20" />
				</div>	
			</td>
			<td>
				<div id="cours_orange" style="margin-right=5%;">
				<table>
					<th>Date</th>
					<th>Cours</th>
					<th>%Var</th>
					</tr>
					<table>
					<?php
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
							}
							fclose($fileres);
						}
						else 
						{
							echo '<tr align = "center">';
							echo '<td>Aucun cours lu</td>';
							echo '</tr>';
						}
					?>
					</table>
			</td>
			<td>
				<div id="cours_orange" style="margin-right=5%;">
				<table>
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
						echo "<br>Nom de l'application courante : <b>" . $jtab->application_name . "</b><br>";
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
						fclose($stderr);
						fclose($stdout);
					?>
				</table></td>
				</tr></table>
				</div>
			</td>
		</tr>
	</table>
 <div id="page" class="ui-widget">
   <div id="formulaire">
   <!-- html5/CSS3 in progress -->
   <input type="text" id="searchbar" name="search" size="30" placeholder="Recherche dans la base de donnees..." />
	<script type="text/javascript">
	$(function() {
		$("#searchbar").autocomplete({
			source: "doSomething.php",
			minLength: 1,
			disabled : false,
			delay : 100
		});
	});
	</script>
   </div>
   </div>
</body>
</html>
