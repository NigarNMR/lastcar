<form>
    <p class="text-left">
    <?php echo $label ?>
    <?php if ($required) { ?>
      <span class="simplecheckout-required">*</span>
    <?php } ?>
    </p>
    <?php if ($type == 'select') { ?>
      <select class="form-control" name="<?php echo $name ?>" id="<?php echo $id ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
        <?php foreach ($values as $info) { ?>
          <option value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'selected="selected"' : '' ?>><?php echo $info['text'] ?></option>
        <?php } ?>
      </select>
    <?php } elseif ($type == 'radio') { ?>
      <?php foreach ($values as $info) { ?>
        <label>
            <input class="form-control" type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $info['text'] ?>
        </label>
      <?php } ?>
    <?php } elseif ($type == 'checkbox') { ?>
      <?php foreach ($values as $info) { ?>
        <input class="form-control" type="hidden" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" value="0">
        <label><input class="form-control" type="checkbox" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" id="<?php echo $id ?>" value="1" <?php echo !empty($value[$info['id']]) ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $info['text'] ?></label>
      <?php } ?>
    <?php } elseif ($type == 'textarea') { ?>
      <textarea name="<?php echo $name ?>" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $value ?></textarea>
    <?php } elseif ($type == 'captcha') { ?>
      <?php if ($site_key) { ?>
        <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
      <?php } else { ?>
        <input class="form-control" type="text" name="<?php echo $name ?>" id="<?php echo $id ?>" value="" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
        <div class="simple-captcha-container"><img src="index.php?<?php echo $additional_path ?>route=common/simple_connector/captcha&t=<?php echo time() ?>" alt="" id="captcha" /></div>
      <?php } ?>
    <?php } elseif ($type == 'file') { ?>
      <input class="form-control" type="button" value="<?php echo $button_upload; ?>" data-file="<?php echo $id ?>" class="button">
      <div id="text_<?php echo $id ?>" style="margin-top:3px;max-width:200px;"><?php echo $filename ?></div>
      <input class="form-control" type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
    <?php } else { ?>
      <input class="form-control" type="<?php echo ($type == 'date' || $type == 'time') ? 'text' : $type ?>" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
    <?php } ?>
    <?php if (!empty($rules)) { ?>
      <div class="simplecheckout-rule-group" data-for="<?php echo $id ?>">
        <?php foreach ($rules as $rule) { ?>
          <div <?php echo $rule['display'] && !$rule['passed'] ? '' : 'style="display:none;"' ?> data-for="<?php echo $id ?>" data-rule="<?php echo $rule['id'] ?>" class="simplecheckout-error-text simplecheckout-rule" <?php echo $rule['attrs'] ?>><?php echo $rule['text'] ?></div>
        <?php } ?>
      </div>
    <?php } ?>
    <?php if ($description) { ?>
      <div class="simplecheckout-tooltip" data-for="<?php echo $id ?>"><?php echo $description ?></div>
    <?php } ?>
  </td>
