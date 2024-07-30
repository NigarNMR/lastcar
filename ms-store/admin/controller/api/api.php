<?php
require "httpful.phar";
class ControllerApiApi extends Controller
{


    public function index()
    {
        // $this->get();
    }


    public function getCrosses($data)
    {

        $url = GET_CROSSES."?sort=$data";
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =$response->body;
        //$a=urldecode($a);
        foreach ($a as $value) {

                $brand_name1 = $value->brand_name1;
                $akey1 = $value->akey1;
                $brand_name2 =$value->brand_name2;
                $akey2 = $value->akey2;
                $side = $value->side;
                $code = $value->code;
                $result[] = ['brand_name1' => $brand_name1, 'akey1' => $akey1, 'brand_name2' => $brand_name2, 'akey2' => $akey2, 'side' => $side, 'code' => $code];

            }

            /*
            elseif (isset($value->gob_name) && isset($value->gob_id)){
                $gob_name = $value->gob_name;
                $gob_id = $value->gob_id;
                $result[] = ['gob_name' => $gob_name, 'gob_id' => $gob_id];*/

        $result=json_encode($result);

        return ($result);

    }

    public function searchBrand($data)
    {
        $arrays=array();
        $url = GET_BRAND."?sort=$data";
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =$response->body;
        foreach ($a as $value) {

            $b__name = $value->b__name;
            $b__parent_id = $value->b__parent_id;
            $b__status =$value->b__status;
            $gob__name=$value->gob__name;
            $array[] = ['b__name' => $b__name, 'b__parent_id' =>  $b__parent_id, 'b__status' => $b__status, 'gob__name'=>$gob__name];

        }
      //  var_dump($array);

        foreach ($array as $ar)
        {
            if($ar['b__status']==1) {
                echo("<b>Название: </b>" . $ar['b__name'] . "  - " . "<b>Группа: </b> " . $ar['gob__name'] . "<br/>");
            }
            else{
                echo("<b>Название: </b>" . $ar['b__name']  . "<br/>");
            }
        }

     //   $arrays['array']=$array;
      //  $this->response->setOutput($this->load->view('brands/Searchbrands',$arrays));
       // $result=json_encode($array);
        //return ($result);

    }

    public function countCrosses()
    {

        $url = GET_COUNT_CROSSES;
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $count=$response->body->count;
        /*   if ($sort_brands['sort'] == "brand_name1")
           {
               if (($sort_brands['order'] == "ASC") || ($sort_brands['order'] == NULL)) {

                   usort($crosses, function($a, $b){
                       return ($a['brand_name1'] - $b['brand_name1']);
                   });
               }
               elseif ($sort_brands['order'] == "DESC")
               {
                   $crosses=krsort($crosses);
               }
           }*/

        return (json_encode($count));
    }




    public function postCross($data)
    {

         $url = ADD_CROSSES;
         $response = \Httpful\Request::post($url)
             ->body($data)
             ->sendsJson()
             ->send();
    }


    public function GetSimilarity()
    {

        $url = GET_SIMILARITY;
        $response = \Httpful\Request::post($url)
            ->sendsJson()
            ->send();
    }

    public function GetSimilarityGroup($data)
    {

        $url = GET_SIMILARITY_GROUP."?name=$data";
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);

        foreach ($a as $value) {

            $group = $value->group;
            $arData = $value->arData;


            $result[]=['group'=>$group,'arData'=>$arData];

        }

        $result=json_encode($result);

        return ($result);

    }

    public function PostMainStatus($data)
    {

        $url = POST_MAIN_STATUS;
        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
    }


    public function deleteCross($data)
    {

        $url = DELETE_CROSSES;
        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function putCross($data)
    {

        $url = EDIT_CROSSES;
        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function getBrandsGroup($data)
    {

        $url = GET_BRANDS_GROUP."?sort=$data";
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);
        foreach ($a as $value) {

            $gob_name = $value->gob_name;
            $gob_id = $value->gob_id;
            $b__id = $value->gob_id;

            $result[] = ['gob_name' => $gob_name, 'gob_id' => $gob_id];

        }

        $result=json_encode($result);

        return ($result);

    }

    public function getDictContent($data)
    {

        $url = GET_DICT_CONTENT."?sort=$data";

        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);
        if($a==NULL){
            echo "";
        }
        else {
            foreach ($a as $value) {
                $repl_brand = $value->repl_brand;
                $desc_brand = $value->desc_brand;
                $brand_id = $value->brand_id;
                $dict_code = $value->dict_code;
                $gob_id = $value->gob_id;
                $gob_name = $value->gob_name;
                $count = $value->count;
                $result[] = ['repl_brand' => $repl_brand, 'desc_brand' => $desc_brand, 'brand_id' => $brand_id, 'dict_code' => $dict_code, 'gob_id' => $gob_id, 'gob_name' => $gob_name, 'count'=>$count];

            }

            $result = json_encode($result);

            return ($result);
        }
    }


    public function getGroupByBrand($data)
    {

        $url = GET_GROUP_BY_BRAND."?b_id=$data";

        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);
        if($a==NULL){
            echo "";
        }
        else {
            foreach ($a as $value) {
                $gob_id = $value->gob__id;
                $gob_name = $value->gob__name;
                $result[] = ['gob_id' => $gob_id, 'gob_name' => $gob_name];

            }

            $result = json_encode($result);


            return ($result);
        }
    }


    public function getGroupIdByName($data)
    {

        $url = GET_BRAND_GROUP_ID_BY_NAME."?gob_name=$data";

        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $result =($response->body);

        return ($result);

    }
