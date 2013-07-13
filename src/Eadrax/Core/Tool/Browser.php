<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Browser
{
    /**
     * Screenshots a website, and generates a thumbnail of it
     *
     * Example:
     * $image->screenshot('http://foo.com');
     *
     * @param string $url The website to screenshot
     *
     * @return string The path to the screenshot file
     */
    public function screenshot($url);
}
