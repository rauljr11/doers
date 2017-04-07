<section class="row">
	<div class="center">
		<h1>Welcome</h1>
		<strong class="subHeading"><?php echo $user->username; ?></strong>
		<strong class="subHeading">This is you dashboard</strong>
		<div class="buttons">
			<a href="<?php echo $this->url(['Users::logout']);?>" class="btn btnGreen" ><span>Logout</span></a>
		</div>
	</div>
</section>