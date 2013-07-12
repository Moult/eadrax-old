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
    public function __construct(Data\User $user, Repository $repository, Tool\Authenticator $authenticator, Tool\Validator $validator)
    {
        $auth_user = $authenticator->get_user();
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
        $this->authenticator = $authenticator;
        $this->validator = $validator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in.');
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    private function setup_validation()
    {
        $this->validator->setup(array(
            'password' => $this->password,
            'email' => $this->email,
            'website' => $this->website,
            'avatar' => array(
                'name' => $this->avatar->name,
                'tmp_name' => $this->avatar->tmp_name,
                'type' => $this->avatar->mimetype,
                'size' => $this->avatar->filesize_in_bytes,
                'error' => $this->avatar->error_code
            ),
            'dob' => $this->dob
        ));
        $this->validator->rule('password', 'not_empty');
        $this->validator->rule('password', 'min_length', '6');
        $this->validator->rule('password', 'matches', 'password_verify');
        $this->validator->rule('email', 'not_empty');
        $this->validator->rule('email', 'email');
        $this->validator->rule('website', 'url');
        $this->validator->rule('avatar', 'upload_valid');
        $this->validator->rule('avatar', 'upload_type', array('jpg', 'png'));
        $this->validator->rule('avatar', 'upload_size', '1M');
        $this->validator->rule('dob', 'date');
    }

    public function update()
    {
        $avatar_path = $this->repository->update_avatar(
            $this->id,
            $this->avatar->name,
            $this->avatar->tmp_name,
            $this->avatar->mimetype,
            $this->avatar->filesize_in_bytes,
            $this->avatar->error_code
        );

        $this->repository->edit_user(
            $this->id,
            $this->password,
            $this->email,
            $this->bio,
            $this->website,
            $this->location,
            $avatar_path,
            $this->dob,
            $this->gender,
            $this->show_email,
            $this->receive_notifications
        );
    }
}
