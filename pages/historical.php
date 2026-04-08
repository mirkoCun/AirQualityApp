<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo Trentino - Forecast History</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/historical.css">
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
            <a href="airQuality.php" class="nav-link"><i data-lucide="wind" style="width: 16px; height: 16px;"></i> Air Quality</a>
            <a href="historical.php" class="nav-link active"><i data-lucide="history" style="width: 16px; height: 16px;"></i> Forecast History</a>
        </nav>
    </header>

    <main class="container">
        
        <div class="section-title-wrapper mb-6">
            <div class="section-title">
                <h2>Forecast History</h2>
                <p>Historical analysis of weather conditions and air quality for <span id="title-city">Trento</span></p>
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
                    <option value="Riva del Garda">Riva del Garda</option>
                </select>
            </div>
        </div>

        <div class="summary-grid">
            <div class="card">
                <p class="stat-card-title flex-title"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i> ANALYZED PERIOD</p>
                <h3 style="font-size: 24px; font-weight: 700; margin: 8px 0;">Last 7 days</h3>
                <p class="stat-trend" style="color: #64748B;">26 Feb - 3 Mar 2026</p>
            </div>
            <div class="card">
                <p class="stat-card-title">TEMPERATURE TREND</p>
                <div class="stat-flex">
                    <i data-lucide="trending-up" style="color: #EF4444; width: 24px; height: 24px;"></i>
                    <p class="stat-value" style="color: #0F172A;">+3.0°</p>
                </div>
                <p class="stat-trend" style="color: #64748B;">Weekly variation</p>
            </div>
            <div class="card">
                <p class="stat-card-title">AIR QUALITY TREND</p>
                <div class="stat-flex">
                    <i data-lucide="trending-down" style="color: #10B981; width: 24px; height: 24px;"></i>
                    <p class="stat-value" style="color: #0F172A;">-7</p>
                </div>
                <p class="stat-trend" style="color: #64748B;">Weekly AQI variation</p>
            </div>
        </div>

        <div class="tabs-container">
            <button class="tab-btn active" onclick="switchTab('temp')">Temperature</button>
            <button class="tab-btn" onclick="switchTab('air')">Air Quality</button>
        </div>

        <div id="temp-section" class="chart-section active">
            <div class="card chart-card">
                <h3 class="chart-title">Temperature Trend - Last 7 days</h3>
                <div class="canvas-container">
                    <canvas id="tempChart"></canvas>
                </div>
            </div>
        </div>

        <div id="air-section" class="chart-section" style="display: none;">
            <div class="card chart-card mb-6">
                <h3 class="chart-title">Air Quality Index (AQI) - Last 7 days</h3>
                <div class="canvas-container">
                    <canvas id="aqiChart"></canvas>
                </div>
            </div>
            <div class="card chart-card">
                <h3 class="chart-title">Particulate Pollutants - Last 7 days</h3>
                <div class="canvas-container">
                    <canvas id="pollutantsChart"></canvas>
                </div>
            </div>
        </div>

    </main>

    <script src="../javascript/historical.js"></script>
    <script src="../javascript/globalSelector.js"></script>
</body>
</html>