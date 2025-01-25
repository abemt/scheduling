<?php include 'db_connect.php' ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Advanced Search</h5>
                </div>
                <div class="card-body">
                    <div class="row">
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
                                    $faculty = $conn->query("SELECT *, CONCAT(firstname, ' ', lastname) as name FROM faculty ORDER BY lastname ASC");
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
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button class="btn btn-primary" id="apply_filters">
                                <i class="fa fa-search"></i> Apply Filters
                            </button>
                            <button class="btn btn-secondary" id="reset_filters">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if(isset($_GET['id'])){
        $qry = $conn->query("SELECT s.*, concat(f.firstname,' ',f.lastname) as faculty_name, r.room_name, r.room_type 
            FROM schedules s 
            LEFT JOIN faculty f ON s.faculty_id = f.id 
            INNER JOIN rooms r ON s.room_id = r.id 
            WHERE s.id=".$_GET['id'])->fetch_array();
        foreach($qry as $k =>$v){
            $$k = $v;
        }
    }
    ?>
    <div class="container-fluid">
        <p>Schedule for: <b><?php echo ucwords($title) ?></b></p>
        <p>Faculty: <b><?php echo $faculty_id == 0 ? "All" : ucwords($faculty_name) ?></b></p>
        <p>Description: <b><?php echo $description ?></b></p>
        <p>Room: </i> <b><?php echo "[".ucwords($room_type)."] ".$room_name ?></b></p>
        <p>Time Start: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_from)) ?></b></p>
        <p>Time End: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_to)) ?></b></p>
        <hr class="divider">
    </div>
    <div class="modal-footer display">
        <div class="row">
            <div class="col-md-12">
                <button class="btn float-right btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn float-right btn-danger mr-2" type="button" id="delete_schedule">Delete</button>
                <button class="btn float-right btn-primary mr-2" type="button" id="edit">Edit</button>
            </div>
        </div>
    </div>
</div>

<style>
    p{
        margin:unset;
    }
    #uni_modal .modal-footer{
        display: none;
    }
    #uni_modal .modal-footer.display {
        display: block;
    }
    .select2-container {
        width: 100% !important;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
        background-color: #007bff;
        color: white;
    }
    .btn {
        margin-right: 0.5rem;
    }
    .btn i {
        margin-right: 0.5rem;
    }
</style>

<script>
    $('#edit').click(function(){
        uni_modal('Edit Schedule','manage_schedule.php?id=<?php echo $id ?>','mid-large')
    })
    $('#delete_schedule').click(function(){
        _conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')])
    })
    
    function delete_schedule($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_schedule',
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

    $(document).ready(function(){
        // Initialize select2
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Handle filter application
        $('#apply_filters').click(function(){
            filterSchedules();
        });

        // Handle filter reset
        $('#reset_filters').click(function(){
            $('#course_filter, #subject_filter, #faculty_search, #room_filter').val('').trigger('change');
            filterSchedules();
        });

        function filterSchedules() {
            var course = $('#course_filter').val();
            var subject = $('#subject_filter').val();
            var faculty = $('#faculty_search').val();
            var room = $('#room_filter').val();

            $.ajax({
                url: 'ajax.php?action=filter_schedules',
                method: 'POST',
                data: {
                    course_id: course,
                    subject_id: subject,
                    faculty_id: faculty,
                    room_id: room
                },
                success: function(response){
                    try {
                        var data = JSON.parse(response);
                        // Clear existing calendar events
                        calendar.removeAllEvents();
                        // Add filtered events
                        calendar.addEventSource(data);
                        
                        // Show success message
                        alert_toast("Filters applied successfully", 'success');
                    } catch(e) {
                        console.error(e);
                        alert_toast("Error applying filters", 'error');
                    }
                },
                error: function(err){
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                }
            });
        }
    });
</script>