// Attendre que le DOM soit complètement chargé avant d'exécuter le script

document.addEventListener('DOMContentLoaded', function () {
    const clock = document.querySelector('.clock');
    const locations = document.querySelectorAll('.location');
    const aiguilles = document.querySelectorAll('.aiguille');

    function positionLocations() {
        const radius = clock.clientWidth / 2 * 0.8;
        locations.forEach((location, index) => {
            const angle = (index / locations.length) * 2 * Math.PI;
            const x = Math.cos(angle) * radius;
            const y = Math.sin(angle) * radius;
            location.style.transform = `translate(${x}px, ${y}px)`;
            location.dataset.angle = angle * (180 / Math.PI);
        });
    }
}
    // Fonction pour mettre à jour l'horloge
    function updateClock() {
        const now = new Date();
        const hours = now.getHours() % 12;
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        // Calculer l'angle de rotation pour chaque aiguille
        // L'aiguille des heures tourne de 30 degrés par heure (360 / 12) plus 0.5 degré par minute
        aiguilles[0].style.transform = `rotate(${(hours * 30) + (minutes * 0.5)}deg)`;
        // L'aiguille des minutes tourne de 6 degrés par minute (360 / 60)
        aiguilles[1].style.transform = `rotate(${minutes * 6}deg)`;
        // L'aiguille des secondes tourne de 6 degrés par seconde (360 / 60)
        aiguilles[2].style.transform = `rotate(${seconds * 6}deg)`;

        // Mettre à jour l'affichage numérique de l'heure pour plus de précision
        digitalTime.textContent = now.toLocaleTimeString();
    }

    // Initialiser la position des locations et l'horloge
    positionLocations();
    updateClock();

    // Mettre à jour l'horloge chaque seconde pour un mouvement fluide
    setInterval(updateClock, 1000);

    // Repositionner les locations lors du redimensionnement de la fenêtre
    // pour maintenir la disposition correcte sur différentes tailles d'écran
    window.addEventListener('resize', positionLocations);
});
