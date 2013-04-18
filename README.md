Rackspace CloudFiles Helper
===========================

I was looking to simplify the interactions with Rackspace CloudFiles within my code, and so have created this basic helper library.

It needs some minor config to get up and running, but this repo includes a demo upload file to allow you to test if your settings are done properly.

The directory structure is modelled as if you were using Rackspace CloudSites, where SITE_NAME/web/content/ is the public webroot.


** Setup **
Configuration settings related to your cloud files account need to be documented somewhere. For the purposes of this setup, the settings file is located at:

    site-online/web/Settings.php

However, you can use whichever generic settings file you would like. You need to specify your Rackspace username, api-key and account location at minimum


** Usage **

Example usage is available through a demo file at:

    site-online/web/content/upload.php

The file where functionality is embedded is in:

    site-online/web/lib/rackspace_helper/RackspaceHelper.php


** Dependencies **

This repo is dependent on Rackspace's php-opencloud libraries, and that is configured to be available as a submodule to this repo. It is positioned in:

    site-online/web/lib/php-opencloud


** LICENSE **

This code is public domain. No warranty is provided, and I'm not responsible for what happens to you, your cats, or anything else if you use it.