<?php
    global $SClient;
    if (empty($SClient)) {
        $SClient = new SoapClient("http://ws.emex.ru/EmExService.asmx?wsdl",array('trace'=>0, 'exceptions'=>0, 'encoding'=>'UTF-8'));
    }
    //$arPart['BKEY'] = $arPart['BRAND'];
  /*print '<pre>';
    var_dump($SClient);
    print '</pre>';*/
	//foreach($arWsParts as $arPart){
		//$ParCnt++;
    $params=array();
    $params["login"]=$arWS['LOGIN'];
    $params["password"]=$arWS['PASSW'];
    if (isset($arPart['BRAND'])) {
        $params["makeLogo"]=$arPart['BRAND'];
    }
    else {
        $params["makeLogo"]="";
    }
    $params["detailNum"]=$arPart['ARTICLE'];
    //if($exclAnalogs) {
    //    $params["substLevel"]="OriginalOnly";
    //} else {
        $params["substLevel"]="All";  
    //}
    if($exclAnalogs)
    {
      $params["substLevel"]="OriginalOnly";
    }
    $params["substFilter"]="FilterOriginalAndAnalogs"; 
    $params["deliveryRegionType"]="PRI"; 
    //$params["minDeliveryPercent"]=""; 
    //$params["maxADDays"]=""; 
    //$params["minQuantity"]=""; 
    //$params["maxResultPrice"]=""; 
    //$params["maxOneDetailOffersCount"]=""; 
    //$params["detailNumsToLoad"]=""; 
    
    /*print '<pre>';
    print_r($params);
    print '</pre>';*/
    //exit;
    $obCRes = $SClient->FindDetailAdv4($params);
    /*print '<pre>';
    var_dump($obCRes);
    print '</pre>';*/
    
    //Если детали / прайсы по полученным данным найдены -> продолжить выполнение
    if(isset($obCRes->FindDetailAdv4Result->Details->SoapDetailItem)){
        if (is_array($obCRes->FindDetailAdv4Result->Details->SoapDetailItem)) {
            foreach($obCRes->FindDetailAdv4Result->Details->SoapDetailItem as $obRes)
            {	
                /**print '<pre>';
                print_r($obRes);
                print '</pre>';*/

                //$arPrice = Array(); 
                $arPrice = TDMPriceArray($arPart); 

                $detGroup = (string)$obRes->PriceGroup;

                $arPrice['ARTICLE'] = (string)$obRes->DetailNum;    
                $arPrice['ALT_NAME'] = (string)$obRes->DetailNameRus;
                $arPrice['AVAILABLE'] = (string)$obRes->Quantity;
                $arPrice['BRAND'] = (string)$obRes->MakeName;
                $arPrice['CURRENCY'] = $arWS['CURRENCY'];      
                $arPrice['DAY'] = intval($obRes->ADDays);
                $arPrice['PRICE'] = (string)($obRes->ResultPrice);  
                $arPrice["STOCK"] = trim((string)$obRes->PriceCountry);

                $arPrice["LINK_TO_BKEY"] = $arPart['BKeyTmp'];
                $arPrice["LINK_TO_AKEY"] = $arPart['AKEY'];

                $arOps = Array();
                //Партия (при заказе количество деталей должно быть кратно этой величине)
                $MINIMUM = (string)$obRes->LotQuantity;
                                if($MINIMUM>1){$arOps['MINIMUM']=$MINIMUM;}
                //Вероятность поставки товара поставщика
                $PERCENTGIVE = (string)$obRes->DDPercent; 
                                if($PERCENTGIVE>0){$arOps['PERCENTGIVE']=$PERCENTGIVE;}

                $arPrice['PRICE_ORIG'] = (string)($obRes->ResultPrice);
                //$arPrice["OPTIONS"] = TDMOptionsImplode($arOps,$arPrice);
                $arPrice["SUPPLIER_OPTIONS"] = json_encode(
                        array(
                            //"GroupId" => (string)$obRes->GroupId,
                            //"PriceGroup" => (string)$obRes->PriceGroup,
                            "MakeLogo" => (string)$obRes->MakeLogo,
                            "MakeName" => (string)$obRes->MakeName,
                            "DetailNum" => (string)$obRes->DetailNum,
                            //"NewDetailNum" => (string)$obRes->NewDetailNum,
                            //"DetailNameRus" => (string)$obRes->DetailNameRus,
                            "PriceLogo" => (string)$obRes->PriceLogo,
                            "DestinationLogo" => (string)$obRes->DestinationLogo,
                            "PriceCountry" => (string)$obRes->PriceCountry,
                        )
                    );
                if($detGroup!=="PartOfDetail" AND $detGroup!=="DetailParts"){
                    $arPrices[] = $arPrice; //Add record
                }
                /*print '<pre>';
                print_r($arPrice);
                print '</pre>';*/
                //$arPrices[] = $arAPrice;
            }
        }
        else {
                $obRes = $obCRes->FindDetailAdv4Result->Details->SoapDetailItem;
                $arPrice = TDMPriceArray($arPart); 

                $detGroup = (string)$obRes->PriceGroup;

                $arPrice['ARTICLE'] = (string)$obRes->DetailNum;    
                $arPrice['ALT_NAME'] = (string)$obRes->DetailNameRus;
                $arPrice['AVAILABLE'] = (string)$obRes->Quantity;
                $arPrice['BRAND'] = (string)$obRes->MakeName;
                $arPrice['CURRENCY'] = $arWS['CURRENCY'];      
                $arPrice['DAY'] = intval($obRes->ADDays);
                $arPrice['PRICE'] = (string)$obRes->ResultPrice;  
                $arPrice["STOCK"] = trim((string)$obRes->PriceCountry);

                $arPrice["LINK_TO_BKEY"] = $arPart['BKeyTmp'];
                $arPrice["LINK_TO_AKEY"] = $arPart['AKEY'];

                $arOps = Array();
                //Партия (при заказе количество деталей должно быть кратно этой величине)
                $MINIMUM = (string)$obRes->LotQuantity;
                                if($MINIMUM>1){$arOps['MINIMUM']=$MINIMUM;}
                //Вероятность поставки товара поставщика
                $PERCENTGIVE = (string)$obRes->DDPercent; 
                                if($PERCENTGIVE>0){$arOps['PERCENTGIVE']=$PERCENTGIVE;}
                
                $arPrice['PRICE_ORIG'] = (string)$obRes->ResultPrice;
                //$arPrice["OPTIONS"] = TDMOptionsImplode($arOps,$arPrice);
                $arPrice["SUPPLIER_OPTIONS"] = json_encode(
                        array(
                            //"GroupId" => (string)$obRes->GroupId,
                            //"PriceGroup" => (string)$obRes->PriceGroup,
                            "MakeLogo" => (string)$obRes->MakeLogo,
                            "MakeName" => (string)$obRes->MakeName,
                            "DetailNum" => (string)$obRes->DetailNum,
                            //"NewDetailNum" => (string)$obRes->NewDetailNum,
                            //"DetailNameRus" => (string)$obRes->DetailNameRus,
                            "PriceLogo" => (string)$obRes->PriceLogo,
                            "DestinationLogo" => (string)$obRes->DestinationLogo,
                            "PriceCountry" => (string)$obRes->PriceCountry,
                        )
                    );
                if($detGroup!=="PartOfDetail" AND $detGroup!=="DetailParts"){
                    $arPrices[] = $arPrice; //Add record
                }
        }
    }
		//$obCRes = $SClient->Code_Search(Array("Search_Code"=>$arPart['ARTICLE'], "ClientID"=>$arWS['LOGIN'], "Password"=>$arWS['PASSW'])); //
		//echo $arPart['ARTICLE'].'<pre>'; print_r($obCRes); echo '</pre>'; die();  
		//if($obCRes->Code_SearchResult->Code_Search!=''){$ResCnt++;}
    /*
		if(count($obCRes->Code_SearchResult->List->Code_List_Row)>0){
			foreach($obCRes->Code_SearchResult->List->Code_List_Row as $obRes){
				$PriCnt++;
				$MikadoBrand = (string)$obRes->ProducerBrand;
				if(TDMSingleKey($MikadoBrand ,true)==$arPart['BKEY']){//Only searched BRAND
					//Make valid Price array
					$arPrice = TDMPriceArray(); 
					$arPrice["LINK_TO_BKEY"] = $arPart['BKEY'];		//If links (cross) number returned
					$arPrice["LINK_TO_AKEY"] = $arPart['AKEY'];		//If links (cross) number returned
					
					$arPrice["ARTICLE"] = (string)$obRes->ProducerCode;
					$arPrice["ALT_NAME"] = (string)$obRes->Name;
					$arPrice["BRAND"] = $MikadoBrand;
					$arPrice["PRICE"] = (string)$obRes->PriceRUR;
					$arPrice["CURRENCY"] = "RUB";
					$arPrice["DAY"] = (string)$obRes->Srock;
					$arPrice["AVAILABLE"] = (string)$obRes->OnStock;
					$arPrice["STOCK"] = (string)$obRes->Supplier;
					$arPrice["OPTIONS"] = '';
					//Price options
					$arOps = Array();
					$arPrice["OPTIONS"] = TDMOptionsImplode($arOps,$arPrice);
					$arPrices[] = $arPrice; //Add record
				}
			}
		}*/
	//}
	
//}

?>