<?php
include 'configurations/config.php';
include 'configurations/db-connection.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pet Society</title>
    <meta charset="utf-8" />
    <meta description="Social media for pet owners" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header>
        <div>
            <!-- <img src="img/logo.png" id="logo"> -->
            <p>logo</p>
        </div>
        <?php if ($_SESSION) { ?>
            <nav id="topnav">
                <ul>
                    <li><a href="mypets.php" class="<?php echo ($currentPage == 'mypets.php') ? 'active' : ''; ?>">My Pets</a></li>
                    <li><a href="petprofiles.php" class="<?php echo ($currentPage == 'petprofiles.php') ? 'active' : ''; ?>">Browse Pets</a></li>
                    <li><a href="feed.php" class="<?php echo ($currentPage == 'feed.php') ? 'active' : ''; ?>">Feed</a></li>
                    <li><a href="animalcare.php" class="<?php echo ($currentPage == 'animalcare.php') ? 'active' : ''; ?>">Animal Care</a></li>
                    <li><a href="account.php" class="<?php echo ($currentPage == 'account.php') ? 'active' : ''; ?>">Account</a></li>
                </ul>
            </nav>
        <?php } ?>
    </header>
    <div id="pagecontainer">