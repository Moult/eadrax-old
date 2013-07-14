<?php

namespace spec\Eadrax\Core\Usecase\User\Edit;

use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\File $avatar
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $auth_user
     * @param Eadrax\Core\Usecase\User\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($avatar, $user, $auth_user, $repository, $authenticator, $validator)
    {
        $avatar->name = 'name';
        $avatar->tmp_name = 'tmp_name';
        $avatar->mimetype = 'mimetype';
        $avatar->filesize_in_bytes = 'filesize_in_bytes';
        $avatar->error_code = 'error_code';

        $authenticator->get_user()->shouldBeCalled()->willReturn($auth_user);
        $auth_user->id = 'id';
        $user->password = 'password';
        $user->password_verify = 'password_verify';
        $user->email = 'email';
        $user->bio = 'bio';
        $user->website = 'website';
        $user->location = 'location';
        $user->avatar = $avatar;
        $user->dob = 'dob';
        $user->gender = 'gender';
        $user->show_email = 'show_email';
        $user->receive_notifications = 'receive_notifications';

        $this->beConstructedWith($user, $repository, $authenticator, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Edit\User');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_authorises_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_does_not_authorise_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_checks_for_valid_user_information($validator)
    {
        $validator->setup(array(
            'password' => 'password',
            'email' => 'email',
            'website' => 'website',
            'avatar' => array(
                'name' => 'name',
                'tmp_name' => 'tmp_name',
                'type' => 'mimetype',
                'size' => 'filesize_in_bytes',
                'error' => 'error_code'
            ),
            'dob' => 'dob'
        ))->shouldBeCalled();
        $validator->rule('password', 'not_empty')->shouldBeCalled();
        $validator->rule('password', 'min_length', '6')->shouldBeCalled();
        $validator->rule('password', 'matches', 'password_verify')->shouldBeCalled();
        $validator->rule('email', 'not_empty')->shouldBeCalled();
        $validator->rule('email', 'email')->shouldBeCalled();
        $validator->rule('website', 'url')->shouldBeCalled();
        $validator->rule('avatar', 'upload_valid')->shouldBeCalled();
        $validator->rule('avatar', 'upload_type', array('jpg', 'png'))->shouldBeCalled();
        $validator->rule('avatar', 'upload_size', '1M')->shouldBeCalled();
        $validator->rule('dob', 'date')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(TRUE);
        $this->validate();
    }

    function it_checks_for_invalid_user_information($validator)
    {
        $validator->setup(array(
            'password' => 'password',
            'email' => 'email',
            'website' => 'website',
            'avatar' => array(
                'name' => 'name',
                'tmp_name' => 'tmp_name',
                'type' => 'mimetype',
                'size' => 'filesize_in_bytes',
                'error' => 'error_code'
            ),
            'dob' => 'dob'
        ))->shouldBeCalled();
        $validator->rule('password', 'not_empty')->shouldBeCalled();
        $validator->rule('password', 'min_length', '6')->shouldBeCalled();
        $validator->rule('password', 'matches', 'password_verify')->shouldBeCalled();
        $validator->rule('email', 'not_empty')->shouldBeCalled();
        $validator->rule('email', 'email')->shouldBeCalled();
        $validator->rule('website', 'url')->shouldBeCalled();
        $validator->rule('avatar', 'upload_valid')->shouldBeCalled();
        $validator->rule('avatar', 'upload_type', array('jpg', 'png'))->shouldBeCalled();
        $validator->rule('avatar', 'upload_size', '1M')->shouldBeCalled();
        $validator->rule('dob', 'date')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->willReturn(array('foo'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_updates_the_users_data($repository)
    {
        $repository->update_avatar(
            'id',
            'name',
            'tmp_name',
            'mimetype',
            'filesize_in_bytes',
            'error_code'
        )->shouldBeCalled()->willReturn('avatar_path');

        $repository->edit_user(
            'id',
            'password',
            'email',
            'bio',
            'website',
            'location',
            'avatar_path',
            'dob',
            'gender',
            'show_email',
            'receive_notifications'
        )->shouldBeCalled();

        $this->update();
    }
}
