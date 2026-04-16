<?php

header('Content-Type: application/json');
require '../database.php';

$city = isset($_GET['city']) ? $_GET['city'] : 'Trento';

// Query 1: Tabella scritta con la 'A' maiuscola e colonne esplicite
$query1 = "SELECT date_time, aqi, pm2_5, pm10, no2, o3, co, nh3 
FROM registrationAir 
WHERE City_Name = :city 
  AND aqi IS NOT NULL 
  AND pm2_5 IS NOT NULL 
  AND pm10 IS NOT NULL 
  AND no2 IS NOT NULL 
  AND o3 IS NOT NULL 
  AND co IS NOT NULL 
  AND nh3 IS NOT NULL
ORDER BY date_time DESC;";
$stmt1 = $pdo->prepare($query1);
$stmt1->execute(['city' => $city]);
$citiesData = $stmt1->fetch(PDO::FETCH_ASSOC);

// Query 2: Classifica (Tabella corretta)
$query2 = "
SELECT City_name, aqi
FROM (
    SELECT City_name, aqi, 
           ROW_NUMBER() OVER (PARTITION BY City_name ORDER BY date_time DESC) as rn
    FROM registrationAir
) t
WHERE rn = 1
ORDER BY aqi ASC";
$stmt2 = $pdo->query($query2);
$ranking = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Query 3: Dati di ieri (Tabella corretta)
$query3 = "SELECT City_name, AVG(aqi) AS average_aqi
FROM registrationAir 
WHERE City_name = :city
  AND date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)
  AND date_time < CURRENT_DATE
GROUP BY City_name";

$stmt3 = $pdo->prepare($query3);
$stmt3->execute(['city' => $city]);
$yesterdayData = $stmt3->fetch(PDO::FETCH_ASSOC);

// Risultato finale
$finalResult = [
    "citiesData" => $citiesData,
    "ranking" => $ranking,
    "yesterdayData" => $yesterdayData
];

echo json_encode($finalResult);

?>