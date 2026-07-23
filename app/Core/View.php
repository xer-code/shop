<?php

namespace App\Core;

/**
 * View — Simple PHP templating engine with layouts and partials
 */
class View
{
    /**
     * Render a view within a layout
     */
    public function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extract data to make variables available in views
        extract($data);

        // Capture the view content
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render within layout
        $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout file not found: {$layoutFile}");
        }

        // Page title (set in view or default)
        $pageTitle = $pageTitle ?? APP_NAME;

        require $layoutFile;
    }

    /**
     * Render a view without layout
     */
    public function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }
        require $viewFile;
    }

    /**
     * Render an error page
     */
    public function renderError(int $code, string $message): void
    {
        $data = ['code' => $code, 'message' => $message, 'pageTitle' => "{$code} — " . APP_NAME];
        extract($data);
        
        $errorView = VIEW_PATH . '/errors/' . $code . '.php';
        if (file_exists($errorView)) {
            ob_start();
            require $errorView;
            $content = ob_get_clean();
        } else {
            $content = "<div class='min-h-screen flex items-center justify-center'>
                <div class='text-center'>
                    <h1 class='text-6xl font-bold text-gold mb-4'>{$code}</h1>
                    <p class='text-xl text-gray-400'>{$message}</p>
                    <a href='" . url('/') . "' class='mt-6 inline-block btn-gold'>Go Home</a>
                </div>
            </div>";
        }

        $layoutFile = VIEW_PATH . '/layouts/main.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Include a partial (used within views)
     */
    public static function partial(string $name, array $data = []): void
    {
        extract($data);
        $file = VIEW_PATH . '/partials/' . $name . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
}
