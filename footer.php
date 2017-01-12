<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 */

?>

	</div><!-- wrapper -->

<footer id="footer">
	<div class="wrapper">
		<section class="left">
			<h4>Contact</h4>
			<div id="formwrap">
				<form method="post" id="submitform" action="<?php bloginfo('template_url') ?>/submitemail.php" >
					<input type="text" class="formstyle" title="Name" name="name" />
					<input type="text" class="formstyle" title="Email" name="email" />
					<textarea name="message" title="Message"></textarea>
					<input class="formstyletwo" type="submit" value="Send">
				</form>
			</div>
			<div id="error"></div>
		</section>

		<!-- DON'T TOUCH THIS SECTION END -->

			<?php if(!dynamic_sidebar('sidebar-1')): ?>
				<span style="color: white">Это область сайдбара, добавляемого из виджета</span>
			<?php endif; ?>
	</div>
	<div class="clearfix"></div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
