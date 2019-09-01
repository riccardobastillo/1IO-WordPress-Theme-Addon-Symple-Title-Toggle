<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'customize_register', 'stt_simply_customize_register',100 );
//IT adds the comments in archives checkbox to the customize
function stt_simply_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'simply_title_toggle_sec', array(
		'title' => __( 'Simply Toggle Title','stt' ),
		'panel'   => 'fl-content',
		'priority' => 2
    ) );
	$wp_customize->add_setting( 'ric_stt_disable_at_title',
		array( 'default' => false,
			'sanitize_callback' => 'esc_attr',
			'transport'   => 'refresh'
		)
	);	
	$wp_customize->add_control( new Stt_Disable_Title_Control( $wp_customize,'stt_disable_title', array(
		'settings' => 'ric_stt_disable_at_title',
		'section' => 'simply_title_toggle_sec',
		'label' => __( 'Disable titles on','stt' ),
	) ) );	
	foreach( array(
		'ric_stt_disable_at_front_home' => __( 'Front page','stt' ),
		'ric_stt_disable_at_page' => __( 'Pages','stt' ), 
		'ric_stt_disable_at_single' => __( 'Single posts','stt' )
	) as $k => $v ){
		$wp_customize->add_setting( $k,
			array( 'default' => false,
				'sanitize_callback' => 'esc_attr',
				'transport'   => 'refresh'
			)
		);
		$wp_customize->add_control( $k.'_ctrl', array(
			'label' => $v,
			'type' => 'checkbox',
			'settings' => $k,
			'section' => 'simply_title_toggle_sec'
		) );
	}	
}
//Full size control to restore the original customize window sizes
class Stt_Disable_Title_Control extends WP_Customize_Control{
	public function render_content(){
	?>
	<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
	<?php
	}
}