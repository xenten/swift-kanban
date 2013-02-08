 RD Download v0.9 README
 ($Id: readme.txt 446 2009-11-13 17:30:53Z deutz $)
-----------------------------------------

 About
----------
RD Download is a simpe download extension for Joomla 1.5 and PHP 5 only.
It features embedding of downloads into content items, automated addition 
of new files into the database (directory watch) and a download counter.

 
 Installation
-----------------------------
1. Install component (plugin will be automatically installed and published)

2. Create a download-dir

3. Set component parameters "Download dir" in backend.
   If the value start with an / I suppose it is a absolut dir, if not then 
   this must be a relative dir to joomla base dir
 
4. Set component parameters "Display warnings" in backend. 

 Update
-----------------------------
Deinstall the component and install the new one, keep in mind all Database tables 
where droped when deinstallation is processed. If you like to save your download 
counter backup the rd_download table and reimport the table after update.

 How to use
-----------------------------
1. Upload your file into download dir
2. Insert {rddl}filename.ext{/rddl} into a content item and browse to it
    --> the new file will be displayed and automatically added to the database
3. If you want to change the dl-link text, change "title" in backend
4. If you want to change the download counter you can do this also in the edit view

* Assign a menu item to RD Download to get a list all published downloads

