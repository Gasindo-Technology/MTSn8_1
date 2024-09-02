<?php
class BBlocksVideo extends BBlocks{
	public function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit(){
		register_block_type( __DIR__, [
			'render_callback' => [$this, 'render']
		] ); // Register Block
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'b-blocks-video-style' );
		wp_enqueue_script( 'b-blocks-video-script', B_BLOCKS_DIST . 'video.js', [ 'react', 'react-dom', 'plyr' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-video-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		ob_start(); ?>
		<div class='wp-block-b-blocks-video <?php echo 'align' . esc_attr( $align ); ?>' id='bBlocksVideo-<?php echo esc_attr( $clientId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	}
}
new BBlocksVideo();