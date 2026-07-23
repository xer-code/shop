<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\User;
use App\Models\Cart;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', ['pageTitle' => 'Sign In — ShopX Global']);
    }
    
    public function login(): void
    {
        $this->validateCsrf();
        $email = $this->input('email', '');
        $password = $this->input('password', '');
        
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please fill in all fields.');
            Session::flashInput($_POST);
            $this->redirect('/login');
            return;
        }
        
        if (Auth::attempt($email, $password)) {
            // Merge guest cart
            Cart::mergeGuestCart(session_id(), Auth::id());
            $this->updateCartCount();
            Session::flash('success', 'Welcome back, ' . Auth::name() . '!');
            $this->redirect('/');
        } else {
            Session::flash('error', 'Invalid email or password.');
            Session::flashInput($_POST);
            $this->redirect('/login');
        }
    }
    
    public function registerForm(): void
    {
        $this->render('auth/register', ['pageTitle' => 'Create Account — ShopX Global']);
    }
    
    public function register(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $password = $this->input('password', '');
        $passwordConfirm = $this->input('password_confirmation', '');
        
        // Validation
        $errors = [];
        if (empty($name)) $errors[] = 'Name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $passwordConfirm) $errors[] = 'Passwords do not match.';
        if (User::emailExists($email)) $errors[] = 'Email already registered.';
        
        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            Session::flashInput($_POST);
            $this->redirect('/register');
            return;
        }
        
        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => Auth::hashPassword($password),
            'role' => 'customer',
            'wallet_balance' => 0,
        ]);
        
        $user = User::find($userId);
        Auth::login($user);
        Cart::mergeGuestCart(session_id(), $userId);
        $this->updateCartCount();
        
        Session::flash('success', 'Welcome to ShopX Global, ' . $name . '!');
        $this->redirect('/');
    }
    
    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'You have been signed out.');
        $this->redirect('/');
    }
    
    private function updateCartCount(): void
    {
        if (Auth::check()) {
            $cartId = Cart::getOrCreate(Auth::id(), session_id());
            Session::set('cart_count', Cart::getItemCount($cartId));
        }
    }
}
