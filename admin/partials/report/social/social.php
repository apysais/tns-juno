<div class="wrap">
  <div class="bootstra-iso">

    <h1>Social Manager Reporting</h1>

    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/social/heading.php', $data); ?>
          </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <h5>Combine Total Value: <?php echo isset($bracket['combine_total_value']) ? tns_money_format($bracket['combine_total_value']):0; ?></h5>
            <h5>Combine Bonus Value: <?php echo isset($bracket['combine_bonus_value']) ? tns_money_format($bracket['combine_bonus_value']):0; ?></h5>
          </div>
      </div>
    </div>
    <p></p>
    <p></p>
    <hr>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/social/bracket_one.php', $data); ?>
          </div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/social/bracket_two.php', $data); ?>
          </div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/social/bracket_three.php', $data); ?>
          </div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <?php TNS_View::get_instance()->admin_partials('report/social/bracket_four.php', $data); ?>
          </div>
      </div>
    </div>

  </div>
</div>
