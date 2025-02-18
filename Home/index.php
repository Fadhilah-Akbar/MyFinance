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
    <div class="row">
    <!-- Begin of chart section -->
    <div class="col-sm-6">
      <div class="card shadow my-4">
        <div class="card-body">
          <div class="d-flex justify-content-center">
            <h4>Cash Flow Chart</h4>
          </div>
          <div class="card p-3">
            <div class="d-flex justify-content">
              <div class="card p-2 mt-3">
                <select id="chartFilter" class="form-select" onchange="updateChartData()">
                  <option value="monthly">Monthly</option>
                  <option value="daily">Daily</option>
                  <option value="yearly">Yearly</option>
                </select>
              </div>
            </div>
            <canvas id="charts"></canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- End of chart section -->
    <!-- Begin of cashflow by category -->
    <div class="col-sm-6">
      <div class="card shadow my-4">
        <div class="card-body">
          <h4>Cashflow By Category</h4>
          <div class="card">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <button id="pdfCategory" class="btn btn-info">PDF</button>
                </div>
                <div>
                  <input style="width: auto;" class="form-control mb-2" type="text" id="categoryDateRange" placeholder="filter Date Range" />
                  <select id="categoryFilter" class="form-select" onchange="">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                  </select>
                </div>
              </div>

              <table id="cashFlowByCategory" class="table table-striped table-bordered my-3" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Details</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be generated by datatables with ajax -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End of cashflow by category -->
    <!-- Begin of Detail CashFlow -->
    <div class="col-sm-12">
      <div class="card shadow my-4">
        <div class="card-body">
          <h4>Detail Cashflow</h4>
          <div class="card">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <button id="pdfDetail" class="btn btn-info">PDF</button>
                </div>
                <div class="d-flex justify-content-between">
                  <input style="width: auto;" class="form-control me-2" type="text" id="detailDateRange" placeholder="filter Date Range" />
                  <select id="detailFilter" class="form-select me-2" onchange="">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                  </select>
                  <input type="search" name="search" class="form-control" placeholder="Search...">
                </div>
              </div>

              <table id="detailCashFlow" class="table table-striped table-bordered my-3" style="width:100%">
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
    <!-- End of Detail CashFlow -->
  </div>
</div>


