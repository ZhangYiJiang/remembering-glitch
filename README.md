A memorial for the game of Giant Imaginations 

# Installation 

The site is built using PHP and CodeIgniter. To install a copy on a server, 

1. Download a copy of CodeIgniter from http://codeigniter.com/. Extract the 
package into the root of the site's folder

2. Download a copy of all files from this repository, and copy it into the 
root of the site's folder. Certain config and controller files should be 
overwritten. 

3. Download a copy of PHP Purifier, standalone distribution from 
http://htmlpurifier.org/download and unzip it in the `application/helper` 
folder. Rename `HTMLPurifier.standalone.php` to `purify_helper`

4. Edit `config.php` and `database.php` in the `application/config` folder. 
If Glitch's API is still up, apply for an API key. If using the Tweetbot desired, 
apply for a Twitter API key. Use the OAuth test page in the developer page to 
find the user token and secret of the account from which the bot is tweeting 
from, then add the following lines to `config.php`: 

		/*
		|--------------------------------------------------------------------------
		| Glitch API Client ID and secret 
		|--------------------------------------------------------------------------
		|
		| Client secret and ID for Glitch API. Go to http://developer.glitch.com/keys/
		| to make your own if you need one. 
		|
		*/
		$config['client_id'] = 'XXXXXXXXXXXXXXXX';
		$config['client_secret'] = 'XXXXXXXXXXXXXXXX';

		/*
		|--------------------------------------------------------------------------
		| Twitter API Client and User ID and secret 
		|--------------------------------------------------------------------------
		|
		| Client secret and ID for Glitch API. Go to http://developer.glitch.com/keys/
		| to make your own if you need one. 
		|
		*/

		$config['consumer_key'] = 'XXXXXXXXXXXXXXXX';
		$config['consumer_secret'] = 'XXXXXXXXXXXXXXXX';
		$config['user_token'] = 'XXXXXXXXXXXXXXXX';
		$config['user_secret'] = 'XXXXXXXXXXXXXXXX';

5. Obtain a copy of the database tables. Currently you have to ask me for one 
(contact details below), but hopefully I'll set something up something soon 
to do this automatically 

6. If you want to use the Tweetbot, add 
`*/15 * * * * php <path to site>/index.php twitter` to the crontab file 

If any issue crops up during installation, send an email to mediumdeviation 
[at] gmail [dot] com

# Colophon

This project uses the CodeIgniter framework, the PHP Purifier and PHP Markdown 
libraries, jQuery and the jQuery Masonry plugin. It would not be possible 
without the wonderful players of Glitch, for whom this project is dedicated to.
