<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AccountRegisterController extends BaseController
{
    public function index()
    {
        return view('admin/account_register');
    }

    public function store()
    {
        $db = db_connect();

        $username         = $this->request->getPost('username');
        $email            = $this->request->getPost('email');
        $password         = trim($this->request->getPost('password'));
        $confirm_password = trim($this->request->getPost('confirm_password'));
        $roles            = $this->request->getPost('roles');

        if ($password !== $confirm_password) {
            return redirect()->back()->withInput()->with('error', 'Password confirmation does not match.');
        }

        if (empty($roles)) {
            return redirect()->back()->withInput()->with('error', 'Please select at least one role.');
        }

        try {
            $db->table('users')->insert([
                'username'   => $username,
                'email'      => $email,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'role'       => implode(',', $roles),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to('admin/account-register')->with('success', 'Account registered successfully!');
        } catch (DatabaseException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }
}
