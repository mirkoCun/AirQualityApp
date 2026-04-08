<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo Trentino - Air Quality</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/airQuality.css">
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
            <a href="../index.php" class="nav-link"><i data-lucide="home" style="width: 16px; height: 16px;"></i> Home</a>
            <a href="forecast.php" class="nav-link"><i data-lucide="cloud-sun" style="width: 16px; height: 16px;"></i> Weather Forecast</a>
            <a href="airQuality.php" class="nav-link active"><i data-lucide="wind" style="width: 16px; height: 16px;"></i> Air Quality</a>
            <a href="historical.php" class="nav-link"><i data-lucide="history" style="width: 16px; height: 16px;"></i> Forecast History</a>
        </nav>
    </header>

    <main class="container">
        
        <div class="section-title-wrapper mb-6">
            <div class="section-title">
                <h2>Air Quality</h2>
                <p>Real-time air quality monitoring for <span id="title-city">Trento</span></p>
            </div>
        </div>

        <div class="card city-selector">
            <div class="icon-box" style="border-radius: 50%; padding: 12px; background-color: #3B82F6;">
                <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i>
            </div>
            <div class="select-wrapper">
                <label>Select Location</label>
                <select id="city-select">
                    <option value="Trento">Trento</option>
                    <option value="Rovereto">Rovereto</option>
                </select>
            </div>
        </div>

        <div class="aria-grid">
            <div class="card air-card">
                <div class="air-header">
                    <div class="air-title">
                        <i data-lucide="wind" style="width: 18px; height: 18px;"></i>
                        <span>Air Quality</span>
                    </div>
                    <span class="badge badge-good" id="aqi-badge">Good</span>
                </div>
                <div class="aqi-display">
                    <span class="aqi-value" id="aqi-val">45</span>
                    <span class="aqi-label">AQI</span>
                </div>
                <div class="pollutants-grid">
                    <div class="pollutant-box">
                        <p class="pollutant-name">PM2.5</p>
                        <p><span class="pollutant-value" id="pm25-val">10.2</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">PM10</p>
                        <p><span class="pollutant-value" id="pm10-val">15</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">NO₂</p>
                        <p><span class="pollutant-value" id="no2-val">28.3</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">O₃</p>
                        <p><span class="pollutant-value" id="o3-val">52.8</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">CO</p>
                        <p><span class="pollutant-value" id="co-val">6.7</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                    <div class="pollutant-box">
                        <p class="pollutant-name">NH₃</p>
                        <p><span class="pollutant-value" id="nh3-val">6.67</span> <span class="pollutant-unit">µg/m³</span></p>
                    </div>
                </div>
            </div>

            <div class="side-cards">
                <div class="card">
                    <h3 class="small-card-title">Trend</h3>
                    <div class="trend-flex">
                        <i data-lucide="trending-down" class="trend-icon good" style="width: 32px; height: 32px;"></i>
                        <div class="trend-text">
                            <h4 id="trend-text">Improving</h4>
                            <p>Compared to yesterday</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h3 class="small-card-title">Recommendations</h3>
                    <div class="rec-box">
                        <i data-lucide="check-circle-2" class="rec-icon" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
                        <span id="rec-text">Excellent air quality. Ideal for outdoor activities.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="ranking-title">Air Quality Ranking - Trentino</h3>
            <div id="ranking-list">
                </div>
        </div>

    </main>

    <script src="../javascript/airQuality.js"></script>
    <script src="../javascript/globalSelector.js"></script>
</body>
</html>