<?php include 'configurations/config.php';?>

<header>
    <div>
        <!-- <img src="img/logo.png" id="logo"> -->
    </div>
    <nav id="topnav">
        <ul>
            <li><a href="mypets.php" class="<?php echo($currentPage == 'mypets.php') ? 'active' : '';?>">My Pets</a></li>
            <li><a href="browse.php" class="<?php echo($currentPage == 'browse.php') ? 'active' : '';?>">Browse Pets</a></li>
            <li><a href="feed.php" class="<?php echo($currentPage == 'feed.php') ? 'active' : '';?>">Feed</a></li>
            <li><a href="animalcare.php" class="<?php echo($currentPage == 'animalcare.php') ? 'active' : '';?>">Animal Care</a></li>
            <li><a href="account.php" class="<?php echo($currentPage == 'account.php') ? 'active' : '';?>">Account</a></li>                                
        </ul>
    </nav>
</header>