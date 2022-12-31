<?php

class TestTimberSugar extends WP_UnitTestCase {

    function testDummy() {
    	$template = '{{dummy(10)}}';
        $str = Timber::compile_string($template);
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '', $str);
        $this->assertEquals(10, str_word_count($str));
    }

    function testDummyFilter() {
        $post_id = $this->factory->post->create(array('post_content' => ''));
        $post = new TimberPost($post_id);
        $template = '{{post.post_content|dummy(10)}}';
        $str = Timber::compile_string($template, array('post' => $post));
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '', $str);
        $this->assertEquals(10, str_word_count($str));
    }

    function testDummyFilterNadda() {
        $post_id = $this->factory->post->create(array('post_content' => 'Jared is Cool'));
        $post = new TimberPost($post_id);
        $template = '{{post.post_content|dummy(10)}}';
        $str = Timber::compile_string($template, array('post' => $post));
        $this->assertEquals('Jared is Cool', $str);
    }

    function testTwitterify() {
    	$tweet = 'Love this thing from @jaredNova about #timberwp http://upstatement.com';
    	$template = '{{tweet|twitterify}}';
        $str = Timber::compile_string($template, array('tweet' => $tweet));
        $this->assertEquals('Love this thing from  <a href="https://www.twitter.com/jaredNova" target="_blank">@jaredNova</a> about <a href="https://twitter.com/hashtag/timberwp?src=hash" target="_blank">#timberwp</a> <a href="http://upstatement.com" target="_blank">http://upstatement.com</a>', $str);
    }

}
