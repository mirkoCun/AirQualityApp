<?php
// Diciamo al browser che questa pagina restituirà SOLO dati JSON
header('Content-Type: application/json');

// Disabilitiamo gli errori HTML standard di PHP che romperebbero il JSON
error_reporting(0); 

try {
    require '../database.php';

    // Forziamo PDO a lanciare eccezioni in caso di errori SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $city = isset($_GET['city']) ? $_GET['city'] : 'Trento';

    // 1. CREIAMO IL CALENDARIO (Usa null come default, non 0)
    $dates_labels = [];
    $aqi_data = [];
    $pm25_data = [];
    $pm10_data = [];
    $temp_data = [];

    for ($i = 6; $i >= 0; $i--) {
        $dateStr = date('Y-m-d', strtotime("-$i days")); 
        
        $dates_labels[$dateStr] = date('d M', strtotime($dateStr)); 
        $aqi_data[$dateStr] = null;
        $pm25_data[$dateStr] = null;
        $pm10_data[$dateStr] = null;
        $temp_data[$dateStr] = null;
    }

    // 2. QUERY DATI ARIA (Attenzione alle Maiuscole!)
    $queryAir = "
        SELECT 
            DATE(date_time) as data_giorno,
            AVG(aqi) as media_aqi,
            AVG(pm2_5) as media_pm25,
            AVG(pm10) as media_pm10
        FROM RegistrationAir
        WHERE City_name = :city
          AND date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
        GROUP BY DATE(date_time)
    ";
    $stmtAir = $pdo->prepare($queryAir);
    $stmtAir->execute(['city' => $city]);

    foreach ($stmtAir->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $giorno = $row['data_giorno'];
        if (array_key_exists($giorno, $aqi_data)) {
            // Convertiamo esplicitamente in numeri interi e decimali
            $aqi_data[$giorno] = (int)round($row['media_aqi']);
            $pm25_data[$giorno] = (float)round($row['media_pm25'], 1);
            $pm10_data[$giorno] = (float)round($row['media_pm10'], 1);
        }
    }

    // 3. QUERY DATI METEO (Attenzione alle Maiuscole!)
    $queryWeather = "
        SELECT 
            DATE(date_time) as data_giorno,
            AVG(temperature) as media_temp 
        FROM RegistrationWeather
        WHERE City_name = :city
          AND date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
        GROUP BY DATE(date_time)
    ";
    $stmtWeather = $pdo->prepare($queryWeather);
    $stmtWeather->execute(['city' => $city]);

    foreach ($stmtWeather->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $giorno = $row['data_giorno'];
        if (array_key_exists($giorno, $temp_data)) {
            $temp_data[$giorno] = (float)round($row['media_temp'], 1);
        }
    }

    // 4. IMPACCHETTIAMO E STAMPIAMO IL JSON
    echo json_encode([
        "dates" => array_values($dates_labels),
        "aqi"   => array_values($aqi_data),
        "pm25"  => array_values($pm25_data),
        "pm10"  => array_values($pm10_data),
        "temp"  => array_values($temp_data)
    ]);

} catch (Exception $e) {
    // Se c'è un errore, restituiamo un JSON con l'errore per il debug
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>