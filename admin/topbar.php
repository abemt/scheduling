<style>
.navbar {
  padding: 0.5rem 1rem;
  background: #2783d0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  height: 60px; /* Fixed height */
}

.navbar-container {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.brand-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.logo-img {
  width: 45px;
  height: 45px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
  object-fit: cover;
}

.logo-img:hover {
  transform: scale(1.05);
}

.title-container {
  display: flex;
  flex-direction: column;
}

.system-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: #ffffff;
  margin: 0;
}

.sub-title {
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.85);
  margin: 0;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-dropdown {
  position: relative;
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.user-dropdown:hover {
  background: rgba(255, 255, 255, 0.15);
  border-color: rgba(255, 255, 255, 0.2);
}

.dropdown-toggle {
  color: #ffffff;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.dropdown-toggle:hover {
  color: #ffffff;
  text-decoration: none;
}

.dropdown-menu {
  margin-top: 0.5rem;
  border: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 6px;
  background: #ffffff;
}

.dropdown-item {
  padding: 0.7rem 1rem;
  display: flex;
  align-items: center;
  gap: 0.7rem;
  transition: all 0.2s ease;
  color: #222d32;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #2783d0;
}

.dropdown-item i {
  color: #2783d0;
}

@media (max-width: 768px) {
  .sub-title {
    display: none;
  }
  
  .system-name {
    font-size: 1rem;
  }
  
  .logo-img {
    width: 40px;
    height: 40px;
  }

  .navbar {
    padding: 0.3rem;
    height: 50px; /* Smaller height on mobile */
    padding: 0.3rem 0.5rem;
  }

  .brand-section {
    margin-left: 60px; /* Increased space for sidebar toggle */
  }

  .logo-container {
    gap: 0.4rem;
  }

  .logo-img {
    width: 35px;
    height: 35px;
  }

  .system-name {
    font-size: 0.9rem;
    max-width: 200px; /* Prevent text overflow */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .sub-title, 
  .user-dropdown span:not(.fa) {
    display: none;
  }

  .user-dropdown {
    padding: 0.3rem 0.6rem;
  }

  .dropdown-toggle {
    gap: 0;
  }

  .dropdown-menu {
    left: auto !important;
    right: 0 !important;
  }
}
</style>

<nav class="navbar fixed-top">
  <div class="navbar-container">
    <div class="brand-section">
      <div class="logo-container">
        <img src="./assets/img/Untitled-1-13.png" alt="Logo" class="logo-img">
        <div class="title-container">
          <h1 class="system-name">Faculty Scheduler</h1>
          <p class="sub-title">School Faculty Scheduling System</p>
        </div>
      </div>
    </div>
    
    <div class="user-section">
      <div class="user-dropdown">
        <a href="#" class="dropdown-toggle" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-user-circle"></i>
          <span><?php echo $_SESSION['login_name'] ?></span>
          <i class="fa fa-chevron-down" style="font-size: 0.8rem;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account_settings">
          <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account">
            <i class="fa fa-cog"></i> Manage Account
          </a>
          <a class="dropdown-item" href="ajax.php?action=logout">
            <i class="fa fa-power-off"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>