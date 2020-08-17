### Admin tool "User related debugmode"
#### Purpose
If you want to activate the debugging mode for some users on your system, the setting of `$CFG->debugusers` isn’t the best solution. You have to edit the `config.php` which allways is a risk to break you system. Also with this setting you only change the behaviour of the function `debugging()`. But if the debugging setting is turned of you’ll never see the debug messages.
*Why do I need this?*
If you adminstrate some productive systems sometimes you have a situation to need to turn on the debugging mode. But if you do this on a productive system, all users will see the debugging messages. In most cases it isn’t what you want.
So with this plugin you can turn on the debugging mode just for you or some selected users.
#### Here are the main features of this plugin:
* You can do all settings on the interface. There is no need to edit the `config.php`.
* You can turn on the full set of debugging features for defined users. This means you can leave the moodle standard debug setting to “None”.
* You can turn on the debuggin mode just for the current session (*ad hoc debugging*). So you don’t need to turn it off after usage.
#### Installation
1. Copy this plugin into the folder `admin/tool` inside your moodle installation.
1. Go to the *Site administration -> Notifications* to start the install process.
#### Usage
After the installation you will find the new link *User related debugmode* below *Site administration -> Development*
The *Ad hoc debugmode* you can turn on in your profile settings or in the frontpage menu.
