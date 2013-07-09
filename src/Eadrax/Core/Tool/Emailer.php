<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Emailer
{
    /**
     * Sets the 'to' email address.
     *
     * Example:
     * $emailer->set_to(array('foo@bar.com', 'bar@foo.com'));
     *
     * @param array $to An array of email addresses to send to.
     *
     * @return void
     */
    public function set_to($to);

    /**
     * Sets the 'from' email address.
     *
     * Example:
     * $emailer->set_from(array('foo@bar.com', 'bar@foo.com'));
     *
     * @param array $from An array of email addresses to send from.
     *
     * @return void
     */
    public function set_from($from);

    /**
     * Sets whether or not the email is HTML.
     *
     * Example:
     * $emailer->set_html(TRUE);
     *
     * @param bool $is_html TRUE is the email is HTML, else FALSE for plaintext
     *
     * @return void
     */
    public function set_html($is_html);

    /**
     * Sets the subject of the email.
     *
     * Example:
     * $emailer->set_subject('Foobar');
     *
     * @param string $subject The subject of the email
     *
     * @return void
     */
    public function set_subject($subject);

    /**
     * Sends out a new email.
     *
     * Example:
     * $mail->send();
     *
     * @return void
     */
    public function send();
}
