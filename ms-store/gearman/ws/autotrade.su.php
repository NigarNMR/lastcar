<?php

$salt = '1>6)/MI~{J';

$apiPath = 'https://api2.autotrade.su/?json';

$hash = MD5($arWS['LOGIN'] . MD5($arWS['PASSW']) . $salt);

$curlHandler = curl_init();

$getCrosses = 1;
if($exclAnalogs)
{
  $getCrosses = 0;
}

$data = array(
  "auth_key" => $hash,
  "method" => "getItemsByQuery",
  "params" => array(
    "q" => $arPart['ARTICLE'],
    "strict" => 0,
    "page" => 1,
    "limit" => 500,
    "cross" => $getCrosses,
    "replace" => 1,
    "order_by" => "brand_name",
    "with_stocks_and_prices" => 1,
    "with_delivery" => 1,
  )
);

$request = 'data=' . json_encode($data);

echo($request . '<hr/>');

curl_setopt($curlHandler, CURLOPT_URL, $apiPath);  
curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $request);
curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

$sOutput = curl_exec($curlHandler);

curl_close($curlHandler);

$arResponse = json_decode($sOutput, true);

if (is_array($arResponse) && !empty($arResponse))
{
  foreach ($arResponse['items'] as $itemNum => $itemData)
  {
    if (is_array($itemData) && !empty($itemData))
    {
      foreach ($itemData['stocks'] as $stockData)
      {
        $arPrice = TDMPriceArray($arPart); 

        $art = trim(mb_strtoupper($itemData['article']));
        $art = str_replace("\xc3\x8b", "E", $art);
        $art = str_replace("\xc3\x96", "O", $art);
        $art = str_replace("\xc3\x92", "O", $art);
        $art = str_replace("\xc3\x84", "A", $art);
        $art = str_replace("\xc3\x9c", "U", $art);
        $art = str_replace("O'", "O", $art);
        $art = str_replace("\xe2\x84\x96", "", $art);
        $art = preg_replace("/[^A-Z\xd0\x90-\xd0\xaf0-9a-z\xd0\xb0-\xd1\x8f]/", "", $art);
        $art = trim($art);

        $arPrice['ARTICLE'] = $art;
        $arPrice['ALT_NAME'] = $itemData['name'];
        $arPrice['AVAILABLE'] = intval($stockData['quantity_unpacked']) + intval($stockData['quantity_packed']);
        $arPrice['BRAND'] = $itemData['brand_name'];
        $arPrice['CURRENCY'] = $arWS['CURRENCY'];      
        $arPrice['DAY'] = intval($stockData['delivery_period']);
        $arPrice['PRICE'] = intval($itemData['price']);  
        $arPrice['STOCK'] = trim($stockData['name']);

        $arPrice['LINK_TO_BKEY'] = $arPart['BKeyTmp'];
        $arPrice['LINK_TO_AKEY'] = $arPart['AKEY'];

        $arPrice['PRICE_ORIG'] = intval($itemData['price']);
        $arPrice['SUPPLIER_OPTIONS'] = json_encode(
          array(
            'brand_name' => $itemData['brand_name'],
            'brand_id' => $itemData['brand_id'],
            'id' => $itemData['id'],
            'inside_id_in' => $itemData['inside_id_in'],
            'stock_id' => $stockData['stock_id'],
            'stock_name' => $stockData['stock_name'],
            'stock_name_eng' => $stockData['stock_name_eng'],
            'stock_legend' => $stockData['stock_legend'],
          )
        );

        //Проверяем прайс на то, чтобы и цена, и время доставки были ненулевыми
        if ($arPrice['AVAILABLE'] && $arPrice['PRICE'])
        {
          $arPrices[] = $arPrice;
        }
      }
    }
  }
}
?>