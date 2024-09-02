<?php
namespace BBlocks\Inc;

class BBlocksAjax{
	function __construct(){
		add_action( 'wp_ajax_bBlocksUserRoles', [$this, 'bBlocksUserRoles'] );
		add_action( 'wp_ajax_nopriv_bBlocksUserRoles', [$this, 'bBlocksUserRoles'] );
	}

	function bBlocksUserRoles(){
		$nonce = $_POST['_wpnonce'] ?? null;

		if( !wp_verify_nonce( $nonce, 'wp_ajax' )){
			wp_send_json_error( 'Invalid Request' );
		}

		global $wp_roles;
		wp_send_json_success( $wp_roles->get_names() );
	}
}

new BBlocksAjax();