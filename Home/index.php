<?php 
$page = "Dashboard";
include 'header.php'; 
include 'sidebar.php'; 
?>

<!-- Main Content -->
<div id="content">
  <?php include 'topbar.php' ?>

  <!-- Dashboard Content -->
  <div class="container mt-4">
    <!-- Card for Welcome Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h2>My Dashboard</h2>
            <p>Easily track your income and expenses, evaluate your finances, and review your financial history.</p>
        </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4">
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <h4>Cash Flow Chart</h4>
            </div>
            <div class="card p-3">
                <div class="d-flex justify-content">
                    <div class="card p-2 ms-5 mt-3">
                        <select id="chartFilter" class="form-select" onchange="updateChartData()">
                            <option value="daily">Daily</option>
                            <option value="yearly">Yearly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
                <canvas id="charts"></canvas>
            </div>
        </div>
    </div>

    <!-- Detail of Cashflow -->
    <div class="card shadow my-4">
      <div class="card-body">
        <h4>Detail Cashflow</h4>
        <div class="card">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                  <input type="search" name="search" class="form-control" placeholder="Search...">
              </div>
            </div>

            <table id="cashFlowTable" class="table table-striped table-bordered my-3" style="width:100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Tipe</th>
                  <th>Keterangan</th>
                  <th>Nominal</th>
                  <th>Kategori</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data will be populated here dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>


<script>
    $(document).ready(function() {
    table = $('#cashFlowTable').DataTable({
      "ajax": {
        "url": "config/dashboard/fetch.php", // Replace with the path to your PHP script
        "dataSrc": ""
      },
      "columns": [
        { 
          "data": null, 
          "className": 'text-center',
          "render": function (data, type, row, meta) {
            return meta.row + 1; // Auto-generate row number
          }
        },
        { 
          "data": "tanggal",
          "className": 'text-center'
        },
        { 
          "data": "type",
          "className": 'text-center'
        },
        { 
          "data": "judul",
          "className": 'text-center'
        },
        { 
          "data": "nominal",
          "className": 'text-center'
        },
        { 
          "data": "nama",
          "className": 'text-center card-shadow'
        }
      ],
      "lengthMenu": [3, 5, 10, 25, 50, 100],
      "dom": 't<"d-flex justify-content-between"<"align-self-start"l><"text-center"i><"align-self-end"p>>',
      responsive: true,
      autoWidth: false,
      "createdRow": function(row, data, dataIndex) {
        // Tambahkan class bg-success atau bg-danger pada row berdasarkan 'type'
        if (data.type == 'income') {
        $(row).find('td').addClass('bg-success text-light'); // Kolom 'type' (kolom ke-3) diberi kelas bg-success
        } else if (data.type == 'spend') {
        $(row).find('td').addClass('bg-danger text-light'); // Kolom 'type' (kolom ke-3) diberi kelas bg-danger
        }
    }
    });

    $('input[type="search"]').on('keyup change', function() {
      table.search(this.value).draw();
    });
  });

    // charts
    const chartObj = document.getElementById('charts').getContext('2d');

    // Initialize empty chart
    const charts = new Chart(chartObj, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Income',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Spending',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Function to fetch data from the server based on selected filter
    function fetchChartData(filter) {
        fetch(`config/dashboard/fetch_chart.php?filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                // Update the chart with new data
                charts.data.labels = data.labels;
                charts.data.datasets[0].data = data.incomeData;
                charts.data.datasets[1].data = data.expenseData;
                charts.update(); // Update chart with the new data
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
            });
    }

    // Function to update chart based on filter
    function updateChartData() {
        const filter = document.getElementById('chartFilter').value;
        fetchChartData(filter);
    }

    // Initial load for the daily data
    fetchChartData('daily');
</script>

<?php include 'footer.php'; ?>
