<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // --- Login page ---
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('login');
    }

    // --- Register page (public user) ---
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('register');
    }

    // --- Process register (public user = lecturer) ---
    public function process_register()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = trim($this->request->getPost('password'));
        $confirm  = trim($this->request->getPost('confirm_password'));
        $email    = $this->request->getPost('email');

        // default lecturer
        $role = 'lecturer';

        log_message('debug', "AuthController - Register - Password: [{$password}] Confirm: [{$confirm}]");

        // check duplicate username/email
        if ($userModel->where('username', $username)->first()) {
            return redirect()->to('/register')->with('error', 'Username already exists');
        }
        if ($userModel->where('email', $email)->first()) {
            return redirect()->to('/register')->with('error', 'Email already exists');
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
        }

        $data = [
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Register success. Please login.');
    }

    // --- Admin Register Form ---
    public function accountRegister()
    {
        return view('admin/account_register');
    }

    // --- Process admin register (multi role) ---
    public function process_accountRegister()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $password = trim($this->request->getPost('password'));
        $confirm  = trim($this->request->getPost('confirm_password'));
        $roles    = $this->request->getPost('roles') ?? [];

        $roleString = implode(',', $roles);


        // check duplicate username/email
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username already exists');
        }
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email already exists');
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
        }

        $data = [
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => $roleString,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $userModel->insert($data);

        return redirect()->back()->with('success', 'User registered successfully.');
    }

    // --- Process login ---
    public function process_login()
    {
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id'         => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    // --- Reset password page ---
    public function resetPassword()
    {
        return view('reset_password');
    }

    // --- Process reset password ---
    public function process_resetPassword()
    {
        $rules = [
            'email'        => 'required|valid_email',
            'new_password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $email     = trim($this->request->getPost('email'));
        $user      = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email not found');
        }

        $userModel->update($user['id'], [
            'password'   => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Password updated successfully. Please login.');
    }

    // --- Logout ---
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
