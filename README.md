# modwordpress
Wordpress Bridge for Prestashop 1.4.x

** UPDATED - Version 1.1 now attached **

This is my first PrestaShop module. Only took a few hours and I'm quite pleased with it.

Basically this will allow you to incorporate WordPress posts into your PrestaShop in the easiest possible way. Screenshots are attached of what it looks like. Please enjoy and if you use and like this module why not donate using PayPal to lee@leeandgrace.co.uk to keep me making new modules :)

##Installation
Install WordPress and configure - Store the tables in the same database as PrestaShop<BR>
Upload the files <BR>
Configure the Module<BR>
Transplant the module<BR>

##Documentation
There are only a few options that you can set in the Configure section :

##Module Display Title
This is what is shown at the top of your module eg. Latest News or Blog Posts etc.

##WordPress Posts Table
Specify the name of the table that contains your WordPress posts. By default WordPress installs to wp_posts however you can change the prefix from wp when installing.

Qty<BR>
Number of posts to display.

CMS ID<BR>
The ID number of the CMS page you want to appear at the header of this module

CMS Class<BR>
DIV Class for the header

WordPress Class<BR>
DIV class for the WordPress segment

CMS Footer ID<BR>
The ID number of the CMS page you want to appear at the foot of this module

CMS Footer Class<BR>
DIV Class for the footer

##Prerequisites
PrestaShop 1.4.x<BR>
WordPress<BR>


###ChangeLog
v1.1<BR>
Added some CMS integration for header and footer which will remain static<BR>
Added class settings for CMS header and footer<BR>

v1.0<BR>
First iteration<BR>
Database Changes<BR>
Adds 3 fields to Configuration :<BR>
PS_WPBTITLE<BR>
PS_WPBTABLE<BR>
PS_WPBQTY<BR>
PS_WPBCMSID<BR>
PS_WPBCMSCLASS<BR>
PS_WPBWPCLASS<BR>
PS_WPBCMSFOOTCLASS<BR>
PS_WPBCMSFOOTID<BR>
