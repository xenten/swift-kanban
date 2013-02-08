-----------------------------------------------------------------
JFUploader 2.15.x - README

For the latest version and more information please go to 
http://jfu.tinywebgallery.com
-----------------------------------------------------------------

Requirements
------------
Browser:    Flash 8 plugin
Web Server: Joomla 1.0.x, php > 4.x with gdlib > 2.0

Installation
------------
Simply install the zip file as a component in the Joomla backend.

There could be problem with the installation when your server does 
have safe mode active. To install JFU with safe mode properly please:

1. Use the safe mode patch - This is actually useful for all your other 
   installtions too (if you have safe mode active on your server) ;).
   AND
2. Go to 'Site -> Configuration -> Server' and change the settings for
   'CHMOD new files' and 'CHMOD new directories' to 777 (or 775).    
   This should be done if you get one of the following error messages:
   - Failed to move uploaded file to /media directory.
   - Unrecoverable error "PCLZIP_ERR_READ_OPEN_FAIL (-2)"
   
   If you change the permissions please do this BEFORE installing JFU.
   Please note that there are many server configurations do exist and 
   every setting is a little bit different. Therefore you should first 
   try to install JFU without any modifications and then try to make 
   the settings described above. 
   
   
Unistall
--------
You can select on the configuration page if you want to keep your 
profiles and user mappings when uninstalling JFU. By default this it 
turned ON! if you completely want to remove JFU please change the 
setting in the configuration first and then uninstall JFU again.

License
-------
Please note that 2 licenses affect this software bundle.
The JFUploader is bridge between Joomla and the 
TWG Flash Uploader. Therefore all parts that belong to the 
Joomla integration are distributed under the GNU GENERAL 
PUBLIC LICENSE. The TWG Flash Uploader Flash itself is distributed 
under the TWG Flash Upload Freeware License Agreement. 
Please see license.txt for details.

From the Joomla Webpage:
A bridge links Joomla! to an external application (here the TWG 
Flash Uploader) so that they can exchange data and cooperate. 
On the Joomla! side of the bridge, the bridge is treated just like 
a component, module, or plugin; it must comply with the GPL unless 
it is a separate work (and some bridges might indeed be separate works).

If the external application is separate enough from Joomla! 
that it is a separate work under copyright law, it may be licensed 
under whatever license the holder of its copyright sees fit. 
   
Have fun using JFU.
/Michael