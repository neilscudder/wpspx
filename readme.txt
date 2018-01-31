=== WPSPX ===

Contributors: pixelpudu
Donate link: https://www.paypal.me/martingreenwood
Tags: spektrix, tickets, api, booking, theatre
Requires at least: 4.3
Tested up to: 4.5
Stable tag: 1.0.0
License: GPL v2 or later
Author: Martin Greenwood
Author URI: https://profiles.wordpress.org/pixelpudu/

A plugin for WordPress that intergrates with Spektrix API V2.

== Description ==

This plugin allows you to quickly integrate your Spektrix API data into your WordPress website. 

== Features out of the Box ==

- Display a list of all upcoming shows
- Display shows for the next week / six weeks
- Display a list of matinee shows
- Display the Spektrix basket, checkout and my account (We recommend acquiring a SSL Certificate)

== Frequently Asked Questions ==

= I have an error when trying to add a show "Oops, no XML received from Spektrix". = 
Double check your API, account name and CRT/Key locations

= My data is out of date =
Visit the Settings page and delete the cache or do it manually by emptying the cache folder withn the plugin folder

== Installation ==

- Add via the plugin screen by searching for WPSPX or
- Upload WPSPX to the /wp-content/plugins/ directory
- Upload your Signed SSL certificates from Spektrix to the server outside of the root folder (eg: before /public_html)
- Activate the plugin through the 'Plugins' menu in WordPress
- Visit the settings page under Settings > WPSPX
- Enter your API Key, account name, and the path to your signed Spektrix certificates.
- Add your first show

== Screenshots ==

1. WPSPX Settings

== Donations ==

If you would like to donate to the future development of this plugin you can do so [here](https://www.paypal.me/martingreenwood)

== Support ==

Even though this plugin is completely free, any help with installation or personal support will be charged at a standard hourly rate. 

== Changelog ==

= 1.1.0 =

tba

= 1.0.1 =

Removed CMB2 plugin from human made
Added custom meta box for poster images
Added ABSPATH Check
Added a bunch of empty dir index files

= 1.0.0 =

Initial Release.
