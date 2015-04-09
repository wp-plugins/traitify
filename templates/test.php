<?php
/**
 * Template Name: Test
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

	<div id="wrapper">
	    <div class="row">
	    	
	           	<div id="primary" class="content-area">
					<div id="content" class="site-content" role="main">

						<?php
							// Start the Loop.
							while ( have_posts() ) : the_post();

								// Include the page content template.
								traitify_result( 'ed7774dc-da2a-4a5b-81f1-c9a1811013ed' );

							endwhile;
						?>

					</div><!-- #content -->
				</div><!-- #primary -->
	       

	    </div>
	</div>
</div><!-- #main-content -->

<?php
get_footer();
