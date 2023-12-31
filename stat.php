<?php
require_once 'dbcon.php'; // Include your database connection code

// Fetch data from the database
$sql = "SELECT c.name as car_name, COUNT(*) as count 
        FROM bookings b
        JOIN cars c ON b.car_id = c.id
        GROUP BY c.name";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Create a canvas element for the chart -->
<canvas id="carChart"></canvas>

<script>
// Use the fetched data to create a pie chart
var ctx = document.getElementById('carChart').getContext('2d');
var data = <?php echo json_encode($data); ?>;

var carNames = data.map(function(item) {
    return item.car_name;
});

var counts = data.map(function(item) {
    return item.count;
});

var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: carNames,
        datasets: [{
            data: counts,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                // Add more colors as needed
            ],
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 1,
    },
});
</script>
