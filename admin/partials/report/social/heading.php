<table class="table table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Bracket 1 (3-5 Months) - 1%</th>
      <th scope="col">Bracket 2 (6-8 Months) - 2%</th>
      <th scope="col">Bracket 3 (9-11 Months) - 3%</th>
      <th scope="col">Bracket 4 (12+ Months) - 4%</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <p>Total Value : <?php
          if(isset($bracket['bracket_1']['total_value'])){
            echo tns_money_format($bracket['bracket_1']['total_value']);
          }
        ?></p>
        <p>Bonus Value : <?php
          if(isset($bracket['bracket_1']['total_bonus_value'])){
            echo tns_money_format($bracket['bracket_1']['total_bonus_value']);
          }
        ?></p>
      </td>
      <td>
        <p>Total Value : <?php
          if(isset($bracket['bracket_2']['total_value'])){
            echo tns_money_format($bracket['bracket_2']['total_value']);
          }
        ?></p>
        <p>Bonus Value : <?php
          if(isset($bracket['bracket_2']['total_bonus_value'])){
            echo tns_money_format($bracket['bracket_2']['total_bonus_value']);
          }
        ?></p>
      </td>
      <td>
        <p>Total Value : <?php
          if(isset($bracket['bracket_3']['total_value'])){
            echo tns_money_format($bracket['bracket_3']['total_value']);
          }
        ?></p>
        <p>Bonus Value : <?php
          if(isset($bracket['bracket_3']['total_bonus_value'])){
            echo tns_money_format($bracket['bracket_3']['total_bonus_value']);
          }
        ?></p>
      </td>
      <td>
        <p>Total Value : <?php
          if(isset($bracket['bracket_4']['total_value'])){
            echo tns_money_format($bracket['bracket_4']['total_value']);
          }
        ?></p>
        <p>Bonus Value : <?php
          if(isset($bracket['bracket_4']['total_bonus_value'])){
            echo tns_money_format($bracket['bracket_4']['total_bonus_value']);
          }
        ?></p>
      </td>
    </tr>

  </tbody>
</table>
