<?php
echo "Je vais tuer httpd !!<br>";
exec('pkill httpd 2>&1', $res);
$Response = "il s'est passe : " . $res . "<br>";
echo json_encode($Response);
return;
?>