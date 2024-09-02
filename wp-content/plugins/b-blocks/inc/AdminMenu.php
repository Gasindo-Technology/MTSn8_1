<?php
namespace BBlocks\Inc;

class AdminMenu{
	function __construct(){
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
		add_action( 'admin_menu', [$this, 'adminMenu'] );
	}

	function adminEnqueueScripts( $hook ) {
		if( str_contains( $hook, 'b-blocks' ) ){
			wp_enqueue_style( 'b-blocks-admin-style', B_BLOCKS_DIR_URL . 'dist/admin.css', [], B_BLOCKS_VERSION );
			wp_enqueue_script( 'b-blocks-admin-script', B_BLOCKS_DIR_URL . 'dist/admin.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		}
	}

	function adminMenu() {
		$menuIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 500 500' fill='#fff'>
			<path d='M57.7 50.1c-.4 5.1-.7 25.7-.7 45.9v36.7l11.3.6c6.1.4 16 .7 22 .7H101v230H51v92.1l131.3-.4c141.5-.4 139.3-.3 164.1-5.8 44-9.7 76.7-33.5 88.7-64.4 5.8-14.8 7.2-24.5 6.6-46-.6-21-2.2-30-7.7-44.3-8.2-21.3-25.2-38.7-50.9-52l-11-5.8 6.7-2.9c14.7-6.4 32-20.7 41.1-33.8 12-17.5 17-40.5 14.2-65.9-2.6-23.4-9.8-41-22.6-54.6-20.2-21.4-45.9-31.7-92.8-36.9-11.9-1.3-34.1-1.6-137.3-2l-123.1-.4-.6 9.2zm229.5 87.1c6.6 3.1 13 9.3 16 15.7 1.9 4 2.2 6.3 2.3 17.1 0 11-.3 13.1-2.3 17.5-6.1 13.2-18 18.8-43.6 20.5-7.8.5-16.7 1-19.8 1H234v-75.1l23.8.3c23.3.3 23.8.3 29.4 3zm-10.9 141.4c18.1 4.1 28.4 13.6 31.8 29.6 1.7 7.7.6 24.1-2 30.9-6.3 16.4-22.6 23.6-55.3 24.6l-16.8.6V277h17.6c12.5 0 19.6.5 24.7 1.6z' />
		</svg>";

		add_menu_page(
			__( 'B Blocks', 'b-blocks' ),
			__( 'B Blocks', 'b-blocks' ),
			'manage_options',
			'b-blocks',
			'',
			'data:image/svg+xml;base64,' . base64_encode( $menuIcon ),
			20
		);
		add_submenu_page(
			'b-blocks',
			__( 'Help', 'b-blocks' ),
			__( 'Help', 'b-blocks' ),
			'manage_options',
			'b-blocks',
			[$this, 'helpPage']
		);
	}

	function helpPage(){ ?>
		<div class='bplAdminHelpPage'></div>
	<?php }
}
new AdminMenu();