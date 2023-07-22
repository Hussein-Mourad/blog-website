 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">

     <!-- Container wrapper -->
     <div class="container">
         <!-- Navbar brand -->
         <a class="navbar-brand" href="index.php">Blogs</a>
         <!-- Toggle button -->
         <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
             <i class="fas fa-bars"></i>
         </button>

         <!-- Collapsible wrapper -->
         <div class="collapse navbar-collapse" id="navbarButtonsExample">
             <!-- Left links -->
             <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 <?php if ($user && $user->getRole() === 'admin') : ?>
                     <li class="nav-item">
                         <a class="nav-link" href="dashboard.php">Dashboard</a>
                     </li>
                 <?php endif; ?>
                 <li class="nav-item">
                     <a class="nav-link" href="index.php">Home</a>
                 </li>

                 <?php if ($user) : ?>
                     <li class="nav-item">
                         <a class="nav-link" href="createPost.php">Add Post</a>
                     </li>
                 <?php endif; ?>

             </ul>
             <!-- Left links -->

             <div class="d-flex align-items-center">
                 <?php if ($user) : ?>
                     <a href="forms/auth/handleLogout.php">
                         <button type="button" class="btn btn-primary px-3 me-2">
                             Logout
                         </button>
                     </a>
                 <?php else : ?>
                     <a href="login.php">
                         <button type="button" class="btn btn-link px-3 me-2">
                             Login
                         </button>
                     </a>
                     <a href="signup.php">
                         <button type="button" class="btn btn-primary me-3">
                             Signup
                         </button>
                     </a>
                 <?php endif; ?>
             </div>
         </div>
         <!-- Collapsible wrapper -->
     </div>
     <!-- Container wrapper -->
 </nav>