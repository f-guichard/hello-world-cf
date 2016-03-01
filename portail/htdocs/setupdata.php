<?php
echo "Called setupdate.php....";

$jtab = json_decode($_ENV["VCAP_APPLICATION"]);
$jservices = json_decode($_ENV["VCAP_SERVICES"]);
$table_name = $jtab->application_name;
$datasource = "../data/cours_bourse_alldatas.txt";
$createtablestmt = "CREATE TABLE IF NOT EXISTS '" . $table_name . "' (`date` DATE, `cote` REAL(4,2))";
$insertstmt = "INSERT INTO '" . $table_name ."' VALUES (\'1789-07-14\', \'99.99\')";
try
{
	echo "<br>Setup in progress....";
	$dbconnctx = mysqli_connect($jservices->{'p-mysql'}[0]->{'credentials'}->{'uri'}); //die("Impossible de se connecter : " . mysqli_error($dbconnctx));

	//Creer la table	
	$query = mysqli_query($dbconnctx, $createtablestmt);

	//Inserer la donnee de test 
	$query = mysqli_query($dbconnctx, $insertstmt);
	while($rez = mysqli_fetch_array($query))
	{
		echo "<br>" . $rez;
	}
	mysqli_close($con);
	
}
catch(Exception $e)
{
	//APP GENERATED stderr
	fwrite($stderr, "CATCH => Erreur  : " . $e->getMessage());
	error_log("CATCH => Erreur  : " . $e->getMessage());
}
