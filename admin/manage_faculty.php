<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM faculty where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage-faculty" onsubmit="return validateForm()">
		<div id="msg"></div>
				<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']:'' ?>" class="form-control">
		<div class="row form-group">
			<div class="col-md-4">
						<label class="control-label">ID No.</label>
						<input type="text" name="id_no" class="form-control" value="<?php echo isset($id_no) ? $id_no:'' ?>" >
						<small><i>Leave this blank if you want to a auto generate ID no.</i></small>
					</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label class="control-label">First Name</label>
				<input type="text" name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname:'' ?>" required>
			</div>
			<div class="col-md-6">
				<label class="control-label">Last Name</label>
				<input type="text" name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname:'' ?>" required>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label class="control-label">Email</label>
				<input type="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
					oninvalid="this.setCustomValidity('Please enter a valid email address')"
					oninput="this.setCustomValidity('')"
					value="<?php echo isset($email) ? $email:'' ?>" required>
			</div>
			<div class="col-md-3">
				<label class="control-label">Contact #</label>
				<input type="text" name="contact" class="form-control" pattern="[0-9]+" minlength="10" maxlength="11"
					title="Please enter a valid phone number" value="<?php echo isset($contact) ? $contact:'' ?>" required>
			</div>
			<div class="col-md-3">
				<label class="control-label">Gender</label>
				<select name="gender" required="" class="custom-select" id="">
					<option <?php echo isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
					<option <?php echo isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
				<label class="control-label">Address</label>
				<textarea name="address" class="form-control"><?php echo isset($address) ? $address : '' ?></textarea>
			</div>
		</div>
	</form>
</div>

<script>
function validateForm() {
    // Get all form values
    const formInputs = {
        firstname: document.querySelector('input[name="firstname"]').value.trim(),
        lastname: document.querySelector('input[name="lastname"]').value.trim(),
        email: document.querySelector('input[name="email"]').value.trim(),
        contact: document.querySelector('input[name="contact"]').value.trim(),
        address: document.querySelector('textarea[name="address"]').value.trim(),
        gender: document.querySelector('select[name="gender"]').value
    };

    // Check if any field is empty
    for (const [key, value] of Object.entries(formInputs)) {
        if (!value) {
            alert_toast(`${key.charAt(0).toUpperCase() + key.slice(1)} is required`, 'error');
            return false;
        }
    }

    // Email validation with strict regex
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(formInputs.email)) {
        alert_toast('Please enter a valid email address', 'error');
        return false;
    }

    // Phone number validation (10-11 digits only)
    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(formInputs.contact)) {
        alert_toast('Please enter a valid phone number (10-11 digits)', 'error');
        return false;
    }

    // Address minimum length validation
    if (formInputs.address.length < 5) {
        alert_toast('Please enter a complete address', 'error');
        return false;
    }

    // If all validations pass, proceed with form submission
    start_load();
    $.ajax({
        url: 'ajax.php?action=save_faculty',
        method: 'POST',
        data: $('#manage-faculty').serialize(),
        success: function(resp) {
            if(resp == 1) {
                alert_toast("Data successfully saved.", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else if(resp == 2) {
                $('#msg').html('<div class="alert alert-danger">ID No already existed.</div>');
                end_load();
            }
        }
    });
    return false;
}

// Prevent non-numeric input for contact field
document.querySelector('input[name="contact"]').addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
    }
});

// Remove original form submit handler since we're using onsubmit="return validateForm()"
</script>