<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class User extends Data\User
{
    public function __construct(Data\User $user, Repository $repository, Tool\Auth $auth, Tool\Validation $validation)
    {
        $auth_user = $auth->get_user();
        $this->id = $auth_user->id;

        $this->password = $user->password;
        $this->password_verify = $user->password_verify;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->website = $user->website;
        $this->location = $user->location;
        $this->avatar = $user->avatar;
        $this->dob = $user->dob;
        $this->gender = $user->gender;
        $this->show_email = $user->show_email;
        $this->receive_notifications = $user->receive_notifications;

        $this->repository = $repository;
        $this->auth = $auth;
        $this->validation = $validation;
    }

    public function authorise()
    {
        if ( ! $this->auth->logged_in())
            throw new Exception\Authorisation('You need to be logged in.');
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validation->check())
            throw new Exception\Validation($this->validation->errors());
    }

    private function setup_validation()
    {
        $this->validation->setup(array(
            'password' => $this->password,
            'email' => $this->email,
            'website' => $this->website,
            'avatar' => $this->avatar,
            'dob' => $this->dob
        ));
        $this->validation->rule('password', 'not_empty');
        $this->validation->rule('password', 'min_length', '6');
        $this->validation->rule('password', 'matches', 'password_verify');
        $this->validation->rule('email', 'not_empty');
        $this->validation->rule('email', 'email');
        $this->validation->rule('website', 'url');
        $this->validation->rule('avatar', 'upload_valid');
        $this->validation->rule('avatar', 'upload_type', array('jpg', 'png'));
        $this->validation->rule('avatar', 'upload_size', '1M');
        $this->validation->rule('dob', 'date');
    }

    public function update()
    {
        $this->repository->edit_user($this);
    }
}
