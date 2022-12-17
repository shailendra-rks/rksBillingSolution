<?php
	/* redirect to login page*/
	function send_home($msg)
	{	
		session_start();
		$_SESSION['internalMsg'] = $msg;
		$host = $_SERVER['HTTP_HOST'];
		/*$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');*/
		$extra = 'index.php';
		header("Location: http://$host/$extra");								//address tag
	}
	
	/* redirect to Dashboard*/
	function send_dashboard($msg)
	{	
		session_start();
		$_SESSION['internalMsg'] = $msg;
		$host = $_SERVER['HTTP_HOST'];
		/*$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');*/
		$extra = 'dashboard.php';
		header("Location: http://$host/php/$extra");								//address tag
	}
	
	function send_to($msg)
	{	
		$host = $_SERVER['HTTP_HOST'];
		/*$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');*/
		header("Location: http://$host/php/$msg");								//address tag
	}
	
	/* return status html markup */
	function status_html($value)
	{
		if($value == 1){
			return "<td><i class='bx bx-check'></i></td>";
		}
		elseif($value == 0){
			return "<td><i class='bx bx-x'></i></td>";
		}
	}
	
	function test_input($data) {
		$data = strip_tags($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	function billEditPermission($billDate, $type){
		$edit_status = "";
		
		$curr_date_s = strtotime(date("Y-m-d"));

		$curr_year = date('Y', $curr_date_s);
		$bill_year = date('Y', $billDate);

		$curr_month = date('m', $curr_date_s);
		$bill_month = date('m', $billDate);
		
		
		//Monthly ITR filing before 20th.
		if($type == "A"){
			$month_diff = (($curr_year - $bill_year) * 12) + ($curr_month - $bill_month);
			if($month_diff < 1){
				$edit_status = "true";
			}
			elseif($month_diff == 1){
				if(date("d") > 20){
					$edit_status = "false";
				}
				else{
					$edit_status = "true";
				}
			}
			else{
				$edit_status = "false";
			}
		}		
		return $edit_status;
	}
	
	function log_msg($log_msg) {
		$usrParam = unserialize($_SESSION['usrParam']);
		date_default_timezone_set("Asia/Calcutta");
		$log_line = date("H:i:s")."|".$usrParam['usrId']."|".$usrParam['usrType']."|".$log_msg."|".$_SERVER['REMOTE_ADDR']."\n";
		$log_filename = $_SERVER['DOCUMENT_ROOT']."/log";                                               //address tag
		if (!file_exists($log_filename)){
			// create directory/folder uploads.
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
		file_put_contents($log_file_data, $log_line, FILE_APPEND);
	}

?>