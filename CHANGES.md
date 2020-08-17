## Release notes

### Version 2020080100
* New version without the need to modify any core files
* No it is triggered by the hook function `tool_userdebug_after_config` which came with Moodle 3.8
* Some small bugfixes for better integrating in Moodle

### Version 2020081600
* Introducing ad hoc debugging mode
Now in the frontpage menu and in your profile settings you can find an entry to activate debugging just for the current session (ad hoc)
You also can use a url parameter `userdebug=1` at any time to activate the debugging.
Site admins can do this by default. For other users you have to give them the capability `tool/userdebug:adhocdebug`

#### Release 2020081601
* Put more infos into README.txt
