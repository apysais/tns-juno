<a class="btn btn-primary" data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
  Show / Hide Advance Search Filters
</a>
<div class="collapse <?php echo $show_collapse ? 'show':'';?>" id="filterCollapse">
  <form method="post" action="admin.php?page=report-retention">
    <input type="hidden" name="_method" value="report-retention">
    <h6>Select Date Range</h6>
    <div class="form-row">
      <div class="form-group col-md-5">
        <label for="startDate">Start Date</label>
        <input type="text" name="startDate" value="<?php echo $post_start_date;?>" class="form-control form-control-sm" id="startDate" autocomplete="off">
      </div>
      <div class="form-group col-md-5">
        <label for="endDate">End Date</label>
        <input type="text" name="endDate" value="<?php echo $post_end_date;?>" class="form-control form-control-sm" id="endDate" autocomplete="off">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="services">Service</label>
        <?php tns_get_service_dropdown($post_service); ?>
      </div>
      <div class="form-group col-md-6">
        <label for="client">Active Client</label>
        <?php tns_get_active_client_dropdown($post_client); ?>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
