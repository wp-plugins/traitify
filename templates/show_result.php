<style>
@font-face{font-family:"Source Sans Pro";font-style:normal;font-weight:400;src:local("Source Sans Pro"),local("Source Sans Pro"),url(https://s3.amazonaws.com/traitify-cdn/assets/fonts/source-sans-pro.woff) format("woff")}@font-face{font-family:"Adelle Sans Bold";font-style:bold;font-weight:800;src:local("Adelle Sans Bold"),url(https://s3.amazonaws.com/traitify-cdn/assets/fonts/adelle-sans-bold.woff) format("woff")}@font-face{font-family:"Adelle Sans";font-style:normal;font-weight:400;src:local("Adelle Sans"),url(https://s3.amazonaws.com/traitify-cdn/assets/fonts/adelle-sans.woff) format("woff")}
</style>
<div class = 'tf-personality-types'>
	<div class = 'personality-types-container-scroller'>
		<div class = 'personality-types-container'>
			<div class = 'personality-types'>
				<?php
				if(isset($result->personality_types))	{
				$i = 0;
				?> <div class="arrow" style="left: 130px;"><div class="icon"></div></div> <?php
				foreach($result->personality_types as $single_personality)	{	?>
					<div data-index="<?php echo $i; ?>" class="personality-type">
						<div class="name" style="color: #<?php echo $single_personality->personality_type->badge->color_1; ?>"> <?php echo $single_personality->personality_type->name ?> </div>
						<img src="<?php echo $single_personality->personality_type->badge->image_medium; ?>" class="badge">
						<div class="score"> <?php echo ceil($single_personality->score); ?> / 100</div>
					</div>
					<?php
						$i = $i + 1;
					}
				} ?>
			</div>
		</div>
		<div class = 'description'> </div>
	</div>
</div>
<?php 
if(isset($result->personality_blend))	{ ?>
<div class = 'tf-results'>
	<div class = 'personality-blend'>
		<div class = 'badges-container'> 
			<?php 
			$blend_count = 1;
			foreach($result->personality_blend as $single_blend)	{ 
				if($blend_count == 1)	{
			?>
				<div class = 'left-badge' style = 'border-color: #<?php echo $single_blend->badge->color_1 ?>;'> <img src = "<?php echo $single_blend->badge->image_medium ?>" class = 'left-badge-image'> </div>
			<?php } 
				else if($blend_count == 2)	{
?>
				<div class = 'right-badge' style = 'border-color: #<?php echo $single_blend->badge->color_1 ?>;'> <img src = "<?php echo $single_blend->badge->image_medium ?>" class = 'left-badge-image'> </div>

			<?php  }
				$blend_count = $blend_count + 1;
			}
			?>
		</div>
		<div class="name"> <?php echo $result->personality_blend->name; ?> </div>
		<div class="blend-description"> <?php echo $result->personality_blend->description; ?></div>
	</div> 
</div>
<?php
}

if(isset($result->personality_traits))	{
	$j = 0; ?>
	
<div class = 'tf-personality-traits'>
	<div class = 'personality-traits'>
	<?php
	foreach($result->personality_traits as $single_trait)	{ //echo '<PrE>';print_r($single_trait);die('trait'); 
		if($j >= 8)	
			break; 

	?>
	<div class = 'trait' style="border-color: #<?php echo $single_trait->personality_trait->personality_type->badge->color_1 ?>">
		<div class = "name"> <?php echo $single_trait->personality_trait->name; ?> </div>
		<div class = "background" style = "background-image: url(<?php echo $single_trait->personality_trait->personality_type->badge->image_medium ?>);"></div>
		<div class = 'definition'> <?php echo $single_trait->personality_trait->definition; ?> </div>
	</div>
	<?php 
	$j = $j + 1;
	}
?>	
	</div>
</div>
<?php } ?>
