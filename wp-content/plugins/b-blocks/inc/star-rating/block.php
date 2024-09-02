<?php
class BBlocksStarRating extends BBlocks{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		wp_register_style( 'b-blocks-star-rating-style', B_BLOCKS_DIST . 'star-rating.css', [], B_BLOCKS_VERSION); // Style
		wp_register_style( 'b-blocks-star-rating-editor-style', B_BLOCKS_DIST . 'editor.css', [ 'b-blocks-star-rating-style' ], B_BLOCKS_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'b-blocks-star-rating-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'b-blocks-star-rating-editor-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style('b-blocks-star-rating-style');
		wp_enqueue_script( 'b-blocks-star-rating-script', B_BLOCKS_DIST . 'star-rating.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-star-rating-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-star-rating $className $planClass align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksRating-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new BBlocksStarRating();