<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

	<hr/><!-- Horizontal Line -->

	<header><!-- Work Showcase Section Start -->
		<h1><?= $post->post_title ?></h1><!-- Title of project -->
		<?php $posttag = get_the_tags(); ?>
		<h2><?= $posttag[0]->name ?></h2><!-- Category of project -->
		<!-- Description of project start -->
		<p><?= get_post_meta($post->ID, 'description', true) ?></p>
		<!-- Description of project end -->
	</header>

	<section id="workbody"><!-- Project images start -->
		<?php
		$args = array( 'post_type' => array('work'), 'meta_value' => $post->ID);
		$works = new WP_Query($args);

		if ( $works->have_posts() ) :
			/* Start the Loop */
			while ( $works->have_posts() ) : $works->the_post();?>
				<?php the_content(); ?><!-- Use whatever images you like - they will automatically fit the width of the page -->
				<h5>&ndash; <?php the_title(); ?></h5><!-- Image title -->

			<?php endwhile;
		endif; ?>
		<?php wp_reset_postdata(); ?>
	</section><!-- Project images end -->


	<hr/>	<!-- Horizontal Line -->

	<section id="work"> <!-- Work Links Section Start -->
		<?php
		$args = array( 'post_type' => array('post'),
			'category_name' => 'works',
			'order' => 'ASC' );
		$workLinks = new WP_Query($args);

		if ( $workLinks->have_posts() ) :
			/* Start the Loop */
			while ( $workLinks->have_posts() ) : $workLinks->the_post();?>
				<div class="item">
					<a href="<?php the_permalink(); ?>"><?php remove_filter( 'the_content', 'wpautop' ); the_content(); ?></a><!-- Image must be 400px by 300px -->
					<h3><?php the_title(); ?></h3><!--Title-->
					<?php $posttags = get_the_tags(); ?>
					<p><?= $posttags[0]->name ?></p><!--Category-->
				</div><!--/item-->
				<?php
			endwhile;
		endif; ?>

		<div class="clearfix"></div>
	</section> <!-- Work Links Section End -->


	<section id="bottom"> <!-- Last Words Section Start -->
		<h3>Thanks for looking at my new website!</h3>
	</section><!-- Last Words Section End-->

<?php
get_footer();
