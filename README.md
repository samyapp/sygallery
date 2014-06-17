sygallery
=========

A *very* simple plugin to display image galleries from old nextgen gallery wordpress plugin in the way I want them to... 

Adds a shortcode to wordpress in the form

[sygallery id=42]

where "42" is the gid gallery id of a gallery created with the nextgen gallery plugin.

The shortcode is displayed by thumbnails for each pic in the gallery, as a simple image/link to the permalink page for the post containing the shortcode, with a ?pid=pic-id parameter added on the end.

If the pid request parameter is detected, and the value matches the id of a picture in the specified ngg gallery, then instead of thumbnails, that picture, with its alt text, and thumbnail links to the previous and next pics in the gallery will all be displayed instead of all the gallery thumbnails.

This is very basic stuff thrown together in a hurry for my single use-case... 

no plans to support it, but I'll look at any issues should they be opened.

Good luck.
