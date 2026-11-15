<?php
// Writes docs/atlas.md from the data: php tools/atlas.php
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

file_put_contents(__DIR__ . '/../docs/atlas.md', Shanhai\Atlas::render());
echo "docs/atlas.md written\n";
