<?php
$Reponse="<br>Rewrite Rule activee<br>";
#echo "[ APP ] REWRITE RULE ACTIVATED";
if (isset($_GET['param']))
{
	echo "<br>[ APP ] PARAMETRE EN INPUT : " . $_GET['param'];
	$Reponse = $Reponse . "J'ai reecrit : " . $_GET['param'] ."<br>";
}
echo $Reponse;
return;
?>
