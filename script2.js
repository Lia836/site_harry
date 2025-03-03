// script.js
document.addEventListener('DOMContentLoaded', function () {
    const clock = document.querySelector('.clock');
    const locations = document.querySelectorAll('.location');
    const aiguilles = document.querySelectorAll('.aiguille');
    const usersDataUrl = 'users_modif.json'; // URL du fichier JSON

    // Fonction pour charger les données JSON
    function loadUsersData(url) {
        return fetch(url)
            .then(response => response.json())
            .catch(error => {
                console.error('Erreur lors du chargement du fichier JSON :', error);
            });
    }

    // Fonction pour créer et positionner les localisations
    function createLocations(localisations) {
        const nb_locations = Object.keys(localisations).length;
        const angle_rotation = 360 / nb_locations;
        let first = 0;
        const dist = clock.clientWidth / 2;

        Object.keys(localisations).forEach(nom => {
            const localisation = localisations[nom];
            const locationElement = document.createElement('div');
            locationElement.classList.add('location');
            locationElement.setAttribute('id', nom);

            // Style de l'image de la location
            locationElement.innerHTML = `<img src="${localisation.img}" alt="${nom}">`;

            // Calcul de la position
            const angle = first;
            const x = Math.cos(angle * Math.PI / -180) * dist;
            const y = Math.sin(angle * Math.PI / -180) * dist;

            locationElement.setAttribute("style", `left: calc(42.5% + ${x}px); top: calc(42.5% + ${y}px)`);
            locationElement.setAttribute("data-angle", first);
            clock.appendChild(locationElement);
            first += angle_rotation;
        });
    }

    // Fonction pour positionner les aiguilles
    function positionAiguilles(personnes) {
        aiguilles.forEach(aiguille => {
            const lieu = aiguille.getAttribute('data-lieu');
            const locationElement = document.getElementById(lieu);
            if (locationElement) {
                const angle = locationElement.getAttribute('data-angle');
                aiguille.style.transform = `rotate(-${angle}deg)`;
            }
        });
    }

    // Initialisation : chargement des données et création de l'horloge
    loadUsersData(usersDataUrl)
        .then(data => {
            if (data && data.localisations && data.personnes) {
                createLocations(data.localisations);
                positionAiguilles(data.personnes);
            } else {
                console.error('Format de fichier JSON invalide.');
            }
        });
});