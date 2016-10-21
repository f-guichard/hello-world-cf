<?php
echo "Called setupdate.php....";

$jtab = json_decode($_ENV["VCAP_APPLICATION"]);
$jservices = json_decode($_ENV["VCAP_SERVICES"]);
$table_name = $jtab->application_name;
$datasource = "../data/cours_bourse_alldatas.txt";
$teststmt = "SHOW DATABASES";
$tablesstmt = "SHOW TABLES";
$hostname = $jservices->{'p-mysql'}[0]->{'credentials'}->{'hostname'};
$port = $jservices->{'p-mysql'}[0]->{'credentials'}->{'port'};
$username = $jservices->{'p-mysql'}[0]->{'credentials'}->{'username'};
$pass = $jservices->{'p-mysql'}[0]->{'credentials'}->{'password'};
$database = $jservices->{'p-mysql'}[0]->{'credentials'}->{'name'};
$mysql_uri = $jservices->{'p-mysql'}[0]->{'credentials'}->{'uri'};

try
{
	echo "<br>Setup in progress....";
        //$dbconnctx = mysqli_connect($hostname, $username, $pass, $database, $port);	
        $mysqli = new mysqli($hostname, $username, $pass, $database);
	$mysqli->autocommit(FALSE);

	if (mysqli_connect_errno()) {
    		 echo "<br>Erreur : " . mysqli_connect_error();
                 exit();
	}

	echo "<br>Connexion OK au serveur : " . $mysqli->server_info;

	$querystatement = $mysqli->stmt_init();

	//Afficher la db
        echo "<br>preparation du statement : " . $teststmt;
        $querystatement = $mysqli->prepare($teststmt);
	$querystatement->execute();
	$querystatement->bind_result($dbshow);
        $querystatement->fetch();
        echo "<br>Base de donnee courante : " . $dbshow . "<br>";
	$querystatement->close();

	//Creer une table :
        if(isset($_GET['table']))
	{
		//$createTable = "CREATE TABLE IF NOT EXISTS ? (date DATE, cote REAL(4,2), logo LONGBLOB, message VARCHAR(4000)) TYPE = InnoDB";
		$table_name = $_GET['table'];
		echo "<br>preparation du statement : " . "CREATE TABLE `".$table_name."` ( message VARCHAR(30))";
		if($mysqli->query("CREATE TABLE `".$table_name."` (message VARCHAR(30))"))
		{
			echo "<br>Table " . $table_name . " creee avec succes<br>";
			echo "<br>Insertion de 2 messages....";
			$mysqli->query("INSERT INTO `".$table_name."` VALUES ('Je suis la')");
			$mysqli->query("INSERT INTO `".$table_name."` VALUES ('Je suis toujours la')");
			if (!$mysqli->commit()) 
			{
 				echo "<br> Pb de commit";
			}
		}
		else
		{
			echo "<br>Erreur de creation de la table " . $table_name . "<br>";
			
		}
	}


        //Afficher les tables 
        echo "<br>preparation du statement : " . $tablesstmt;
        $querystatement = $mysqli->prepare($tablesstmt);
        $querystatement->execute();
        $querystatement->bind_result($tableshow);
        $querystatement->store_result();
	if($querystatement->num_rows >= 1)
	{
		echo "<br>" . $querystatement->num_rows . " tables trouvees";
		while($querystatement->fetch())
		{
			echo "<br>" . $tableshow;
		}
	}
        else
	{
		echo "<br>Aucune table trouvee";
	}

	$querystatement->close();
	
}
catch(Exception $e)
{
	//APP GENERATED stderr
	fwrite($stderr, "CATCH => Erreur  : " . $e->getMessage());
	error_log("CATCH => Erreur  : " . $e->getMessage());
        echo "<br>CATCH => Erreur  : " . $e->getMessage();
}
