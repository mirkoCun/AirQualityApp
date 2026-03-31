// Funzione per caricare le città dal database nel menu a tendina
function setupCitySelector() {
    const select = document.getElementById('city-select');

    fetch('php/getCities.php')
        .then(response => response.json())
        .then(cities => {
            if (cities.error) {
                console.error("Errore caricamento città:", cities.error);
                return;
            }

            // Puliamo il menu attuale
            select.innerHTML = '';

            // Aggiungiamo ogni città trovata nel DB come opzione
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                select.appendChild(option);
            });

            // Una volta caricate le città, carichiamo i dati della prima città in lista
            if (cities.length > 0) {
                updateHome(cities[0]);
            }
        })
        .catch(err => console.error("Errore fetch città:", err));
}

// Sostituisci la chiamata finale updateHome("Trento") con questa:
setupCitySelector();