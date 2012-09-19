# About Eadrax

Eadrax is a diary for your creations.

A instance of WIPUP can be found at http://wipup.org/

# About Eadrax-v3

This branch aims to rewrite Eadrax with the following major changes.

## Re-target WIPUP's audience

 * A more distinctive visual aesthetic (clean and whimsical)
 * Donationware usage
 * Improved project privacy settings
 * Added "highlights" pick for updates
 * Better bulk update management
 * Away from just ambitious projects to just constant production
 * Remove the group blog idea and focus on project views
 * Revitalise the API to prepare for native apps

This might help define WIPUP's new audience:

> You might like WIPUP if you:
>  * Love making stuff, and do it often
>  * Want a no-nonsense place to track and share your creations
>  * Work with different mediums: from images, video, music to code
>  * Have a project-oriented workflow
>  * Want to visualise your process and progress
>  * Prefer open-source software

## Switch to ISC license

More permissive and doesn't make your brain explode.

## Switch from KO2 to KO3

KO2 is nice, but no longer supported, and the recommended method to start new
projects is by using KO3.

## Use best practices

BDD, stricter coding style, templating, improved docs ...

## Refactor

I was guilty of code rot.

# System Requirements

 * PHP >= 5.3

The following are unconfirmed requirements for the v3 branch. If you encounter
bugs, ensure you satisfy these.

 * Apache >= 2.2
 * MySQL >= 5.0
 * ffmpeg >= 0.6 (needed for video features)
 * ffmpeg-php >= 0.6 (needed for video features)
 * diff >= 2.8.0 (needed for revision features)
 * PEAR Text\_Diff >= 1.1.1 (needed for inline revision features)
 * An RPX API key by Janrain's OpenID services. (needed for OpenID features)

The most recent packages are recommended.

# Installing Eadrax

1. You can grab the latest copy of Eadrax project from http://github.com/Moult/Eadrax
    * **master** - latest "live" stable version (used by http://live.wipup.org)
    * **v3** - unstable rewrite

2. Upload a copy of Eadrax to your webserver. It is possible to install Eadrax
in a subdirectory.

3. Make sure the following directories are writeable by your webserver:
    * application/cache/
    * application/logs/

4. Use the schema in DATABASE to create a new MySQL database

5. Edit configuration files.
    * .htaccess
    * application/bootstrap.php
    * application/config/database.php

6. Read KO docs for extra recommended procedures for public deployment

If you did everything right, point your browser at the location you installed
Eadrax into and everything should work.

# Developer information

TODO
