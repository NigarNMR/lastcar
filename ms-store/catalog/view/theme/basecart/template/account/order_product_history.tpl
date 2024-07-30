<?php if ($product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
              <?php $options[$option['name']] = $option; ?>
    <?php } ?>
  <?php } ?>

<table class="table table-bordered">
    <thead>
      <tr>
        <th><?php echo $column_firm; ?></th>
        <th><?php echo $column_article; ?></th>
        <th><?php echo $column_description; ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $product['model']; ?></td>
        <td><?php echo $options['Артикул']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности  ?></td>
        <td><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br>
          <!--<a data-toggle="collapse" href="#collapse_m_<?php echo $product['order_product_id'] ?>"><?php echo $text_more; ?></a>
          <div id="collapse_m_<?php echo $product['order_product_id'] ?>" class="collapse">

            <?php if ($product['option']) { ?>
              <?php foreach ($product['option'] as $option) { ?>
                <br />
                <!--сторока со всеми опциями  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
              <?php } ?>
            <?php } ?>-->
          </div>
        </td>
      </tr>
      
      </tr>
    </tbody>
  </table> 

<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_date_added; ?></td>
      <td class="text-left"><?php echo $column_comment; ?></td>
      <td class="text-left"><?php echo $column_status; ?></td>
      <td class="text-left"><?php echo $column_notify; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td class="text-left"><?php echo $history['date_added']; ?></td>
      <td class="text-left"><?php echo $history['comment']; ?></td>
      <td class="text-left" style="background-color: <?php echo '#' . $history['bg_color'] ?>; color: <?php echo '#' .  $history['text_color']?>;"><?php echo $history['status']; ?></td>
      <td class="text-left"><?php echo $history['notify']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
