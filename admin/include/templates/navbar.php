<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand d-flex" href="profile.php">
            <div class="imageUser"><img src="
            <?php
            $stmt = $con->prepare('SELECT * FROM users WHERE name = ?');
            $stmt->execute([$_SESSION['name'],]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo $user['image'];
            ?>

            " alt=""></div>
            <?php echo explode(' ', ucwords($_SESSION['name']))[0]; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">Reports</a>
                </li>
                <li class="nav-item btn btn-danger p-0 ">
                    <a class="nav-link" href="logout.php">log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>