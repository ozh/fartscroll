<?php
/**
 * Plugin Name:       FartScroll
 * Plugin URI:        http://planetozh.com/blog/my-projects/wordpress-plugin-fart-scroll-theonion/
 * GitHub Plugin URI: https://github.com/ozh/fartscroll
 * Description:       "You want fart noises as you scroll? We've got you covered." A WordPress implementation of TheOnion's <a href="http://theonion.github.io/fartscroll.js/">Fartscroll.js</a> elegant piece of software
 * Version:           1.0.2
 * Requires at least: 3.0
 * Requires PHP:      5.6
 * Author:            Ozh & TheOnion
 * Author URI:        http://ozh.org/
 */


/****************** Public stuff */

// Add JS to pages of the blog
add_action( 'template_redirect', 'fartscroll_add_script' );
function fartscroll_add_script() {
	$options = get_option( 'fartscroll_options' );
	$fart_chance = ( isset( $options['fart_chance'] ) ? $options['fart_chance'] : 100 );
	if( mt_rand( 0, 100 ) <= $fart_chance ) {
		wp_enqueue_script( 'fartscroll', plugin_dir_url( __FILE__) . 'fartscroll.js' );
		add_action( 'wp_footer', 'fartscroll_add_footer' );
	}
}

// Add JS to footer
function fartscroll_add_footer() {
	$options = get_option( 'fartscroll_options' );
	$fart_scroll = ( isset( $options['fart_scroll'] ) ? $options['fart_scroll'] : 800 );	
	echo <<<FART
<script type="text/javascript">
fartscroll( $fart_scroll );
</script>

FART;
}


/****************** Admin stuff */

// Add a menu for our option page
add_action('admin_menu', 'fartscroll_add_page');
function fartscroll_add_page() {
	add_options_page( 'Fartscroll', 'Fartscroll', 'manage_options', 'fartscroll', 'fartscroll_option_page' );
}

// Draw the option page
function fartscroll_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Fartscroll. Pfffrrrtf.</h2>
		<form action="options.php" method="post">
			<?php settings_fields('fartscroll_options'); ?>
			<?php do_settings_sections('fartscroll'); ?>
			<input name="Submit" type="submit" value="Save Changes" />
		</form>
	</div>
	<?php
}

// Register and define the settings
add_action('admin_init', 'fartscroll_admin_init');
function fartscroll_admin_init(){
	register_setting(
		'fartscroll_options',
		'fartscroll_options',
		'fartscroll_validate_options'
	);
	add_settings_section(
		'fartscroll_main',
		'Fartscroll Settings',
		'fartscroll_section_text',
		'fartscroll'
	);
	add_settings_field(
		'fartscroll_fart_scroll',
		'Scrolling',
		'fartscroll_setting_input_scroll',
		'fartscroll',
		'fartscroll_main'
	);
	add_settings_field(
		'fartscroll_fart_chance',
		'Probability',
		'fartscroll_setting_input_chance',
		'fartscroll',
		'fartscroll_main'
	);
}

// Draw the section header
function fartscroll_section_text() {
	echo '<p>Configure how much and how often you want farts on scrolls.</p>';
}

// Display and fill the form field
function fartscroll_setting_input_scroll() {
	// get option 'fart_scroll' value from the database
	$options = get_option( 'fartscroll_options' );
	$fart_scroll = ( isset( $options['fart_scroll'] ) ? $options['fart_scroll'] : 800 );
	// echo the field
	echo "<input id='fart_scroll' name='fartscroll_options[fart_scroll]' type='text' value='$fart_scroll' />";
	echo "<br/>How many pixels scrolled to emit a fart ? (default: 800)";
}

// Display and fill the form field
function fartscroll_setting_input_chance() {
	// get option 'fart_chance' value from the database
	$options = get_option( 'fartscroll_options' );
	$fart_chance = ( isset( $options['fart_chance'] ) ? $options['fart_chance'] : 100 );
	// echo the field
	echo "<input id='fart_chance' name='fartscroll_options[fart_chance]' type='text' value='$fart_chance' />";
	echo "<br/>Probability, in percent, that the script is loaded on a page. 100 = all pages, 10 = 1 page out of ten (default: 100)";
}

// Validate user input
function fartscroll_validate_options( $input ) {
	$valid = array();
	$valid['fart_scroll'] = intval( $input['fart_scroll'] );
	$valid['fart_chance'] = min( absint( $input['fart_chance'] ), 100 );
	
	return $valid;
}

// Settings link in plugin management screen
function fartscroll_settings_link($actions, $file) {
	if( false !== strpos($file, 'fartscroll' ) )
		$actions['settings'] = '<a href="options-general.php?page=fartscroll">Fart settings</a>';
		return $actions; 
}
add_filter( 'plugin_action_links', 'fartscroll_settings_link', 2, 2 );

