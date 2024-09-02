<?php
class BBlocksColumn extends BBlocks{
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

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-column $className $planClass align$align";

		// Style disappearing problem
		global $allowedposttags;
		$allowed_html = wp_parse_args( ['style' => []], $allowedposttags );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksColumn-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'>
			<div class='bBlocksColumnStyle'></div>

			<div class='bBlocksColumn'>
				<?php echo wp_kses( $content, $allowed_html ); ?>
			</div>
		</div>

		<?php return ob_get_clean();
	}
}
new BBlocksColumn();