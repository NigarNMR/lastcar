<?php

class ControllerOrderWorkflowOrderWorkflow extends Controller {

    public function index() {
        $this->load->language('order_workflow/order_workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order_workflow/order_workflow');

        $this->getList();
    }

    public function edit() {
        $this->load->language('order_workflow/order_workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order_workflow/order_workflow');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_order_workflow_order_workflow->editOrderStatusProductPreClear($this->request->get);

            if (isset($this->request->post['selected_if_one'])) {
                foreach ($this->request->post['selected_if_one'] as $key => $order_product_status_id) {
                    $this->model_order_workflow_order_workflow->editOrderStatusProductIfOneWorkflow($this->request->post, $this->request->get);
                }
            }

            if (isset($this->request->post['selected_any'])) {
                foreach ($this->request->post['selected_any'] as $key => $order_product_status_id) {
                    $this->model_order_workflow_order_workflow->editOrderStatusProductAnyWorkflow($this->request->post, $this->request->get);
                }
            }
            
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('order_workflow/order_workflow', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    public function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->load->language('order_workflow/order_workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order_workflow/order_workflow');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');

        $data['breadcrumbs'] = array();
        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('order_workflow/order_workflow', 'token=' . $this->session->data['token'] . $url, true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['order_statuses'] = array();

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $results = $this->model_order_workflow_order_workflow->getOrderStatuses($filter_data);
        foreach ($results as $result) {
            $data['order_statuses'][$result['order_status_id']] = array(
                'order_status_id' => $result['order_status_id'],
                'name' => $result['name'] . (($result['order_status_id'] == $this->config->get('config_order_status_id')) ? $this->language->get('text_default') : null),
                'edit' => $this->url->link('order_workflow/order_workflow/edit', 'token=' . $this->session->data['token'] . '&order_status_id=' . $result['order_status_id'] . $url, true)
            );

            $allstatuses = $this->model_order_workflow_order_workflow->getOrderProductStatusWorkflow($result['order_status_id']);
            /*
            echo '<pre>';
            var_dump($allstatuses);
            echo '</pre>';
            */
            foreach ($allstatuses as $allstatus) {
                $data['order_statuses'][$result['order_status_id']]['workflow'][$allstatus['type']][] = array(
                    'order_product_status_id' => $allstatus['order_product_status_id'],
                    'name' => $allstatus['name'],
                );
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');

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
        $this->response->setOutput($this->load->view('order_workflow/order_workflow', $data));
    }

    public function getForm() {
        $this->load->language('order_workflow/order_workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order_workflow/order_workflow');

        $data['entry_name'] = $this->language->get('entry_name');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['text_form'] = !isset($this->request->get['order_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');

        $data['breadcrumbs'] = array();
        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('order_workflow/order_workflow', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->post['selected_if_one'])) {
            $data['selected_if_one'] = (array) $this->request->post['selected_if_one'];
        } else {
            $data['selected_if_one'] = array();
        }

        if (isset($this->request->post['selected_any'])) {
            $data['selected_any'] = (array) $this->request->post['selected_any'];
        } else {
            $data['selected_any'] = array();
        }

        if (!isset($this->request->get['order_status_id'])) {
            $data['action'] = $this->url->link('order_workflow/order_workflow/add', 'token=' . $this->session->data['token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('order_workflow/order_workflow/edit', 'token=' . $this->session->data['token'] . '&order_status_id=' . $this->request->get['order_status_id'] . $url, true);
        }

        $data['action'] = $this->url->link('order_workflow/order_workflow/edit', 'token=' . $this->session->data['token'] . '&order_status_id=' . $this->request->get['order_status_id'] . $url, true);
        $data['cancel'] = $this->url->link('order_workflow/order_workflow', 'token=' . $this->session->data['token'] . $url, true);

        if (isset($this->request->post['order_status'])) {
            $data['order_status'] = $this->request->post['order_status'];
        } elseif (isset($this->request->get['order_status_id'])) {
            $data['order_status'] = $this->model_order_workflow_order_workflow->getOrderStatus($this->request->get['order_status_id']);
        } else {
            $data['order_status'] = array();
        }
        //var_dump($this->request->get['order_status_id']);
// вывод статусов товара
        $order_status_id = $this->request->get['order_status_id'];

        $results = $this->model_order_workflow_order_workflow->getStatusProductOrder();

        foreach ($results as $result) {
            $data['product_statuses'][] = array(
                'order_product_status_id' => $result['order_product_status_id'],
                'name' => $result['name'],
            );
        }

        $st_results = $this->model_order_workflow_order_workflow->getOrderProductStatusWorkflow($order_status_id);

        foreach ($st_results as $result) {
            $data['product_status_check'][$result['type']][$result['order_product_status_id']] = 1;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');


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

        $this->response->setOutput($this->load->view('order_workflow/order_workflow_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'order_workflow/order_workflow')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}
