<?php

class Test_RSS_Feed extends Unholy_Testcase {

	public function test_rss_feed_loads_post() {
		$this->factory->post->create( array( 'post_title' => 'Unholy Post Title' ) );
		$dom = $this->get_feed_as_dom( home_url( 'feed/' ) );
		$this->assertEquals( 'Unholy Post Title', qp( $dom, 'channel item title' )->eq(0)->text() );
	}

}
