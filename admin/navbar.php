<style>
	.collapse a{
		text-indent:10px;
	}
	nav#sidebar {
		background: #222d32 !important;
		margin-top: 0;
		padding-top: 60px;  /* Increased padding to prevent overlap */
		min-height: 100vh;
		box-shadow: 2px 0 5px rgba(0,0,0,0.2);
		position: fixed;
		left: 0;
		width: 250px;
		height: 100%;
		transition: all 0.3s ease;
		z-index: 1040;
		overflow-x: hidden; /* Prevent text overflow */
	}

	.sidebar-list {
		margin-top: 0; /* Remove top margin since we added padding to sidebar */
		padding: 0;
		padding-top: 5px; /* Reduced from 10px to 5px */
		width: 100%; /* Ensure full width */
	}

	.sidebar-list a {
		padding: 12px 20px;
		display: flex;
		align-items: center;
		gap: 12px;
		color: #ffffff;
		transition: all 0.3s ease;
		text-decoration: none;
		border-left: 4px solid transparent;
		font-weight: 500;
		background: #222d32;
		white-space: nowrap; /* Prevent text wrapping */
		overflow: hidden; /* Hide overflow text */
		text-overflow: ellipsis; /* Add ellipsis for overflow text */
		width: 100%; /* Ensure full width */
	}

	.sidebar-list a:hover, .sidebar-list a.active {
		background: #222d32;
		border-left-color: #2783d0;
	}

	.icon-field {
		width: 25px;
		text-align: center;
		color: #2783d0;
	}

	@media (max-width: 768px) {
		nav#sidebar {
			margin-top: 0;
			width: 60px;
			transform: translateX(-100%); /* Change from -60px to -100% */
			padding-top: 50px; /* Reduced padding for mobile */
		}
		
		nav#sidebar.active {
			transform: translateX(0);
			width: 200px; /* Wider when active on mobile */
		}

		nav#sidebar.active .sidebar-list a span:not(.icon-field) {
			display: inline; /* Show text when sidebar is active */
		}

		.sidebar-list a {
			padding: 10px 15px;
			justify-content: flex-start; /* Align to left */
		}

		.icon-field {
			width: 20px;
			margin-right: 10px;
		}

		#content {
			margin-left: 0 !important;
		}

		#view-panel {
			margin-left: 0 !important;
			width: 100% !important;
		}

		.sidebar-list {
			padding-top: 10px; /* Reduced from 20px to 10px */
		}

		#sidebarCollapse {
			top: 8px;
			left: 8px;
			z-index: 1051;
		}
	}

	/* Toggle button for mobile */
	#sidebarCollapse {
		display: none;
	}

	@media (max-width: 768px) {
		#sidebarCollapse {
			display: block;
			position: fixed;
			left: 10px;
			top: 10px;
			z-index: 1050;
			border: none;
			background: transparent;
			color: white;
		}
	}
</style>

<button id="sidebarCollapse" class="btn">
	<i class="fa fa-bars"></i>
</button>

<nav id="sidebar" class='mx-lt-5 bg-dark' >
		
		<div class="sidebar-list">
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
				<a href="index.php?page=courses" class="nav-item nav-courses"><span class='icon-field'><i class="fa fa-list"></i></span> Course List</a>
				<a href="index.php?page=subjects" class="nav-item nav-subjects"><span class='icon-field'><i class="fa fa-book"></i></span> Subject List</a>
				<a href="index.php?page=faculty" class="nav-item nav-faculty"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Faculty List</a>
				<a href="index.php?page=schedule" class="nav-item nav-schedule"><span class='icon-field'><i class="fa fa-calendar-day"></i></span> Schedule</a>
				<?php if($_SESSION['login_type'] == 1): ?>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
			<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')

	// Add mobile sidebar toggle
	$('#sidebarCollapse').click(function() {
		$('#sidebar').toggleClass('active');
	});

	// Improve mobile sidebar behavior
	$(document).ready(function() {
		// Close sidebar when clicking outside
		$(document).click(function(e) {
			if (!$(e.target).closest('#sidebar, #sidebarCollapse').length) {
				$('#sidebar').removeClass('active');
			}
		});

		// Prevent clicks inside sidebar from closing it
		$('#sidebar').click(function(e) {
			e.stopPropagation();
		});

		// Close sidebar when menu item is clicked on mobile
		if($(window).width() <= 768) {
			$('.nav-item').click(function() {
				$('#sidebar').removeClass('active');
			});
		}
	});
</script>
