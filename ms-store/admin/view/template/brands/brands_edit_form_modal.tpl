<script type="text/javascript">
    function test() {
        var bname = document.getElementById('bname');
        if (bname.disabled == true) {
            bname.disabled = false;
        } else {
            bname.disabled = true;
        }
    }
</script>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <p><?php echo $entry_b_name; ?></p>
          <select name="bname" id="bname" disabled="0" >
            <option disabled>бренд</option>
            <?php if ($AllBrands) { ?>
            <?php foreach ($AllBrands as $brand) { ?>
            <option name="b_name" value="<?php echo $brand['b__name']; ?>"><?php echo $brand['b__name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <p><input type="checkbox" name="agree" onclick="test();">Выбрать из существующих</p>
          <input type="text" name="b_name" value="<?php echo $b_name; ?>" placeholder="<?php echo $entry_b_name; ?>" id="input-firstname" class="form-control" />

          <button type="submit">Отправить форму</button>

    </div>
        </form>












