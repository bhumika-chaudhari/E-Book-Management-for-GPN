<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style-dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </head>
<body>
    <button id="toggleSidebar" class="toggle-button">▣</button>

    <div id="sidebar" class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="#" id="statisticsLink">Statistics</a></li>
            <li><a href="book/books.php">Books</a></li>
            <li><a href="department_login.php">Department Login</a></li>
            <li><a href="admin_login.php">Logout</a></li>
        </ul>
    </div>

    <div id="main-content" class="main-content">
        <h1>Welcome to the Admin Dashboard</h1>
        <div id="statistics-section">
            <h2>Statistics</h2>
            <div class="chart-container">
    <canvas id="pieChart"></canvas>
</div>

            <div id="statistics-cards" class="cards"></div>

        </div>
    </div>

    <script>
     document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const statisticsSection = document.getElementById('statistics-section');
    const statisticsCards = document.getElementById('statistics-cards');
    const statisticsLink = document.getElementById('statisticsLink');
    const pieChartCanvas = document.getElementById('pieChart').getContext('2d');

    // Ensure the sidebar is visible by default
    sidebar.classList.add('open');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

    // Handle click on the "Statistics" link
    statisticsLink.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent page reload

        // Clear any existing cards
        statisticsCards.innerHTML = '';

        // Fetch statistics data from the PHP backend
        fetch('statistics.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data); // Debugging line

                // Show the statistics section
                statisticsSection.style.display = 'block';

                // Create a card for total notes
                const totalNotesCard = document.createElement('div');
                totalNotesCard.classList.add('card');
                totalNotesCard.innerHTML = `
                    <h3>Total Notes</h3>
                    <p>${data.totalNotes}</p>
                `;
                statisticsCards.appendChild(totalNotesCard);

                // Create cards for each department
                data.notesPerDepartment.forEach(department => {
                    const departmentCard = document.createElement('div');
                    departmentCard.classList.add('card');
                    departmentCard.innerHTML = `
                        <h3>${department.department_name}</h3>
                        <p>Total Notes: ${department.total_notes}</p>
                    `;
                    statisticsCards.appendChild(departmentCard);
                });

                // Prepare data for the pie chart
                const chartLabels = data.notesPerDepartment.map(dept => dept.department_name);
                const chartData = data.notesPerDepartment.map(dept => dept.total_notes);

                // Create the pie chart
                new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Department Share',
                            data: chartData,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#E7E9ED',
                                '#FF9F40',
                                '#FFCD56',
                                '#D9D3E8',
                                '#C4E0FF',
                                '#B9D6F2',
                                '#C2C2F0'
                            ],
                            borderColor: '#000',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true, // Allow resizing
                        maintainAspectRatio: false, // Allow the chart to resize based on its container
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (context.parsed !== null) {
                                            label += ': ' + context.parsed.toLocaleString();
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching statistics:', error));
    });

    // Automatically trigger a click event on the "Statistics" link when the page loads
    statisticsLink.click();
});


    </script>
</body>
</html>
