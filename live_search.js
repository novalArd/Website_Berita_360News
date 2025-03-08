document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value;

    if (query.length < 3) {
        document.getElementById('searchResults').innerHTML = '';
        return;
    }

    fetch('live_search.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';

            if (data.length > 0) {
                data.forEach(article => {
                    const resultDiv = document.createElement('div');
                    resultDiv.className = 'result-item';
                    resultDiv.innerHTML = `
                        <a href="penulis/artikel.php?id=${article.id}">${article.judul}</a>
                    `;
                    resultsContainer.appendChild(resultDiv);
                });
            } else {
                resultsContainer.innerHTML = '<p class="no-results">Tidak ada hasil yang sesuai.</p>';
            }
        })
        .catch(error => console.error('Error:', error));
});
