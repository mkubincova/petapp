<!-- if statement to display correct home page (logged in or not) -->

<!doctype html>
<html>
	<?php 
        include 'configurations/config.php';
        include 'configurations/db-connection.php';
        include 'partials/head.php;';
    ?>
    <header>
        <div>
            <!-- <img src="img/logo.png" id="logo"> -->
        </div>
    </header>
    <body>
        <div id="pagecontainer">
			<main>
                <h1>Welcome text</h1>
                <button>Log in</button>
                <button>Register</button>

                <h3>Info about website</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			</main>
			<?php include 'partials/footer.php';?>
		</div>
    </body>
</html>