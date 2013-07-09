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
     * $image->screenshot('http://foo.com', '/path/to/image.png');
     *
     * @param string $url          The website to screenshot
     * @param string $path_to_file The image path to save to
     *
     * @return void
     */
    public function screenshot($url, $path_to_file);
}
