<body class="has-navbar-fixed top">
    <!-- BEGIN PAGE HEADER -->
    <header class="container">

        <!-- BEGIN MAIN NAV -->
        <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="index.php">
                    <span class="icon-text">
                        <span class="icon">
                           <i class="fas fa-home"></i>
                        </span>
                        <span>&nbsp;<?= $siteName ?></span>
                    </span>
                </a>
                <a class="navbar-burger" aria-label="menu" aria-expanded="false">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div class="navbar-menu">
                <div class="navbar-start">
                    <!-- BEGIN ADMIN MENU -->
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['user_role'] == 'admin') : ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <span class="icon">
                                    <i class="fas fa-user-cog"></i>
                                </span>
                                <span>Admin</span>
                            </a>
                            <div class="navbar-dropdown">
                                <a href="admin_dashboard.php" class="navbar-item">
                                    Dashboard
                                </a>
                                <a href="users_manage.php" class="navbar-item">
                                    Manage Users
                                </a>
                                <a href="articles.php" class="navbar-item">
                                    Manage Articles
                                 </a>
                                 <a href="tickets.php" class="navbar-item">
                                    Manage Tickets
                                </a>
                                
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- END ADMIN MENU -->
                </div>
                <div class="navbar-end mr-5">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="contact.php">Contact us</a>

                            <?php if (isset($_SESSION['loggedin'])) : ?>
                        <a class="button is-light" href="ticket_create.php">
                            <strong>Support</strong>
                        </a>
                        <?php endif; ?>

                            <!-- BEGIN USER MENU -->
                            <?php if (isset($_SESSION['loggedin'])) : ?>
                                <div class="navbar-item has-dropdown is-hoverable">
                                    <a class="button navbar-link">
                                        <span class="icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </a>
                                    <div class="navbar-dropdown">
                                        <a href="profile.php" class="navbar-item">Profile</a>
                                        <hr class="navbar-divider">
                                        <a href="logout.php" class="navbar-item">Logout</a>
                                    </div>
                                </div>
                            <?php else : ?>
                                <a href="login.php" class="button is-link">Login</a>
                            <?php endif; ?>
                            <!-- END USER MENU -->
                        </div>
                    </div>
                        
                </div>
            </div>
        </nav>
        <!-- END MAIN NAV -->
        <section class="block">&nbsp;<!--only for spacing purposes--></section>
        <section class="block">&nbsp;<!--only for spacing purposes--></section>
       
        <?php if ($_SERVER['PHP_SELF'] == '/index.php') : ?>
            <!-- BEGIN HERO -->
  <section class="hero is-info">
    <div class="hero-body">
      <p class="title">
            Find peace through words
      </p>
      <p class="subtitle">
      Wanting peace? Find them throught the words of others.
      </p>
      <a href="contact.php" class="button is-medium is-info is-light is-rounded">
        <span class="icon is-large">
          <i class="fab fa-2x fa-pagelines"></i>
        </span>
        <span>Share your thoughts...</span>
      </a>
    </div>
  </section>
<!-- END HERO -->
        <?php endif; ?>

        <?php if (!empty($_SESSION['messages'])) : ?>
            <section class="notification is-warning">
                <button class="delete"></button>
                <?php echo implode('<br>', $_SESSION['messages']);
                $_SESSION['messages'] = []; // Clear the user responses?>
            </section>
        <?php endif; ?>

    </header>
    <!-- END PAGE HEADER -->

    <!-- BEGIN MAIN PAGE CONTENT -->
    <main class="container">