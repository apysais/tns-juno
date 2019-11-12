<?php if(isset($bracket['bracket_3'])){ ?>
<h5>Bracket 3 Details</h5>
<p>Total Billing Cycles : <?php echo $bracket['bracket_3']['total_billing_cycle'];?></p>
<p>Total Value : <?php echo tns_money_format($bracket['bracket_3']['total_value']);?></p>
<table class="table table-bracket table-bordered">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Start</th>
      <th scope="col">End</th>
      <th scope="col">Billing Cycles</th>
      <th scope="col">Service</th>
      <th scope="col">Value</th>
      <th scope="col">Active</th>
      <th scope="col">Notes</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($bracket['bracket_3']['data'])){ ?>
      <?php foreach($bracket['bracket_3']['data'] as $k => $v) { ?>
        <tr>
          <td><?php echo $v['account'];?></td>
          <td><?php echo $v['start'];?></td>
          <td><?php echo $v['end'];?></td>
          <td><?php echo $v['billing_cycle'];?></td>
          <td><?php echo $v['service'];?></td>
          <td><?php echo tns_money_format($v['money_value_fields']);?></td>
          <td><?php echo $v['active'];?></td>
          <td><?php echo wp_trim_words($v['note'],10);?></td>
        </tr>
      <?php } ?>
    <?php } ?>
  </tbody>
</table>
<?php } ?>
