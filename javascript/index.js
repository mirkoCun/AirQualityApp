// === index.js ===

// Inizializza le icone della libreria Lucide
lucide.createIcons();

// Funzione per mostrare la data e l'ora correnti
const updateDateString = () => {
    const now = new Date();
    const options = { day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' };
    document.getElementById('last-update').innerText = `Ultimo aggiornamento: ${now.toLocaleDateString('it-IT', options)}`;
};

updateDateString();

// Mappatura dei codici meteo ai testi e alle icone
const weatherMapping = {
    0: { desc: "Sereno", icon: "sun" },
    1: { desc: "Quasi Sereno", icon: "sun" },
    2: { desc: "Parzialmente nuvoloso", icon: "cloud-sun" },
    3: { desc: "Nuvoloso", icon: "cloud" },
    45: { desc: "Nebbia", icon: "cloud-fog" },
    61: { desc: "Pioggia", icon: "cloud-rain" },
    71: { desc: "Neve", icon: "cloud-snow" },
    95: { desc: "Temporale", icon: "cloud-lightning" }
};

// --- FUNZIONE PRINCIPALE (Ora INTEGRATA CON PHP) ---
function updateHome(city) {
    // 1. Indichiamo all'utente che stiamo caricando
    document.getElementById('city-name').innerText = "Caricamento...";

    // 2. Chiamata al file PHP
    fetch(`php/getHomeData.php?city=${city}`)
        .then(response => {
            if (!response.ok) throw new Error("Errore di rete");
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Errore dal database:", data.error);
                document.getElementById('city-name').innerText = "Dati non trovati";
                return;
            }

            // --- AGGIORNA METEO CON DATI DAL DB ---
            document.getElementById('city-name').innerText = city;
            document.getElementById('temp-val').innerText = Math.round(data.temperature);
            document.getElementById('hum-val').innerText = data.humidity + '%';
            document.getElementById('wind-val').innerText = data.wind_speed + ' km/h';
            document.getElementById('feels-val').innerText = Math.round(data.feels_like) + '°C';
            
            // Gestione icone e descrizione
            const weatherInfo = weatherMapping[data.weather_code] || { desc: "Sconosciuto", icon: "help-circle" };
            document.getElementById('weather-desc').innerText = weatherInfo.desc;
            
            const iconContainer = document.getElementById('weather-icon-container');
            iconContainer.innerHTML = `<i data-lucide="${weatherInfo.icon}" style="width: 48px; height: 48px;"></i>`;
            
            // --- AGGIORNA QUALITÀ ARIA CON DATI DAL DB ---
            document.getElementById('aqi-val').innerText = data.aqi;
            document.getElementById('pm25-val').innerText = data.pm2_5;
            document.getElementById('pm10-val').innerText = data.pm10;
            document.getElementById('no2-val').innerText = data.no2;
            document.getElementById('o3-val').innerText = data.o3;

            // Logica del Badge AQI
            const badge = document.getElementById('aqi-badge');
            if (data.aqi < 50) {
                badge.className = "badge badge-good";
                badge.innerText = "Buona";
            } else if (data.aqi < 100) {
                badge.className = "badge badge-fair";
                badge.innerText = "Discreta";
            } else {
                badge.className = "badge badge-poor"; 
                badge.innerText = "Scarsa";
            }

            // Re-inizializza le icone Lucide per mostrare quella nuova
            lucide.createIcons();
            
            // Aggiorna anche la stringa dell'orario
            updateDateString();
        })
        .catch(error => {
            console.error("Errore Fetch:", error);
            document.getElementById('city-name').innerText = "Errore di connessione";
        });
}

// Ascolta i cambiamenti nel menu a tendina
document.getElementById('city-select').addEventListener('change', function(e) {
    updateHome(e.target.value);
});

// Avvia la pagina prendendo il valore iniziale della select (che ora non è più fissa su Trento)
const initialCity = document.getElementById('city-select').value;
