<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="#" class="navbar-brand"><b>Voting</b>System</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <?php
            if(isset($_SESSION['student'])){
              echo "
                <li><a href='index.php'>HOME</a></li>
                <li><a href='transaction.php'>TRANSACTION</a></li>
              ";
            } 
          ?>
        </ul>
      </div>
      <!-- /.navbar-collapse -->

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Results link as plain text -->
          <li>
            <a href="voter_results.php">
              <i class="fa fa-bar-chart"></i> Results
            </a>
          </li>

          <!-- Language Switcher -->
          <li>
            <form method="GET" id="langForm" style="margin: 10px;">
              <select name="lang" onchange="this.form.submit()" class="form-control">
                <?php
                $languages = [
                    'en' => 'English',
                    'am' => 'አማርኛ',
                    'om' => 'Oromoo',
                    'ti' => 'ትግርኛ',
                    'so' => 'Soomaali',
                    'aa' => 'Afar',
                    'hd' => 'Hadiyya',
                    'sid' => 'Sidamo',
                    'wal' => 'Wolaytta',
                    'sg' => 'Gurage'
                ];

                foreach($languages as $code => $name){
                    $selected = (isset($_SESSION['lang']) && $_SESSION['lang'] == $code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$name</option>";
                }
                ?>
              </select>
            </form>
          </li>

          <!-- User Menu -->
          <li class="user user-menu">
            <a href="#">
              <img src="<?php echo (!empty($voter['photo'])) ? 'images/'.$voter['photo'] : 'images/profile.jpg' ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $voter['firstname'].' '.$voter['lastname']; ?></span>
            </a>
          </li>

          <!-- Logout -->
          <li>
            <a href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a>
          </li>

        </ul>
      </div>
      <!-- /.navbar-custom-menu -->
    </div>
    <!-- /.container-fluid -->
  </nav>
</header>
