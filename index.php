<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo Trentino - Home</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header class="site-header">
        <div class="header-logo">
            <div class="icon-box">
                <i data-lucide="cloud" style="width: 24px; height: 24px;"></i>
            </div>
            <div class="logo-text">
                <h1>Meteo Trentino</h1>
                <p>Air quality and forecasts</p>
            </div>
        </div>
        
        <nav class="nav-menu">
            <a href="index.php" class="nav-link active"><i data-lucide="home" style="width: 16px; height: 16px;"></i>Home</a>
            <a href="pages/forecast.php" class="nav-link"><i data-lucide="cloud-sun" style="width: 16px; height: 16px;"></i>Weather Forecast</a>
            <a href="pages/airQuality.php" class="nav-link"><i data-lucide="wind" style="width: 16px; height: 16px;"></i>Air Quality</a>
            <a href="pages/historical.php" class="nav-link"><i data-lucide="history" style="width: 16px; height: 16px;"></i>Forecast History</a>
        </nav>
    </header>

    <main class="container">
        <div class="section-title-wrapper">
            <div class="section-title">
                <h2>Current Conditions</h2>
                <p id="last-update">Last update: Loading...</p>
            </div>
            <button onclick="location.reload()" class="btn-outline">
                <i data-lucide="refresh-cw" style="width: 16px; height: 16px;"></i> Refresh
            </button>
        </div>

        <div class="card city-selector">
            <div class="icon-box" style="border-radius: 50%; padding: 12px;">
                <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i>
            </div>
            <div class="select-wrapper">
                <label>Select Location</label>
                <select id="city-select">
                    <option value="Trento">Trento</option>
                    <option value="Rovereto">Rovereto</option>
                    <option value="Riva del Garda">Riva del Garda</option>
                    <option value="Ala">Ala</option>
                </select>
            </div>
        </div>

        <div class="main-grid">
            <div class="card weather-card">
                <div class="weather-icon-large" id="weather-icon-container">
                    <i data-lucide="cloud" style="width: 48px; height: 48px;"></i>
                </div>
                <p class="weather-city-label">Location</p>
                <h3 class="weather-city-name" id="city-name">Trento</h3>
                
                <div class="temp-display">
                    <span class="temp-value" id="temp-val">18</span>
                    <span class="temp-unit">°C</span>
                </div>
                <p class="weather-desc" id="weather-desc">Partly cloudy</p>

                <div class="weather-details">
                    <div class="detail-item">
                        <p><i data-lucide="droplet" style="width: 12px; height: 12px;"></i> Humidity</p>
                        <p id="hum-val">62%</p>
                    </div>
                    <div class="detail-item">
                        <p><i data-lucide="wind" style="width: 12px; height: 12px;"></i> Wind</p>
                        <p id="wind-val">8 km/h</p>
                    </div>
                    <div class="detail-item">
                        <p><i data-lucide="thermometer" style="width: 12px; height: 12px;"></i> Feels like</p>
                        <p id="feels-val">17°C</p>
                    </div>
                </div>
            </div>

            <div class="card air-card">
                <div class="air-header">
                    <p class="air-title"><i data-lucide="wind" style="width: 16px; height: 16px;"></i> Air Quality</p>
                    <span class="badge badge-good" id="aqi-badge">Good</span>
                </div>
                
                <div class="aqi-display">
                    <span class="aqi-value" id="aqi-val">45</span>
                    <span class="aqi-label">AQI</span>
                </div>

                <div class="pollutants-grid">
                    <div class="pollutant-box">
                        <p class="pollutant-name">PM2.5</p>
                        <p class="pollutant-value"><span id="pm25-val">9.2</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">PM10</p>
                        <p class="pollutant-value"><span id="pm10-val">18.5</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">NO₂</p>
                        <p class="pollutant-value"><span id="no2-val">28.3</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">O₃</p>
                        <p class="pollutant-value"><span id="o3-val">52.8</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card">
                <p class="stat-card-title">Average Temp Trentino</p>
                <div class="stat-flex">
                    <p class="stat-value">18.2<span class="stat-unit">°C</span></p>
                    <i data-lucide="trending-up" style="color: #10B981; width: 20px; height: 20px;"></i>
                </div>
                <p class="stat-trend text-green">+2.5°C compared to yesterday</p>
            </div>

            <div class="card">
                <p class="stat-card-title">Average Regional AQI</p>
                <div class="stat-flex">
                    <p class="stat-value">37<span class="stat-unit"> AQI</span></p>
                    <i data-lucide="trending-down" style="color: #10B981; width: 20px; height: 20px;"></i>
                </div>
                <p class="stat-trend text-green">Air quality improving</p>
            </div>

            <div class="card">
                <p class="stat-card-title">Monitored Locations</p>
                <div class="stat-flex">
                    <p class="stat-value">8</p>
                    <i data-lucide="map-pin" style="color: #3B82F6; width: 20px; height: 20px;"></i>
                </div>
                <p class="stat-trend" style="color: #64748B;">Active stations in Trentino</p>
            </div>
        </div>
    </main>

    <script src="javascript/index.js"></script>
    <script src="javascript/globalSelector.js"></script>
</body>
</html>