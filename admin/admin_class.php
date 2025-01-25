<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
					return 1;
			}else{
				return 3;
			}
	}
	function login_faculty(){
		
		extract($_POST);		
		$qry = $this->db->query("SELECT *, concat(firstname,' ',lastname) as name FROM faculty where id_no = '".$id_no."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 1;
		}else{
			return 3;
		}
}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_course(){
		extract($_POST);
		$data = " course = '$course' ";
		$data .= ", description = '$description' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO courses set $data");
			}else{
				$save = $this->db->query("UPDATE courses set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_course(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_subject(){
		extract($_POST);
		$data = " subject = '$subject' ";
		$data .= ", description = '$description' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO subjects set $data");
			}else{
				$save = $this->db->query("UPDATE subjects set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subjects where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_faculty(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k=> $v){
			if(!empty($v)){
				if($k !='id'){
					if(empty($data))
					$data .= " $k='{$v}' ";
					else
					$data .= ", $k='{$v}' ";
				}
			}
		}
			if(empty($id_no)){
				$i = 1;
				while($i == 1){
					$rand = mt_rand(1,99999999);
					$rand =sprintf("%'08d",$rand);
					$chk = $this->db->query("SELECT * FROM faculty where id_no = '$rand' ")->num_rows;
					if($chk <= 0){
						$data .= ", id_no='$rand' ";
						$i = 0;
					}
				}
			}

		if(empty($id)){
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM faculty where id_no = '$id_no' ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO faculty set $data ");
		}else{
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM faculty where id_no = '$id_no' and id != $id ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("UPDATE faculty set $data where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function check_schedule_conflict($room_id, $time_from, $time_to, $schedule_date = null, $repeating_data = null, $schedule_id = null) {
        $where = " room_id = '$room_id' ";
        if($schedule_id)
            $where .= " AND id != $schedule_id ";
            
        if($schedule_date){
            $where .= " AND schedule_date = '$schedule_date' AND is_repeating = 0 ";
        } else if($repeating_data) {
            $rdata = json_decode($repeating_data, true);
            $where .= " AND is_repeating = 1 AND (
                JSON_EXTRACT(repeating_data, '$.start') <= '{$rdata['end']}' AND 
                JSON_EXTRACT(repeating_data, '$.end') >= '{$rdata['start']}' AND 
                JSON_EXTRACT(repeating_data, '$.dow') REGEXP '[".$rdata['dow']."]'
            )";
        }
        
        $query = $this->db->query("SELECT s.*, r.room_name 
            FROM schedules s 
            INNER JOIN rooms r ON s.room_id = r.id 
            WHERE $where AND (
                ('$time_from' BETWEEN time_from AND time_to) OR
                ('$time_to' BETWEEN time_from AND time_to) OR
                (time_from BETWEEN '$time_from' AND '$time_to') OR
                (time_to BETWEEN '$time_from' AND '$time_to')
            )");
        
        return $query->num_rows > 0;
    }

    function save_schedule(){
        extract($_POST);
        $data = " faculty_id = '$faculty_id' ";
        $data .= ", title = '$title' ";
        $data .= ", schedule_type = '$schedule_type' ";
        $data .= ", description = '$description' ";
        $data .= ", room_id = '$room_id' ";
        
        // Check for schedule conflicts
        $repeating_json = null;
        if(isset($is_repeating)){
            $data .= ", is_repeating = '$is_repeating' ";
            $rdata = array('dow'=>implode(',', $dow),'start'=>$month_from.'-01','end'=>(date('Y-m-d',strtotime($month_to .'-01 +1 month - 1 day '))));
            $repeating_json = json_encode($rdata);
            $data .= ", repeating_data = '".$repeating_json."' ";
            
            if($this->check_schedule_conflict($room_id, $time_from, $time_to, null, $repeating_json, $id ?? null)) {
                return 2; // Collision detected
            }
        } else {
            $data .= ", is_repeating = 0 ";
            $data .= ", schedule_date = '$schedule_date' ";
            
            if($this->check_schedule_conflict($room_id, $time_from, $time_to, $schedule_date, null, $id ?? null)) {
                return 2; // Collision detected
            }
        }
        
        $data .= ", time_from = '$time_from' ";
        $data .= ", time_to = '$time_to' ";

        if(empty($id)){
            $save = $this->db->query("INSERT INTO schedules set ".$data);
            $schedule_id = $this->db->insert_id;
        }else{
            $save = $this->db->query("UPDATE schedules set ".$data." where id=".$id);
            $schedule_id = $id;
        }
        
        if($save){
            // Save course and subject information
            $this->db->query("DELETE FROM class_schedule_info WHERE schedule_id = $schedule_id");
            $class_data = "INSERT INTO class_schedule_info (schedule_id, course_id, subject_id) VALUES ($schedule_id, '$course_id', '$subject_id')";
            $this->db->query($class_data);
            return 1;
        }
    }

    function filter_schedules(){
        extract($_POST);
        $where = [];
        
        // Build where conditions
        if(!empty($course_id)) {
            $where[] = "csi.course_id = '$course_id'";
        }
        if(!empty($subject_id)) {
            $where[] = "csi.subject_id = '$subject_id'";
        }
        if(!empty($faculty_id)) {
            $where[] = "s.faculty_id = '$faculty_id'";
        }
        if(!empty($room_id)) {
            $where[] = "s.room_id = '$room_id'";
        }

        // Construct WHERE clause
        $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $sql = "SELECT DISTINCT s.*, 
            f.firstname, f.lastname,
            c.course,
            sub.subject as subject_name,
            r.room_name, r.room_type,
            csi.course_id, csi.subject_id
            FROM schedules s 
            LEFT JOIN faculty f ON s.faculty_id = f.id
            LEFT JOIN rooms r ON s.room_id = r.id 
            LEFT JOIN class_schedule_info csi ON s.id = csi.schedule_id
            LEFT JOIN courses c ON csi.course_id = c.id
            LEFT JOIN subjects sub ON csi.subject_id = sub.id
            $where_clause";

        // For debugging
        // error_log("SQL Query: " . $sql);
            
        $qry = $this->db->query($sql);
        
        if(!$qry) {
            // Log error for debugging
            error_log("MySQL Error: " . $this->db->error);
            return array(); // Return empty array if query fails
        }

        $data = array();
        while($row = $qry->fetch_assoc()){
            $schedule = array();
            $schedule['id'] = $row['id'];
            
            // Format title to include course and subject
            $title = $row['title'];
            if(!empty($row['course'])) $title .= " (" . $row['course'] . ")";
            if(!empty($row['subject_name'])) $title .= " - " . $row['subject_name'];
            
            $schedule['title'] = $title;
            $schedule['location'] = $row['room_name'];
            $schedule['description'] = $row['description'];
            
            if($row['is_repeating'] == 1){
                $rdata = json_decode($row['repeating_data'], true);
                if($rdata) {
                    $schedule['daysOfWeek'] = explode(',', $rdata['dow']);
                    $schedule['startTime'] = $row['time_from'];
                    $schedule['endTime'] = $row['time_to'];
                    $schedule['startRecur'] = $rdata['start'];
                    $schedule['endRecur'] = $rdata['end'];
                }
            } else {
                $schedule['start'] = $row['schedule_date'] . 'T' . $row['time_from'];
                $schedule['end'] = $row['schedule_date'] . 'T' . $row['time_to'];
            }
            
            $data[] = $schedule;
        }
        
        return $data;
    }

	function delete_schedule(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM schedules where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_schecdule(){
		extract($_POST);
		$data = array();
		$qry = $this->db->query("SELECT * FROM schedules where faculty_id = 0 or faculty_id = $faculty_id");
		while($row=$qry->fetch_assoc()){
			if($row['is_repeating'] == 1){
				$rdata = json_decode($row['repeating_data']);
				foreach($rdata as $k =>$v){
					$row[$k] = $v;
				}
			}
			$data[] = $row;
		}
			return json_encode($data);
	}
	function delete_forum(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

		if(empty($id)){
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_comments set ".$data);
		}else{
			$save = $this->db->query("UPDATE forum_comments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_comments where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_event(){
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '".htmlentities(str_replace("'","&#x2019;",$content))."' ";
		if($_FILES['banner']['tmp_name'] != ''){
						$_FILES['banner']['name'] = str_replace(array("(",")"," "), '', $_FILES['banner']['name']);
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['banner']['name'];
						$move = move_uploaded_file($_FILES['banner']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", banner = '$fname' ";

		}
		if(empty($id)){

			$save = $this->db->query("INSERT INTO events set ".$data);
		}else{
			$save = $this->db->query("UPDATE events set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_event(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = ".$id);
		if($delete){
			return 1;
		}
	}
	
	function participate(){
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if($commit)
			return 1;

	}

	function check_free_rooms(){
	    extract($_POST);
	    
	    $where = [];
	    $params = [];
	    
	    // Base query for rooms
	    $sql = "SELECT DISTINCT r.* 
	            FROM rooms r 
	            WHERE r.id NOT IN (
	                SELECT s.room_id 
	                FROM schedules s 
	                WHERE 1=1";

	    // Handle repeating schedules
	    if(isset($is_repeating) && $is_repeating == 1) {
	        if(!empty($dow) && !empty($date_from) && !empty($date_to)) {
	            $sql .= " AND (
	                (s.is_repeating = 1 AND 
	                JSON_EXTRACT(s.repeating_data, '$.start') <= ? AND 
	                JSON_EXTRACT(s.repeating_data, '$.end') >= ? AND 
	                JSON_EXTRACT(s.repeating_data, '$.dow') REGEXP ?)";
	            $params[] = $date_to;
	            $params[] = $date_from;
	            $params[] = implode('|', $dow);
	        }
	    } else {
	        // Handle single-day schedules
	        if(!empty($date)) {
	            $sql .= " AND (s.is_repeating = 0 AND s.schedule_date = ?)";
	            $params[] = $date;
	        }
	    }

	    // Time conflict check
	    if(!empty($time_from) && !empty($time_to)) {
	        $sql .= " AND (
	            (? BETWEEN s.time_from AND s.time_to) OR
	            (? BETWEEN s.time_from AND s.time_to) OR
	            (s.time_from BETWEEN ? AND ?) OR
	            (s.time_to BETWEEN ? AND ?)
	        )";
	        $params = array_merge($params, [$time_from, $time_to, $time_from, $time_to, $time_from, $time_to]);
	    }

	    $sql .= ")";
	    $sql .= " ORDER BY r.room_type, r.room_name";

	    // Prepare and execute statement
	    $stmt = $this->db->prepare($sql);
	    if(!empty($params)) {
	        $types = str_repeat('s', count($params));
	        $stmt->bind_param($types, ...$params);
	    }
	    $stmt->execute();
	    $result = $stmt->get_result();

	    $free_rooms = array();
	    while($row = $result->fetch_assoc()) {
	        $free_rooms[] = array(
	            'id' => $row['id'],
	            'name' => $row['room_name'],
	            'type' => $row['room_type']
	        );
	    }

	    return $free_rooms;
	}
}