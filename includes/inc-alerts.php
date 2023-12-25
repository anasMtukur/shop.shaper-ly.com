<?php if ( $SUCCESS !== '') { ?>
	<div id="success-alert" class="alert alert-success alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class="fa fa-check mr-10"></strong> <?= $SUCCESS ?>
	</div>
<?php } ?>

<?php if ( $INFO !== '') { ?>
	<div id="info-alert" class="alert alert-info alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class="fa fa-info-circle mr-10"></strong> <?= $INFO ?>
	</div>
<?php } ?>

<?php if ( $WARN !== '') { ?>
	<div id="warning-alert" class="alert alert-warning alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class="fa fa-exclamation-triangle mr-10"></strong> <?= $WARN ?>
	</div>
<?php } ?>

<?php if ( $ERROR !== '') { ?>
	<div id="danger-alert" class="alert alert-danger alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class="fa fa-ban mr-10"></strong> <?= $ERROR ?>
	</div>
<?php } ?>