<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Formatter
{
    /**
     * Sets up the formatter with data to use
     *
     * Example:
     * $formatter->setup(['foo' => 'bar']);
     *
     * @param array $data Data to format
     *
     * @return void
     */
    public function setup($data);

    /**
     * Formats the data in the template specified
     *
     * Example:
     * $formatter->format('Email_Notification');
     *
     * @param string $template The name of the formatting template to use
     *
     * @return string
     */
    public function format($template);
}
