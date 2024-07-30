<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <h1><?php echo $heading_title; ?></h1>
      <?php //var_dump($contact_number); ?>
      <div class="col-xs-2 col-md-2">
        <label class="control-label" for="form-contact-number">Контактные номера<!--Phone numbers--></label>
        <textarea rows="7" style="resize:none" class="form-control" id="form-contact-number" readonly><?php echo implode("\r\n", $contact_number); ?></textarea>
      </div>
      <div class="col-xs-2 col-md-2">
        <label class="control-label" for="form-contact-email">E-mail</label>
        <textarea rows="7" style="resize:none" class="form-control" id="form-contact-email" readonly><?php echo $contact_email; ?></textarea>
      </div>
      <div class="col-xs-2 col-md-2">
        <label class="control-label" for="form-contact-address">Адрес</label>
        <textarea rows="7" style="resize:none" class="form-control" id="form-contact-address" readonly><?php echo $contact_address; ?></textarea>
      </div>
      <div class="col-xs-2 col-md-2">
        <label class="control-label" for="form-timetable-workdays">Рабочие дни</label>
        <textarea rows="7" style="resize:none" class="form-control" id="form-timetable-workdays" readonly><?php echo implode("\r\n",$timetable_days); ?></textarea>
      </div>
      <div class="col-md-4">
        <div class="col-xs-12 col-md-12">
          <label class="control-label" for="form-timetable-weekdays">Время работы (будни)</label>
          <textarea rows="1" style="resize:none" class="form-control" id="form-timetable-weekdays" readonly><?php echo $timetable_weekdays; ?></textarea>
        </div>
        <div class="col-xs-12 col-md-12">
          <label class="control-label" for="form-timetable-weekends">Время работы (выходные)</label>
          <textarea rows="1" style="resize:none" class="form-control" id="form-timetable-weekends" readonly><?php echo $timetable_weekends; ?></textarea>
        </div>
      </div>
    </div>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>