<script>
  // initial variable
  var categoryFilter = "monthly";
  var categoryDateFilter = "";
  var detailFilter = "monthly";
  var detailDateFilter = "";
  
  $(document).ready(function() {
    // section category
    CashFlowCategory = $('#cashFlowByCategory').DataTable({
      ajax: {
        "url": "config/dashboard/fetch_by_category.php",
        "dataSrc": ""
      },
      columns: [
        { 
          "data": null, 
          "className": 'text-center',
          "render": function (data, type, row, meta) {
            return meta.row + 1;
          }
        },
        { 
          "data": "date",
          "className": 'text-center'
        },
        { 
          "data": "detail",
          "className": 'text-center'
        },
        { 
          "data": "title",
          "className": 'text-center'
        },
        { 
          "data": "total",
          "className": 'text-center card-shadow'
        }
      ],
      lengthMenu : [3, 5, 10, 25, 50, 100],
      dom : 't<"d-flex justify-content-between"<"align-self-start"l><"text-center"i><"align-self-end"p>>',
      responsive: true,
      autoWidth: false,
      createdRow : function(row, data, dataIndex) {
        if (data.type == 'income') {
          $(row).find('td').addClass('bg-success text-light');
        } else if (data.type == 'spend') {
          $(row).find('td').addClass('bg-danger text-light');
        }
      }
    });

    const categoryPicker = new Litepicker({
      element: document.getElementById('categoryDateRange'),
      singleMode: false,
      format: 'YYYY-MM-DD',
      setup: (picker) => {
        picker.on('selected', (startDate, endDate) => {
          categoryDateFilter = `start=${startDate.format('YYYY-MM-DD')}&end=${endDate.format('YYYY-MM-DD')}`;
          fetchCategoryData(categoryFilter,categoryDateFilter);
        });
      }
    });

    function fetchCategoryData(filter,date) {
      fetch(`config/dashboard/fetch_by_category.php?filter=${filter}&${date}`)
        .then(response => response.json())
        .then(data => {
          CashFlowCategory.clear();
          CashFlowCategory.rows.add(data);
          CashFlowCategory.draw();
        })
        .catch(error => {
          console.error('Error fetching category data:', error);
        });
    }

    $('#categoryFilter').on('change', function() {
      categoryFilter = $(this).val();
      fetchCategoryData(categoryFilter,categoryDateFilter);
    });

    $('#pdfCategory').on('click', function() {
      fetch(`config/dashboard/fetch_by_category.php?filter=${categoryFilter}&${categoryDateFilter}`)
        .then(response => response.json())
        .then(dataJson => {
          let dates = dataJson.map(item => item.date);
          let dateObjects = dates.map(dateStr => new Date(dateStr.replace('-', '1,')));

          let start = new Date(Math.min(...dateObjects));
          let end = new Date(Math.max(...dateObjects));

            $.ajax({
                url: 'http://localhost/MyFinance/home/pdf/category.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ 
                  start: start,
                  end : end,
                  data: dataJson }),
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    if (response instanceof Blob) {
                        const url = window.URL.createObjectURL(response);
                        const a = document.createElement('a');
                        a.href = url;
                        a.target = '_blank';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        console.error('File PDF tidak diterima sebagai Blob');
                    }
                },
                error: function(xhr) {
                  console.error('Error:', xhr.responseText);
                },
            });
        })
        .catch(error => {
          console.error('Error download PDF data:', error);
        });
    });

    // section detail
    detailCashFlow = $('#detailCashFlow').DataTable({
      "ajax": {
        "url": "config/dashboard/fetch.php",
        "dataSrc": ""
      },
      "columns": [
        { 
          "data": null, 
          "className": 'text-center',
          "render": function (data, type, row, meta) {
            return meta.row + 1;
          }
        },
        { 
          "data": "date",
          "className": 'text-center'
        },
        { 
          "data": "type",
          "className": 'text-center'
        },
        { 
          "data": "detail",
          "className": 'text-center'
        },
        { 
          "data": "nominal",
          "className": 'text-center'
        },
        { 
          "data": "category",
          "className": 'text-center card-shadow'
        }
      ],
      "lengthMenu": [10, 25, 50, 100],
      "dom": 't<"d-flex justify-content-between"<"align-self-start"l><"text-center"i><"align-self-end"p>>',
      "responsive": true,
      "autoWidth": false,
      "createdRow": function(row, data, dataIndex) {
        if (data.type == 'income') {
          $(row).find('td').addClass('bg-success text-light');
        } else if (data.type == 'spend') {
          $(row).find('td').addClass('bg-danger text-light');
        }
      }
    });

    const detailPicker = new Litepicker({
      element: document.getElementById('detailDateRange'),
      singleMode: false,
      format: 'YYYY-MM-DD',
      setup: (picker) => {
        picker.on('selected', (startDate, endDate) => {
          detailDateFilter = `start=${startDate.format('YYYY-MM-DD')}&end=${endDate.format('YYYY-MM-DD')}`;
          fetchDetailData(detailFilter,detailDateFilter);
        });
      }
    });

    function fetchDetailData(filter,date) {
      fetch(`config/dashboard/fetch.php?filter=${filter}&${date}`)
        .then(response => response.json())
        .then(data => {
          detailCashFlow.clear();
          detailCashFlow.rows.add(data);
          detailCashFlow.draw();
        })
        .catch(error => {
          console.error('Error fetching detail data:', error);
        });
    }

    $('#detailFilter').on('change', function() {
      detailFilter = $(this).val();
      fetchDetailData(detailFilter,detailDateFilter);
    });

    $('#pdfDetail').on('click', function() {      
      fetch(`config/dashboard/fetch.php?filter=${detailFilter}&${detailDateFilter}`)
        .then(response => response.json())
        .then(dataJson => {
          let dates = dataJson.map(item => item.date);
          let dateObjects = dates.map(dateStr => new Date(dateStr));

          let start = new Date(Math.min(...dateObjects));
          let end = new Date(Math.max(...dateObjects));

            $.ajax({
                url: 'http://localhost/MyFinance/home/pdf/detail.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ 
                  start: start,
                  end : end,
                  data: dataJson }),
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    if (response instanceof Blob) {
                        const url = window.URL.createObjectURL(response);
                        const a = document.createElement('a');
                        a.href = url;
                        a.target = '_blank';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        console.error('File PDF tidak diterima sebagai Blob');
                    }
                },
                error: function(xhr) {
                  console.error('Error:', xhr.responseText);
                },
            });
        })
        .catch(error => {
          console.error('Error download PDF data:', error);
        });
    });
    
    $('input[type="search"]').on('keyup change', function() {
      detailCashFlow.search(this.value).draw();
    });

    fetchDetailData(categoryFilter,categoryDateFilter);
    fetchCategoryData(detailFilter,detailDateFilter);
  });

  //section charts
  const chartObj = document.getElementById('charts').getContext('2d');
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

  function fetchChartData(filter) {
      fetch(`config/dashboard/fetch_chart.php?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            charts.data.labels = data.labels;
            charts.data.datasets[0].data = data.incomeData;
            charts.data.datasets[1].data = data.expenseData;
            charts.update();
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        });
  }

  function updateChartData() {
      const filter = document.getElementById('chartFilter').value;
      fetchChartData(filter);
  }

  fetchChartData('monthly');
</script>

<?php include 'footer.php'; ?>
