<div class="wrap">
  <div class="bootstra-iso">
    <h1>Report</h1>
    <?php //tns_dd($retention); ?>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/retention/search.php', $data); ?>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/retention/chart.php', $data); ?>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/retention/content.php', $data); ?>
          </div>
      </div>
    </div>

  </div>
</div>
