<?php
class ModelTotalCredit extends Model {
	public function getTotal($total) {
		$this->load->language('total/credit');
                //print '<pre>';
                //var_dump(debug_backtrace());
                //debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
      //var_dump($total);
      //print '</pre>'; 

		$balance = $this->customer->getBalance();
               /* print '<pre>';
      var_dump($balance);
      print '</pre>'; */

		if ((float)$balance) {
			if ($balance > $total['total']) {
				$credit = $total['total'];
			} else {
				$credit = $balance;
			}
                        /* print '<pre>';
      var_dump($credit);
      print '</pre>'; */

			if ($credit > 0) {
				$total['totals'][] = array(
					'code'       => 'credit',
					'title'      => $this->language->get('text_credit'),
					'value'      => -$credit,
					'sort_order' => $this->config->get('credit_sort_order')
				);

				$total['total'] -= $credit;
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$this->load->language('total/credit');

		if ($order_info['customer_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}
}