// Fetch data for the chart using AJAX
fetch('fetch_data.php')
    .then(response => response.json())
    .then(data => {
        console.log('Fetched data:', data); // Log fetched data
        
        // Initialize variables for counts of each status for each type of cleaning
        var laundryPending = 0;
        var laundryInProgress = 0;
        var laundryCompleted = 0;

        var dryCleaningPending = 0;
        var dryCleaningInProgress = 0;
        var dryCleaningCompleted = 0;

        var spotCleaningPending = 0;
        var spotCleaningInProgress = 0;
        var spotCleaningCompleted = 0;

        // Extract counts for each status for each type of cleaning if data is available
        if (data.laundry) {
            laundryPending = data.laundry.find(item => item.status === 'pending')?.pending || 0;
            laundryInProgress = data.laundry.find(item => item.status === 'InProgress')?.inprogress || 0;
            laundryCompleted = data.laundry.find(item => item.status === 'completed')?.completed || 0;
        }

        if (data.drycleaning) {
            dryCleaningPending = data.drycleaning.find(item => item.status === 'pending')?.pending || 0;
            dryCleaningInProgress = data.drycleaning.find(item => item.status === 'inProgress')?.inprogress || 0;
            dryCleaningCompleted = data.drycleaning.find(item => item.status === 'completed')?.completed || 0;
        }

        if (data.spotcleaning) {
            spotCleaningPending = data.spotcleaning.find(item => item.status === 'Pending')?.pending || 0;
            spotCleaningInProgress = data.spotcleaning.find(item => item.status === 'inProgress')?.inprogress || 0;
            spotCleaningCompleted = data.spotcleaning.find(item => item.status === 'completed')?.completed || 0;
        }

        // Data for the chart
        var chartData = {
            labels: ['Laundry', 'Dry Cleaning', 'Spot Cleaning'],
            datasets: [{
                label: 'Pending',
                data: [laundryPending, dryCleaningPending, spotCleaningPending],
                backgroundColor: 'rgba(255, 255, 0, 0.5)', // Yellow color for pending
                borderColor: 'rgba(255, 255, 0, 1)',
                borderWidth: 1
            },
            {
                label: 'In Progress',
                data: [laundryInProgress, dryCleaningInProgress, spotCleaningInProgress],
                backgroundColor: 'rgba(255, 206, 86, 0.5)', // Orange color for in progress
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            },
            {
                label: 'Completed',
                data: [laundryCompleted, dryCleaningCompleted, spotCleaningCompleted],
                backgroundColor: 'rgba(75, 192, 192, 0.5)', // Teal color for completed
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Get the canvas element
        var ctx = document.getElementById('orderChart1').getContext('2d');

        // Chart configuration
        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Create the bar chart
        var orderChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: options
        });
    })
    .catch(error => console.error('Error fetching data:', error));
