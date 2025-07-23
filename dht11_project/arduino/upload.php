<?php
$json = file_get_contents("php://input");
$data = json_decode($json, true);


$log = date("Y-m-d H:i:s") . " - Temp: " . $data["temperature"] .
       ", Hum: " . $data["humidity"] .
       ", Soil: " . $data["soil"] .
       ", Relay: " . $data["relay"] . "\n";

file_put_contents("sensor_log.txt", $log, FILE_APPEND);
echo "Data received and logged.";
?>
