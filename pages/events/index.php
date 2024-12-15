<style>
    #add-event-form {
        padding: 20px;
        border: 2px solid #ccc;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>

<!-- Bouton et titre -->
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold">Liste des Événements</h2>
    <button id="add-event-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Ajouter
    </button>
</div>

<!-- Formulaire d'ajout d'événement (caché par défaut) -->
<div id="add-event-form">
    <h3 class="text-xl font-bold mb-4">Ajouter un événement</h3>
    <form id="event-form">
        <!-- Champ "title" -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-bold mb-2">Titre</label>
            <input type="text" id="title" name="title" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Titre de l'événement">
        </div>

        <!-- Champ "description" -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Description de l'événement"></textarea>
        </div>

        <!-- Champ "filename" -->
        <div class="mb-4">
            <label for="filename" class="block text-sm font-bold mb-2">Nom du fichier (image)</label>
            <input type="text" id="filename" name="filename" required
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Nom du fichier (ex: image.png)">
        </div>

        <!-- Champ "duration" -->
        <div class="mb-4">
            <label for="duration" class="block text-sm font-bold mb-2">Durée (en minutes)</label>
            <input type="number" id="duration" name="duration" required min="1"
                class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Durée de l'événement">
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Ajouter l'événement
        </button>
    </form>
</div>

<!-- Table des événements -->
<table class="table-auto w-full border-collapse border border-gray-200">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">Id</th>
            <th class="border border-gray-300 px-4 py-2">Titre</th>
            <th class="border border-gray-300 px-4 py-2">Description</th>
            <th class="border border-gray-300 px-4 py-2">Fichier</th>
            <th class="border border-gray-300 px-4 py-2">Durée</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody id="events-tbody"></tbody>
</table>

<script defer>
    const addEventButton = document.getElementById('add-event-button');
    const addEventForm = document.getElementById('add-event-form');
    const eventForm = document.getElementById('event-form');
    const tbody = document.getElementById('events-tbody');

    addEventForm.style.display = 'none';

    // Affichage du formulaire au clic sur le bouton "Ajouter"
    addEventButton.addEventListener('click', () => {
        addEventForm.style.display = addEventForm.style.display === 'none' ? 'block' : 'none';
    });

    // Récupération des événements et affichage dans le tableau
    async function fetchEvents() {
        const response = await fetch('https://feffs.elioooooo.fr/event/get');
        const events = await response.json();
        tbody.innerHTML = ''; // Réinitialisation du tableau
        events.forEach(event => {
            const row = `
                <tr>
                    <td class="border border-gray-300 px-4 py-2">${event._id}</td>
                    <td class="border border-gray-300 px-4 py-2">${event.title}</td>
                    <td class="border border-gray-300 px-4 py-2">${event.description}</td>
                    <td class="border border-gray-300 px-4 py-2">${event.filename}</td>
                    <td class="border border-gray-300 px-4 py-2">${event.duration} min</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded">Modifier</button>
                        <button class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded" onclick="deleteEvent('${event._id}')">Supprimer</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Envoi des données du formulaire
    eventForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Récupération des champs du formulaire
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const filename = document.getElementById('filename').value;
        const duration = document.getElementById('duration').value;

        const eventData = {
            title,
            description,
            filename,
            duration: parseInt(duration)
        };

        try {
            const response = await fetch('https://feffs.elioooooo.fr/event/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            });

            if (response.ok) {
                alert('Événement ajouté avec succès !');
                eventForm.reset(); // Réinitialisation du formulaire
                addEventForm.style.display = 'none'; // Cacher le formulaire
                fetchEvents(); // Recharger la liste des événements
            } else {
                alert('Erreur lors de l’ajout de l’événement.');
            }
        } catch (error) {
            alert('Erreur de communication avec le serveur.');
        }
    });

    async function deleteEvent(id) {
        try {
            const response = await fetch(`https://feffs.elioooooo.fr/event/delete/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                alert('Événement supprimé avec succès !');
                fetchEvents(); // Recharger la liste des événements
            } else {
                alert('Erreur lors de la suppression de l’événement.');
            }
        } catch (error) {
            alert('Erreur de communication avec le serveur.');
        }
    }

    // Charger les événements au démarrage
    document.addEventListener('DOMContentLoaded', fetchEvents);
</script>