
// Fonction pour rechercher les noms en temps réel
function searchNames() {
    let query = document.getElementById('nameInput').value.trim();
    let resultsDiv = document.getElementById('results');

    // Cachez le conteneur des résultats si le champ est vide
    if (query.length === 0) {
        resultsDiv.innerHTML = '';
        resultsDiv.style.display = 'none'; // Cache le conteneur des résultats
        return;
    }

    // Envoi d'une requête AJAX pour obtenir les résultats de la recherche
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let results = JSON.parse(this.responseText);
            resultsDiv.innerHTML = '';

            if (results.length > 0) {
                results.forEach(function (result) {
                    let div = document.createElement('div');
                    div.textContent = `${result.nomVente} | ${result.codeACF} | ${result.region} | ${result.source}`; // Affiche le nom et la région

                    // Ajouter un événement au clic sur un résultat
                    div.onclick = function () {
                        document.getElementById('nameInput').value = result.alphaName; // Remplacer l'input
                        document.getElementById('name-output').textContent = result.alphaName; // Afficher le nom
                        document.getElementById('description-output').textContent = result.description;

                        // Mettre à jour d'abord la description
                        let histoElement = document.getElementById('descriptionFamille-output');
                         histoElement.textContent = result.histo;

                        // Ajoute l'ancien nom ou le fief selon la base
                        if (result.base[0] == "B") {
                            histoElement.textContent += `Ce nom est une probable dérivation de ${result.anc}.`; // concatène l'ancien nom
                        } else if (result.base[0] == "C") {
                            histoElement.textContent += `Ce nom est une probable dérivation de ${result.anc}.` + result.fiefAlpha; // concatène l'ancien nom et le fief
                        }
                        document.getElementById('source-output').textContent = "Source bibliographique: " + result.source; // Afficher la région
                        document.querySelector('.blason-image').src = result.blason; // Mettre à jour l'image du blason
                        resultsDiv.innerHTML = ''; // Vider les résultats après sélection
                        resultsDiv.style.display = 'none'; // Cacher les résultats après sélection
                    };
                    resultsDiv.appendChild(div);
                });
                resultsDiv.style.display = 'block'; // Affiche le conteneur des résultats
            } else {
                resultsDiv.innerHTML = '<div>Aucun résultat trouvé</div>';
                resultsDiv.style.display = 'block'; // Affiche le conteneur des résultats même si aucun résultat trouvé
            }
        }
    };
    xhr.open('GET', 'search2.php?query=' + encodeURIComponent(query), true);
    xhr.send();
}



function imprimer() {
    window.print();
}



function affichage_client() {
    const name = document.getElementById('name-output').textContent;
    const description = document.getElementById('description-output').textContent;
    const image = document.getElementById('blason-output').src;

    const url = `affiche_client.html?name=${encodeURIComponent(name)}&description=${encodeURIComponent(description)}&image=${encodeURIComponent(image)}`;

    window.open(url, '_blank');
}


function choix_police() {
    let police = document.getElementById('police').value;
    let name = document.getElementById('name-output');
    let desBlason = document.getElementById('description-output');
    if (police === 'anglaise') {
        name.style.fontFamily = 'Anglaise';
        desBlason.style.fontFamily = 'Anglaise';
    }else{
        console.log('police a installer');
    }
}