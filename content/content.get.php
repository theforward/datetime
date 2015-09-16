<?php

	/**
	 * @package content
	 */
	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(EXTENSIONS . '/datetime/lib/class.calendar.php');

	class contentExtensionDatetimeGet extends AjaxPage {

		public function handleFailedAuthorisation(){
			$this->setHttpStatus(self::HTTP_STATUS_UNAUTHORIZED);
			$this->_Result = json_encode(array('status' => __('You are not authorised to access this page.')));
		}

		public function view(){
			$this->_Result = Calendar::formatDate(General::sanitize($_GET['date']), General::sanitize($_GET['time']), NULL, true);

			if ($this->_Result  && !is_numeric($_GET['date']) && substr($_GET['date'], -8) !== "12:00 am"){
				$tmpResult = json_decode($this->_Result);
				if ($tmpResult->status == 'valid' && substr($tmpResult->date, -8) == "12:00 am"){
					$tmpResult->date = substr($tmpResult->date, 0 , -8) . '05:00 am';
					$tmpResult->timestamp = $tmpResult->timestamp + 5 * 3600;
					$this->_Result = json_encode($tmpResult);
					// var_dump(substr($tmpResult->date, 0 , -8));die;
				}
			}
		}

		public function generate($page = null){
			header('Content-Type: application/json');
			echo $this->_Result;
			exit;
		}

	}
