<?php
// Loads the game's own classes (Shanhai\ → src/). boot.php loads MyPHP.
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'Shanhai\\')) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 8)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});
