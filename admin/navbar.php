<style>
	.collapse a{
		text-indent:10px;
	}
	nav#sidebar {
		background: #222d32 !important;
		margin-top: 0;  /* Changed from 60px to 0 */
		padding-top: 1rem;
		min-height: 100vh;  /* Changed from calc(100vh - 60px) to 100vh */
		box-shadow: 2px 0 5px rgba(0,0,0,0.2);
		position: fixed;
		left: 0;
		width: 250px;
		height: 100%;
		transition: all 0.3s ease;
		z-index: 1040;
	}

	.sidebar-list {
		margin-top: 60px; /* Added to offset the fixed topbar */
		padding: 0;
		padding-top: 70px;
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
			transform: translateX(-60px);
		}
		
		nav#sidebar.active {
			transform: translateX(0);
		}

		.sidebar-list a span:not(.icon-field) {
			display: none;
		}

		.sidebar-list a {
			padding: 8px 5px;
			justify-content: center;
		}

		.icon-field {
			margin: 0;
		}

		#content {
			margin-left: 0 !important;
		}

		#view-panel {
			margin-left: 0 !important;
			width: 100% !important;
		}

		.sidebar-list {
			padding-top: 60px;
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
</script>
