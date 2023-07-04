<?php
namespace WPSitemapAshlin\Sitemap;

use WPSitemapAshlin\BaseController;

if (!defined('ABSPATH')) exit;

/**
 * Sitemap class
 */
class Sitemap extends BaseController {

	/**
	 * Class instance.
	 * @var Sitemap $instance
	 * */
	protected static $instance = null;

	/**
	 * @var string
	 * */
	protected $home_page_content = '';

	/**
	 * @var array
	 * */
	protected $sitemap_data = array();

	/**
	 * Get class instance.
	 *
	 * @return Sitemap
	 */
	public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Register the event.
	 * */
	public function hooks(){
		add_action('wp_footer', [$this, 'displaySitemapLinkInFooter']);
	}

	/**
	 * Display link to sitemap in footer
	 * */
	public function displaySitemapLinkInFooter(){
		$upload_dir = wp_get_upload_dir();
		if(file_exists($upload_dir['basedir'] . '/wp-sitemap/sitemap.html')){
			$filepath = WPS_ASHLIN_PATH . 'src/templates/footer.php';
			$data = array('sitemap_url' => $upload_dir['baseurl'] . '/wp-sitemap/sitemap.html');
			$this->render($filepath, $data);
		}
	}

	/**
	 * Initiate sitemap generation process
	 *
	 * @return array
	 * */
	public function initiateSitemapGenerationProcess(){
		if (!current_user_can('manage_options')) return $this->response(false, esc_html__('Invalid access.', 'wp-sitemap-ashlin'));
		$result = $this->createSitemap();

		if($result === true){
			$status = Cron::instance()->registerCron();
			if($status){
				return $this->response(true, esc_html__('Sitemap generated successfully and scheduled to auto generate on every one hour.', 'wp-sitemap-ashlin'));
			} else {
				return $this->response(true, esc_html__('Sitemap generated successfully and failed to scheduled cron.', 'wp-sitemap-ashlin'));
			}
		} else {
			return $this->response(false, esc_html__('Failed to generate sitemap.', 'wp-sitemap-ashlin'));
		}
	}

	/**
	 * Get Sitemap HTML content
	 *
	 * @return string
	 * */
	private function getSitemapHTMLContent(){
		$updated_at = '';
		if(isset($this->sitemap_data['updated_at'])){
			$updated_at = $this->dateFormat($this->sitemap_data['updated_at']);
		}
		$filepath = WPS_ASHLIN_PATH . 'src/Admin/templates/sitemap-html.php';
		$data = array('sitemap' => $this->sitemap_data, 'last_updated_at' => $updated_at);
		return $this->getHTML($filepath, $data);
	}

	/**
	 * Get file information
	 *
	 * @return array
	 * */
	private function getFileInformation(){
		$upload_dir      = wp_get_upload_dir();
		return array(
			array(
				'base'    => $upload_dir['basedir'] . '/wp-sitemap',
				'file'    => 'home-page.html',
			),
			array(
				'base'    => $upload_dir['basedir'] . '/wp-sitemap',
				'file'    => 'sitemap.html',
			)
		);
	}

	/**
	 * Remove folder
	 * */
	private function removeFolder(){
		$upload_dir = wp_get_upload_dir();
		$files = $this->getFileInformation();
		foreach ( $files as $file ) {
			if (file_exists(trailingslashit($file['base']) . $file['file'])) {
				wp_delete_file(trailingslashit($file['base']) . $file['file']);
			}
		}
		if (is_dir($upload_dir['basedir'] . '/wp-sitemap')) {
			@rmdir($upload_dir['basedir'] . '/wp-sitemap');
		}
	}

	/**
	 * Create files and folders
	 * */
	private function createFiles(){
		$files = $this->getFileInformation();
		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
				if ( $file_handle ) {
					if($file['file'] === 'home-page.html'){
						$content = $this->home_page_content;
					} else {
						$content = $this->getSitemapHTMLContent();
					}
					fwrite( $file_handle, $content ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
					fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
				}
			}
		}
	}

	/**
	 * Format response
	 *
	 * @param $status boolean
	 * @param $message string
	 * @return array
	 * */
	private function response($status, $message){
		return array(
			'status' => $status,
			'message' => $message
		);
	}

	/**
	 * Refresh sitemap
	 * */
	public function refreshSitemap(){
		return $this->createSitemap();
	}

	/**
	 * Create sitemap
	 *
	 * @return boolean
	 * */
	private function createSitemap(){
		$this->removeFolder();
		$URLs = $this->getHomePageURLs();
		// Possible to through an exception
		if($URLs === false) return false;
		$groupedURLs = $this->groupURLForSitemap($URLs);
		$result = $this->storeSitemap($groupedURLs);
		if($result) $this->createFiles();
		return $result;

	}

	/**
	 * Store sitemap in options
	 *
	 * @param $groupedURLs array
	 * @return boolean
	 * */
	private function storeSitemap($groupedURLs){
		$this->sitemap_data = [
			'sitemap' => $groupedURLs,
			'updated_at' => current_time('timestamp')
		];
		return update_option("wp_sitemap_ashlin", $this->sitemap_data);
	}

	/**
	 * Get sitemap from option table
	 *
	 * @return array
	 * */
	public function getSitemap(){
		return get_option("wp_sitemap_ashlin");
	}

	/**
	 * Remove sitemap information from DB and also the files and folders created by our plugin on deactivate
	 * */
	public function deleteSitemapDataAndFiles(){
		delete_option("wp_sitemap_ashlin");
		$this->removeFolder();
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
			$content = $this->home_page_content = file_get_contents(home_url());
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
