<header class="site__header">
	<div class="site__header-top">
		<div class="site__header-logo"><a href="index.php"><img src="img/logo.png" alt="logo"></a></div>
		<nav class="site__navigation">
			<ul>
				<li><a <?php if ($pageTitle === 'Main') echo "class='current'" ;?> href="index.php">Main</a></li>
				<li><a <?php if ($pageTitle === 'About') echo "class='current'" ;?> href="about.php">About</a></li>
				<li><a <?php if ($pageTitle === 'Contacts') echo "class='current'" ;?> href="contacts.php">Contacts</a></li>     
			</ul>
		</nav>
		
	</div>
	<div> 
		<p class="site__header-date"><?php echo "Today: ".date("D d F Y"); ?></p>
	</div>
	<div class="site__top">
		<div class="site__top_photo"><img src="img/face.png" alt="photo"></div>
		<div class="site__top_greeting">Hi there!</div>
		<div class="site__top_bio">My name is Daniel Bennett. I'm a freelamce front-end developer, author and speaker
			based in Austin,TX. It's nice to meet you.
		</div>
	</div>
</header>