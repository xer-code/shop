<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please log in to edit your profile.');
            $this->redirect('/login');
            return;
        }

        $user = User::find(Auth::id());
        $this->render('profile/index', [
            'pageTitle' => 'My Profile Settings',
            'user' => $user
        ]);
    }

    public function update(): void
    {
        $this->validateCsrf();
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = Auth::id();
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $password = trim($this->input('password', ''));

        if (empty($name) || empty($email)) {
            Session::flash('error', 'Name and Email are required.');
            $this->redirect('/profile');
            return;
        }

        // Check email uniqueness if changed
        $currentUser = User::find($id);
        if ($email !== $currentUser['email']) {
            if (User::emailExists($email)) {
                Session::flash('error', 'This email address is already in use.');
                $this->redirect('/profile');
                return;
            }
        }

        $updateData = [
            'name' => $name,
            'email' => $email
        ];

        // Process password update if provided
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Process avatar file upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['avatar']['tmp_name'];
            $filename = basename($_FILES['avatar']['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $uploadDir = APP_PATH . '/../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $newFilename = uniqid('avatar_', true) . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                    $updateData['avatar'] = 'uploads/avatars/' . $newFilename;
                }
            } else {
                Session::flash('error', 'Avatar image must be in PNG, JPG, or JPEG format.');
                $this->redirect('/profile');
                return;
            }
        }

        User::update($id, $updateData);
        Auth::refreshWallet(); // Refresh session credentials
        
        // Re-authenticate in session with new email/name
        $refreshed = User::find($id);
        Session::set('user_name', $refreshed['name']);
        Session::set('user_email', $refreshed['email']);

        Session::flash('success', 'Your profile details have been successfully updated.');
        $this->redirect('/profile');
    }
}
