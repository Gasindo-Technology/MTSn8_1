<?php
class BBlocksRow extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-row-style' );
		wp_enqueue_script( 'b-blocks-row-script', B_BLOCKS_DIST . 'row.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-row-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-row $className $planClass align$align";

		// Style disappearing problem
		global $allowedposttags;
		$allowed_html = wp_parse_args( ['style' => []], $allowedposttags );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksRow-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'>
			<div class='bBlocksRowStyle'></div>

			<div class='bBlocksRow'>
				<?php echo wp_kses( $content, $allowed_html ); ?>
			</div>
		</div>

		<?php return ob_get_clean();
	}
}
new BBlocksRow();