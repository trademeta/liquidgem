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

    <header>	<!-- Header Title Start -->
        <?php $user_info = get_userdata(1); ?>
            <h1>Hello there, I'm <span>&quot;<?= $user_info->display_name ?>&quot;</span>. Welcome to my design portfolio!</h1>
            <h2>&ndash; Photographer and Web Developer &ndash;</h2>
    </header>	<!-- Header Title End -->

	<section id="slideshow">	<!-- Slideshow Start -->
        <div class="html_carousel">
			<div id="slider">

				<?php $slider = new WP_Query(['post_type' => 'slide']); ?>
				<?php if ($slider->have_posts()) :  while ($slider->have_posts()) : $slider->the_post(); ?>
					<div class="slide">
                        <?php remove_filter( 'the_content', 'wpautop' ); ?>
						<?php the_content(); ?>
					</div><!--/slide-->
				<?php endwhile; ?>
				<?php else: ?>
					<p>Место для слайдера</p>
				<?php endif; ?>

			</div><!--/slider-->
			<div class="clearfix"></div>
		</div><!--/html_carousel-->
    </section>	<!-- Slideshow End -->


    <aside id="about" class=" left"> <!-- Text Section Start -->
    	<h3>about me</h3><!-- Replace all text with what you want -->
    	<p>Hey there, my name is &quot;<?= $current_user->display_name ?>&quot; and I am a photographer and web developer! This is my brand new portfolio. It's super cool because it's completely responsive! That means you can re-size it to whatever size you like and it always looks great. Have a look around and enjoy.</p>
    </aside>
    <aside class="right">
    	<h3>my work</h3>
    	<p>Below, you will be able to find lots of my work. I take loads of pretty pictures and I also make websites. If you like what you see then you can contact me below! Maybe you would like to hire me or just have a chat, either way, I look forward to hearing from you.</p>
    </aside>
    <div class="clearfix"></div> <!-- Text Section End -->


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
                    <a href="<?php the_permalink(); ?>"><?php the_content(); ?></a><!-- Image must be 400px by 300px -->
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
