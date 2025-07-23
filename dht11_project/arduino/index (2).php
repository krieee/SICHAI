<?php
$logFile = __DIR__ . '/sensor_log.txt';

if (!file_exists($logFile)) {
    die('Log file not found.');
}


$lines = array_filter(array_map('trim', file($logFile)));

$entries = [];
$pattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) - Temp:\s*([0-9.]+),\s*Hum:\s*([0-9.]+),\s*Soil:\s*(\d+),\s*Relay:\s*(\d*)/i';

foreach ($lines as $line) {
    if (preg_match($pattern, $line, $m)) {
        $entries[] = [
            'timestamp' => $m[1],
            'temp'      => (float) $m[2],
            'hum'       => (float) $m[3],
            'soil'      => (int)   $m[4],
            'relay'     => $m[5] === '' ? null : (int) $m[5],
        ];
    }
}

usort($entries, fn($a, $b) => strcmp($b['timestamp'], $a['timestamp']));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sensor Log</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f9fafb; }
        tr:nth-child(even) { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Temperature, Humidity, Soil Moisture & Relay Status</h1>
    <table>
        <thead>
            <tr>
                <th>Date &amp; Time</th>
                <th>Temp (°C)</th>
                <th>Humidity (%)</th>
                <th>Soil</th>
                <th>Relay</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($entries as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['timestamp']) ?></td>
                <td><?= $row['temp'] ?></td>
                <td><?= $row['hum'] ?></td>
                <td><?= $row['soil'] ?></td>
                <td><?= $row['relay'] === null ? '-' : $row['relay'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>