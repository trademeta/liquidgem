<?php

function liquidgem_setup() {

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'custom-logo');

	register_nav_menus( array(
		'menu' => esc_html__( 'Menu')
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'liquidgem_custom_background_args', array(
		'default-color' => 'f9f9f9'
	) ) );

	//Удаление некоторых прав у пользователя user
	remove_cap_user();

}
add_action( 'after_setup_theme', 'liquidgem_setup' );

function remove_cap_user() {
	$user = get_role('editor');
	$caps = array(
		'delete_published_pages',
		'delete_published_posts',
		'delete_posts',
		'delete_pages',
		'publish_posts',
		'upload_files'
	);

	foreach ( $caps as $cap ) {
		$user->remove_cap( $cap );
	}
}


//Добавление мета-поля для типа записи "work"
add_action( 'add_meta_boxes', 'liquidgem_register_meta_box' );
function liquidgem_register_meta_box() {

	// create our custom meta box
	add_meta_box( 'liquidgem_work_meta', __( 'Works' ), 'liquidgem_meta_boxes', 'work' );

}

function liquidgem_meta_boxes($post) {
	$liquidgem_meta = get_post_meta( $post->ID, '_liquidgem_work_meta', true );

	$workId = ( ! empty( $liquidgem_meta ) ) ? (int)$liquidgem_meta : '';

	//nonce field for security
	wp_nonce_field( 'meta-box-save', 'liquidgem_meta' );

	// display meta box form
	echo '<select name="liquidgem_select">';
	$my_posts = get_posts(['posts_per_page' => 10]);
	foreach ($my_posts as $post) : ?>
		<option value="<?= $post->ID ?>" <?= selected( $workId, $post->ID, false ) ?>><?= $post->post_title ?></option>
	<?php
	endforeach;
	echo '</select>';
	wp_reset_postdata();
}
add_action( 'save_post','liquidgem_save_meta_box' );

//save meta box data
function liquidgem_save_meta_box( $post_id ) {

	if ( get_post_type( $post_id ) == 'work' && isset( $_POST['liquidgem_select'] ) ) {

		//if autosave skip saving data
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		//check nonce for security
		wp_verify_nonce( 'meta-box-save', 'liquidgem_meta' );

		//store option values in a variable
		$work_meta_data = $_POST['liquidgem_select'];

		//use array map function to sanitize option values
		$work_meta_data = array_map( 'sanitize_text_field', [$work_meta_data] );

		// save the meta box data as post metadata
		update_post_meta( $post_id, '_liquidgem_work_meta', $work_meta_data[0] );

	}
}


function disable_new_posts() {
	// Hide sidebar link
	global $submenu;

	$user = wp_get_current_user();
	$current_screen = get_current_screen();
	if ($user->user_login == 'user' && ( $current_screen->parent_base != 'edit' || $current_screen->parent_base != 'post')) {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
//			$('.page-title-action,#publishing-action').remove();
		});
	</script>
	<?php
	}
}
add_action('admin_footer', 'disable_new_posts');


//Изменение css-класса логотипа
add_filter('get_custom_logo','change_logo_class');

function change_logo_class($html)
{
	$html = str_replace('class="custom-logo"', 'class="logoimage"', $html);
	return $html;
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function liquidgem_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Networks' ),
		'id'            => 'sidebar-1',
		'class'         => '',
		'description'   => esc_html__( 'Networks links.' ),
		'before_widget' => '<section class="right social">',
		'after_widget'  => '</section>',
		'before_title'  => '',
		'after_title'   => '',
	) );

	register_widget( 'My_Text_Widget' );

}
add_action( 'widgets_init', 'liquidgem_widgets_init' );

//Расширение базового класса виджета текста, чтобы убрать блок с классом textwidget
class My_Text_Widget extends WP_Widget_Text {
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		$text = apply_filters( 'widget_text', $widget_text, $instance, $this );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
		<?php echo $args['after_widget'];
	}
}

/**
 * Enqueue scripts and styles.
 */
function liquidgem_styles_scripts() {
	wp_enqueue_style( 'liquidgem-style', get_stylesheet_uri() );
	if ( is_front_page() || is_404() ) {
		wp_enqueue_style( 'main-style', get_template_directory_uri() . '/css/style.css' );
	}elseif (is_single()) {
		wp_enqueue_style( 'work-style', get_template_directory_uri() . '/css/work.css' );
	}
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' ,[],'', true);
	wp_enqueue_script( 'liquidgem-carouFredSel', get_template_directory_uri() . '/js/jquery.carouFredSel-5.5.2.js', ['jquery'], '', true);
	wp_enqueue_script( 'liquidgem-easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', [], '', true);
	wp_enqueue_script( 'liquidgem-form', get_template_directory_uri() . '/js/jquery.form.js', [], '', true);
	wp_enqueue_script( 'liquidgem-scripts', get_template_directory_uri() . '/js/scripts.js', [], '', true);
}
add_action( 'wp_enqueue_scripts', 'liquidgem_styles_scripts' );

//слайдер
function slider_posts(){
	register_post_type('slide', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Слайды', // основное название для типа записи
			'singular_name'      => 'Слайдер', // название для одной записи этого типа
			'add_new'            => 'Добавить новый', // для добавления новой записи
			'add_new_item'       => 'Добавление нового Слайда', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование Слайда', // для редактирования типа записи
			'new_item'           => 'Новый Слайд', // текст новой записи
			'view_item'          => 'Смотреть Слайдер', // для просмотра записи этого типа.
			'search_items'       => 'Искать Слайдер', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Слайды', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => null,
		'exclude_from_search' => null,
		'show_ui'             => null,
		'show_in_menu'        => null, // показывать ли в меню адмнки
		'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => null,
		'menu_position'       => null,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','thumbnail','editor'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array(),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}
add_action('init', 'slider_posts');

//Новый тип записи для работ
function works_posts(){
	register_post_type('work', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Works', // основное название для типа записи
			'singular_name'      => 'Work', // название для одной записи этого типа
			'add_new'            => 'Добавить новый', // для добавления новой записи
			'add_new_item'       => 'Добавление новой работы', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Edit work', // для редактирования типа записи
			'new_item'           => 'New work', // текст новой записи
			'view_item'          => 'View work', // для просмотра записи этого типа.
			'search_items'       => 'Search work', // для поиска по этим типам записи
			'not_found'          => 'No works found', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'No works found', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Works', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_admin_bar'   => true, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => null,
		'menu_position'       => null,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','editor'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array(),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}
add_action('init', 'works_posts');

