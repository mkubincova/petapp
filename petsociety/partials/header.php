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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link href="css/cropper.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/emojionearea.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,700;1,800&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet" type="text/css">

</head>

<body>
    <header>
        <div>
            <a href="index.php"><img src="img/logo/logo-big.svg" id="logo"></a>
        </div>
        <?php if ($_SESSION) { ?>
            <nav id="topnav">
                <ul>
                    <li><a href="mypets.php" class="<?php echo ($currentPage == 'mypets.php') ? 'active' : ''; ?>">My Pets</a></li>
                    <li><a href="petprofiles.php" class="<?php echo ($currentPage == 'petprofiles.php') ? 'active' : ''; ?>">Browse Pets</a></li>
                    <li><a href="feed.php" class="<?php echo ($currentPage == 'feed.php') ? 'active' : ''; ?>">Feed</a></li>
                    <li><a href="animalcare.php" class="<?php echo ($currentPage == 'animalcare.php') ? 'active' : ''; ?>">Animal Care</a></li>
                    <li><a href="account.php" class="<?php echo ($currentPage == 'account.php') ? 'active' : ''; ?>"><img class='nav-icon' src='img/icons/account-purple.png'></a></li>
                </ul>
            </nav>
        <?php } ?>
    </header>
    <div id="pagecontainer">