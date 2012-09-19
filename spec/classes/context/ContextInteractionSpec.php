<?php

class DescribeContextInteraction extends \PHPSpec\Context
{
    use Context_Interaction;

    public function itCanAddLinksToOtherObjectsOrRoles()
    {
        // Check other role awareness
        $imaginary_role = Mockery::mock('Imaginary_Role');
        $this->link(array('imaginary_role' => $imaginary_role));

        $this->spec($this->imaginary_role)->should->be($imaginary_role);
    }
}
