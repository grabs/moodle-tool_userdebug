Admin tool "User related debugmodus"
====================================
Purpose:
--------
With this plugin you can define the debugmodus for only some selected users.
The main advantage of this is, that you can activate the debugmodus just in
a production environment without disturbing the regular work.
Only users who are defined as debuguser can see the debug informations.

Installation:
-------------
1)
Copy this plugin into the folder "admin/tool" inside your moodle installation.

2)
Go to the "Site administration -> Notifications" to start the install process.

3)
Go to your config.php and add the following line at the end:
require_once($CFG->dirroot.'/admin/tool/userdebug/lib.php');

Usage:
------
After the installation you will find the new link "User related debugmodus"
below "Site administration -> Development"
