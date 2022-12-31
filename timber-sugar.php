<?php
/*
Plugin Name: Timber Sugar
Plugin URI: http://timber.upstatement.com
Description: Some bonus features to make templates fun again
Author: Jared Novack + Upstatement
Version: 0.0.1
Author URI: http://upstatement.com/
*/

  use Twig\TwigFilter;
  use Twig\TwigFunction;

	class TimberSugar {

		function __construct(){
			add_filter('timber/twig', function( $twig ){
        $twig->addFilter(new TwigFilter('dummy', array(__CLASS__, 'apply_dummy_filter')));
        $twig->addFunction(new TwigFunction('dummy', array(__CLASS__, 'apply_dummy')));

        $twig->addFilter(new TwigFilter('twitterify', array(__CLASS__, 'twitterify')));
        $twig->addFilter(new TwigFilter('twitterfy', array(__CLASS__, 'twitterify')));
				return $twig;
			});
		}

		static function apply_dummy_filter( $text, $words = 100 ){
			if (!strlen(trim($text))){
				return self::apply_dummy($words);
			}
			return $text;
		}

		static function apply_dummy( $word_count = 100 ){
			$text = file_get_contents(__DIR__.'/assets/lorem-ipsum.txt');
			$words = explode(' ', $text);
			$starting_word = rand(0, count($words));
			$more_words = array_merge($words, $words);
			$resulting_words = array_slice($more_words, $starting_word, $word_count);
			$text = implode(' ', $resulting_words);
			return ucfirst($text);
		}

		public static function twitterify($ret) {
			$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
			$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
			$pattern = '#([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.';
			$pattern .= '[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)#i';
			$ret = preg_replace($pattern, '<a href="mailto:\\1">\\1</a>', $ret);
			$ret = preg_replace("/\B@(\w+)/", " <a href=\"https://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
			$ret = preg_replace("/\B#(\w+)/", "<a href=\"https://twitter.com/hashtag/\\1?src=hash\" target=\"_blank\">#\\1</a>", $ret);
			return $ret;
		}

	}

	$GLOBALS['timber_sugar'] = new TimberSugar();
