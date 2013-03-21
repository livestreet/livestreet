/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/


LiveStreet 1.0.3

INSTALLATION
1. Copy files to the engine to the desired directory site
2. Go the address http://you_site/install/
3. Follow the instructions of the installer.

Upgrading from 0.5.1
0. Be sure to make backup of your site and database
1. Update to version 1.0.3 can only database, so copy the new version over the old NOT to install, use a clean directory
2. Copy files to the engine to the desired directory site
3. Copy the file to a new directory on your config.local.php old version 0.5.1 and copy the directory /uploads/ all files
4. Enter the address http://you_site/install/
5. Follow the instructions of the installer. When you create the database required By clicking the "Convert base 0.5.1 in 1.0.3"

Upgrading from 1.0
0. Be sure to make backup of your site and database
1. Delete old files (except /config/config.local.php and directory /uploads/ all files) and copy the new files to a directory site
2.1 Enter the address http://you_site/install/ and to step in creating a database to mark checkbox "Convert 1.0 DB to 1.0.3"
2.2 Or execute the SQL patch /install/convert_1.0_to_1.0.3.sql in phpMyAdmin or via the console MySQL, replacing prefix tables (prefix_) in the database on your

Upgrading from 1.0.1
0. Be sure to make a backup of your site and database
1. Since version 1.0.1 changes is the system files, the templates are not affected. So you can just copy all the files over the current, except the directory /templates/skin/, if you change the template.
   Be sure to replace the file /config/config.php, all changes you need to make the file config.local.php

Upgrading from 1.0.2
0. Be sure to make a backup of your site and database
1. Since version 1.0.2 changes is the system files, the templates are not affected. So you can just copy all the files over the current, except the directory /templates/skin/, if you change the template.
   Be sure to replace the file /config/config.php.
   Changed only 4 files: config/jevix.php, engine/lib/external/Jevix/jevix.class.php, engine/lib/external/swfupload/swfupload.swf, engine/modules/session/Session.class.php


Configuration and Tuning Engines
Settings are in a file /config/config.php. For them to change is desirable to override these settings in the file config.local.php, this will avoid problems with future upgrades.
Management plug-ins can be found at /admin/plugins/

STANDARD TEMPLATES
Version 1.* support only jQuery templates!

OPPORTUNITIES SEARCH
LiveStreet supports full-text search on the site using the search engine Sphinx.
Accordingly, if you need search on the site, you must install and configure the server Sphinx, a sample configuration file (sphinx.conf) is located in the directory /install/


Official site of the project http://livestreetcms.com
Russian community http://livestreet.ru