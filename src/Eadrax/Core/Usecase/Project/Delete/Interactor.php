<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Delete;

class Interactor
{
    private $author;
    private $proposal;

    public function __construct(Author $author, Proposal $proposal)
    {
        $this->author = $author;
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->author->authorise();
        $this->proposal->authorise();
        $this->proposal->delete();
    }
}
