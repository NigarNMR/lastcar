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


    <table class="table table-bordered" id="articleBrandList">
        <tr class="row list-header">
            <td class="col-xs-12 col-md-7">
                <div class="col-xs-12 col-md-2 h5"><b>Фирма</b></div>
                <div class="col-xs-12 col-md-3 h5"><b>Код детали</b></div>
                <div class="col-xs-12 col-md-5 h5"><b>Описание</b></div>
                <div class="col-xs-12 col-md-2 h5"><b>Инфо</b></div>
            </td>
            <td class="col-md-5">
                <div class="col-xs-12 col-md-2 h5"><b>Склад</b></div>
                <div class="col-xs-12 col-md-2 h5"><b>Нал.</b></div>
                <div class="col-xs-12 col-md-2 h5"><b>Срок</b></div>
                <div class="col-xs-12 col-md-3 h5"><b>Цена</b></div>
                <div class="col-xs-12 col-md-3 h5"><b>Заказ</b></div>
            </td>
        </tr>
        <tr class="row loader-placeholder text-center">
            <td colspan="2">
                <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
                <span>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</span>
                <span id="loader-placeholder-timer">0</span>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function returnSearchResult(){
        
            function placeholderTimerFiller() {
                currentTimerValue = $('#loader-placeholder-timer').text();
                newTimerValue = + currentTimerValue + 1;
                $('#loader-placeholder-timer').text(newTimerValue);
            }
            timerId = setInterval(placeholderTimerFiller, 1000);
            getPreResults();
        
            /** НАЧАЛО ФУНКЦИИ СОЗДАНИЯ СПИСКОВ ГРУПП */
            
            function headerFiller(grDesc,grType) {
                
                appendLine = '';
                appendLine += '<tr class="row group-header alert alert-danger" data-toggle="collapse" data-target=".group-list-'+grType+'">';
                appendLine += '<td colspan="2" class="col-xs-12 col-md-12 h5 text-left">';
                appendLine += '<div><span class="glyphicon glyphicon-chevron-up toggle-icon-'+grType+'"></span> <b>'+grDesc+'</b></div>';
                appendLine += '</td></tr>';
                
                return appendLine;
                
            }
            
            /** КОНЕЦ ФУНКЦИИ СОЗДАНИЯ СПИСКОВ ГРУПП */
        
            /** НАЧАЛО ФУНКЦИИ СОЗДАНИЯ СТРОКИ ТАБЛИЦЫ */
        
            function tableFiller(arData, grType, rowNum) {
                //Переопределяем полученные данные
                brand = arData['0'];
                article = arData['1'];
                description = arData['2'];
                imgUrl = arData['3'];
                imgType = arData['4'];
                dopinfoId = '';
                dopinfoId = arData['5'];
                arPrices = arData['items'];
                pCnt = 0; pKey = '';
                pKey = brand+article;
                priceListLimit = 3;
                
                //Установка вывода изображений по деталям
                if (imgType === 1) {
                    zoom = imgUrl; zClass = 'cbx_imgs';
                    picText = ''; target = 'target="_blank"';
                } else {
                    zoom = 'https://www.google.com/search?q='+brand+article+'&tbm=isch'; 
                    zClass = ''; 
                    picText = 'Поиск изображения в google'; target = 'target="_blank"';
                }
                
                //Инициализация и заполнение табличной строки
                appendLine = '';
                appendLine += '<tr class="row group-list-'+grType+' collapse in"><td class="col-xs-12 col-md-7">';
                appendLine += '<div class="col-xs-12 col-md-2 h6"><a href="javascript:void(0)" title="Информация о бренде">';
                appendLine += ''+brand+'</a></div>';
                appendLine += '<div class="col-xs-12 col-md-3 h6">';
                appendLine += ''+article+'</div><div class="col-xs-12 col-md-5 h6">';
                appendLine += ''+description+'</div><div class="col-xs-12 col-md-2 h6">';
                appendLine += '<div class="row"><div class="col-xs-12 col-md-4 part-image-box">';
                appendLine += '<a href="'+zoom+'" class="image '+zClass+'" rel="img'+pKey+'" '+target+' title="'+brand+' '+article+'">';
                
                if (true/*imgType === 2*/) {
                    appendLine += '<div class="google-srch" title="'+picText+'"></div>';
                } else {
                    appendLine += '<div class="part-photo"></div>';
                }
                
                appendLine += '</a></div>';
                
                /*
                if (imgType === 1) {
                    appendLine += '<div class="col-xs-12 col-md-4 part-info-box">';
                    appendLine += '<a href="/<?=TDM_ROOT_DIR?>/props.php?of='+dopinfoId+'" class="dopinfo popup" target="_blank" title="Дополнительная информация"></a>';
                    appendLine += '</div><div class="col-xs-12 col-md-4 part-app-box">';
                    appendLine += '<a href="javascript:void(0)" OnClick="AppWin(\'<?=TDM_ROOT_DIR?>\','+dopinfoId;
                    appendLine += ',980)" class="carsapp" target="_blank" title="Применимость к моделям авто"></a>';
                    appendLine += '</div>';
                }
                */
                
                appendLine += '</div>';
                if (grType !== 'request') {
                    appendLine += '<div class="row"><div class="col-xs-12 col-md-12">';
                    /*appendLine += '<a href="/<?=TDM_ROOT_DIR?>/search/'+article+'/'+brand+'/" class="lookup_analogues">Найти аналоги</a>';*/
                    appendLine += '<a href="#"></a>';
                    appendLine += '</div></div>';
                }
                appendLine += '</div></td>';
                
                appendLine += '<td class="col-xs-12 col-md-5">';
                
                if (arPrices.length>0) {
                    jQuery.each(arPrices, function(priceNumber,priceArray) {
                        pCnt++;
                        stockName = priceArray['0'];
                        availCount = priceArray['1'];
                        dTime = priceArray['2'];
                        price = priceArray['3'];
                        productId = priceArray['4'];
                        if (pCnt>priceListLimit){
                            hClass='pr'+'-'+grType+'-'+rowNum; 
                            hStyle='style="display:none;"';
                        } else {
                            hClass = '';
                            hStyle = '';
                        }
                        
                        appendLine += '<div class="trow row '+hClass+'" '+hStyle+' >';
                        appendLine += '<div class="code_stock col-xs-12 col-md-2">'+stockName+'</div>';
                        appendLine += '<div class="avail col-xs-12 col-md-2" style="color:#dd4814;">';
                        
                        
                        if (availCount === undefined || availCount === null) {
                          availCount = 0;
                        }
                        else{
                          regexp_available = /(\d+)/; // шаблон регулярного выражения для определения цены товара
                          availCounter = regexp_available.exec(availCount);                          
                          if (availCounter === undefined || availCounter === null) {
                            availCount = 5;
                          }
                          else{
                            availCount = availCounter[0];
                          }                          
                        }
                        
                        if (availCount>99) {
                            appendLine += '99';
                        }else 
                          if(availCount == 0){
                          appendLine += 'зак.';
                        } 
                        else {
                            appendLine += availCount;
                        }
                        appendLine += '</div>';
                        appendLine += '<div class="day ttip col-xs-12 col-md-2">'+dTime+'</div>';
                        if (price!==null && price!=='null')
                        {
                          appendLine += '<div class="cost ttip col-xs-12 col-md-3" style="color:#379C08;">'+price+'</div>';
                        }
                        appendLine += '<div class="tocart col-xs-12 col-md-3" id="product-'+productId+'">';
                        appendLine += '<div class="row"><div class="col-xs-12 col-md-8">';
                        //console.log(phid);
                        appendLine += '<input type="number" class="count_inp" name="qty"';
                        if (availCount > 0) {
                            appendLine += 'value="1" min="1" max="'+availCount+'"';
                        } else {
                            appendLine += 'value="1" min="1" max="999"';
                        }
                        appendLine += ' style="width: 100%;"/></div><div class="col-xs-12 col-md-4 tocartdiv">';
                        appendLine += '<button class="btn btn-xs btn-default tdcartadd" style="margin-left: -20px;" OnClick="cart.add_from_tdm(\''+productId+'\')"><i class="fa fa-cart-plus" style="margin: 0px 5px 0px 5px;"></i></button>';
                        appendLine += '</div></div></div></div>';
                    });
                }
                
                if (pCnt>priceListLimit) {
                    appendLine += '<a href="javascript:void(0)" class="sbut sPriceButton sbt'+'-'+grType+'-'+rowNum+'">&#9660; Развернуть цены ('+(pCnt-priceListLimit)+')</a>';
                    appendLine += '<a href="javascript:void(0)" style="display:none;" class="sbut hPriceButton hbt'+'-'+grType+'-'+rowNum+'">&#9650; Скрыть цены ('+(pCnt-priceListLimit)+')</a>';
                }
                
                appendLine += '</td>';
                
                return (appendLine);
            }
            
            /** КОНЕЦ ФУНКЦИИ СОЗДАНИЯ СТРОКИ ТАБЛИЦЫ */
            
            function getPreResults() {
                $.ajax({
                    type: "GET",
                    url: "<?=$url_search_prices_manager?>",
                    data: "article=<?=$article?>&brand=<?=$brand?>",
                    dataType:'JSON',
                    success: function(json) {

                        if (json.status === 1 || json.status === '1' || json.status === 0 || json.status === '0') {

                            setTimeout(getPreResults, 5000);


                        }
                        else if (json.status === 2 || json.status === '2' ) {
                            setTimeout(getPreResults, 5000);
                            GetAnalogJson();
                            console.log("Status 2");
                        }

                        else if (json.status === 3 || json.status === '3') {
                            setTimeout(getPreResults, 5000);
                            GetOriginalPriceJson();
                            console.log("Status 3");
                        }

                       else  if (json.status === 4 || json.status === '4') {
                            setTimeout(getPreResults, 5000);
                            GetAnalogPriceJson();
                            console.log("Status 4");
                        }

                        else {
                            GetFinalPriceJson();
                            console.log("Status 5");
                        }
                    }
                });
            }

        function GetAnalogJson() {
            $.ajax({
                type: "GET",
                url: "<?=$url_analog_json?>",
                //data: "article=<?=$article?>&brand=<?=$brand?>",
                dataType: 'JSON',
                success: function (json){

                    clearInterval(timerId);
                    analogData= json.analog_data;
                    console.log(analogData);
                }

            });
        }

        function  GetOriginalPriceJson() {
            $.ajax({
                type: "GET",
                url: "<?=$url_original__price_json?>",
                //data: "article=<?=$article?>&brand=<?=$brand?>",
                dataType: 'JSON',
                success: function (json){

                    clearInterval(timerId);
                    originalPriceData= json.original_price_data;
                    console.log(originalPriceData);
                }

            });
        }

        function  GetAnalogPriceJson() {
            $.ajax({
                type: "GET",
                url: "<?=$url_analog_price_json?>",
                //data: "article=<?=$article?>&brand=<?=$brand?>",
                dataType: 'JSON',
                success: function (json){

                    clearInterval(timerId);
                   analogPriceData= json.analog_price_data;
                    console.log(analogPriceData);
                }

            });
        }

        function  GetFinalPriceJson() {
            $.ajax({
                type: "GET",
                url: "<?=$url_final_price_json?>",
                //data: "article=<?=$article?>&brand=<?=$brand?>",
                dataType: 'JSON',
                success: function (json){

                    clearInterval(timerId);
                    finalPriceData= json.final_price_data;
                    console.log(finalPriceData);
                }

            });
        }


            function getResults() {
                $.ajax({
                    type: "GET",
                    url: "<?=$url_collect?>",
                    data: "article=<?=$article?>&brand=<?=$brand?>",
                    dataType:'JSON',
                    beforeSend: function() {
                        //placeholderTimerId = setInterval(placeholderTimerFiller, 1000);
                    },
                    success: function(json){

                        //clearInterval(placeholderTimerId);
                        clearInterval(timerId);

                        recommendData = json.PARTS.parts_recomend.data;
                        requestData = json.PARTS.parts_request.data;
                        replacementData = json.PARTS.parts_analog_original.data;
                        analogsData = json.PARTS.parts_analog.data;

                        if (recommendData.length>0){
                            $('#articleBrandList').append(headerFiller('Рекомендуемые запчасти','recommend'));
                            rowCount = 0;
                            jQuery.each(recommendData, function(key,array){
                                rowCount++;
                                $('#articleBrandList').append(tableFiller(array,'recommend', rowCount));
                            });
                        }

                        if (requestData.length>0){
                            $('#articleBrandList').append(headerFiller('Запрашиваемый артикул','request'));
                            rowCount = 0;
                            jQuery.each(requestData, function(key,array){
                                rowCount++;
                                $('#articleBrandList').append(tableFiller(array,'request', rowCount));
                            });
                        }

                        if (replacementData.length>0){
                            $('#articleBrandList').append(headerFiller('Оригинальные заменители','replacement'));
                            rowCount = 0;
                            jQuery.each(replacementData, function(key,array){
                                rowCount++;
                                $('#articleBrandList').append(tableFiller(array,'replacement', rowCount));
                            });
                        }

                        if (analogsData.length>0){
                            $('#articleBrandList').append(headerFiller('Аналоги','analogs'));
                            rowCount = 0;
                            jQuery.each(analogsData, function(key,array){
                                rowCount++;
                                $('#articleBrandList').append(tableFiller(array,'analogs', rowCount));
                            });
                        }

                        $('#articleBrandList').delegate('.sPriceButton', 'click', function() {
                            pKeyText = $(this).attr('class');
                            regexpPKey = /sbt(.*)/;
                            regexpPKeyValue = regexpPKey.exec(pKeyText);

                            pKey = regexpPKeyValue[1];

                            $('.pr'+pKey).show('fast'); 
                            $('.sbt'+pKey).hide();
                            $('.hbt'+pKey).show();
                        });
                        $('#articleBrandList').delegate('.hPriceButton', 'click', function() {
                            pKeyText = $(this).attr('class');
                            regexpPKey = /hbt(.*)/;
                            regexpPKeyValue = regexpPKey.exec(pKeyText);

                            pKey = regexpPKeyValue[1];

                            $('.pr'+pKey).hide('fast'); 
                            $('.sbt'+pKey).show();
                            $('.hbt'+pKey).hide();
                        });

                        //$(".popup").colorbox({rel:false, current:'', preloading:false, arrowKey:false, scrolling:false, overlayClose:false});
                        //$(".ttip").tooltip({ position:{my:"left+25 top+20"}, track:true, content:function(){return $(this).prop('title');}});
                        //$(".cbx_imgs").colorbox({ current:'', innerWidth:900, innerHeight:600, onComplete:function(){$('.cboxPhoto').unbind().click($.colorbox.next);} });
                        //$(".cbx_chars").colorbox({rel:false, current:'', overlayClose:true, arrowKey:false, opacity:0.6});
                    }
                }).done(function() {
                    $('.loader-placeholder').hide();
                });
            }
        });
</script>
<?php echo $footer; ?>