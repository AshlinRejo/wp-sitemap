<?php
namespace WPSitemapAshlin\Sitemap;

if (!defined('ABSPATH')) exit;

/**
 * Sitemap class
 */
class Sitemap {

	/**
	 * Initiate sitemap generation process
	 * */
	public function initiateSitemapGenerationProcess(){
		if (!current_user_can('manage_options')) return;
		return $this->createSitemap();
	}

	/**
	 * Create sitemap
	 *
	 * @return boolean
	 * */
	private function createSitemap(){
		$URLs = $this->getHomePageURLs();
		// Possible to through an exception
		if($URLs === false) return false;
		$groupedURLs = $this->groupURLForSitemap($URLs);
		return $this->storeSitemap($groupedURLs);
	}

	/**
	 * Store sitemap in options
	 *
	 * @param $groupedURLs array
	 * @return boolean
	 * */
	private function storeSitemap($groupedURLs){
		$data = [
			'sitemap' => $groupedURLs,
			'updated_at' => current_time('timestamp')
		];
		current_time('timestamp');
		return update_option("wp_sitemap_ashlin", $data);
	}

	/**
	 * Group the URLs
	 *
	 * @param $URLs array
	 * @return array
	 * */
	private function groupURLForSitemap($URLs){
		if(empty($URLs)) return array();
		$grouped = array();
		$homePageURL = home_url();
		foreach ($URLs as $url){
			if($this->startsWith($url['href'], $homePageURL)){
				$relativeURL = substr($url['href'], strlen($homePageURL));
				$relativeURL = trim($relativeURL, '/');
				$relativeURLArray = explode('/', $relativeURL);
				if(count($relativeURLArray) > 1){
					$grouped[$relativeURLArray[0]][] = $url;
				} else {
					$grouped['home'][] = $url;
				}
			} else {
				$grouped['external-url'][] = $url;
			}
		}

		return $grouped;
	}

	/**
	 * To verify the string start with
	 *
	 * @param $string string
	 * @param $startString string
	 * @return boolean
	 * */
	private function startsWith($string, $startString) {
		$len = strlen($startString);
		return (substr($string, 0, $len) === $startString);
	}

	/**
	 * Get home page urls
	 *
	 * @return array|boolean
	 * */
	private function getHomePageURLs(){
		try {
			$content = file_get_contents(home_url());
			$content = strip_tags($content,"<a>");
			$content = preg_replace('/&(?!amp)/', '&amp;', $content);
			$dom = new \DomDocument();
			$dom->loadHTML($content);
			$URLs = array();
			foreach ($dom->getElementsByTagName('a') as $item) {
				$anchorHTML = $dom->saveHTML($item);
				$anchorURL = $item->getAttribute('href');
				if($this->verifyURL($URLs, $anchorHTML, $anchorURL) === true){
					if(filter_var($anchorURL, FILTER_VALIDATE_URL)){
						//For removing inner HTML content
						$text = strip_tags($item->nodeValue);
						$text = explode("\n", $text)[0];

						$URLs[] = array (
							'href' => $anchorURL,
							'anchorText' => $text
						);
					}
				}
			}

			return $URLs;
		} catch (\Exception $exception){
			return false;
		}
	}

	/**
	 * Verify for valid URL
	 *
	 * @param $URLs array
	 * @param $anchorHTML string
	 * @param $anchorURL string
	 * @return boolean
	 * */
	private function verifyURL($URLs, $anchorHTML = '', $anchorURL = ''){
		// Check for empty html content
		if(empty(trim($anchorHTML))) return false;

		// Check for empty uel content
		if(empty(trim($anchorURL))) return false;

		// Check for duplicate entry
		$href = array_column($URLs, 'href');
		if(is_numeric(array_search($anchorURL, $href))) return false;

		// Check valid URL
		if(!filter_var($anchorURL, FILTER_VALIDATE_URL)) return false;

		return true;
	}
}