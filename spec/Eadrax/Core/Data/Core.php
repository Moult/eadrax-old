<?php

namespace spec\Eadrax\Core\Data;

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

    function it_can_check_whether_or_not_data_has_been_set()
    {
        $this->beConstructedWith();
        $this->exists()->shouldBe(FALSE);

        $this->set_id('foo');
        $this->exists()->shouldBe(TRUE);
    }
}
