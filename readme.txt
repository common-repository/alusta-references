=== Alusta References ===
Contributors: usamamazhar
Donate link: #
Tags: feedback, work, showcase
Requires at least: 4.7
Tested up to: 5.5
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Work/service feedback plugin. Using ACF collect the feedback of user.

== Description ==

Alusta Reference can use to collect the feedback of user, using a frontend form. Feedback can of any service or work related. The submited feedback stord in a custom post type called "References" as draft.
The submited feedback/reference can also be show at fronted using a shortcode. Only the published (approved) feedback will be display in the feedback list.
Default language of this plugin is Finnish (fi-FI). Though English translation is also added.

To display the form you can use the shortcode ""
[alusta_references_form]

To display the listing of approved/published feedback.
[alusta_work_listing]
[alusta_work_listing number="12" project_link="yes" button_tex="Show all refrences" button_link="http://www.pagename.com"]
This shortcode accept some parameters;
{number="12"} To Limit the number of refernces, accept numaric value, defult value is 12. 
{project_link="yes"} To show/hide Read More Link for each reference item, value can be yes or no, default value is yes.
{button_tex="Show all refrences"} Button To show at the end of listing, value can be alphanumaric, default is Näytä kaikki, if no value is given then button will not show.
{button_link="http://www.pagename.com"} Button link, value can be any valid url, default is empty.

= Minimum Requirements =

* ACf, or ACF PRO

== Frequently Asked Questions ==

= Does it work without Acf (Advanced Custom Fields) plugin? =

No, Acf plugin must be enabled to use this plugin.

= Can the form fields shows on any page? =

Yes form fields are showing using shortcode, and that shortcode can be put on any page/post.

= Can we change/modify the form fields ? =

No, right now there is no such functionality exist to change or modify the form fields.


== Screenshots ==

1. screenshot-1.png
2. screenshot-1.png
3. screenshot-1.png


== Changelog ==

= 1.0 =
* First version of plugin.


== Upgrade Notice ==


`<?php code(); // goes in backticks ?>`
