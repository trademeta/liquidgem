<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $blogname = get_bloginfo('name', 'display'); ?>
<title><?php echo $blogname; wp_title(); ?></title>
<meta name="description" content="<?php bloginfo('description') ?>">
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700|Cookie' rel='stylesheet' type='text/css'>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="wrapper">
    <div id="top">
        <div id="logo">
            <?php
            if( has_custom_logo() ):
                get_custom_logo();
            else:
            ?>
            <a href="<?= get_home_url() ?>"><img class="logoimage" src="<?php bloginfo('template_url') ?>/images/logo.png" alt="logo"></a>
            <?php endif; ?>

            <h1 id="logotitle"><?= $blogname ?></h1>	<!-- Logo text -->
        </div><!--/logo-->

        <nav>	<!-- Navigation Start -->
            <ul>
                <li><a href="<?= get_home_url() ?>">HOME</a></li>
                <?php if( is_front_page() ): ?><li><a href="#about">About</a></li><?php endif; ?>
                <li><a href="#work">Work</a></li>
                <li><a href="#footer">Contact</a></li>
                <li class="adminka" title="Имя: user, password: 123"><a href="<?= get_admin_url() ?>">adminka</a></li>
            </ul>
        </nav>	<!-- Navigation End -->
    </div><!--/top-->

