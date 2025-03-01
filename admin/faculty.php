<?php include('db_connect.php');?>

<div class="container-fluid">
<style>
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

@media (max-width: 768px) {
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.875rem;
    }
    
    table td, table th {
        white-space: nowrap;
    }
    
    .card-header {
        flex-direction: column;
    }
    
    .card-header button {
        width: 100%;
        margin-top: 10px;
    }
    
    .table td {
        min-width: 100px;
    }
    
    .table td:last-child {
        min-width: 120px;
    }
}
</style>
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>faculty List</b>
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_faculty">
					<i class="fa fa-plus"></i> New</button>
				</span>
					</div>
					<div class="card-body">
					    <div class="table-responsive">
						<table class="table table-bordered table-condensed table-hover">
							<colgroup>
								<col width="5%">
								<col width="20%">
								<col width="30%">
								<col width="20%">
								<col width="10%">
								<col width="15%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">ID No</th>
									<th class="">Name</th>
									<th class="">Email</th>
									<th class="">Contact</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$faculty =  $conn->query("SELECT *, concat(firstname,' ',lastname) as name from faculty order by concat(firstname,' ',lastname) asc");
								while($row=$faculty->fetch_assoc()):
								?>
								<tr>
									
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p><b><?php echo $row['id_no'] ?></b></p>
										 
									</td>
									<td class="">
										 <p><b><?php echo ucwords($row['name']) ?></b></p>
										 
									</td>
									<td class="">
										 <p><b><?php echo $row['email'] ?></b></p>
									</td>
									<td class="text-right">
										 <p><b><?php echo $row['contact'] ?></b></p>
										 
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary view_faculty" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
										<button class="btn btn-sm btn-outline-primary edit_faculty" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_faculty" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					    </div>
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
		max-height:150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_faculty').click(function(){
		uni_modal("New Entry","manage_faculty.php",'mid-large')
	})
	$('.view_faculty').click(function(){
		uni_modal("Faculty Details","view_faculty.php?id="+$(this).attr('data-id'),'')
		
	})
	$('.edit_faculty').click(function(){
		uni_modal("Manage Job Post","manage_faculty.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.delete_faculty').click(function(){
		_conf("Are you sure to delete this topic?","delete_faculty",[$(this).attr('data-id')],'mid-large')
	})

	function delete_faculty($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_faculty',
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
</script>