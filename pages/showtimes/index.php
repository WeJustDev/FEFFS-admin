<style>
    #add-showtime-form {
        padding: 20px;
        border: 2px solid #ccc;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>

<!-- Bouton et titre -->
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold">Liste des Showtimes</h2>
    <button id="add-showtime-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Ajouter
    </button>
</div>

<!-- Formulaire d'ajout de showtime (caché par défaut) -->
<div id="add-showtime-form" style="display: none;">
    <h3 class="text-xl font-bold mb-4">Ajouter un showtime</h3>
    <form id="showtime-form">
        <!-- Champ "title" -->
        <div class="mb-4">
            <label for="showtime-title" class="block text-sm font-bold mb-2">Titre</label>
            <input type="text" id="showtime-title" name="title" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Titre du showtime">
        </div>

        <!-- Champ "date" -->
        <div class="mb-4">
            <label for="date" class="block text-sm font-bold mb-2">Date</label>
            <input type="datetime-local" id="date" name="date" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Date du showtime">
        </div>

        <!-- Champ "localisation" -->
        <div class="mb-4">
            <label for="localisation" class="block text-sm font-bold mb-2">Localisation</label>
            <input type="text" id="localisation" name="localisation" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Localisation du showtime">
        </div>

        <!-- Sélection d'un événement -->
        <div class="mb-4">
            <label for="event-select" class="block text-sm font-bold mb-2">Événement</label>
            <select id="event-select" name="event" required
                class="border rounded w-full py-2 px-3 text-gray-700">
                <!-- Options avec les événements vont ici -->
            </select>
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Ajouter le showtime
        </button>
    </form>
</div>

<!-- Table des showtimes -->
<table class="table-auto w-full border-collapse border border-gray-200">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">Id</th>
            <th class="border border-gray-300 px-4 py-2">Titre</th>
            <th class="border border-gray-300 px-4 py-2">Date</th>
            <th class="border border-gray-300 px-4 py-2">Localisation</th>
            <th class="border border-gray-300 px-4 py-2">Événement lié</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody id="showtimes-tbody"></tbody>
</table>

<script defer>
    const addShowtimeButton = document.getElementById('add-showtime-button');
    const addShowtimeForm = document.getElementById('add-showtime-form');
    const showtimeForm = document.getElementById('showtime-form');
    const showtimesTbody = document.getElementById('showtimes-tbody');
    const eventSelect = document.getElementById('event-select');

    // Affichage du formulaire au clic sur le bouton "Ajouter"
    addShowtimeButton.addEventListener('click', () => {
        addShowtimeForm.style.display = addShowtimeForm.style.display === 'none' ? 'block' : 'none';
    });

    // Récupération des showtimes et événements
    async function fetchShowtimes() {
        const response = await fetch('https://feffs.elioooooo.fr/showtime/get');
        const showtimes = await response.json();
        showtimesTbody.innerHTML = ''; // Réinitialisation du tableau
        showtimes.forEach(showtime => {
            const row = `
                <tr>
                    <td class="border border-gray-300 px-4 py-2">${showtime._id}</td>
                    <td class="border border-gray-300 px-4 py-2">${showtime.title}</td>
                    <td class="border border-gray-300 px-4 py-2">${new Date(showtime.date).toLocaleString()}</td>
                    <td class="border border-gray-300 px-4 py-2">${showtime.localisation}</td>
                    <td class="border border-gray-300 px-4 py-2">${showtime.event.title}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded" onclick="deleteShowtime('${showtime._id}')">Supprimer</button>
                    </td>
                </tr>
            `;
            showtimesTbody.innerHTML += row;
        });
    }

    async function fetchEvents() {
        const response = await fetch('https://feffs.elioooooo.fr/event/get');
        const events = await response.json();
        eventSelect.innerHTML = ''; // Réinitialisation des options
        events.forEach(event => {
            const option = `<option value="${event._id}">${event.title}</option>`;
            eventSelect.innerHTML += option;
        });
    }

    // Envoi des données du formulaire
    showtimeForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Récupération des champs du formulaire
        const title = document.getElementById('showtime-title').value;
        const date = document.getElementById('date').value;
        const localisation = document.getElementById('localisation').value;
        const eventId = document.getElementById('event-select').value;

        const showtimeData = {
            title,
            date,
            localisation,
            event: eventId
        };

        try {
            const response = await fetch('https://feffs.elioooooo.fr/showtime/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(showtimeData)
            });

            if (response.ok) {
                alert('Showtime ajouté avec succès !');
                showtimeForm.reset(); // Réinitialisation du formulaire
                addShowtimeForm.style.display = 'none'; // Cacher le formulaire
                fetchShowtimes(); // Recharger la liste des showtimes
            } else {
                alert('Erreur lors de l’ajout du showtime.');
            }
        } catch (error) {
            alert('Erreur de communication avec le serveur.');
        }
    });

    async function deleteShowtime(id) {
        try {
            const response = await fetch(`https://feffs.elioooooo.fr/showtime/delete/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                alert('Showtime supprimé avec succès !');
                fetchShowtimes(); // Recharger la liste des showtimes
            } else {
                alert('Erreur lors de la suppression du showtime.');
            }
        } catch (error) {
            alert('Erreur de communication avec le serveur.');
        }
    }

    // Charger les showtimes et événements au démarrage
    document.addEventListener('DOMContentLoaded', () => {
        fetchShowtimes();
        fetchEvents();
    });
</script>