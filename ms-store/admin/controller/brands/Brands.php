<?php
class ControllerBrandsBrands extends Controller {
    public $error = array();

    public function index() {

        $this->load->language('brands/brands');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getNoCheckedBrands();

    }

    public function add() {
       $this->load->language('brands/brands');
        $this->document->setTitle($this->language->get('heading_title'));

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                $this->request->post['gob_id']= $this->request->get['gob_id'];
                if ($this->request->post['b_name']==''){
                    $this->request->post['b_name']=$this->request->post['bname'];
                }

                 $json_param=urlencode(json_encode($this->request->post));
                echo ($this->load->controller('api/api/postBrandInGroup',$json_param));
                $this->session->data['success'] = $this->language->get('text_success');
                //$this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));
            }
        $this->getForm();

        if (isset($this->request->get['modall'])){
            $this->load->controller('brands/GroupBrands');            
        }
    }

    public function noCheckedBrandAdd() {
        $this->load->language('brands/brands');
      if (isset($this->request->post['b_name'])) {
          $name = $this->request->post['b_name'];
      }

        $this->load->language('brands/brands');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->request->post['b_name']= $name;

                echo ($this->load->controller('api/api/postNocheckedBrand', urlencode(json_encode($this->request->post))));

            }

            $this->session->data['success'] = $this->language->get('text_success');
            //$this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));



        $this->getForm();

        if(isset($this->request->get['a'])) {

            $this->getNoCheckedBrands();

        }

    }
    public function noCheckedAdd() {

        if(isset($this->request->get['b_id'])) {
            $name = $this->request->get['b_id'];
        }

        $this->load->language('brands/brands');
        $this->document->setTitle($this->language->get('heading_title'));

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                $this->request->post['b_name']= $name;

                $json_param=json_encode($this->request->post);


                    if (isset($this->request->post['agree']) && $this->request->post['agree'] == 'on') {
                        $this->request->post['gob_name'] = $name;
                        $json_param = json_encode($this->request->post);
                        echo($this->load->controller('api/api/postBrandsGroup', urlencode($json_param)));
                        $id = $this->load->controller('api/api/getGroupIdByName', urlencode(json_encode($name)));
                        $paramsToAdd = ['b_name' => $name, 'gob_id' => $id, 'newGroup'=>true];
                        $paramsToAdd = urlencode(json_encode($paramsToAdd));
                        echo($this->load->controller('api/api/postBrandInGroup', $paramsToAdd));

                    } else {
                        echo($this->load->controller('api/api/postBrandInGroup', urlencode($json_param)));

                    }

                $this->session->data['success'] = $this->language->get('text_success');
                //$this->response->redirect($this->url->link('crosses/crosses', 'token=' . $this->session->data['token'] . $url, true));
            }

        if(isset($this->request->get['red'])) {

            $this->getNoCheckedBrands();

        }
        elseif(isset($this->request->get['d'])) {

            $this->load->controller('brands/AliasDict');

        }
        else {
            $this->getForm();
        }

    }

     public function edit() {
         $this->load->language('brands/brands');

         $this->document->setTitle($this->language->get('heading_title'));

         if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {


             $this->request->post['b__nameO']=urldecode($this->request->get['b_name']);

             $json_param= urlencode(json_encode($this->request->post));
             var_dump($this->request->post);
             echo ($this->load->controller('api/api/putBrands',$json_param));
             $this->session->data['success'] = $this->language->get('text_success');


         }
       

        $this->getForm();

         if(isset($this->request->get['a'])) {

             if (isset($this->request->get['modall'])){
                 $this->load->controller('brands/GroupBrands');
                 
             }
             elseif (isset($this->request->get['ed'])){
                 $this->getNoCheckedBrands();

             }

             else {
                 $this->getNoCheckedBrands();
             }

         }

    }


    public function delete() {


        $this->document->setTitle($this->language->get('heading_title'));
        $this->request->post['b_id']=$this->request->get['b_id'];

         $json_param=json_encode($this->request->post);
        echo ($this->load->controller('api/api/deleteBrands',urlencode($json_param)));

        if(isset($this->request->get['modal_group'])) {
            $this->load->controller('brands/GroupBrands');
        }
        elseif (isset($this->request->get['sim'])) {
            $this->load->controller('brands/similarity');
        }
        else {
            $this->getNoCheckedBrands();
        }
    }

    public function deleteInGroup() {


        $this->document->setTitle($this->language->get('heading_title'));
        $this->request->post['b_id']=$this->request->get['b_id'];
        $this->request->post['gob_id']=$this->request->get['gob_id'];
         $json_param=json_encode($this->request->post);
        echo ($this->load->controller('api/api/deleteBrandsInGroup',urlencode($json_param)));

        // $this->getList();
    }


    public function getBrandsInGroup($id) {

        $this->load->language('brands/brands');
        $id=urlencode($id);

        $brands= $this->load->controller('api/api/getBrandsInGroup',$id);
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
                $data['smlr_checked'] = $brand['smlr_checked'],
                'edit' => $this->url->link('brands/brands/edit', 'token=' . $this->session->data['token'] . '&b_id=' . $brand['b__id']  . '&b_name=' . $brand['b__name'] , true),
                'deleteInGroup' => $this->url->link('brands/brands/deleteInGroup', 'token=' . $this->session->data['token']  . '&gob_id=' . $brand['gob_id']   . '&b_id=' . $brand['b__id'] ,  true),
                'delete' => $this->url->link('brands/brands/delete', 'token=' . $this->session->data['token']  . '&b_id=' . $brand['b__id']  , true)
            );
        }
        

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('brands/brands', 'token=' . $this->session->data['token'] , true)
        );
        $data['add'] = $this->url->link('brands/brands/add', 'token=' . $this->session->data['token'] , true);
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_parent_brand'] = $this->language->get('column_parent_brand');
        $data['column_status'] = $this->language->get('column_status');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_action'] = $this->language->get('column_action');



        $data['entry_brand_group'] = $this->language->get('entry_brand_group');
        $data['entry_brand_name'] = $this->language->get('entry_brand_name');
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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['sort']=1;
        $this->response->setOutput($this->load->view('brands/brands_in_group', $data));
        //var_dump($data);
    }

    public function getNoCheckedBrands() {

        $this->load->language('brands/brands');

        if (isset($this->request->get['filter_b_name'])) {
            $filter_b_name = $this->request->get['filter_b_name'];

        } else {
            $filter_b_name = null;
        }



        $filter_array=['b_name'=> $filter_b_name];

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_list_no_checked'),
            'href' => $this->url->link('brands/brands', 'token=' . $this->session->data['token'] , true)
        );
        $data['add'] = $this->url->link('brands/brands/noCheckedBrandAdd', 'token=' . $this->session->data['token'] .'&NO=true', true);


        $data ['text_list_no_checked']= $this->language->get('text_list_no_checked');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_parent_brand'] = $this->language->get('column_parent_brand');
        $data['column_status'] = $this->language->get('column_status');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_action'] = $this->language->get('column_action');



        $data['entry_brand_group'] = $this->language->get('entry_brand_group');
        $data['entry_brand_name'] = $this->language->get('entry_brand_name');
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
            $url = '&order=DESC';
        } else {
            $url = '&order=ASC';
        }



        $data['sort_brand_group'] = $this->url->link('brands/brands', 'token=' . $this->session->data['token'] . '&sort=b_name' . $url, true);

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $post_param=['sort'=>$sort,'order'=>$order, 'filter'=>$filter_array];


        $post_param['page']=$page;
         $json_param=json_encode($post_param);
        $json_param=urlencode($json_param);
        $brands= $this->load->controller('api/api/getNoCheckedBrands',$json_param);
        $brands=json_decode($brands,true);
        $data['brands']=array();
        // var_dump($brands);
        foreach ($brands as $brand) {
            $data['brands'][] = array(
                $data['b__name'] = $brand['b__name'],
                $data['b__id'] = $brand['b__id'],
                $data['count'] = $brand['count'],
                $data['b__parent_id'] = $brand['b__parent_id'],
                'edit' => $this->url->link('brands/brands/edit', 'token=' . $this->session->data['token'] . '&b_id=' . $brand['b__id']  . '&b_name=' . $brand['b__name'] , true),
                'delete' => $this->url->link('brands/brands/delete', 'token=' . $this->session->data['token']  . '&b_id=' . $brand['b__id']  , true)
            );
        }

      //  var_dump($data['group_brands']);
        $pagination = new Pagination();
        $pagination->total = $data['count'];
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('brands/brands', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);
        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($data['count']) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($data['count'] - 20)) ? $data['count'] : ((($page - 1) * 20) + 20), $data['count'], ceil( $data['count'] / 20));

        $data['filter_b_name'] = $filter_b_name;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['sort']=1;
        $this->response->setOutput($this->load->view('brands/nochecked_brands', $data));
       // var_dump($data);
    }

    public function getForm()
    {
        $this->load->language('brands/brands');

        if(isset($this->request->get['nochecked']))
        {
            $Allbrands= $this->load->controller('api/api/getALLBrandsGroup');
            $Allbrands=json_decode($Allbrands,true);
        }

        else {
            $Allbrands = $this->load->controller('api/api/getALLBrands');
            $Allbrands = json_decode($Allbrands, true);
        }

        $data['AllBrands']= $Allbrands;
        $data['entry_b_name'] = $this->language->get('entry_brand_name');


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
        $data['entry_brand_group'] = $this->language->get('entry_brand_group');

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



       if (isset($this->request->get['b_name'])) {
            $data['b_name'] = $this->request->get['b_name'];

        } else {
            $data['b_name'] = '';
        }

        if (isset($this->request->get['gob_id'])) {
            $data['gob_id'] = $this->request->get['gob_id'];
        } else {
            $data['gob_id'] = 0;
        }

        if (isset($this->request->get['b_id'])) {
            $data['b_id'] = $this->request->get['b_id'];
        } else {
            $data['b_id'] = 0;
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
            'href' => $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->request->get['add'])) {
            $data['action'] = $this->url->link('brands/brands/add', 'token=' . $this->session->data['token'] . '&b_name=' . $data['b_name']. '&gob_id=' . $data['gob_id'] .'&modall=true' , true);

        }
        elseif (isset($this->request->get['NO'])) {
            $data['action'] = $this->url->link('brands/brands/noCheckedBrandAdd', 'token=' . $this->session->data['token'] . '&b_name=' . $data['b_name'] . '&a=true' , true);
        }
            elseif (isset($this->request->get['nochecked'])) {
            $data['action'] = $this->url->link('brands/brands/noCheckedAdd', 'token=' . $this->session->data['token'] . '&b_id=' . $data['b_id']. '&gob_id=' . $data['gob_id'] . '&red=true' , true);

        }

        elseif (isset($this->request->get['dict']) ) {
            $data['action'] = $this->url->link('brands/brands/noCheckedAdd', 'token=' . $this->session->data['token'] . '&b_id=' . $data['b_id']. '&gob_id=' . $data['gob_id'] . '&d=true' , true);

        }
        else {
            $data['action'] = $this->url->link('brands/brands/edit', 'token=' . $this->session->data['token'] . '&b_name=' . $data['b_name'] . '&a=true' . '&modall=true' ."&ed=true", true);
        }
        $data['cancel'] = $this->url->link('brands/brands', 'token=' . $this->session->data['token'], true);


        if (isset($this->request->post['b_name'])) {
            $data['b_name'] = $this->request->post['b_name'];

        } else {
            $data['b_name'] = '';
        }




        if (isset($this->request->get['b_id'])) {
            $data['b_id'] = $this->request->get['b_id'];

        } else {
            $data['b_id'] = '';
        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');



        if (isset($this->request->get['modal'])) {
            if(isset($this->request->get['nochecked']) || isset($this->request->get['dict']) )
            {
                $this->response->setOutput($this->load->view('brands/nochecked_brands_edit_form_modal', $data));
            }
            else {
                $this->response->setOutput($this->load->view('brands/brands_edit_form_modal', $data));
            }

        } else {
            $this->response->setOutput($this->load->view('brands/brands_edit_form', $data));
        }
       // var_dump($data);
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
