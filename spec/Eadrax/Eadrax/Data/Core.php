<?php

namespace spec\Eadrax\Eadrax\Data;

trait Core
{
    function it_should_be_able_to_define_ids()
    {
        $this->set_id('foo');
        $this->get_id()->shouldBe('foo');
    }

    function it_should_be_able_to_construct_attributes()
    {
        $this->beConstructedWith(array('id' => 'foo'));
        $this->get_id()->shouldBe('foo');
    }
}
