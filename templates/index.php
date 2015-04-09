<div class="container">
	<div class="col-lg-5 col-sm-6 text-center">
    		<div class="well">
        		<h3>Short codes</h3>
    			<hr data-brackets-id="12673">
    			<ul data-brackets-id="12674" id="sortable" class="list-unstyled ui-sortable">
			<?php foreach($traitify->types as $type_key => $type_value)	{ ?>
				<li class="ui-state-default"> 
					<strong class = "pull-left primary-font"> <?php echo $type_value; ?> </strong> 
					<strong class = 'pull-right'> [<?php echo $traitify->shortcode ?> type="<?php echo $type_key; ?>"] </strong> 
				</li>
				<br> <br>
			<?php } ?>
    			</ul>
    		</div>
    	</div>
    	<div class="col-lg-8 col-sm-6 text-center">
    		<div class="well">
        		<h3>Codes</h3>
    			<hr data-brackets-id="12673">
    			<ul data-brackets-id="12674" id="sortable" class="list-unstyled ui-sortable">
			<?php foreach($traitify->types as $type_key => $type_value)	{ ?>
				<li class="ui-state-default"> 
					<strong class = "pull-left primary-font"> <?php echo $type_value; ?> </strong> 
					<strong class = 'pull-right '> if(class_exists('wp_traitify')) {  wp_traitify::traitify_show_questions('<?php echo $type_key ?>'); } </strong>
				</li>
				<br> <br>
			<?php } ?>
    			</ul>
    		</div>
    	</div>
</div>
