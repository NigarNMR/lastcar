<?php


class ControllerBrandsGroupBrands extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('brands/brands');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();

    }

    public function add() {
        $this->load->language('brands/brands');
        $this->document->setTitle($this->language->get('heading_title'));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $json_param=json_encode($this->request->post);

            echo ($this->load->controller('api/api/postBrandsGroup',urlencode($json_param)));
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();

        if(isset($this->request->get['a'])) {
            $this->getList();
        }
    }

    public function edit() {
        $this->load->language('brands/brands');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
           // var_dump($this->request->get);
            $this->request->post['gob_nameO']=$this->request->get['gob_name'];
             //var_dump($this->request->post);
             $json_param=json_encode($this->request->post);
            echo ($this->load->controller('api/api/putBrandsGroup',urlencode($json_param)));


            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();

      /*  if(isset($this->request->get['a'])) {
            $this->getList();
        }*/
    }

    public function delete() {
        $this->load->language('brands/brands');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->request->post['gob_name']=$this->request->get['gob_name'];
        $json_param=json_encode($this->request->post);
        echo ($this->load->controller('api/api/deleteBrandsGroup',$json_param));


        $this->getList();
    }

    public function PostMainStatus() {
        $this->load->language('brands/brands');

        $json_param=json_encode($this->request->get['b__id']);
         echo ($this->load->controller('api/api/PostMainStatus',$json_param));
         if(isset($this->request->get['al']))
         {
             echo ($this->load->controller('brands/AliasDict',$json_param));
         }
         else {
             $this->getList();
         }
    }

    public function GetBrandsInGroups() {

        $this->document->setTitle($this->language->get('heading_title'));
        $this->request->post['gob_name']=$this->request->get['gob_name'];
        $json_param=json_encode($this->request->post);
        echo ($this->load->controller('brands/brands/getBrandsInGroup',$json_param));

        //$this->getList();
    }

    public function SearchBrand() {

        $key=['name'=>$_GET['key'],'st'=>$_GET['st']];
        $json_param=json_encode($key);
        $json_param=urlencode($json_param);
        echo ($this->load->controller('api/api/searchBrand',$json_param));
    }


    public function getList() {

        if (isset($this->request->get['filter_gob_name'])) {
            $filter_gob_name = $this->request->get['filter_gob_name'];

        } else {
            $filter_gob_name = null;
        }

        $filter_array=['gob_name'=> $filter_gob_name];

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
            'href' => $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] , true)
        );
        $data['add'] = $this->url->link('brands/GroupBrands/add', 'token=' . $this->session->data['token']  , true);

        $data['customers'] = array();

        $filter_data = array(
            'filter_brands_group' => $filter_gob_name,

        );

        $data['column_parent_brand'] = $this->language->get('column_parent_brand');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_percent'] = $this->language->get('column_percent');
        $data['text_list'] = $this->language->get('text_list');;
        $data['text_no_results']=$this->language->get('text_no_results');

        $data['column_action'] = $this->language->get('column_action');



        $data['entry_brand_group'] = $this->language->get('entry_brand_group');
        $data['entry_brand_name'] = $this->language->get('entry_brand_name');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

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


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }



        $data['sort_brand_group'] = $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] . '&sort=gob_name' . $url, true);



        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $post_param=['sort'=>$sort,'order'=>$order, 'filter'=>$filter_array];


        $post_param['page']=$page;
        $json_param=json_encode($post_param);
        $count = $this->load->controller('api/api/countBrandsGroup',$json_param);
        $count=json_decode($count,true);


         $json_param=json_encode($post_param);
        $group_brands= $this->load->controller('api/api/getBrandsGroup',urlencode($json_param));
        $group_brands=json_decode($group_brands,true);
        $data['group_brands']=array();
        foreach ($group_brands as $group) {
            $gob_name1=['gob_name'=> $group['gob_name']];
            $json_param=urlencode(json_encode($gob_name1));
            $brands=$this->load->controller('api/api/getBrandsInGroup',$json_param);

            $data['group_brands'][] = array(
                'gob_id' => $group['gob_id'],
                'gob_name' => $group['gob_name'],
                'edit' => $this->url->link('brands/GroupBrands/edit', 'token=' . $this->session->data['token'] . '&gob_name=' . urlencode($group['gob_name'])  . $url, true),
                'delete' => $this->url->link('brands/GroupBrands/delete', 'token=' . $this->session->data['token']  . '&gob_name=' . urlencode($group['gob_name'])  . $url, true),
                'getBrands' => json_decode($brands,true),
                'brands'=>$this->url->link('brands/GroupBrands/GetBrandsInGroups', 'token=' . $this->session->data['token']  . '&gob_name=' . urlencode($group['gob_name'])  . $url, true),
            );


}


      $this->load->controller('api/api/postBrandsGroup',$json_param);
       // var_dump($data['group_brands']);
        $pagination = new Pagination();
        $pagination->total = $count;
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($count) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($count - 20)) ? $count : ((($page - 1) * 20) + 20), $count, ceil( $count / 20));



        $data['filter_gob_name'] = $filter_gob_name;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('brands/brands_list', $data));
        //var_dump($data);
    }

    public function getForm() {
        $this->load->language('brands/brands');


        $data['entry_brand_group'] = $this->language->get('entry_brand_group');

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



        $data['token'] = $this->session->data['token'];



        if (isset($this->request->get['gob_name'])) {
            $data['gob_name'] = $this->request->get['gob_name'];
        } else {
            $data['gob_name'] = 0;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['gob_name'])) {
            $data['error_gob_name'] = $this->error['gob_name'];
        } else {
            $data['error_gob_name'] = '';
        }



        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('customer/customer', 'token=' . $this->session->data['token'] , true)
        );

        if (!(isset($this->request->get['gob_name']) )) {
            $data['action'] = $this->url->link('brands/GroupBrands/add', 'token=' . $this->session->data['token'] .'&a=true' , true);
        }

        else {
            $data['action'] = $this->url->link('brands/GroupBrands/edit', 'token=' . $this->session->data['token'] . '&gob_name=' . $data['gob_name'] . "&a=true" , true);
        }

        $data['cancel'] = $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] , true);



        if (isset($this->request->post['gob_name'])) {
            $data['gob_name'] = $this->request->post['gob_name'];

        } else {
            $data['gob_name'] = '';
        }


        if (isset($this->request->get['gob_name'])) {
            $data['gob_nameO'] = $this->request->get['gob_name'];

        } else {
            $data['gob_nameO'] = '';
        }



        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('brands/brands_form', $data));
    }



    public function validateForm() {
        if (!$this->user->hasPermission('modify', 'brands/brands')) {
             $this->error['warning'] = $this->language->get('error_permission');
             }

            if ((utf8_strlen($this->request->post['brand_name1']) < 1) || (utf8_strlen(trim($this->request->post['brand_name1'])) > 32)) {
                 $this->error['brand_name1'] = "Поле должно быть от 1 до 32 символов!";
             }


            return !$this->error;

    }

}
