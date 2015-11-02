<?php

class Test_TwentyFifteen_Theme extends Unholy_Testcase {

	public function test_header() {
		update_option( 'blogname', 'Unholy Site Title' );
		update_option( 'blogdescription', 'Unholy Site Description' );
		$dom = $this->get_permalink_as_dom( '/' );
		$this->assertEquals( 'Unholy Site Title', qp( $dom, '#masthead .site-title' )->text() );
		$this->assertEquals( 'Unholy Site Description', qp( $dom, '#masthead .site-description' )->text() );
	}

}
