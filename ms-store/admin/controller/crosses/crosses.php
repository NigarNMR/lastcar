<?php
class ControllerCrossesCrosses extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('crosses/crosses');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();

    }

    public function add() {

        $this->load->language('crosses/crosses');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            echo $json_param=json_encode($this->request->post);
            $json_param=urlencode($json_param);
            echo ($this->load->controller('api/api/postCross',$json_param));
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

/*
                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }*/



            $this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
        if (isset($this->request->get['a']))
        {
            $this->getList();
        }
    }

    public function edit() {
        $this->load->language('crosses/crosses');

        $this->document->setTitle($this->language->get('heading_title'));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->request->post['brand_name1O']=$this->request->get['brand_name1'];
            $this->request->post['akey1O']=$this->request->get['akey1'];
            $this->request->post['brand_name2O']=$this->request->get['brand_name2'];
            $this->request->post['akey2O']=$this->request->get['akey2'];
            $this->request->post['sideO']=$this->request->get['side'];
            $this->request->post['codeO']=$this->request->get['code'];
            // var_dump($this->request->post);
            echo $json_param=json_encode($this->request->post);
            $json_param=urlencode($json_param);
            echo ($this->load->controller('api/api/putCross',$json_param));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'],true));
        }

        $this->getForm();
        if (isset($this->request->get['a']))
        {
            $this->getList();
        }
    }

    public function delete() {
        $this->load->language('crosses/crosses');

        $this->document->setTitle($this->language->get('heading_title'));


        $this->request->post['brand_name1']=$this->request->get['brand_name1'];
        $this->request->post['akey1']=$this->request->get['akey1'];
        $this->request->post['brand_name2']=$this->request->get['brand_name2'];
        $this->request->post['akey2']=$this->request->get['akey2'];
        $this->request->post['side']=$this->request->get['side'];
        $this->request->post['code']=$this->request->get['code'];
        echo $json_param=json_encode($this->request->post);
        $json_param=urlencode($json_param);
        echo ($this->load->controller('api/api/deleteCross',$json_param));

        $this->getList();
    }



    protected function getList() {

        if (isset($this->request->get['filter_akey1'])) {
            $filter_akey1 = $this->request->get['filter_akey1'];

        } else {
            $filter_akey1 = null;
        }

        if (isset($this->request->get['filter_brand1'])) {

            $filter_brand1 = $this->request->get['filter_brand1'];

        } else {
            $filter_brand1 = null;
        }

        if (isset($this->request->get['filter_brand2'])) {

            $filter_brand2 = $this->request->get['filter_brand2'];

        } else {
            $filter_brand2 = null;
        }


        if (isset($this->request->get['filter_akey2'])) {
            $filter_akey2 = $this->request->get['filter_akey2'];

        } else {
            $filter_akey2 = null;
        }

        if (isset($this->request->get['filter_side'])) {
            $filter_side = $this->request->get['filter_side'];

        } else {
            $filter_side = null;
        }

        if (isset($this->request->get['filter_code'])) {
            $filter_code = $this->request->get['filter_code'];

        } else {
            $filter_code = null;
        }

        $filter_array=['akey1'=>$filter_akey1, 'brand1'=>  $filter_brand1, 'brand2'=>  $filter_brand2, 'akey2'=>$filter_akey2,'side'=>$filter_side,'code'=>$filter_code];

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = NULL;
        }



        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = NULL;
        }

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
            'href' => $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true)
        );
        $data['add'] = $this->url->link('crosses/crosses/add', 'token=' . $this->session->data['token'] . $url, true);



        $filter_data = array(
            'filter_brand1'             => $filter_brand1,
            'filter_brand2'             => $filter_brand2,
            'filter_akey1'             => $filter_akey1,
            'filter_akey2'             => $filter_akey2,
            'filter_side'             => $filter_side,
            'filter_code'             => $filter_code,
            'sort'                     => $sort,
            'order'                    => $order
        );


        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand_name1'] = $this->language->get('column_brand_name1');
        $data['column_akey2'] = $this->language->get('column_akey2');
        $data['column_brand_name2'] = $this->language->get('column_brand_name2');
        $data['column_akey1'] = $this->language->get('column_akey1');
        $data['column_side'] = $this->language->get('column_side');
        $data['column_code'] = $this->language->get('column_code');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_action'] = $this->language->get('column_action');

        $data['text_balance'] = $this->language->get('text_balance');

        $data['entry_brand_name1'] = $this->language->get('entry_brand_name1');
        $data['entry_akey1'] = $this->language->get('entry_akey1');
        $data['entry_brand_name2'] = $this->language->get('entry_brand_name2');
        $data['entry_akey2'] = $this->language->get('entry_akey2');
        $data['entry_side'] = $this->language->get('entry_side');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_approve'] = $this->language->get('button_approve');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_unlock'] = $this->language->get('button_unlock');

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






        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }



        $data['sort_brand_name1'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=brand_name1' . $url, true);
        $data['sort_akey1'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=akey1' . $url, true);
        $data['sort_brand_name2'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=brand_name2' . $url, true);
        $data['sort_akey2'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=akey2' . $url, true);
        $data['sort_side'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=side' . $url, true);
        $data['sort_code'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&sort=code' . $url, true);



        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $post_param=['sort'=>$sort,'order'=>$order, 'filter'=>$filter_array];



        $post_param['page']=$page;
        echo $json_param=json_encode($post_param);
        $count = $this->load->controller('api/api/countCrosses',$json_param);
        $count=json_decode($count,true);


        echo $json_param=(json_encode($post_param));
        $json_param=urlencode($json_param);
        $crosses=  $this->load->controller('api/api/getCrosses',$json_param);
        $crosses=json_decode($crosses,true);
        $data['crosses']=array();
        foreach ($crosses as $cross) {
            $data['crosses'][] = array(
                'akey1' => $cross['akey1'],
                'akey2' => $cross['akey2'],
                'edit' => $this->url->link('crosses/crosses/edit', 'token=' . $this->session->data['token'] . '&brand_name1=' . $cross['brand_name1'] . '&brand_name2=' . $cross['brand_name2'] . '&akey1=' . $cross['akey1'] . '&akey2=' . $cross['akey2']  . '&side=' . $cross['side'] . '&code=' . $cross['code'], true),
                'delete' => $this->url->link('crosses/crosses/delete', 'token=' . $this->session->data['token'] . '&brand_name1=' . $cross['brand_name1'] . '&brand_name2=' . $cross['brand_name2'] . '&akey1=' . $cross['akey1'] . '&akey2=' . $cross['akey2'] . $url, true),
                'brand_name1' => ($cross['brand_name1']),
                'brand_name2' => $cross['brand_name2'],
                'side' => $cross['side'],
                'code' => $cross['code'],

            );
        }

        $pagination = new Pagination();
        $pagination->total = $count;
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($count) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($count - 20)) ? $count : ((($page - 1) * 20) + 20), $count, ceil( $count / 20));



        $data['filter_akey2'] = $filter_akey2;
        $data['filter_akey1'] = $filter_akey1;
        $data['filter_brand1'] = $filter_brand1;
        $data['filter_brand2'] = $filter_brand2;
        $data['filter_side'] = $filter_side;
        $data['filter_code'] = $filter_code;


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        //var_dump($data);
        $this->response->setOutput($this->load->view('crosses/crosses_list', $data));
    }

    protected function getForm() {
        $this->load->language('crosses/crosses');

        $data['entry_brand_name1'] = $this->language->get('entry_brand_name1');
        $data['entry_brand_name2'] = $this->language->get('entry_brand_name2');
        $data['entry_akey1'] = $this->language->get('entry_akey1');
        $data['entry_akey2'] = $this->language->get('entry_akey2');
        $data['entry_side'] = $this->language->get('entry_side');
        $data['entry_code'] = $this->language->get('entry_code');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_safe'] = $this->language->get('entry_safe');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_default'] = $this->language->get('entry_default');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_limit'] = $this->language->get('entry_limit');

        $data['help_safe'] = $this->language->get('help_safe');
        $data['help_points'] = $this->language->get('help_points');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_address_add'] = $this->language->get('button_address_add');
        $data['button_history_add'] = $this->language->get('button_history_add');
        $data['button_transaction_add'] = $this->language->get('button_transaction_add');
        $data['button_reward_add'] = $this->language->get('button_reward_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_address'] = $this->language->get('tab_address');
        $data['tab_history'] = $this->language->get('tab_history');
        $data['tab_transaction'] = $this->language->get('tab_transaction');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_ip'] = $this->language->get('tab_ip');

        $data['token'] = $this->session->data['token'];



        if (isset($this->request->get['brand_name1'])) {
            $data['brand_name1'] = $this->request->get['brand_name1'];
        } else {
            $data['brand_name1'] = 0;
        }

        if (isset($this->request->get['brand_name2'])) {
            $data['brand_name2'] = $this->request->get['brand_name2'];
        } else {
            $data['brand_name2'] = 0;
        }

        if (isset($this->request->get['akey1'])) {
            $data['akey1'] = $this->request->get['akey1'];
        } else {
            $data['akey1'] = 0;
        }

        if (isset($this->request->get['akey2'])) {
            $data['akey2'] = $this->request->get['akey2'];
        } else {
            $data['akey2'] = 0;
        }

        if (isset($this->request->get['side'])) {
            $data['side'] = $this->request->get['side'];
        } else {
            $data['side'] = 0;
        }

        if (isset($this->request->get['code'])) {
            $data['code'] = $this->request->get['code'];
        } else {
            $data['code'] = 0;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['brand_name1'])) {
            $data['error_brand_name1'] = $this->error['brand_name1'];
        } else {
            $data['error_brand_name1'] = '';
        }

        if (isset($this->error['brand_name2'])) {
            $data['error_brand_name2'] = $this->error['brand_name2'];
        } else {
            $data['error_brand_name2'] = '';
        }

        if (isset($this->error['akey1'])) {
            $data['error_akey1'] = $this->error['akey1'];
        } else {
            $data['error_akey1'] = '';
        }

        if (isset($this->error['akey2'])) {
            $data['error_akey2'] = $this->error['akey2'];
        } else {
            $data['error_akey2'] = '';
        }

        if (isset($this->error['side'])) {
            $data['error_side'] = $this->error['side'];
        } else {
            $data['error_side'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] ./* $url,*/ true)
        );

        if (!(isset($this->request->get['brand_name1']) && isset($this->request->get['brand_name2']) && isset($this->request->get['akey1']) &&  isset($this->request->get['akey2']))) {
            $data['action'] = $this->url->link('crosses/crosses/add', 'token=' . $this->session->data['token'] . '&a=true' ,true);
        }

        else {
            $data['action'] = $this->url->link('crosses/crosses/edit', 'token=' . $this->session->data['token'] . '&brand_name1=' . $data['brand_name1'] .  '&brand_name2=' . $data['brand_name2'] .  '&akey1=' . $data['akey1'] .  '&akey2=' . $data['akey2'] .   '&side=' . $data['side'] .  '&code=' . $data['code'] . '&a=true', true);
        }

        $data['cancel'] = $this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] , true);



        if (isset($this->request->post['brand_name1'])) {
            $data['brand_name1'] = $this->request->post['brand_name1'];
           
        } else {
            $data['brand_name1'] = '';
        }

        if (isset($this->request->post['akey1'])) {
            $data['akey1'] = $this->request->post['akey1'];
           
        } else {
            $data['akey1'] = '';
        }

        if (isset($this->request->post['brand_name2'])) {
            $data['brand_name2'] = $this->request->post['brand_name2'];
           
        } else {
            $data['brand_name2'] = '';
        }

        if (isset($this->request->post['akey2'])) {
            $data['akey2'] = $this->request->post['akey2'];
           
        } else {
            $data['akey2'] = '';
        }

        if (isset($this->request->post['side'])) {
            $data['side'] = $this->request->post['side'];
           
        } else {
            $data['side'] = '';
        }

        if (isset($this->request->post['code'])) {
            $data['code'] = $this->request->post['code'];
           
        } else {
            $data['code'] = '';
        }

        if (isset($this->request->get['brand_name1'])) {
            $data['brand_name1O'] = $this->request->get['brand_name1'];
            
        } else {
            $data['brand_name1O'] = '';
        }

        if (isset($this->request->get['akey1'])) {
            $data['akey1O'] = $this->request->get['akey1'];
            
        } else {
            $data['akey1O'] = '';
        }

        if (isset($this->request->get['brand_name2'])) {
            $data['brand_name2O'] = $this->request->get['brand_name2'];
            
        } else {
            $data['brand_name2O'] = '';
        }

        if (isset($this->request->get['akey2'])) {
            $data['akey2O'] = $this->request->get['akey2'];
          
        } else {
            $data['akey2O'] = '';
        }

        if (isset($this->request->get['side'])) {
            $data['sideO'] = $this->request->get['side'];
           
        } else {
            $data['sideO'] = '';
        }

        if (isset($this->request->get['code'])) {
            $data['codeO'] = $this->request->get['code'];
           
        } else {
            $data['codeO'] = '';
        }





        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('crosses/customer_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'crosses/crosses')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['brand_name1']) < 1) || (utf8_strlen(trim($this->request->post['brand_name1'])) > 32)) {
            $this->error['brand_name1'] = "Поле должно быть от 1 до 32 символов!";
        }

        if ((utf8_strlen($this->request->post['akey1']) < 1) || (utf8_strlen(trim($this->request->post['akey1'])) > 32)) {
            $this->error['akey1'] = "Поле должно быть от 1 до 32 символов!";
        }

        if ((utf8_strlen($this->request->post['brand_name2']) < 1) || (utf8_strlen(trim($this->request->post['brand_name2'])) > 32)) {
            $this->error['brand_name2'] = "Поле должно быть от 1 до 32 символов!";
        }

        if ((utf8_strlen($this->request->post['akey2']) < 1) || (utf8_strlen(trim($this->request->post['akey2'])) > 32)) {
            $this->error['akey2'] = "Поле должно быть от 1 до 32 символов!";
        }
        if ((utf8_strlen($this->request->post['side']) < 1) || (utf8_strlen(trim($this->request->post['side'])) > 32)) {
            $this->error['side'] = "Поле должно быть от 1 до 32 символов!";
        }
        if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen(trim($this->request->post['code'])) > 32)) {
            $this->error['code'] = "Поле должно быть от 1 до 32 символов!";
            //$this->language->get('error_firstname');
        }



        return !$this->error;
    }

}
