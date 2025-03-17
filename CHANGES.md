## Release notes

### Release v4.5.1
* Do not show info box without real debug settings (#15)
* Add ad hoc debug button to user menu (#16)

### Release v4.5
* Implement new hook api (after_config)

### Release v4.4
* Move legacy style config settings to plugin config settings
* Remove toggle button for debug settings
* Show an infobox if the user debug mode is active

### Release v4.3.1
* Check the realuser from \core\session\manager::get_realuser() to have debugging in "loginas" sessions too.

### Release v4.3
* Add icon for ad hoc debuggin to the usernavigation on top of the page.
* Adjust new coding style rules for arrays.

### Release v4.2
* Modify github actions

### Release v4.1
* Put the settings in addition to the $CFG into $CFG->config_php_settings to prevent accidental saving of the settings.
* Add behat testing features
* Optimization of coding style

### Release 2020081601
* Put more infos into README.txt

### Version 2020081600
* Introducing ad hoc debugging mode
Now in the frontpage menu and in your profile settings you can find an entry to activate debugging just for the current session (ad hoc)
You also can use a url parameter `userdebug=1` at any time to activate the debugging.
Site admins can do this by default. For other users you have to give them the capability `tool/userdebug:adhocdebug`

### Version 2020080100
* New version without the need to modify any core files
* No it is triggered by the hook function `tool_userdebug_after_config` which came with Moodle 3.8
* Some small bugfixes for better integrating in Moodle
