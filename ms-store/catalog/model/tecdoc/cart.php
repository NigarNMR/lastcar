<?php

class ModelTecdocCart extends Model {
  /*
   * @note Получить описание товара из TDM_PRICES
   * в нужной структуре
   */

  public function getProduct($productId, $uGroupId,$mark_up) {
    // запрос
    // получение и составление массива
    // возврат массива

    /* SELECT BKEY, AKEY, ARTICLE, ALT_NAME,"
      . " BRAND, PRICE, TYPE, CURRENCY, DAY, AVAILABLE, SUPPLIER,"
      . " STOCK, OPTIONS, CODE, DATE, PHID FROM TDM_PRICES"
      . " WHERE */
    //$order_query = $this->db_tdm->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");
    $product_query = $this->db_tdm->query("SELECT PRODUCT_ID, BKEY, AKEY, ARTICLE, ALT_NAME,"
        . " BRAND, PRICE_ORIG, PRICE, TYPE, CURRENCY, DAY, AVAILABLE, SUPPLIER,"
        . " STOCK, OPTIONS, CODE, DATE, PHID FROM TDM_PRICES"
        . " WHERE PRODUCT_ID = '" . $productId . "'");
   
    if ($product_query->num_rows) {
      $product = $product_query->row;
      //$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");
      /*echo '<pre>';
      var_dump($product);
      echo '</pre>';*/
       
      $arOCBasket = array();
      $cpid = substr(filter_var($product["PHID"], FILTER_SANITIZE_NUMBER_INT), 0, 9);
      $available = 999;
      if(is_numeric($product['AVAILABLE']) && $product['AVAILABLE'] > 0){
        $available = $product['AVAILABLE'];
      }
      $arOCBasket['tecdoc'] = "Y";
      $arOCBasket['P_ID'] = $productId;
      $arOCBasket['product_id'] = $cpid;
      $arOCBasket['key'] = $cpid;
      //$arOCBasket['price'] = TDMConvertPriceNew($arCartPrice['CURRENCY'], $OC_CURRENCY, $arCartPrice['PRICE']);
      $arOCBasket['price'] = $product['PRICE']*(1+ $mark_up/100);
      //$arOCBasket['quantity'] = $QUANTITY;
      $arOCBasket['quantity'] = 1; // по умолчанию
      $arOCBasket['stock'] = $available;
      $arOCBasket['name'] = $product['ALT_NAME'];
      $arOCBasket['image'] = ''; // тут
      $arOCBasket['brand'] = $product['BRAND'];
      $arOCBasket['product_url'] = '/autoparts/search/'.$product['ARTICLE'].'/'.$product['BRAND']; // тут
      $arOCBasket['day'] = $product['DAY'];
      $arOCBasket['article'] = $product['ARTICLE'];
      //Minimum
      $arOCBasket['minimum'] = 1;
      /*if ($arCartPrice['OPTIONS']['MINIMUM'] > 0) {
        $arOCBasket['minimum'] = $arCartPrice['OPTIONS']['MINIMUM'];
      }*/
      //Weight
      $arOCBasket['weight'] = '';
      $arOCBasket['weight_prefix'] = '';
      $arOCBasket['weight_class_id'] = 2; //1-Kg. 2-Gr
      /*if ($arCartPrice['OPTIONS']['WEIGHT'] > 0) {
        $arOCBasket['weight'] = $arCartPrice['OPTIONS']['WEIGHT'];
      }*/
      //Points
      $arOCBasket['points'] = '';
      $arOCBasket['points_prefix'] = 'Шт.';
      /*if ($arCartPrice['OPTIONS']['SET'] > 0) {
        $arOCBasket['points'] = $arCartPrice['OPTIONS']['SET'];
      }*/
      //Options
      $arOCBasket['option'] = array();
      $arOCBasket['option'][] = Array('name' => 'Артикул', 'option_value' => $product['ARTICLE'], 'value' => $product['ARTICLE'], 'type' => 'text');
      $arOCBasket['option'][] = array('name' => 'Price_Original', 'option_value' => $product['PRICE_ORIG'], 'value' => $product['PRICE_ORIG'], 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Поставщик', 'option_value' => $product['SUPPLIER'], 'value' => $product['SUPPLIER'], 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Срок поставки (дней)', 'option_value' => $product['DAY'], 'value' => $product['DAY'], 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Наличие', 'option_value' => $product['AVAILABLE'], 'value' => $product['AVAILABLE'], 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Price', 'option_value' => $arOCBasket['price'] . ' ' . $product['CURRENCY'], 'value' => $arOCBasket['price'] . ' ' . $product['CURRENCY'], 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Date', 'option_value' => '', 'value' => '', 'type' => 'text');
      $arOCBasket['option'][] = Array('name' => 'Code', 'option_value' => $product['CODE'], 'value' => $product['CODE'], 'type' => 'text');
      /*if (is_array($arCartPrice['OPTIONS']) AND count($arCartPrice['OPTIONS']) > 0) {
        foreach ($arCartPrice['OPTIONS'] as $OpCode => $OpValue) {
          $OpName = $arCartPrice['OPTIONS_NAMES'][$OpCode];
          if ($OpName == '') {
            $OpName = $OpCode;
          }
          $arOCBasket['option'][] = Array('name' => $OpName, 'option_value' => $OpValue, 'value' => $OpValue, 'type' => 'text');
        }
      }*/
      /*echo '<pre>';
      var_dump($arOCBasket);
      echo '</pre>';*/
      return $arOCBasket;
    }
    else {
      return false;
    }
  }

}
