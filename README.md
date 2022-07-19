# Late Night Linux Discoveries
A simple PHP script that extracts the Discoveries section from the Late Night Linux Podcast

In Episode 185 of Late Night Linux, the guys read feedback from someone who wanted an ongoing list of the discoveries section of the podcast.  Joe said, it couldn't be too hard, but the description would be the hard part, since it's not in the show notes.  It felt like a fun coding challenge, so why not!

This is what I came up with...

![Screenshot from 2022-07-18 20-16-04](https://user-images.githubusercontent.com/6528226/179657572-18848a07-e774-49bb-937a-c202da726d2d.png)

This is all self contained in single PHP file that you can drop on ANY webserver running Apache or Nginx, and just uses good old vanilla PHP, to parse out the podcast RSS feed, then a little jQuery to async load the descriptions, and finally little bit of TailwindCSS to lay it out and make it look decent.

The descriptions simply follow the discovery URL and pulls in the meta description for that site.  It's not perfect, and not all of the links even have a meta description (shame!!!), but it ends up filling in the details pretty nicely.

# Installation 

Simply drop this discoveries.php file on any server running Apache or Nginx and PHP 7.x or newer, and you should be able to load it in a browser window.  You can even rename the file if you wanted.

NOTE: You will have to enable CORS (cross-origin resources) to pull the podcast feed and descriptions

Working example can be found here:
https://beta.vipmembervault.com/discoveries.php
