<?php

namespace spec\Eadrax\Core\Usecase\User\Edit;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\File $avatar
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $auth_user
     * @param Eadrax\Core\Usecase\User\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($avatar, $user, $auth_user, $repository, $auth, $validation)
    {
        $auth->get_user()->shouldBeCalled()->willReturn($auth_user);
        $auth_user->id = 'id';
        $user->password = 'password';
        $user->password_verify = 'password_verify';
        $user->email = 'email@address.com';
        $user->bio = 'bio';
        $user->website = 'http://website.com';
        $user->location = 'location';
        $user->avatar = $avatar;
        $user->dob = 'dob';
        $user->gender = 'gender';
        $user->show_email = TRUE;
        $user->receive_notifications = TRUE;

        $this->beConstructedWith($user, $repository, $auth, $validation);

        $this->id->shouldBe('id');
        $this->password->shouldBe('password');
        $this->password_verify->shouldBe('password_verify');
        $this->email->shouldBe('email@address.com');
        $this->bio->shouldBe('bio');
        $this->website->shouldBe('http://website.com');
        $this->location->shouldBe('location');
        $this->avatar->shouldBe($avatar);
        $this->dob->shouldBe('dob');
        $this->gender->shouldBe('gender');
        $this->show_email->shouldBe(TRUE);
        $this->receive_notifications->shouldBe(TRUE);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Edit\User');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_authorises_logged_in_users($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_does_not_authorise_guests($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_checks_for_valid_user_information($validation)
    {
        $validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->validate();
    }

    function it_checks_for_invalid_user_information($validation, $avatar)
    {
        $validation->setup(array(
            'password' => 'password',
            'email' => 'email@address.com',
            'website' => 'http://website.com',
            'avatar' => $avatar,
            'dob' => 'dob'
        ))->shouldBeCalled();
        $validation->rule('password', 'not_empty')->shouldBeCalled();
        $validation->rule('password', 'min_length', '6')->shouldBeCalled();
        $validation->rule('password', 'matches', 'password_verify')->shouldBeCalled();
        $validation->rule('email', 'not_empty')->shouldBeCalled();
        $validation->rule('email', 'email')->shouldBeCalled();
        $validation->rule('website', 'url')->shouldBeCalled();
        $validation->rule('avatar', 'upload_valid')->shouldBeCalled();
        $validation->rule('avatar', 'upload_type', array('jpg', 'png'))->shouldBeCalled();
        $validation->rule('avatar', 'upload_size', '1M')->shouldBeCalled();
        $validation->rule('dob', 'date')->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->willReturn(array('foo'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_updates_the_users_data($repository)
    {
        $repository->edit_user($this)->shouldBeCalled();
        $this->update();
    }
}