public function postNocheckedBrand($data)
{

    $url = ADD_NO_CHECKED_BRAND;
    $response = \Httpful\Request::post($url)
        ->body($data)
        ->sendsJson()
        ->send();
}

    public function getBrandsInGroup($data)
    {

        $url = GET_BRANDS_IN_GROUP."?name=".$data;
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);
        if($a==NULL){
            echo "";
        }

        else{

        foreach ($a as $value) {
            $b__name = $value->b__name;
            $gob_id = $value->gob_id;
            $gob_name = $value->gob_name;
            $b__id = $value->b__id;
            $b__parent_id = $value->b__parent_id;
            $b__status = $value->b__status;
            $smlr_percent = $value->smlr_percent;
            $smlr_checked = $value->smlr_checked;
            $main=$value->main;
            $count=$value->count;
            $dict=$value->dict;
            $result[] = ['b__name' => $b__name, 'gob_id' => $gob_id, 'gob_name' => $gob_name, 'b__id' => $b__id, 'b__parent_id' => $b__parent_id, 'b__status' => $b__status, 'smlr_percent' => $smlr_percent, 'smlr_checked' => $smlr_checked,'main'=>$main,'count'=>$count,'dict'=>$dict];

        }
            $result=json_encode($result);
            return ($result);

        }


    }



public function getNoCheckedBrands($data)
{
    $url = GET_NO_CHECKED_BRANDS."?sort=$data";

    $response = \Httpful\Request::get($url)
        ->expectsJson()
        ->send();
    $a =($response->body);

    foreach ($a as $value) {
        $b__name = $value->b__name;
        $b__id = $value->b__id;
        $b__parent_id = $value-> b__parent_id;
        $count = $value->count;


        $result[] = ['b__name' =>$b__name, 'b__id'=> $b__id, 'b__parent_id' => $b__parent_id,'count'=>$count];


    }

    $result=json_encode($result);
    return ($result);
}

    public function getAllBrands()
    {


        $url = GET_ALL_BRANDS;
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);

        foreach ($a as $value) {
            $b__name = $value->b__name;
            $b__id = $value->b__id;
            $b__parent_id = $value-> b__parent_id;
            $b__status=$value->b__status;

            $result[] = ['b__name' =>$b__name, 'b__id'=> $b__id, 'b__parent_id' => $b__parent_id, 'b__status' => $b__status];


        }

        $result=json_encode($result);
        return ($result);

    }


    public function getAllBrandsGroup()
    {


        $url = GET_ALL_BRANDS_GROUP;
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $a =($response->body);

        foreach ($a as $value) {
            $gob__name = $value->b__name;
            $gob__id = $value->b__id;


            $result[] = ['b__name' =>$gob__name, 'b__id'=> $gob__id];


        }

        $result=json_encode($result);
        return ($result);

    }


    public function countBrandsGroup()
    {

        $url =GET_COUNT_BRANDS_GROUP ;
        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
        $count=$response->body->count;
        /*   if ($sort_brands['sort'] == "brand_name1")
           {
               if (($sort_brands['order'] == "ASC") || ($sort_brands['order'] == NULL)) {

                   usort($crosses, function($a, $b){
                       return ($a['brand_name1'] - $b['brand_name1']);
                   });
               }
               elseif ($sort_brands['order'] == "DESC")
               {
                   $crosses=krsort($crosses);
               }
           }*/

        return (json_encode($count));
    }




    public function postBrandsGroup($data)
    {

        $url = ADD_BRANDS_GROUP;
        $response = \Httpful\Request::post($url)

            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function postBrandInGroup($data)
    {

        $url = ADD_BRAND_IN_GROUP;

        $response = \Httpful\Request::post($url)

            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function deleteBrandsInGroup($data)
    {

        $url = DELETE_BRANDS_IN_GROUP;
        $response = \Httpful\Request::post($url)

            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function deleteBrandsGroup($data)
    {

        $url = DELETE_BRANDS_GROUP;
        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function deleteBrands($data)
    {

        $url = DELETE_BRANDS;
        $response = \Httpful\Request::post($url)

            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function putBrandsGroup($data)
    {

        $url = EDIT_BRANDS_GROUP;

        $response = \Httpful\Request::post($url)


            ->body($data)
            ->sendsJson()
            ->send();
    }

    public function putBrands($data)
    {

        $url = EDIT_BRANDS;

        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
    }
}

