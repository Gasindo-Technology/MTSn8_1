<?php
class BBlocksQRCode extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-qr-code-style' );
		// wp_enqueue_script( 'b-blocks-qr-code-script', B_BLOCKS_DIST . 'qr-code.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true ); // Not Needed
		// wp_set_script_translations( 'b-blocks-qr-code-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-qr-code $className $planClass align$align";

		// Generate Styles
		$qrCodeStyle = new BBlocksStyleGenerator();
		$qrCodeStyle::addStyle( "#bBlocksQRCode-$cId .bBlocksQRCode", [
			'text-align' => $qrAlign
		] );

		$siteUrl = $url ? $url : 'http://bplugins.com';

		$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=". $dimension ."x". $dimension ."&color=". str_replace( '#', '', $color ) ."&bgcolor=". str_replace( '#', '', $bgColor ) ."&ecc=L&qzone=1&data=". $siteUrl;

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksQRCode-<?php echo esc_attr( $cId ); ?>'>
			<style><?php echo wp_kses( $qrCodeStyle::renderStyle(), [] ); ?></style>

			<div class='bBlocksQRCode'>
				<img src='<?php echo esc_url( $qrUrl ); ?>' alt='<?php echo esc_url( $url ); ?>' />
			</div>
		</div>
		<?php $qrCodeStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksQRCode();