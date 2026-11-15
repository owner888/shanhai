<?php
// Runs every test here (the *_test.php files) and reports; exits 1 when one fails: php tests/run.php [name...]
// These need PHP alone, no window and no MyPHP: they test the game's rules and data, not its drawing.
declare(strict_types=1);

namespace Shanhai\Tests;

require __DIR__ . '/../src/load.php';

$checks = 0;
$failures = [];

function check(bool $cond, string $what): void
{
    $GLOBALS['checks']++;
    if (!$cond) {
        $GLOBALS['failures'][] = $what;
        echo "  FAIL: $what\n";
    }
}

function same(mixed $want, mixed $got, string $what): void
{
    check($want === $got, $what . ': want ' . json_encode($want, JSON_UNESCAPED_UNICODE) . ', got ' . json_encode($got, JSON_UNESCAPED_UNICODE));
}

/** Whether $fn throws. */
function throws(\Closure $fn): bool
{
    try {
        $fn();
    } catch (\Throwable) {
        return true;
    }
    return false;
}

error_reporting(E_ALL);
set_error_handler(function (int $no, string $msg, string $file, int $line): bool {
    check(false, "$msg at $file:$line");
    return true;
});

$names = array_slice($argv, 1);
foreach (glob(__DIR__ . '/*_test.php') as $file) {
    $name = basename($file, '_test.php');
    if ($names !== [] && !in_array($name, $names, true)) {
        continue;
    }
    echo "$name\n";
    // Each file in a scope of its own: a variable one sets cannot reach another, or this loop.
    (static function (string $file): void {
        require $file;
    })($file);
}
printf("%d checks, %d failed\n", $checks, count($failures));
exit($failures === [] ? 0 : 1);
