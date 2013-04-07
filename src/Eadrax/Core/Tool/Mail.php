<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Mail
{
    /**
     * Sends out a new email.
     *
     * Example:
     * $mail->send(
     *     'foo@bar.com',
     *     'my@email.com',
     *     'The Email Subject',
     *     'Plain non-HTML body text goes here'
     * );
     *
     * @return void
     */
    public function send($to, $from, $subject, $body);
}
