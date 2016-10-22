<?php
echo "Called setupsession.php....";

$jtab = json_decode($_ENV["VCAP_APPLICATION"]);
$jservices = json_decode($_ENV["VCAP_SERVICES"]);
$current_application_name = $jtab->application_name;
$current_application_instance = $jtab->instance_id;
$hostname = $jservices->{'redis'}[0]->{'credentials'}->{'host'};
$port = $jservices->{'redis'}[0]->{'credentials'}->{'port'};
$password = $jservices->{'redis'}[0]->{'credentials'}->{'password'};
    
echo "<br>Parsing VCAP_APP et VCAP_SERVICES OK";
echo "<br>Configuration du backend redis.....";

#http://php.net/manual/fr/function.ini-set.php#25601
#Redis adapte de http://redis4you.com/articles.php?id=001&name=Redis+as+session+handler+in+PHP
#La custo par .bp-config implique des check, watch your time :)

echo "<br>Parametres de conf : <br>redis <br>tcp://" .$hostname. ":" .$port. "?auth=" .$password;

ini_set("session.save_handler", "redis");
ini_set("session.save_path", "tcp://" .$hostname. ":" .$port. "?auth=" .$password);

session_start();
echo "<br>Session back par redis                           : " . session_id();
echo "<br>Nom courant de l'application                     : " . $current_application_name;
echo "<br>ID du serveur repondant a cette request          : " . $current_application_instance;
