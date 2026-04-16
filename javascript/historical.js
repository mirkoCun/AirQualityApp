// Variabili globali
let tempChart, aqiChart, pollutantsChart;

document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const citySelect = document.getElementById('city-select');
    const titleCity = document.getElementById('title-city');

    // Carica subito i dati
    loadHistoricalData(citySelect.value);

    // Quando cambi città...
    citySelect.addEventListener('change', (e) => {
        titleCity.textContent = e.target.value;
        loadHistoricalData(e.target.value);
    });
});

async function loadHistoricalData(city) {
    try {
        // 1. Chiamata API
        const response = await fetch(`../php/getHistoricalData.php?city=${city}&t=${Date.now()}`);
        
        // Controllo se il PHP ha risposto correttamente (es. no errori 404 o 500)
        if (!response.ok) {
            throw new Error(`Errore di rete: ${response.status}`);
        }

        const data = await response.json();
        
        // STAMPA DI DEBUG: Guarda la console (F12) per vedere se i dati arrivano!
        console.log("Dati caricati dal DB:", data);

        // --- GRAFICO 1: TEMPERATURA ---
        if (tempChart) tempChart.destroy(); 
        const ctxTemp = document.getElementById('tempChart').getContext('2d');
        tempChart = new Chart(ctxTemp, {
            type: 'line',
            data: {
                labels: data.dates, 
                datasets: [{
                    label: 'Average Temperature (°C)',
                    data: data.temp, 
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
                    data: data.aqi, 
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
                        data: data.pm25, 
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    },
                    {
                        label: 'PM10 (µg/m³)',
                        data: data.pm10, 
                        backgroundColor: '#06b6d4',
                        borderRadius: 4
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

    } catch (error) {
        console.error("Errore nel caricamento dei dati storici. Controlla il file PHP!", error);
    }
}

// Funzione globale per cambiare le schede (Tabs) corretto
function switchTab(tabId) {
    // Rimuovi 'active' da tutti i bottoni
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Nascondi tutte le sezioni
    const sections = document.querySelectorAll('.chart-section');
    sections.forEach(sec => sec.style.display = 'none');

    // Mostra la sezione cliccata e seleziona il bottone giusto (Senza usare l'oggetto 'event' problematico)
    if(tabId === 'temp') {
        document.getElementById('temp-section').style.display = 'block';
        document.querySelector("button[onclick=\"switchTab('temp')\"]").classList.add('active');
    } else if(tabId === 'air') {
        document.getElementById('air-section').style.display = 'block';
        document.querySelector("button[onclick=\"switchTab('air')\"]").classList.add('active');
        
        // SOLUZIONE BUG CHART.JS: Forza il ridimensionamento dei grafici ora che il div è visibile!
        if (aqiChart) aqiChart.resize();
        if (pollutantsChart) pollutantsChart.resize();
    }
}