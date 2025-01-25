<?php include('db_connect.php');?>
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Schedule</b>
						<span class="float:right"><button class="btn btn-primary btn-block btn-sm col-sm-2 float-right"  id="new_schedule">
					<i class="fa fa-plus"></i> New Entry
				</button></span>
					</div>
					<div class="card-body">
						<div class="row mb-4">
							<div class="col-md-3">
								<div class="form-group">
									<label for="course_filter">Course:</label>
									<select class="form-control select2" id="course_filter">
										<option value="">All Courses</option>
										<?php 
										$courses = $conn->query("SELECT * FROM courses ORDER BY course ASC");
										while($row = $courses->fetch_assoc()):
										?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['course'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="subject_filter">Subject:</label>
									<select class="form-control select2" id="subject_filter">
										<option value="">All Subjects</option>
										<?php 
										$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject ASC");
										while($row = $subjects->fetch_assoc()):
										?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['subject'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="faculty_search">Faculty:</label>
									<select class="form-control select2" id="faculty_search">
										<option value="">All Faculty</option>
										<?php 
										$faculty = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM faculty order by concat(firstname,' ',lastname) asc");
										while($row = $faculty->fetch_assoc()):
										?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="room_filter">Room:</label>
									<select class="form-control select2" id="room_filter">
										<option value="">All Rooms</option>
										<?php 
										$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_type, room_name ASC");
										while($row = $rooms->fetch_assoc()):
										?>
										<option value="<?php echo $row['id'] ?>"><?php echo "[".ucwords($row['room_type'])."] ".$row['room_name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-md-12">
								<button class="btn btn-primary" id="apply_filters">
									<i class="fa fa-search"></i> Apply Filters
								</button>
								<button class="btn btn-secondary" id="reset_filters">
									<i class="fa fa-refresh"></i> Reset
								</button>
							</div>
						</div>
						<hr>
						<div id="calendar"></div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: 150px;
	}
	.avatar {
	    display: flex;
	    border-radius: 100%;
	    width: 100px;
	    height: 100px;
	    align-items: center;
	    justify-content: center;
	    border: 3px solid;
	    padding: 5px;
	}
	.avatar img {
	    max-width: calc(100%);
	    max-height: calc(100%);
	    border-radius: 100%;
	}
		input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
a.fc-daygrid-event.fc-daygrid-dot-event.fc-event.fc-event-start.fc-event-end.fc-event-past {
    cursor: pointer;
}
a.fc-timegrid-event.fc-v-event.fc-event.fc-event-start.fc-event-end.fc-event-past {
    cursor: pointer;
}

/* Responsive calendar */
@media (max-width: 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.2em;
    }
    
    .fc .fc-button-group {
        margin: 5px 0;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .col-md-3 {
        margin-bottom: 15px;
    }
    
    #calendar {
        height: auto !important;
    }
}
</style>

<script>
	
	$('#new_schedule').click(function(){
		uni_modal('New Schedule','manage_schedule.php','mid-large')
	})
	$('.view_alumni').click(function(){
		uni_modal("Bio","view_alumni.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.delete_alumni').click(function(){
		_conf("Are you sure to delete this alumni?","delete_alumni",[$(this).attr('data-id')])
	})
	
	function delete_alumni($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_alumni',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	var calendarEl = document.getElementById('calendar');
	var calendar;
	document.addEventListener('DOMContentLoaded', function() {
		calendar = new FullCalendar.Calendar(calendarEl, {
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			},
			initialDate: '<?php echo date('Y-m-d') ?>',
			weekNumbers: true,
			navLinks: true, // can click day/week names to navigate views
			editable: false,
			selectable: true,
			nowIndicator: true,
			dayMaxEvents: true, // allow "more" link when too many events
			height: 'auto',
			expandRows: true,
			handleWindowResize: true,
			events: []
		});
		calendar.render();
	});

	// Handle filter application
	$('#apply_filters').click(function(){
		filterSchedules();
	});

	// Handle filter reset
	$('#reset_filters').click(function(){
		$('#course_filter, #subject_filter, #faculty_search, #room_filter').val('').trigger('change.select2');
		filterSchedules();
	});

	function filterSchedules() {
		start_load();
		$.ajax({
			url: 'ajax.php?action=filter_schedules',
			method: 'POST',
			data: {
				course_id: $('#course_filter').val(),
				subject_id: $('#subject_filter').val(),
				faculty_id: $('#faculty_search').val(),
				room_id: $('#room_filter').val()  // Add room filter
			},
			success: function(response){
				try {
					var events = JSON.parse(response);
					calendar.removeAllEvents();
					calendar.addEventSource(events);
					end_load();
				} catch(e) {
					console.error('Error parsing response:', e);
					alert_toast("Error applying filters", 'error');
					end_load();
				}
			},
			error: function(err){
				console.error('AJAX error:', err);
				alert_toast("An error occurred", 'error');
				end_load();
			}
		});
	}

	$(document).ready(function(){
		// Initialize calendar with all events
		filterSchedules();
		
		// Initialize select2
		$('.select2').select2({
			placeholder: "Select an option",
			allowClear: true
		});

		// Handle filter changes
		$('#course_filter, #subject_filter, #faculty_search, #room_filter').change(function(){
			filterSchedules();
		});
	});
</script>