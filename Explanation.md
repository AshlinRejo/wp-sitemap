# WP Sitemap

WP Sitemap helps to generate and list hyperlinks from home page, which helps to do SEO rankings.

# How it works

- Once we enable the plugin, we will get a menu **WP Sitemap**
- Click on the menu **WP Sitemap** which take to a page where it displays a button **Create sitemap**
- On clicking the button **Create sitemap** it search for hyperlinks from the home page and displays the result.
- It also displays a link on front-end footer which takes to sitemap.html page

# How it creates sitemap

- The plugin loads the content of home page and filter only the anchor tags.
- Then it takes the innerHTML and href from each anchor tags. Now we have the list of hyperlinks and its title. 
- Now it group by title based on first part of hyperlink and store in **options** table on the key name `wp_sitemap_ashlin` with the created date and time.
- Then generates `sitemap.html` and `home-page.html` (has home page content) in the following path `wp-content/uploads/wp-sitemap/`
- Then it schedule a cron for every 1 hours to regenerate sitemap.

# Cron process

- It checks for files `sitemap.html` and `home-page.html` and removes if exists.
- And do the sitemap creation process.

# On uninstall the plugin

- Remove/delete the cron jobs.
- Delete the folder `wp-sitemap` from `wp-content/uploads/`
- Delete the option `wp_sitemap_ashlin` 
