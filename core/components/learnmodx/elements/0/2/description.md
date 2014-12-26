Basic Concepts
==============

MODX, in essence, has a ton of moving parts. But the basics parts are:

Resources
---------

Resources are basically a webpage location. It can be actual HTML content, or a file, forwarding link, or a symlink,
or anything else.

Templates
---------

Templates are the house a Resource lives in. They usually contain the footer and header for a page.

Template Variables
------------------
Template Variables (TVs) are custom fields for a Template that allow the user to assign dynamic values to a Resource.
A great example would be a 'tags' TV that allows you to specify tags for a Resource. You can have an unlimited number
of TVs per page.

Chunks
------
Chunks are simply small blocks of content, be it whatever you want inside it. They can contain Snippets, or any other
Element type (Snippet, Chunk, TV, etc).

Snippets
--------
Snippets are dynamic bits of PHP code that run when the page is loaded. They can do anything you can code, including
building custom menus, grabbing custom data, tagging elements, processing forms, grabbing tweets, etc.

Plugins
-------
Plugins are event hooks that run whenever an event is fired. They are usually used for extending the Revolution core
to do something during a part of the loading process - such as stripping out bad words in content, adding dictionary
links to words, managing redirects for old pages, etc.

System Settings
---------------
System Settings give you near infinite configuration options. Most of these are set the best way they should be, but
some things (such as friendly urls) are disabled by default or could be improved for your specific needs just by
changing a setting value. After installation, head over to System > System Settings in the Manager and browse
through the available options. Definitely check out the "Site" area (use the dropdown that says "Filter on area..."),
there are some interesting things there for you.