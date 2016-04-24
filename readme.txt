=== Readme Parser ===
Contributors: Tom Braider
Donate link: http://www.unicef.org
Tags: plugin, parse
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 0.3

Shows any plugin readme.txt file on a page/post.
'[readme-parser url="http://www.xyz.com/readme.txt"]'

== Description ==

If you make Wordpress plugins you know the readme.txt.
With this plugin you can show this readme.txt in every post or page.

== Installation ==

1. unzip plugin directory into the '/wp-content/plugins/' directory
1. activate the plugin through the 'Plugins' menu in WordPress
1. insert '[readme-parser url="http://domain/path/readme.txt"]' in your page

== Frequently Asked Questions ==

= Who can i change the style? =

You can change the Stylesheet in you own theme style.css.
'.readme-parser' is the main div.
'.readme-parser-screenshots' is the special '&lt;ol&gt;' with the screenshots,

== Screenshots ==

1. readme.txt on page

== Arbitrary section ==

**Filelist**

* readme-parser.php

== Changelog ==

= 0.3 =

+ compatibility to WordPress 4
+ all functions in a class
+ new possible format '[readme-parser]http://domain/path/readme.txt[/readme-parser]'

= 0.2 =

+ urls to links
+ separate DIVs for every section, easier css access

= 0.1 =

+ first release