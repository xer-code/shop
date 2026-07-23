<?php

namespace App\Core;

/**
 * Controller — Base class with render, redirect, json helpers
 */
class Controller
{
    /**
     * Render a view with layout
     */
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewEngine = new View();
        $viewEngine->render($view, $data, $layout);
    }

    /**
     * Render a view without layout
     */
    protected function renderPartial(string $view, array $data = []): void
    {
        $viewEngine = new View();
        $viewEngine->renderPartial($view, $data);
    }

    /**
     * Render admin view with admin layout
     */
    protected function renderAdmin(string $view, array $data = []): void
    {
        $viewEngine = new View();
        $viewEngine->render($view, $data, 'admin/layout');
    }

    /**
     * Send JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . url($url));
        exit;
    }

    /**
     * Redirect back to the previous page
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $referer);
        exit;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::verifyCsrf($token)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            $this->back();
        }
    }

    /**
     * Get POST data with optional default
     */
    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET parameter
     */
    protected function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
