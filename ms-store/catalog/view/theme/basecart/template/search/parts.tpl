<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php if  ($breadcrumb['href'] !== NULL) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php }
              elseif  ($breadcrumb['href'] == NULL) { ?>
        <li><?php echo $breadcrumb['text']; ?></li>
        <?php  } ?>
        <?php  } ?>


    </ul>
  <table class="table table-hover table-bordered table-striped" id="articleList">
    <tr class="row">
        <td class="col-md-1 h5"><b>Фирма</b></td>
        <td class="col-md-2 h5"><b>Код детали</b></td>
        <td class="col-md-7 h5"><b>Описание</b></td>
        <td class="col-md-2 h5"><b>Поиск</b></td>
    </tr>
    <tr class="row loader-placeholder text-center">
        <td colspan="4">
            <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
            <span>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</span>
            <span id="loader-placeholder-timer">0</span>
        </td>
    </tr>
    </table>
</div>
<script type="text/javascript">
    $('document').ready(function() {
        
            function placeholderTimerFiller() {
                currentTimerValue = $('#loader-placeholder-timer').text();
                newTimerValue = + currentTimerValue + 1;
                $('#loader-placeholder-timer').text(newTimerValue);
            }
            timerId = setInterval(placeholderTimerFiller, 1000);
            getPreResults();
            
            function getPreResults() {
                $.ajax({
                    type: "GET",
                    url: "<?=$url_search_parts_manager?>",
                    data: "article=<?=$article?>",
                    dataType:'JSON',
                    success: function(json) {
                        if (json.status === 1 || json.status === '1' || json.status === 0 || json.status === '0') {
                            setTimeout(getPreResults, 5000);
                        } else {
                            getResults();
                        }
                    }
                });
            }
            function getResults() {
                $.ajax({
                    type: "GET",
                    url: "<?=$url_collect?>",
                    data: "article=<?=$article?>",
                    dataType:'JSON',
                    beforeSend: function() {
                        placeholderTimerId = setInterval(placeholderTimerFiller, 1000);
                    },
                    success: function(json){

                        clearInterval(placeholderTimerId);

                        if (json.PARTS.data.length>0){
                            jQuery.each(json.PARTS.data, function(key, arPart){
                                bkey = arPart[0];
                                akey = arPart[1];
                                name = arPart[2];
                                bid  = arPart[3];
                                console.log(arPart);
                                appendLine = '<tr class="row" onclick="window.document.location=\'<?php echo $url_collect_prices; ?>&article=' + akey + '&brand=' + bid + '\';">';
                                appendLine += '<td class="col-md-1 h6">';
                                appendLine += '<a href="javascript:void(0)" title="">'+bkey+'</a></td>';
                                appendLine += '<td class="col-md-2 h6">';
                                appendLine += ''+akey+'</td>';
                                appendLine += '<td class="col-md-7 h6">';
                                appendLine += '<span class="name" title="'+name+'">'+name+'</span><br></td>';
                                appendLine += '<td class="col-md-2 h6" style="padding:0px;">';
                                appendLine += '<table class=""><tr><td>';
                                appendLine += '<a href="<?php echo $url_collect_prices; ?>&article=' + akey + '&brand=' + bid + '">Цены и аналоги</a>';
                                appendLine += '<td/></tr></table></td></tr>';
                                $('#articleList').append(appendLine);
                            });
                        } else {
                            appendLine = '<tr class="row text-center h6">';
                            appendLine += '<td colspan="4">';
                            appendLine += '<span>Запчасти не найдены</span>';
                            appendLine += '</td></tr>';
                            $('#articleList').append(appendLine);
                        }
                    }
                }).done(function() {
                    $('.loader-placeholder').hide();
                });
            }
        });
</script>
<?php echo $footer; ?>