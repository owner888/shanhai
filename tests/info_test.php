<?php
// What the box beside a list says of the entry the cursor is on (Info): a move, a thing; every move and thing has its say.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Info};

$moves = Data::moves();
$elements = Data::elements();
$items = Data::items();

same(['善走', '无属性　不造成伤害　命中 100　次数 12/30', '狌狌，食之善走。'], Info::move($moves['swift'], $elements, 12), 'a move: its name; its element, power, accuracy and times left; where it comes from');
$striking = array_key_first(array_filter($moves, fn(array $m) => $m['power'] > 0 && $m['element'] !== null));
check(str_starts_with(Info::move($moves[$striking], $elements, 1)[1], $elements[$moves[$striking]['element']]['name'] . '属性　威力 ' . $moves[$striking]['power']), 'one that strikes: its element and its power');
foreach ($moves as $id => $m) {
    check(count(Info::move($m, $elements, $m['pp'])) === 3, "the move $id has its say");
}

same(['祝余　300 贝', '招摇之山：「食之不饥」。回复 20 点体力。'], Info::thing($items['zhuyu'], $items['zhuyu']['price']), 'a thing sold: its name and price, and what it is');
same(['祝余', '招摇之山：「食之不饥」。回复 20 点体力。'], Info::thing($items['zhuyu']), 'in the bag, no price');
foreach ($items as $id => $t) {
    check(Info::thing($t)[0] === $t['name'] && count(Info::thing($t)) === 2, "the thing $id says what it is");
}

// The UI is drawn through Render\Ui alone (the developer's rule): Play says what shows, and draws nothing of the UI
// itself, so that how a box, a list or a line looks is changed in one place. What Play may paint is the 3D view and
// its lens.
$play = file_get_contents(__DIR__ . '/../src/Play.php');
preg_match_all('/\$p->(\w+)\(/', $play, $calls);
same([], array_values(array_diff(array_unique($calls[1]), ['scale', 'blurLevel', 'fillRadialGradient', 'image'])), 'Play paints only the view and its lens; the rest is Ui\'s');

// The record of every beast to read outside the game (docs/bestiary.html) is what tools/bestiary.php makes now: when a
// beast is drawn or its data changes, the page is made again (php tools/bestiary.php).
same(\Shanhai\Bestiary::page(), file_get_contents(\Shanhai\Bestiary::PAGE), 'docs/bestiary.html is up to date: run php tools/bestiary.php');
$png = \Shanhai\Bestiary::png(str_repeat("\xff\0\0\xff", 4), 2, 2);
same("\x89PNG\r\n\x1a\n", substr($png, 0, 8), 'the sprites are PNGs');
