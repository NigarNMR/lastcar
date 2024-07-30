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
      <hr/>
      <div>
        <label class="control-label" for="form-org-name">Имя компании</label>
        <textarea rows="1" style="resize:none" class="form-control" id="form-org-name" readonly><?php echo $org_name; ?></textarea>
      </div>
      <br/>
      <div>
        <label class="control-label" for="form-taxpayer-id">ИНН</label>
        <textarea rows="1" style="resize:none" class="form-control" id="form-taxpayer-id" readonly><?php echo $taxpayer_id; ?></textarea>
      </div>
      <br/>
      <div>
        <label class="control-label" for="form-paccount-id">Расчетный счет</label>
        <textarea rows="1" style="resize:none" class="form-control" id="form-paccount-id" readonly><?php echo $paccount_id; ?></textarea>
      </div>
      <br/>
      <div>
        <label class="control-label" for="form-bank-id">БИК (Банковский идентификационный код)</label>
        <textarea rows="1" style="resize:none" class="form-control" id="form-contact-number" readonly><?php echo $bank_id; ?></textarea>
      </div>
      <br/>
      <div>
        <label class="control-label" for="form-bank-name">Наименование банка</label>
        <textarea rows="1" style="resize:none" class="form-control" id="form-bank-name" readonly><?php echo $bank_name; ?></textarea>
      </div>
    </div>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>