<!-- Navbar -->
<nav>
    <ul>
        <li><img src="imgs/Logo_of_the_United_States_Bureau_of_Land_Management.svg" alt="Bureau of Land Management" style="width:32px; height:32px;"></li>
        <li><a href="dashboard.php"><h2>Dashboard</h2></a></li>
        <!-- <li><a href="profile.php">Profile</a></li> -->
        <?php if ($user['role'] == 'admin'): ?>
            <!-- Only show the Employees link if the user is an admin -->
            <li><a href="view_employees.php">Employees</a></li>
        <?php endif; ?>
        <?php if ($user['role'] == 'user'): ?>
            <!-- Only show the Employees link if the user is not an admin -->
            <li><a href="training.php">View Training Assignments</a></li>
        <?php endif; ?>
        <li class="login-button"><a href="logout.php">Logout</a></li>
    </ul>
</nav>