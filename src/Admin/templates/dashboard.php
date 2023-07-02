<?php
if (!defined('ABSPATH')) exit;
?>
<style>
	.wp_sitemap_block {
		width: 23%;
		min-width: 250px;
		display: inline-table;
	}
</style>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e("WP Sitemap", 'wp-sitemap-ashlin'); ?>
	</h1>
	<?php if(!empty($message)){
		echo $message;
	} ?>
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=wp-sitemap-ashlin' ) ); ?>" method="post">
		<p>
			<input type="submit" id="wp-sitemap-crawl-submit" class="button" value="<?php esc_attr_e("Create sitemap", 'wp-sitemap-ashlin'); ?>">
			<input type="hidden" name="_nonce" value="<?php echo esc_attr(wp_create_nonce('wp_sitemap_crawl'));?>">
			<input type="hidden" name="action" value="wp_sitemap_crawl">
		</p>
		<?php if(!empty($next_cron_at)){ ?>
		<p>
			<?php echo sprintf(esc_attr__('Sitemap will refresh on every 1 hour. Next cron is scheduled on %s', 'wp-sitemap-ashlin'), $next_cron_at); ?>
		</p>
		<?php } ?>
	</form>
	<?php if(!empty($sitemap)){ ?>
		<br>
		<h3><?php esc_html_e('Sitemap', 'wp-sitemap-ashlin'); ?></h3>
		<?php if(isset($last_updated_at)){ ?>
			<p><?php echo sprintf(esc_html__('Last updated on %s', 'wp-sitemap-ashlin'), $last_updated_at); ?></p>
		<?php } ?>
			<br>
		<?php if(isset($sitemap['sitemap'])){ ?>
			<?php foreach ($sitemap['sitemap'] as $key => $items){ ?>
				<div class="wp_sitemap_block">
					<p>
						<b><?php echo esc_html(strtoupper(str_replace('-', ' ', $key))); ?></b>
					</p>
					<p>
					<ul>
						<?php foreach ($items as $item){ ?>
							<li>
								<a href="<?php echo esc_attr($item['href']); ?>"><?php echo esc_html($item['anchorText']);?></a>
							</li>
						<?php } ?>
					</ul>
					</p>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>
