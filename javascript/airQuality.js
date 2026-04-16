// === airQuality.js ===

// Inizializza le icone
lucide.createIcons();


function updateAir(city) {
    if (!city) return;

    // Chiamata al file PHP
    fetch(`../php/getAirQualityData.php?city=${city}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network error");
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Database error:", data.error);
                document.getElementById('title-city').innerText = "No data";
                return;
            }

            // 1. Aggiorna i dati numerici dell'aria
            document.getElementById('title-city').innerText = city;
            document.getElementById('aqi-val').innerText = data.citiesData.aqi;
            document.getElementById('pm25-val').innerText = data.citiesData.pm2_5;
            document.getElementById('pm10-val').innerText = data.citiesData.pm10;
            document.getElementById('no2-val').innerText = data.citiesData.no2;
            document.getElementById('o3-val').innerText = data.citiesData.o3;
            document.getElementById('co-val').innerText = data.citiesData.co;
            document.getElementById('nh3-val').innerText = data.citiesData.nh3;
            
            // 2. Logica del Badge AQI (colore)
            const badge = document.getElementById('aqi-badge');
            const currentAqi = parseInt(data.citiesData.aqi);

            // 2. Logica del Badge AQI (6 Livelli Europei)
            if (currentAqi <= 30) {
                badge.className = "badge badge-good";
                badge.innerText = "Buona";
            } else if (currentAqi <= 50) {
                badge.className = "badge badge-fair";
                badge.innerText = "Discreta";
            } else if (currentAqi <= 70) {
                badge.className = "badge badge-moderate";
                badge.innerText = "Moderata";
            } else if (currentAqi <= 80) {
                badge.className = "badge badge-poor";
                badge.innerText = "Scadente";
            } else if (currentAqi <= 100) {
                badge.className = "badge badge-very-poor";
                badge.innerText = "Pessima";
            } else {
                badge.className = "badge badge-hazardous"; 
                badge.innerText = "Emergenza";
            }

            // 3. Raccomandazioni testuali
            let recomendation;
            if (currentAqi <= 30) {
                recomendation = "Air quality is excellent. A perfect day for all outdoor activities and ventilating your home.";
            } else if (currentAqi <= 50) {
                recomendation = "Air quality is acceptable. Good for outdoor activities, but unusually sensitive people should monitor their symptoms.";
            } else if (currentAqi <= 70) {
                recomendation = "Air quality is moderate. Sensitive groups might experience minor health effects. Consider reducing intense outdoor exertion.";
            } else if (currentAqi <= 80) {
                recomendation = "Air quality is poor. Increased likelihood of health effects for sensitive groups. Reduce prolonged outdoor activities.";
            } else if (currentAqi <= 100) {
                recomendation = "Air quality is very poor. Health effects can be felt by everyone. Sensitive groups should strictly avoid outdoor exertion.";
            } else {
                recomendation = "Hazardous conditions. Everyone should limit outdoor time, avoid physical exertion outside, and keep windows closed.";
            }

            // 4. Logica Trend (confronto con ieri)
            let trend = "stabile";
            // Cambiato .aqi in .average_aqi
            if (data.yesterdayData && data.yesterdayData.average_aqi) {
                const yesterdayAqi = parseInt(data.yesterdayData.average_aqi); 
                if (currentAqi <= yesterdayAqi - 5) {
                    trend = "in miglioramento";
                } else if (currentAqi >= yesterdayAqi + 5) {
                    trend = "in peggioramento";
                }
            }

            document.getElementById('trend-text').innerText = trend;
            document.getElementById('rec-text').innerText = recomendation;

            // 5. CLASSIFICA REALE: Se il PHP invia l'array 'ranking', lo passiamo alla funzione
            if (data.ranking && Array.isArray(data.ranking)) {
                renderRanking(data.ranking);
            } else {
                console.warn("Classifica non trovata nel JSON del database.");
                // Opzionale: svuota o mostra un messaggio di errore nella UI della classifica
                document.getElementById('ranking-list').innerHTML = '<p style="color:#64748B; padding: 10px;">Dati classifica non disponibili.</p>';
            }

            // Ricarica le icone per i nuovi elementi appena generati
            lucide.createIcons(); 
        })
        .catch(error => {
            console.error("Errore Fetch:", error);
            document.getElementById('title-city').innerText = "Errore di connessione";
        }); 
}

// Funzione che accetta i dati dal DB e crea l'HTML della classifica
function renderRanking(rankingData) {
    const listContainer = document.getElementById('ranking-list');
    if (!listContainer) return;
    
    listContainer.innerHTML = ''; // Svuota la lista vecchia

    rankingData.forEach((item, index) => {
        const rankNum = index + 1;
        
        // Assegna colori speciali ai primi 3
        let rankClass = "rank-number";
        if (rankNum === 1) rankClass += " rank-1";
        if (rankNum === 2) rankClass += " rank-2";
        if (rankNum === 3) rankClass += " rank-3";

        // Adatta il nome della colonna in base a come l'hai chiamata nel DB (city o City_name)
        const cityName = item.city || item.City_name || item.city_name || "Sconosciuta";
        const cityAqi = parseInt(item.aqi) || 0;

        // Badge della classifica
        let badgeClass = "rank-badge good";
        if (cityAqi > 50 && cityAqi <= 100) badgeClass = "rank-badge fair";
        if (cityAqi > 100) badgeClass = "rank-badge poor";

        const rowHTML = `
            <div class="ranking-item">
                <div class="ranking-left">
                    <div class="${rankClass}">${rankNum}</div>
                    <span class="rank-city">${cityName}</span>
                </div>
                <div class="${badgeClass}">AQI ${cityAqi}</div>
            </div>
        `;
        listContainer.innerHTML += rowHTML;
    });
}

// === Event Listeners e Inizializzazione ===
const citySelect = document.getElementById('city-select');

if (citySelect) {
    // Ascolta i cambiamenti nel menu a tendina
    citySelect.addEventListener('change', function(e) {
        updateAir(e.target.value);
    });

    // Avvia la pagina prendendo il valore iniziale
    if (citySelect.value) {
        updateAir(citySelect.value);
    }
}