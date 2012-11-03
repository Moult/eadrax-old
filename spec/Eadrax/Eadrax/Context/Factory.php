<?php

namespace spec\Eadrax\Eadrax\Context;

trait Factory
{
    function it_should_be_able_to_store_and_retrive_data()
    {
        $this->beConstructedWith(array('foo' => 'bar'));
        $this->get_data('foo')->shouldBe('bar');
    }
}
