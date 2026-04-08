// Variabili globali per memorizzare i grafici ed evitare che si sovrappongano
let tempChart, aqiChart, pollutantsChart;

document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const citySelect = document.getElementById('city-select');
    const titleCity = document.getElementById('title-city');

    // Carica subito i dati per la città predefinita all'avvio
    loadHistoricalData(citySelect.value);

    // Quando cambi città nel menù a tendina...
    citySelect.addEventListener('change', (e) => {
        titleCity.textContent = e.target.value;
        // ... ricarica i grafici con i nuovi dati!
        loadHistoricalData(e.target.value);
    });
});

async function loadHistoricalData(city) {
    
    try {
        // Chiamata al nostro nuovo file PHP
        const response = await fetch(`../php/getHistoricalData.php?city=${city}&t=${Date.now()}`);
        const data = await response.json();

        // --- GRAFICO 1: TEMPERATURA ---
        if (tempChart) tempChart.destroy(); // Distrugge il vecchio grafico se esiste
        const ctxTemp = document.getElementById('tempChart').getContext('2d');
        tempChart = new Chart(ctxTemp, {
            type: 'line',
            data: {
                labels: data.dates, // Usa le date vere arrivate dal DB!
                datasets: [{
                    label: 'Average Temperature (°C)',
                    data: data.temp, // Usa le temperature vere arrivate dal DB!
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // --- GRAFICO 2: AQI ---
        if (aqiChart) aqiChart.destroy();
        const ctxAqi = document.getElementById('aqiChart').getContext('2d');
        aqiChart = new Chart(ctxAqi, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [{
                    label: 'AQI',
                    data: data.aqi, // Dati AQI veri!
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#8b5cf6',
                    tension: 0.1 
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // --- GRAFICO 3: INQUINANTI (Barre) ---
        if (pollutantsChart) pollutantsChart.destroy();
        const ctxPollutants = document.getElementById('pollutantsChart').getContext('2d');
        pollutantsChart = new Chart(ctxPollutants, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [
                    {
                        label: 'PM2.5 (µg/m³)',
                        data: data.pm25, // Dati veri
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    },
                    {
                        label: 'PM10 (µg/m³)',
                        data: data.pm10, // Dati veri
                        backgroundColor: '#06b6d4',
                        borderRadius: 4
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

    } catch (error) {
        console.error("Errore nel caricamento dei dati storici:", error);
    }
}

// Funzione globale per cambiare le schede (Tabs)
function switchTab(tabId) {
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    const sections = document.querySelectorAll('.chart-section');
    sections.forEach(sec => sec.style.display = 'none');

    if(tabId === 'temp') {
        document.getElementById('temp-section').style.display = 'block';
    } else if(tabId === 'air') {
        document.getElementById('air-section').style.display = 'block';
    }
}