<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM schedules where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
if(!empty($repeating_data)){
$rdata= json_decode($repeating_data);
	foreach($rdata as $k => $v){
		 $$k = $v;
	}
	$dow_arr = isset($dow) ? explode(',',$dow) : '';
	// var_dump($start);
}
}
?>
<style>
</style>
<div class="container-fluid">
	<form action="" id="manage-schedule">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-16">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Faculty</label>
						<select name="faculty_id" id="" class="custom-select select2">
							<option value="0">All</option>
						<?php 
							$faculty = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM faculty order by concat(firstname,' ',lastname) asc");
							while($row= $faculty->fetch_array()):
						?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Title</label>
						<textarea class="form-control" name="title" cols="30" rows="3"><?php echo isset($title) ? $title : '' ?></textarea>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Schedule Type</label>
						<select name="schedule_type" id="" class="custom-select">
							<option value="1" <?php echo isset($schedule_type) && $schedule_type == 1 ? 'selected' : ''  ?>>Class</option>
							<option value="2" <?php echo isset($schedule_type) && $schedule_type == 2 ? 'selected' : ''  ?>>Meeting</option>
							<option value="3" <?php echo isset($schedule_type) && $schedule_type == 3 ? 'selected' : ''  ?>>Others</option>
						</select>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Description</label>
						<textarea class="form-control" name="description" cols="30" rows="3"><?php echo isset($description) ? $description : '' ?></textarea>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Room</label>
						<div class="input-group">
							<select name="room_id" id="room_id" class="custom-select select2" required>
								<option value="">Select Room</option>
								<?php 
								$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_type, room_name");
								while($row = $rooms->fetch_array()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($room_id) && $room_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo "[".ucwords($row['room_type'])."] ".$row['room_name'] ?>
								</option>
								<?php endwhile; ?>
							</select>
							<div class="input-group-append">
								<button class="btn btn-success" type="button" id="check_free_rooms">
									<i class="fa fa-search"></i> Find Free Rooms
								</button>
							</div>
						</div>
						<div id="free_rooms_result" class="mt-2"></div>
					</div>
					<div class="form-group">
						<label for="course_id" class="control-label">Course</label>
						<select name="course_id" id="course_id" class="custom-select select2" required>
							<option value="">Select Course</option>
							<?php 
							$courses = $conn->query("SELECT * FROM courses ORDER BY course ASC");
							while($row = $courses->fetch_array()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($course_id) && $course_id == $row['id'] ? 'selected' : '' ?>>
								<?php echo $row['course'] ?>
							</option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="subject_id" class="control-label">Subject</label>
						<select name="subject_id" id="subject_id" class="custom-select select2" required>
							<option value="">Select Subject</option>
							<?php 
							$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject ASC");
							while($row = $subjects->fetch_array()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($subject_id) && $subject_id == $row['id'] ? 'selected' : '' ?>>
								<?php echo $row['subject'] ?>
							</option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="1" id="is_repeating" name="is_repeating" <?php echo isset($is_repeating) && $is_repeating != 1 ? '' : 'checked' ?>>
						  <label class="form-check-label" for="type">
						   	Weekly Schedule
						  </label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group for-repeating">
						<label for="dow" class="control-label">Days of Week</label>
						<select name="dow[]" id="dow" class="custom-select select2" multiple="multiple">
							<?php 
							$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
							for($i = 0; $i < 7;$i++):
							?>
							<option value="<?php echo $i ?>"  <?php echo isset($dow_arr) && in_array($i,$dow_arr) ? 'selected' : ''  ?>><?php echo $dow[$i] ?></option>
						<?php endfor; ?>
						</select>
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month From</label>
						<input type="month" name="month_from" id="month_from" class="form-control" value="<?php echo isset($start) ? date("Y-m",strtotime($start)) : '' ?>">
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month To</label>
						<input type="month" name="month_to" id="month_to" class="form-control" value="<?php echo isset($end) ? date("Y-m",strtotime($end)) : '' ?>">
					</div>
					<div class="form-group for-nonrepeating" style="display: none">
						<label for="" class="control-label">Schedule Date</label>
						<input type="date" name="schedule_date" id="schedule_date" class="form-control" value="<?php echo isset($schedule_date) ? $schedule_date : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time From</label>
						<input type="time" name="time_from" id="time_from" class="form-control" value="<?php echo isset($time_from) ? $time_from : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time To</label>
						<input type="time" name="time_to" id="time_to" class="form-control" value="<?php echo isset($time_to) ? $time_to : '' ?>">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
	</div>
<script>
	if('<?php echo isset($id) ? 1 : 0 ?>' == 1){
		if($('#is_repeating').prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	}
	$('#is_repeating').change(function(){
		if($(this).prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	})
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	$('#manage-schedule').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_schedule',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}else if(resp==2){
					Swal.fire({
						icon: 'error',
						title: 'Schedule Conflict',
						text: 'The selected room is already booked for this time period!',
						confirmButtonText: 'OK'
					});
					end_load()
				}
			},
			error:function(err){
				console.log(err)
				alert_toast("An error occurred",'error')
				end_load()
			}
		})
	})
	
	$('#check_free_rooms').click(function(){
    var time_from = $('#time_from').val();
    var time_to = $('#time_to').val();
    
    if(!time_from || !time_to) {
        alert_toast("Please select time range first", 'warning');
        return;
    }
    
    var data = {
        time_from: time_from,
        time_to: time_to
    };
    
    if($('#is_repeating').is(':checked')){
        var dow = $('#dow').val();
        var month_from = $('#month_from').val();
        var month_to = $('#month_to').val();
        
        if(!dow || !month_from || !month_to) {
            alert_toast("Please complete schedule details first", 'warning');
            return;
        }
        
        data.is_repeating = 1;
        data.dow = dow;
        data.date_from = month_from + '-01';
        data.date_to = month_to + '-' + new Date(month_to.split('-')[0], month_to.split('-')[1], 0).getDate();
    } else {
        var schedule_date = $('#schedule_date').val();
        if(!schedule_date) {
            alert_toast("Please select schedule date first", 'warning');
            return;
        }
        data.date = schedule_date;
    }
    
    start_load();
    $.ajax({
        url: 'ajax.php?action=check_free_rooms',
        method: 'POST',
        data: data,
        success: function(response){
            end_load();
            try {
                var rooms = JSON.parse(response);
                var html = '';
                
                if(rooms.length > 0){
                    html += '<div class="alert alert-success">Available Rooms:</div>';
                    html += '<div class="list-group">';
                    rooms.forEach(function(room){
                        html += '<a href="#" class="list-group-item list-group-item-action select-room" data-id="'+room.id+'">';
                        html += '['+room.type+'] '+room.name;
                        html += '</a>';
                    });
                    html += '</div>';
                } else {
                    html = '<div class="alert alert-warning">No free rooms found for the selected time period.</div>';
                }
                
                $('#free_rooms_result').html(html);
            } catch(e) {
                console.error(e);
                alert_toast("Error processing response", 'error');
            }
        },
        error: function(err){
            end_load();
            console.error(err);
            alert_toast("An error occurred", 'error');
        }
    });
});

	$(document).on('click', '.select-room', function(e){
		e.preventDefault();
		var room_id = $(this).data('id');
		$('#room_id').val(room_id).trigger('change');
		$('#free_rooms_result').html('');
	});
</script>