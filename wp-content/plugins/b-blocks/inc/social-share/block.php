<?php
class BBlocksSocialShare extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-social-share-style' );
		wp_enqueue_script( 'b-blocks-social-share-script', B_BLOCKS_DIST . 'social-share.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-social-share-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		// Enqueue assets where has block
		wp_enqueue_script( 'goodShare' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-social-share $className $planClass align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksSocialShare-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'>
			<div class='socialShareStyle'></div>

			<ul class='bBlocksSocialShare'>
				<?php foreach ( $socials as $index => $social ) {
					extract( $social );
					$upIconUrl = $upIcon['url'];
					$upIconAlt = $upIcon['alt'];
					$iconClass = $icon['class'];

					$upIconEl = !empty( $upIconUrl ) ? "<img src='$upIconUrl' alt='$upIconAlt' />" : '';
					$iconEl = !empty( $iconClass ) ? "<i class='$iconClass'></i>" : '';
					$filterIconEl = $isUpIcon ? $upIconEl : $iconEl; ?>

					<li class='icon icon-<?php echo esc_attr( $index ); ?>' data-social='<?php echo esc_attr( $network ); ?>'>
						<?php echo wp_kses_post( $filterIconEl ); ?>
					</il>
				<?php } ?>
			</ul>
		</div>

		<?php return ob_get_clean();
	}
}
new BBlocksSocialShare();