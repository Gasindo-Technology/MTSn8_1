<?php
class BBlocksButton{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		register_block_type( __DIR__, [
			'render_callback'	=> [$this, 'render']
		] ); // Register Block
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'b-blocks-button-style' );
		wp_enqueue_script( 'b-blocks-button-script', B_BLOCKS_DIST . 'button.js', [ 'wp-util', 'react', 'react-dom', 'aos' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-button-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$extraClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-button $extraClass $className align$align";

		$popup = $popup ?? [ 'type' => 'image', 'content' => '', 'caption' => '' ];

		if ( 'content' === $popup['type'] ) {
			$blocks = parse_blocks( $popup['content'] );
			$popup['content'] = '';
			foreach ( $blocks as $block ) {
				$popup['content'] .= render_block( $block );
			}
		} // Convert the blocks to dom elements

		ob_start(); ?>
		<div
			class='<?php echo esc_attr( $blockClassName ); ?>'
			id='btnButton-<?php echo esc_attr( $cId ) ?>'
			data-nonce='<?php echo esc_attr( wp_json_encode( wp_create_nonce( 'wp_ajax' ) ) ); ?>'
			data-attributes='<?php echo esc_attr( wp_json_encode( array_replace( $attributes, [ 'popup' => $popup ] ) ) ); ?>'
			data-info='<?php echo esc_attr( wp_json_encode( [
				'currentPostId' => get_the_ID(),
				'userRoles' => is_user_logged_in() ? wp_get_current_user()->roles : [],
				'loginURL' => wp_login_url()
			] ) ); ?>'
		></div>

		<?php return ob_get_clean();
	} // Render
}
new BBlocksButton();