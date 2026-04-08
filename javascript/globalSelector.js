// javascript/global-selectors.js

document.addEventListener('DOMContentLoaded', () => {
    const citySelect = document.getElementById('city-select');

    // Se il selettore non esiste in questa pagina, non fare nulla
    if (!citySelect) return;

    fetch('/airQualityApp/php/getCities.php') // Usa il percorso assoluto dalla root
        .then(response => response.json())
        .then(cities => {
            if (cities.error) throw new Error(cities.error);

            // Svuota le opzioni statiche se presenti
            citySelect.innerHTML = '';

            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });

            // Scatena l'evento iniziale per caricare i dati della prima città
            // Questo farà partire le funzioni updateAir() o updateWeather() 
            // specifiche di ogni pagina
            citySelect.dispatchEvent(new Event('change'));
        })
        .catch(err => console.error("Errore caricamento città globale:", err));
});