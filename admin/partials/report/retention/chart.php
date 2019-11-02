<?php

//get months
$months = $retention['month'];
//get completion
$completion = [];
if(isset($retention['data']) && count($retention['data']) > 0 ){
  foreach($retention['data'] as $k => $v){
    $completion[] = $v['completion'];
  }
}

$data = $completion;

?>
<?php if(count($data) > 0){ ?>
  <canvas id="chart"></canvas>

  <script>
  var ctx = document.getElementById('chart');
  var chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: <?php echo json_encode($months);?>,
          datasets: [{
              label: 'turn over rates',
              data: <?php echo json_encode($data);?>,
              backgroundColor: 'blue',
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
      }
  });
  </script>
<?php }//if ?>
