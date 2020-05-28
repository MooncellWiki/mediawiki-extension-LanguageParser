<?php

/**
 * LanguageParser extension from Mooncell Wiki
 *
 * @file
 * @ingroup Extensions
 * @author StarHeartHunt <starheart233@gmail.com>
 */

/**
 * It is assumed that the $wgLanguageTagLanguages is defined in LocalSettings.php, in a line before
 * this file is included with require.
 * This extension is used together with the LanguageSelector extension, ideally
 * $wgLanguageTagLanguages is identical with $wgLanguageSelectorLanguages.
 *
 * Since the tags are not accessible in the hooked render functions (no way to access that parameter
 * at runtime without altering the MediaWiki base code) render-functions for all language-tag's will
 * be created. First setting the hooks.
 */
class LanguageParser {

	/**
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) {

		global $wgLanguageTagLanguages;

		$parser->setFunctionHook( 'tsl', [ __CLASS__, 'LanguageTagRender' ] );
	}
	
	// Render the output of {{#tsl:}}.
   public static function LanguageTagRender( Parser $parser, $lang = '', $content = '' ) {
       
      // The input parameters are wikitext with templates expanded.
      // The output should be wikitext too.
      $output = self::languageTagCheck( $content, $lang );

      return $output;
   }

	/**
	 * This function is helpful in checking against the passed Language
	 *
	 * @param string $input
	 * @param string $lang
	 * @return string
	 */
	private static function languageTagCheck( $input, $lang ) {
	    
		global $wgLang;
		
		if ( strpos($wgLang->getCode(), "zh") === false && $wgLang->getCode() != "ja" && $lang === "nozhja" ) {
            return [ $input, 'noparse' => false ];
        }
        
        if ( strpos($wgLang->getCode(), "zh") !== false || $lang == "zh" ) {
            return [ $input, 'noparse' => false ];
        }

		if ( $wgLang->getCode() === $lang ) {
		   // Match. The Language (set by LanguageSelector) is equal to the language tag.
		   return [ $input, 'noparse' => false ];
		} else {
		   // Other wise we return the text as html-comment, thus not visible in the browser.
		   return [ '<!-- ' . $lang . ': ' . $input . ' -->', 'noparse' => true, 'isHTML' => true ];
		}
	}
}
