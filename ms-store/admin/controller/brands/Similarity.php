<?php
class ControllerBrandsSimilarity extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('brands/brands');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();

    }
    public function SimilarityGroup(){
        $b_name=$this->request->get['b_name'];
        $result = $this->load->controller('api/api/GetSimilarityGroup',urlencode($b_name));
        $result=json_decode($result,true);

        echo "<H4>" . $b_name . "</H4>";
        foreach ($result as $res)
        {
            echo "Группа:  " . "<b>". $res['group'] . "</b>" . "  - Соответствие (%): " . "<b>". $res['arData'] . "</b>". "</br>";
        }

    }

    public function GetSimilarity(){

        $result = $this->load->controller('api/api/GetSimilarity');
        $this->load->language('brands/brands');
        $this->getList();


    }



    protected function getList() {

        if (isset($this->request->get['from'])) {
            $filter_from = $this->request->get['from'];

        } else {
            $filter_from = 0;
        }

        if (isset($this->request->get['before'])) {

            $filter_before = $this->request->get['before'];

        } else {
            $filter_before = 100;
        }


        $filter_array=['from'=>$filter_from, 'before'=>  $filter_before];

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('brands/similarity', 'token=' . $this->session->data['token'] . $url, true)
        );




        $filter_data = array(
            'filter_from'             => $filter_from,
            'filter_before'             => $filter_before

        );

        $data['text_list_similarity'] = $this->language->get('text_list_similarity');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_main'] = $this->language->get('column_main');
        $data['column_percent'] = $this->language->get('column_percent');
        $data['column_suitable_group'] = $this->language->get('column_suitable_group');

        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['column_action'] = $this->language->get('column_action');

        $data['from'] = $this->language->get('from');
        $data['before'] = $this->language->get('before');



        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');


        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $post_param=['filter'=>$filter_array];
        $post_param['page']=$page;
        $json_param=json_encode($post_param);
        $brands= $this->load->controller('api/api/getBrandsInGroup',$json_param);
        $brands=json_decode($brands,true);
        $data['brands']=array();


        foreach ($brands as $brand) {
            $data['brands'][] = array(
                $data['b__name'] = $brand['b__name'],
                $data['gob_id'] = $brand['gob_id'],
                $data['gob_name'] = $brand['gob_name'],
                $data['b__id'] = $brand['b__id'],
                $data['b__parent_id'] = $brand['b__parent_id'],
                $data['b__status'] = $brand['b__status'],
                $data['smlr_percent'] = $brand['smlr_percent'],
                $data['count'] = $brand['count'],
                $data["dict"] = $brand["dict"],
                'delete' => $this->url->link('brands/brands/delete', 'token=' . $this->session->data['token']  . '&b_id=' . $brand['b__id'] .'&sim=true'  , true)
            );
        }

        $pagination = new Pagination();
        $pagination->total = $data['count'];
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('brands/similarity', 'token=' . $this->session->data['token'] . '&page={page}' . '&before='. $filter_before.'&from='. $filter_from , true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($data['count']) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($data['count'] - 20)) ? $data['count'] : ((($page - 1) * 20) + 20), $data['count'], ceil( $data['count'] / 20));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
       // var_dump($data);
        $this->response->setOutput($this->load->view('brands/similarity', $data));
    }



}
