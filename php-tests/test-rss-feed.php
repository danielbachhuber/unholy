<?php

class Test_RSS_Feed extends Unholy_Testcase {

	public function test_rss_feed_loads_post() {
		$user_id = $this->factory->user->create( array( 'display_name' => 'Unholy Author' ) );
		$this->factory->post->create( array( 'post_title' => 'Unholy Post Title', 'post_author' => $user_id ) );
		$dom = $this->get_feed_as_dom( home_url( 'feed/' ) );
		$this->assertEquals( 'Unholy Post Title', qp( $dom, 'channel item title' )->eq(0)->text() );
		$this->assertEquals( 'Unholy Author', qp( $dom, 'channel item dc|creator')->eq(0)->text() );
	}

}
