<?php
class BBlocksPricingTable extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-pricing-table-style' );
		wp_enqueue_script( 'b-blocks-pricing-table-script', B_BLOCKS_DIST . 'pricing-table.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-pricing-table-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? 'is-style-basic';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';

		$isStandard = false !== strpos($className, 'standard');
		$isUltimate = false !== strpos($className, 'ultimate');

		$basicClass = !$isStandard && !$isUltimate ? ' is-style-basic': '';
		$blockClassName = "wp-block-b-blocks-pricing-table $className $basicClass $planClass align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksPricingTables-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	}
}
new BBlocksPricingTable();