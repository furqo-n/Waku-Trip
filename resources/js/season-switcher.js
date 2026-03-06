window.switchSeason = function switchSeason(season, element) {
    if (element.classList.contains('active')) return;

    const textContent = document.getElementById('seasonal-text-content');
    const cardsContainer = document.getElementById('seasonal-cards-container');
    textContent.style.opacity = '0.3';
    cardsContainer.style.opacity = '0.3';

    fetch(`/?season=${season}&_t=${Date.now()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) throw new Error("HTTP Status " + response.status);
            return response.json();
        })
        .then(data => {
            const section = document.getElementById('seasonal-section');
            section.style.setProperty('--season-accent', data.seasonData.accent_color);

            document.querySelectorAll('.season-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('bg-white', 'text-dark', 'border');
                btn.style.backgroundColor = '';
                btn.style.color = '';
                btn.style.borderColor = '';
            });
            element.classList.add('active');
            element.classList.remove('bg-white', 'text-dark', 'border');
            element.style.backgroundColor = data.seasonData.accent_color;
            element.style.color = 'white';
            element.style.borderColor = data.seasonData.accent_color;

            const icon = document.getElementById('seasonal-icon');
            section.style.background = data.seasonData.bg_gradient;
            icon.textContent = data.seasonData.icon;

            document.getElementById('seasonal-title').textContent = data.seasonData.title;
            document.getElementById('seasonal-description').textContent = data.seasonData.description;
            document.getElementById('seasonal-button-text').textContent = data.seasonData.button_text;
            document.getElementById('seasonal-explore-btn').href = data.plannedUrl;

            cardsContainer.innerHTML = data.seasonalToursHtml;

            textContent.style.opacity = '1';
            cardsContainer.style.opacity = '1';

            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?season=' + season;
            window.history.pushState({ path: newUrl }, '', newUrl);
        })
        .catch(error => {
            console.error('Error switching season:', error);
            window.location.href = `/?season=${season}`;
        });
}
