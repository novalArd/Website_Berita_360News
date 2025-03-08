document.addEventListener('DOMContentLoaded', function () {
    const dataStatistics = {
      labels: ["Artikel 1", "Artikel 2", "Artikel 3", "Artikel 4", "Artikel 5"], 
      views: [150, 300, 120, 200, 180], 
      likes: [25, 45, 10, 30, 22], 
    };
  
    // Konfigurasi data untuk Chart.js
    const ctx = document.getElementById('statistikChart').getContext('2d');
    new Chart(ctx, {
      type: 'line', 
      data: {
        labels: dataStatistics.labels, 
        datasets: [
          {
            label: 'Views', 
            data: dataStatistics.views, 
            borderColor: 'rgba(75, 192, 192, 1)', 
            backgroundColor: 'rgba(75, 192, 192, 0.2)', 
            borderWidth: 2, 
            tension: 0.3, 
          },
          {
            label: 'Likes', 
            data: dataStatistics.likes, 
            borderColor: 'rgba(255, 99, 132, 1)', 
            backgroundColor: 'rgba(255, 99, 132, 0.2)', 
            borderWidth: 2, 
            tension: 0.3, 
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top', 
          },
          title: {
            display: true,
            text: 'Statistik Artikel (Views & Likes)',
          },
        },
        scales: {
          y: {
            beginAtZero: true, 
          },
        },
      },
    });
  });
  