<?php 

//da finireeee


require '../database.php';

// 1. Diciamo al browser che questo è un file di dati JSON
header('Content-Type: application/json');

$city = isset($_GET['city']) ? $_GET['city'] : 'Trento';

// 3. Prepariamo la query per prendere l'ULTIMO dato inserito per quella città

$query = 
"SELECT temperature, humidity, wind_speed, feels_like, aqi, pm2_5, pm10, no2, o3, weather_code
FROM registrationAir air, registrationWeather weather, city c 
WHERE air.City_Name = c.name AND weather.City_Name = c.name AND air.date_time = weather.date_time AND c.Name = :city 
ORDER BY air.date_time DESC 
LIMIT 1;";

$stmt = $pdo->prepare($query);
$stmt->execute(['city' => $city]);
$dati = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Se troviamo i dati, li stampiamo in JSON, altrimenti diamo un errore
if ($dati) {
    echo json_encode($dati);
} else {
    echo json_encode(['error' => 'No data']);
}

?>