<?php

class Controllercommontecdocmodule extends Controller {

  public function index() {



    //Save customer group ID for TDMod 

    $_SESSION['TDM_CMS_USER_GROUP'] = intval($this->customer->getGroupId());

    $_SESSION['TDM_CMS_DEFAULT_CUR'] = $this->config->get('config_currency');



    //TecDoc

    if (defined('TDM_TITLE')) {
      $this->document->setTitle(TDM_TITLE);
    }

    if (defined('TDM_KEYWORDS')) {
      $this->document->setKeywords(TDM_KEYWORDS);
    }

    if (defined('TDM_DESCRIPTION')) {
      $this->document->setDescription(TDM_DESCRIPTION);
    }



    if (isset($this->request->get['route'])) {

      $this->document->addLink(HTTP_SERVER, 'canonical');
    }


    $_SERVER['SCRIPT_NAME'] = "";
    
    $data['column_left'] = $this->load->controller('common/column_left');

    $data['column_right'] = $this->load->controller('common/column_right');

    $data['content_top'] = $this->load->controller('common/content_top');

    $data['content_bottom'] = $this->load->controller('common/content_bottom');

    $data['footer'] = $this->load->controller('common/footer');

    $data['header'] = $this->load->controller('common/header');

    $_SERVER['SCRIPT_NAME'] = "autoparts";

    if (isset($_SESSION['cart']) && $_SESSION['cart']['latest_added']) {
      $arCart = $_SESSION['cart']['latest'];
      $this->cart->add(50, $arCart['quantity'], $arCart, 0);

      $_SESSION['cart']['latest_added'] = false;
    }
    if (substr_count($_SERVER['REQUEST_URI'],'autoparts/api')>0) {
        $this->response->setOutput($this->load->view('common/tecdoc_api.tpl', $data));
    }
    else {
        $this->response->setOutput($this->load->view('common/tecdoc_module.tpl', $data));
    }
    /*if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/tecdoc_module.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/tecdoc_module.tpl', $data);
		} else {
			return $this->load->view('default/template/common/tecdoc_module.tpl', $data);
		}*/
  }

}

?>