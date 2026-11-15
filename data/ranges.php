<?php
// The ranges of mountains, in the classic's order, by the number a map and a god say they are of (their `range`).
//   book   which of the classic's books it is in
//   name   as the classic names it
//   gym    which of the reference's gyms its levels and its trial are measured from (docs/numbers.md 14)
//   toward which way the classic walks it, mountain after mountain: its first map is the one with no way back
declare(strict_types=1);

return [
    1 => ['book' => '南山经', 'name' => '南山经之首（鹊山）', 'gym' => 1, 'toward' => 'right'],
    2 => ['book' => '南山经', 'name' => '南次二经', 'gym' => 2, 'toward' => 'right'],
    3 => ['book' => '南山经', 'name' => '南次三经', 'gym' => 3, 'toward' => 'right'],
    4 => ['book' => '西山经', 'name' => '西山经之首（华山）', 'gym' => 4, 'toward' => 'left'],
    5 => ['book' => '西山经', 'name' => '西次二经', 'gym' => 5, 'toward' => 'left'],
    6 => ['book' => '西山经', 'name' => '西次三经', 'gym' => 6, 'toward' => 'left'],
];
