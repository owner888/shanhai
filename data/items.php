<?php
// The things a bag holds.
//   name   as it is shown
//   use    what it does when used: 'catch', in a battle with a wild beast, tries to bind it (docs/numbers.md 8); none: nothing yet
//   hold   what it does held by a beast: 'share', the beast takes half of what a fallen beast gives though it did not fight (docs/numbers.md 18)
//   price  what one costs where it is sold (docs/numbers.md 17)
//   from   what in the classic, or in the game's own story, it comes from
declare(strict_types=1);

return [
    'seal' => ['name' => '契印', 'use' => 'catch', 'price' => 200, 'from' => '巫祝所制，人与异兽结契的凭信。游戏自己的设定。'],
    'tongxiu' => ['name' => '同修玉', 'hold' => 'share', 'from' => '佩着它的异兽，不上场也分得一半修行。游戏自己的设定；山经里处处有玉。'],
];
