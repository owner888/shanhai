<?php
// The record of every beast as a page to read outside the game: php tools/bestiary.php
// Writes docs/bestiary.html (src/Bestiary.php): open it in a browser. Run again when a beast is drawn or its data changes;
// a test says when it is behind.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

file_put_contents(\Shanhai\Bestiary::PAGE, \Shanhai\Bestiary::page());
echo "docs/bestiary.html\n";
