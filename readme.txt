=== My Reading Library ===

Contributors: olsonsp4c
Donate link: http://www.affordable-techsupport.com/support/
Tags: wordpress, books, widget, amazon, reading, library
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0

Displays books in the sidebar and library/book page with cover art fetched from amazon.com; displays book details, progress, ratings, and reviews.

== Description ==

My Reading Library is a very heavy modification of the [Now Reading Redux plugin by Ashod Nakashian](http://blog.ashodnakashian.com/projects/now-reading-redux/ "Now Reading Redux") which is a resurrection of the [Now Reading Reloaded plugin by Ben Gunnink](http://heliologue.com/ "Now Reading Reloaded") which is a fork of the [Now Reading plugin by Rob Miller](http://robm.me.uk/projects/plugins/wordpress/now-reading/ "Now Reading Plugin").

It allows you to develop a library, show the book details, track your progress, rate the book, and link to a WP post of your book review. It displays the library within the sidebar and on the library/book page with cover art fetched from Amazon.

The home of My Reading Library is [www.affordable-techsupport.com/support/](http://www.affordable-techsupport.com/support/ "My Reading Library"). You may find updates (as well as within the WP Plugin Repository) and receive support there.

= Disclaimer =

Please backup your database before installing/upgrading. Thought I attempt to releases bug-free plugins (which is very difficult), my ability to perform tests is limited. If you find any bugs or have feature requests, please kindly report them in the Support Forum within my website.

== Installation ==

1. Upload `my-reading-library` to the `.../wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Optionally, make any changes to the provided template files: `.../wp-content/plugins/my-reading-library/templates/custom_templates/`

How to Upgrade from a previous version:

1. Backup your database (truly - backup now).
1. Deactivate any existing versions of `my-reading-libary`.
1. Install `my-reading-library` as described in `Installation`.
1. Activate `my-reading-library`.
1. Your database will be updated and library restored as was previously.

== Frequently Asked Questions ==

= How do I change the sidebar widget and library formatting? =

I have supplied default templates and custom templates that can be modified in order to suit your theme. Your theme's header, sidebar, footer code may need to integrated into `library.php` and `single.php` in order for formatting to appear properly. In addition, you may need to tweak the inline css in both files and `sidebar.php` in order to get the desired results. Basic integration instructions are provided in the administrative options panel. I am available for basic theme integration for a modest fee.

= How similar is this plugin to Now Reading Redux? =

I used the code for Now Reading Redux as the base for my plugin; however, I've made so many fixes, modifications, and additions, that it has morphed into its own plugin. I plan to monitor the development of Now Reading Redux in order to evaluate whether or not to integrate additional features.

= Will this plugin conflict with Now Reading Redux? =

I've taken pains to modify this plugin in such a manner that there should be no conflicts with Now Reading Redux.

= Where can I make suggestions or request support for this plugin? =

http://www.affordable-techsupport.com/support/

= Where can I make a donation to the developer of this plugin? =

http://www.affordable-techsupport.com/support/
At least 10% of donations will be given to the poor.

== Screenshots ==

1. The Add Book Page
2. The Book Manager
3. The Options Page 1
4. The Options Page 2
5. The Options Page 3
6. The Library
7. Book Page
8. Sidebar Widget

== Changelog ==

= 1.0 =
* Added ability to select/hide book categories (read, unread, finished, onhold) in the sidebar widget
* Added ability to select/hide the `Complete Library` button in the sidebar widget
* Added ability to enter total book pages and completed book pages (viewable in library and sidebar widget)
* Added ability to view the book progress in the sidebar widget and library page
* Added ability to link to Amazon Customer Reviews in the library and sibebar widget (hides if it is a custom book)
* Added logic to enable the Amazon book details to open in a new page while Custom books open with the website from both the sidebar widget and library page
* Added ability to link to a WP Post Review from the both the sidebar widget and library page
* Added separate image sizes (small, medium, large) for the sidebar widget and library page
* Added a new png file for `no-image.png`
* Added formatting in the library, book pages, and sidebar
* Added many descriptions and explanations in the MRL options page
* Added many descriptions and explanations to the edit book page
* Added fully functioning and expanded Single-User and Multi-User modes
* Added the ability for Administrators to view, sort, and filter the users responsible to read/review books in Multi-User mode from the manage books page
* Added ability for Administrators to view and modify the users responsible to read/review books in Multi-User mode from the edit book page
* Added the ability to view the status of a book within the admin book manager with pretty names
* Correctly implemented the use of user roles (rather than the deprecated user levels) throughout the plugin
* Streamlined and modded code, removed unnecessary code and file, corrected logic, fixed errors, etc.
* Added default and custom template files with the ability to specify which options to implement within the MRL options page (instructions included).
* Added custom template files that can me safely modded to suit user themes in the `...my-reading-library/templates/custom_templates/` folder. See the MRL options page for implementation.
* Added custom template files for the Weaver Theme (2 column layout). See the MRL options page for implementation.
* Added custom template files for the Twenty Ten Theme. See the MRL options page for implementation.
* Added custom template files for the Twenty Eleven Theme (sidebar template is slightly unstable). See the MRL options page for implementation.
* Added various directory defines in `...my-reading-library/my-reading-library.php` in order to facilitate easier future directory adjustments
* Added the ability to uninstall the plugin AND database entries when the plugin is deleted from within the plugin manager
* Added updated screenshots of all pages in the admin and public site
* Combed through the code in order to make this plugin fully ready for internationalization
* en_US.pot is in the following directory: `...my-reading-library/languages/`

== Upgrade Notice ==

None at this time.

== Update Path ==

* Pull book details from Amazon and set as props.
* Add language, edition, total pages, category, and abridged props.
* Use calendar UI for dates.

== Known Bugs ==
* In the book manager, the Title and Reader columns will not sort properly when the title is clicked
* The integration with the Twenty Eleven WP Theme is inconsistent and buggy

== Demo Site ==

You can see the My Reading Library plugin in action on my demo website: http://test.p4x.org/