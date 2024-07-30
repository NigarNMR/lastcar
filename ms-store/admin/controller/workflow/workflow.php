<?php

class ControllerWorkflowWorkflow extends Controller {

    public function index() {
        $this->load->language('workflow/workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('workflow/workflow');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_workflow_workflow->addOrderProductStatusWorkflow($this->request->post);
            $status = $this->request->post;

        }

        $this->getForm();
    }
    
public function getForm() {
        $this->load->language('workflow/workflow');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('workflow/workflow');
        
        $data['status'] = $this->model_workflow_workflow->getStatusProductOrder();
        $st = count($data['status']);


        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();
        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('workflow/workflow', 'token=' . $this->session->data['token'] . $url, true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('workflow/workflow', 'token=' . $this->session->data['token'], true);
        
       $data_statuses = $this->model_workflow_workflow->getOrderProductStatusWorkflow();
       
       $data['order_product_statuses'] = array();
       foreach($data_statuses as $value){
           $data['order_product_statuses'][$value['order_product_status_id_A']][$value['order_product_status_id_B']]=1;
       }
       
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
        $this->response->setOutput($this->load->view('workflow/workflow', $data));
        
    }

}
