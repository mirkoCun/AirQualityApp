<?php
require 'database.php';

// USING PDO ERRORS
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. CITIES
$check = $pdo->query("SELECT COUNT(*) FROM City")->fetchColumn();
if ($check == 0) {
    $filename = "comuniTrentino.csv";
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        while ($linea = fgets($file)) {
            $parts = explode(",", $linea);
            $name = trim($parts[0]);
            $lat = trim($parts[1]);
            $lon = trim($parts[2]);
            $stmt = $pdo->prepare("INSERT IGNORE INTO City VALUES (:name, 'TN', :lat, :lon)");
            $stmt->execute([':name' => $name, ':lat' => $lat, ':lon' => $lon]);
        }
        fclose($file);
    }
}

$cities = $pdo->query("SELECT Name, Latitude, Longitude FROM City")->fetchAll(PDO::FETCH_ASSOC);

// 2. QUERY
$sqlAir = "INSERT IGNORE INTO RegistrationAir (aqi, pm10, pm2_5, `no`, no2, co, nh3, o3, date_time, City_name) 
           VALUES (:aqi, :pm10, :pm2_5, :no, :no2, :co, :nh3, :o3, :dt, :city)";

$sqlWeather = "INSERT IGNORE INTO RegistrationWeather (date_time, City_name, temperature, feels_like, humidity, precipitation, precip_probability, wind_speed, weather_code) 
               VALUES (:dt, :city, :temp, :feels, :hum, :prec, :prob, :wind, :w_code)";

$stmtAir = $pdo->prepare($sqlAir);
$stmtWeather = $pdo->prepare($sqlWeather);

foreach($cities as $city) {
    $lat = $city['Latitude'];
    $lon = $city['Longitude'];
    $name = $city['Name'];

    // AIR
    $urlAir = "https://air-quality-api.open-meteo.com/v1/air-quality?latitude=$lat&longitude=$lon&hourly=pm10,pm2_5,carbon_monoxide,nitrogen_dioxide,ozone,nitrogen_monoxide,ammonia,european_aqi&past_days=31&timezone=Europe%2FRome";
    
    // WEATHER
    $urlWeather = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&hourly=temperature_2m,relative_humidity_2m,precipitation,precipitation_probability,wind_speed_10m,apparent_temperature,weather_code&past_days=31&timezone=Europe%2FRome";

    $dataAir = json_decode(file_get_contents($urlAir), true);
    $dataWeather = json_decode(file_get_contents($urlWeather), true);

    // CHECK IF THE API WORKS
    if (!isset($dataAir['hourly']['time']) || !isset($dataWeather['hourly']['temperature_2m'])) {
        echo "Errore API per la città $name. Salto...\n<br>";
        continue; //GO TO THE NEXT CITY
    }

    $timeArr = $dataAir['hourly']['time'];

    
    try {
        foreach ($timeArr as $i => $time_val) {
            $dt = str_replace('T', ' ', $time_val); 

            //AIR
            $stmtAir->execute([
                ':aqi'   => $dataAir['hourly']['european_aqi'][$i],
                ':pm10'  => $dataAir['hourly']['pm10'][$i],
                ':pm2_5' => $dataAir['hourly']['pm2_5'][$i],
                ':no'    => $dataAir['hourly']['nitrogen_monoxide'][$i],
                ':no2'   => $dataAir['hourly']['nitrogen_dioxide'][$i],
                ':co'    => $dataAir['hourly']['carbon_monoxide'][$i],
                ':nh3'   => $dataAir['hourly']['ammonia'][$i],
                ':o3'    => $dataAir['hourly']['ozone'][$i],
                ':dt'    => $dt,
                ':city'  => $name
            ]);

            //WEATHER
            $stmtWeather->execute([
                ':dt'     => $dt,
                ':city'   => $name,
                ':temp'   => $dataWeather['hourly']['temperature_2m'][$i],
                ':feels'  => $dataWeather['hourly']['apparent_temperature'][$i],
                ':hum'    => $dataWeather['hourly']['relative_humidity_2m'][$i],
                ':prec'   => $dataWeather['hourly']['precipitation'][$i],
                ':prob'   => $dataWeather['hourly']['precipitation_probability'][$i],
                ':wind'   => $dataWeather['hourly']['wind_speed_10m'][$i],
                ':w_code' => $dataWeather['hourly']['weather_code'][$i]
            ]);
        }
     
        
    } catch (PDOException $e) {
        //IF ERROR
        echo "DB ERROR on $name: " . $e->getMessage() . "\n<br>";
    }
}
?>