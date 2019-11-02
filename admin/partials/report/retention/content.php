<p class="h4">Total Clients : <?php echo isset($retention['total_clients']) ? $retention['total_clients'] : '';?></p>
<p class="h4">Combined Value : <?php echo isset($retention['combine_value']) ? tns_money_format($retention['combine_value']) : '';?></p>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Length Of Time</th>
      <th scope="col">Completion #</th>
      <th scope="col">Segment %</th>
      <th scope="col">Combined %</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($retention['data']) && count($retention['data']) > 0 ){ ?>
      <?php foreach($retention['data'] as $k => $v) {?>
        <tr>
          <th scope="row">
            <?php
              $prefix_length = '';
              $plural = 'Months';
              $singular = 'Month';

              echo $v['length_of_time'];

              if( $v['length_of_time'] > 1){
                echo ' '.$plural;
              }else{
                echo ' '.$singular;
              }
            ?>
          </th>
          <td><?php echo $v['completion']; ?></td>
          <td><?php echo $v['segment']; ?></td>
          <td><?php echo $v['combine']; ?></td>
        </tr>
      <?php }//foreach ?>
    <?php }//if ?>

  </tbody>
</table>
