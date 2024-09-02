<?php
class BBlocksCountdown extends BBlocks{
	public function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit(){
		register_block_type( __DIR__, [
			'render_callback' => [$this, 'render']
		] ); // Register Block
	}

	function render( $attributes, $content ){
		extract( $attributes );

		wp_enqueue_style( 'b-blocks-countdown-style' );
		wp_enqueue_script( 'b-blocks-countdown-script', B_BLOCKS_DIST . 'countdown.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-countdown-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-countdown $className $planClass align$align";

		ob_start(); ?>
		<div
			class='<?php echo esc_attr( $blockClassName ); ?>'
			id='bBlocksCountdown-<?php echo esc_attr( $cId ); ?>'
			data-nonce='<?php echo esc_attr( wp_json_encode( wp_create_nonce( 'wp_ajax' ) ) ); ?>'
			data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'
			data-content='<?php echo esc_attr( wp_json_encode( $content ) ); ?>'
		></div>

		<?php return ob_get_clean();
	}
}
new BBlocksCountdown();