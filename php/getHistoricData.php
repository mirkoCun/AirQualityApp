<?php
header('Content-Type: application/json');
require '../database.php';

$city = isset($_GET['city']) ? $_GET['city'] : 'Trento';

// 1. CREIAMO IL "CALENDARIO" DEGLI ULTIMI 7 GIORNI
// Questo ci assicura che il grafico abbia sempre 7 giorni esatti, anche se nel DB manca qualche dato
$dates_labels = [];
$aqi_data = [];
$pm25_data = [];
$pm10_data = [];
$temp_data = [];

for ($i = 6; $i >= 0; $i--) {
    $dateStr = date('Y-m-d', strtotime("-$i days")); // Formato per il DB: "2026-03-03"
    
    // Riempiamo gli array con dei valori di default (0) per ogni giorno
    $dates_labels[$dateStr] = date('d M', strtotime($dateStr)); // Formato per il grafico: "03 Mar"
    $aqi_data[$dateStr] = 0;
    $pm25_data[$dateStr] = 0;
    $pm10_data[$dateStr] = 0;
    $temp_data[$dateStr] = 0;
}

// 2. QUERY 1: PRENDIAMO I DATI DELL'ARIA (Tabella registrationAir)
$queryAir = "
    SELECT 
        DATE(date_time) as data_giorno,
        AVG(aqi) as media_aqi,
        AVG(pm2_5) as media_pm25,
        AVG(pm10) as media_pm10
    FROM registrationAir
    WHERE City_name = :city
      AND date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
    GROUP BY DATE(date_time)
";
$stmtAir = $pdo->prepare($queryAir);
$stmtAir->execute(['city' => $city]);

// Sostituiamo gli zeri con i dati veri dell'aria per i giorni in cui ci sono
foreach ($stmtAir->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $giorno = $row['data_giorno'];
    if (isset($aqi_data[$giorno])) {
        $aqi_data[$giorno] = round($row['media_aqi']);
        $pm25_data[$giorno] = round($row['media_pm25'], 1);
        $pm10_data[$giorno] = round($row['media_pm10'], 1);
    }
}

// 3. QUERY 2: PRENDIAMO I DATI DEL METEO (Tabella registrationweather)
$queryWeather = "
    SELECT 
        DATE(date_time) as data_giorno,
        AVG(temperature) as media_temp 
    FROM registrationweather
    WHERE City_name = :city
      AND date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
    GROUP BY DATE(date_time)
";
$stmtWeather = $pdo->prepare($queryWeather);
$stmtWeather->execute(['city' => $city]);

// Sostituiamo gli zeri con i dati veri della temperatura
foreach ($stmtWeather->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $giorno = $row['data_giorno'];
    if (isset($temp_data[$giorno])) {
        $temp_data[$giorno] = round($row['media_temp'], 1);
    }
}

// 4. IMPACCHETTIAMO TUTTO PER JAVASCRIPT
// array_values serve a togliere le date usate come "chiavi" e mandare a JS solo una lista pulita di numeri/testi
echo json_encode([
    "dates" => array_values($dates_labels),
    "aqi"   => array_values($aqi_data),
    "pm25"  => array_values($pm25_data),
    "pm10"  => array_values($pm10_data),
    "temp"  => array_values($temp_data)
]);
?>