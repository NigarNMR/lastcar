<?php


class ControllerBrandsAliasDict extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('brands/brands');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();

    }



    protected function getList() {


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
            'text' => $this->language->get('text_list_dictionaries'),
            'href' => $this->url->link('brands/AliasDict', 'token=' . $this->session->data['token'] , true)
        );
        $data['add'] = $this->url->link('brands/GroupBrands/add', 'token=' . $this->session->data['token'] , true);



        $data['text_list_dictionaries'] = $this->language->get('text_list_dictionaries');
        $data['column_parent_brand'] = $this->language->get('column_parent_brand');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_percent'] = $this->language->get('column_percent');
        $data['text_list'] = $this->language->get('text_list');;
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

        if (isset($this->request->post['WS'])) {
            $data['ws'] = $this->request->post['WS'];
        } else {
            $data['ws'] = 0;
        }

        if (isset($this->request->post['gr'])) {
            $status = 0;
        } else {
            $status = 1;
        }


        $count = $this->load->controller('api/api/countBrandsGroup');
        $count=json_decode($count,true);
       // $count = $this->load->controller('api/api/countBrandsGroup',$json_param);
       // $this->load->controller('api/api/postBrandsGroup',$json_param);

        $pagination = new Pagination();
        $pagination->total = $count;
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] . '&page={page}' , true);

        $data['pagination'] = $pagination->render();
        $data['get']=$this->url->link('brands/AliasDict', 'token=' . $this->session->data['token'] . '&page={page}' , true);
        $data['results'] = sprintf($this->language->get('text_pagination'), ($count) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($count - 20)) ? $count : ((($page - 1) * 20) + 20), $count, ceil( $count / 20));
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('brands/AliasDict', $data));
       // var_dump($data);
    }


    public function DictContent()
    {

        $this->load->language('brands/brands');

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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('brands/GroupBrands', 'token=' . $this->session->data['token'] , true)
        );
        $data['add'] = $this->url->link('brands/GroupBrands/add', 'token=' . $this->session->data['token'] , true);




        $data['column_parent_brand'] = $this->language->get('column_parent_brand');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_brand'] = $this->language->get('column_brand');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_brand_group'] = $this->language->get('column_brand_group');
        $data['column_percent'] = $this->language->get('column_percent');
        $data['text_list'] = $this->language->get('text_list');;
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_no_result'] = $this->language->get('text_no_result');
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

        if (isset($this->request->get['ws'])) {
            $data['ws'] = $this->request->get['ws'];
        } else {
            $data['ws'] = 0;
        }

        if (isset($this->request->get['gr'])) {
            if ($this->request->get['gr'] == 'false') {
                $status = 0;
            } else {
                $status = 1;
            }
        }
        $data['status']=$status;

        $post_param1=['dict_name'=>$data['ws'],'page'=>$page, 'status'=> $status];
        $json_param=json_encode($post_param1);
        $dict_brands= $this->load->controller('api/api/getDictContent',urlencode($json_param));
        $dict_brands = json_decode($dict_brands,true);
        $data['dictionaries']=array();
        if($dict_brands==NULL){
            echo "";
            $count=NULL;
        }
        else {
            foreach ($dict_brands as $dict) {
                $count=$dict['count'];
               /* if ($dict['repl_brand']==NULL) $dict['repl_brand']=" ";
                if ($dict['desc_brand']==NULL) $dict['desc_brand']=" ";
                if ($dict['brand_id']==NULL) $dict['brand_id']=" ";
                if ($dict['dict_code']==NULL) $dict['dict_code']=" ";
                if ($dict['gob_id']==NULL) $dict['gob_id']=" ";*/
                if (!isset($dict['gob_name']) || $dict['gob_name']==NULL) $dict['gob_name']=" ";
                if (!isset($dict['group']) || $dict['group']==NULL) $dict['group']=" ";
                $data['dictionaries'][] = array(
                    $brandsInGroup = $this->load->controller('api/api/getBrandsInGroup', urlencode(json_encode($mas = ['gob_name' => $dict['gob_name']]))),
                    $group_brands = $this->load->controller('api/api/getGroupByBrand', urlencode(json_encode($dict['brand_id']))),
                    'repl_brand' => $dict['repl_brand'],
                    'desc_brand' => $dict['desc_brand'],
                    'brand_id' => $dict['brand_id'],
                    'dict_code' => $dict['dict_code'],
                    'gob_id' => $dict['gob_id'],
                    'gob_name' => $dict['gob_name'],
                    'group' => json_decode($group_brands, true),
                    'brands' => $a = json_decode($brandsInGroup, true)

                );

            }
        }
        // $count = $this->load->controller('api/api/countBrandsGroup',$json_param);
        // $count=json_decode($count,true);



        $this->load->controller('api/api/postBrandsGroup',$json_param);
        $pagination = new Pagination();
        if($count) {
            $pagination->total = $count;
            $pagination->page = $page;
            $pagination->limit = 20;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('brands/Dict_content', 'token=' . $this->session->data['token'] . '&page={page}', true);
            $data['pagination'] = $pagination->render();
            $data['get'] = $this->url->link('brands/AliasDict', 'token=' . $this->session->data['token'] . '&page={page}', true);
            $data['results'] = sprintf($this->language->get('text_pagination'), ($count) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($count - 20)) ? $count : ((($page - 1) * 20) + 20), $count, ceil($count / 20));
        }
        else {
            $data['pagination']="";
            $data['results']='';
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('brands/Dict_content', $data));
         // var_dump($data);
    }

}
