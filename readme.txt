=== Custom Posts Parents ===
Contributors: codeko
Donate link: http://codeko.com
Tags: post type, parent, hierarchy
Requires at least: 4.4
Tested up to: 4.7
Stable tag: trunk
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Set any post type (or types) as parent of any post type. Any post structure can be created with this plugin.

== Description ==

With this plugin you can set any post type (or posts types) as parent of any post type. 

Urls will be wellformed, redirects will work, breadcrumbs will work fine.

This plugin is recomended when you need a url structure with a mix of post types. Any url structure you can imagine could be done.

__Post types rewrite slugs will be ignored__

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/custom-post-parents` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Use the Settings → Custom Post Parents screen to configure the plugin and set the post parent of each post type.


== Frequently Asked Questions ==

= I don't see one post type in the configuration screem =

The post type must be a hierarchical post type to be used by the plugin.

= What about the post type slug =

The posts that uses the Custom Post Parent plugin can't have a post type slug. This is because a url can have multiple post types, so no correct post type slug can be used.

== Screenshots ==

1. Configuration screen
2. Post parent selection box
