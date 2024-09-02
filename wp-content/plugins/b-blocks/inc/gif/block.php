<?php
class BBlocksGIF extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-gif-style' );
		// wp_enqueue_script( 'b-blocks-gif-script', B_BLOCKS_DIST . 'gif.js', [], B_BLOCKS_VERSION, true ); // Not Needed
		// wp_set_script_translations( 'b-blocks-gif-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-gif $className $planClass align$align";

		// Generate Styles
		$gifStyle = new BBlocksStyleGenerator();
		$gifStyle::addStyle( "#bBlocksGif-$cId .bBlocksGif", [
			'text-align' => $gifAlign
		] );
		$gifStyle::addStyle( "#bBlocksGif-$cId .bBlocksGif figure", [
			'width' => '0px' === $gifWidth || '0%' === $gifWidth || '0em' === $gifWidth ? 'auto' : $gifWidth
		] );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksGif-<?php echo esc_attr( $cId ); ?>'>
			<style><?php echo wp_kses( $gifStyle::renderStyle(), [] ); ?></style>

			<div class='bBlocksGif'>
				<figure>
					<?php echo !empty( $gif ) ? "<img src='". esc_url( $gif['images']['original']['url'] ?? '' ) ."' alt='". esc_attr( $gif['title'] ?? '' ) ."' />" : ''; ?>
				</figure>
			</div>
		</div>
		<?php $gifStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksGIF();