<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance();
        $users = $db->query("SELECT * FROM users WHERE role != 'customer' ORDER BY created_at DESC")->fetchAll();
        $roles = Session::get('ent_roles', []);
        
        $this->render('admin/users/index', [
            'pageTitle' => 'User Account Management',
            'users' => $users,
            'roles' => $roles
        ], 'admin');
    }

    public function createUser(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $password = trim($this->input('password', ''));
        $role = $this->input('role', 'admin');

        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'All registration fields are required.');
            $this->redirect('/admin/users');
            return;
        }

        if (User::emailExists($email)) {
            Session::flash('error', 'Email is already registered.');
            $this->redirect('/admin/users');
            return;
        }

        // Validate role exists
        $roles = Session::get('ent_roles', []);
        if (!isset($roles[$role]) && $role !== 'admin') {
            Session::flash('error', 'Invalid role selection.');
            $this->redirect('/admin/users');
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        User::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash,
            'role' => $role,
            'wallet_balance' => 0.00
        ]);

        // Dispatch welcome email
        \App\Core\Mailer::sendTriggerEmail('user_welcome', $email, [
            'name' => $name,
            'email' => $email
        ], $name);

        // Audit log
        $logs = Session::get('ent_audit_logs', []);
        $logs[] = [
            'id' => count($logs) + 1,
            'user' => \App\Core\Auth::email() ?? 'admin@shopx.com',
            'action' => "Created admin/staff user account: {$email} with role: {$role}",
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        Session::set('ent_audit_logs', $logs);

        Session::flash('success', 'User account created successfully.');
        $this->redirect('/admin/users');
    }

    public function updateRole(string $id): void
    {
        $this->validateCsrf();
        $user = User::find((int) $id);
        if (!$user) {
            Session::flash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }

        $role = $this->input('role', 'admin');
        $roles = Session::get('ent_roles', []);
        if (!isset($roles[$role]) && $role !== 'admin' && $role !== 'customer') {
            Session::flash('error', 'Invalid role selection.');
            $this->redirect('/admin/users');
            return;
        }

        User::update((int) $id, ['role' => $role]);
        Session::flash('success', 'User role updated successfully.');
        $this->redirect('/admin/users');
    }

    public function suspend(string $id): void
    {
        $this->validateCsrf();
        $user = User::find((int) $id);
        if (!$user) {
            Session::flash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }

        // Suspend toggle
        $newStatus = $user['is_suspended'] ? 0 : 1;
        User::update((int) $id, ['is_suspended' => $newStatus]);
        
        $msg = $newStatus ? 'User account suspended.' : 'User account unsuspended.';
        Session::flash('success', $msg);
        $this->redirect('/admin/users');
    }
}
