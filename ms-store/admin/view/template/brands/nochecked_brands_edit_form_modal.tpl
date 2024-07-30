<script type="text/javascript">
    function test() {
        var b_name = document.getElementById('b_name');
        var gob_id = document.getElementById('gob_id');
        if (b_name.disabled == true) {
            b_name.disabled = false;
            gob_id.disabled = true;
        } else {
            b_name.disabled = true;
            gob_id.disabled = false;
        }
    }
</script>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <p><?php echo $entry_brand_group; ?></p>
          <select name="gob_id" id="gob_id" >
            <option disabled> Группа брендов</option>
            <?php if ($AllBrands) { ?>
            <?php foreach ($AllBrands as $brand) { ?>
            <option name="b_name" value="<?php echo $brand['b__id']; ?>"><?php echo $brand['b__name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </br>
            </br>
            <p><input type="checkbox" name="agree" onclick="test()" > Создать группу по бренду</p>
            <button type="submit" >Добавить бренд</button>

    </div>
        </form>












