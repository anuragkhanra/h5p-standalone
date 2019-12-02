# h5p-standalone
Contributor: anuragkhanra
Requires at least: 3.0.1
Tested up to: WordPress 5.3
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


###  Description

A wordpress plugin to automate the task of running h5p content in static websites.


## Installation

1. Goto Wordpress -> Plugins -> Add New -> Upload Plugin
2. Upload the plugin zip and Activate the plugin
3. Or, Simply clone the repo to wordpress/wp-content/plugins/ and rename the plugin dir to wp-stand
4. Goto Wordpress -> Settings -> h5p StandAlone
5. In the Initial run click 'Set Up'
6. Refresh the page
7. Change the 4 links in wp-stand/admin/partials/h5p/demo/index.html to optimize
8. After proecess download the zip
9. Clone [tunapanda/h5p-standalone](https://github.com/tunapanda/h5p-standalone/tree/090ee3c17c655832cd99b492e1a5d21fbb31ab04)
10. Extract the zip to the new clone /workspace/

## Possible Errors

* Make sure your wordpress dir reside in Host/wordpress/
Else manually change the links on line 120, 184, 191, 222 of wp-stand/admin/partials/wp-stand-admin-display.php

* Recheck if the clone is in /plugins/wp-stand/

### Changelog

* 1.0 
Initial release
* 1.1
 Lots of enhancements
* 1.2
Bugs fixed, Process and Zip download optimized