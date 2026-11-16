<?php
// The ranges of mountains, in the classic's order, by the number a map and a god say they are of (their `range`).
//   book   which of the classic's books it is in
//   name   as the classic names it
//   gym    which of the reference's gyms its levels and its trial are measured from (docs/numbers.md 14)
//   toward which way the classic walks it, mountain after mountain: its first map is the one with no way back
//   compass what the maps' left and right are called, when the classic does not walk the range west or east:
//          the Northern Mountains go north, which the maps draw to the right
declare(strict_types=1);

return [
    1 => ['book' => '南山经', 'name' => '南山经之首（鹊山）', 'gym' => 1, 'toward' => 'right'],
    2 => ['book' => '南山经', 'name' => '南次二经', 'gym' => 2, 'toward' => 'right'],
    3 => ['book' => '南山经', 'name' => '南次三经', 'gym' => 3, 'toward' => 'right'],
    4 => ['book' => '西山经', 'name' => '西山经之首（华山）', 'gym' => 4, 'toward' => 'left'],
    5 => ['book' => '西山经', 'name' => '西次二经', 'gym' => 5, 'toward' => 'left'],
    6 => ['book' => '西山经', 'name' => '西次三经', 'gym' => 6, 'toward' => 'left'],
    7 => ['book' => '西山经', 'name' => '西次四经', 'gym' => 7, 'toward' => 'left'],
    8 => ['book' => '北山经', 'name' => '北山经之首', 'gym' => 8, 'toward' => 'right', 'compass' => ['left' => '南', 'right' => '北']],
    9 => ['book' => '北山经', 'name' => '北次二经', 'gym' => 9, 'toward' => 'right', 'compass' => ['left' => '南', 'right' => '北']],
    10 => ['book' => '北山经', 'name' => '北次三经（太行之山）', 'gym' => 10, 'toward' => 'right', 'compass' => ['left' => '南', 'right' => '北']],
    11 => ['book' => '东山经', 'name' => '东山经之首', 'gym' => 11, 'toward' => 'right', 'compass' => ['left' => '北', 'right' => '南']],
    12 => ['book' => '东山经', 'name' => '东次二经', 'gym' => 12, 'toward' => 'right', 'compass' => ['left' => '北', 'right' => '南']],
    13 => ['book' => '东山经', 'name' => '东次三经', 'gym' => 13, 'toward' => 'right', 'compass' => ['left' => '北', 'right' => '南']],
    14 => ['book' => '东山经', 'name' => '东次四经', 'gym' => 14, 'toward' => 'right', 'compass' => ['left' => '北', 'right' => '南']],
    15 => ['book' => '中山经', 'name' => '中山经之首（薄山）', 'gym' => 15, 'toward' => 'right', 'compass' => ['left' => '西', 'right' => '东']],
    16 => ['book' => '中山经', 'name' => '中次二经（济山）', 'gym' => 16, 'toward' => 'right', 'compass' => ['left' => '东', 'right' => '西']],
    17 => ['book' => '中山经', 'name' => '中次三经（萯山）', 'gym' => 17, 'toward' => 'right', 'compass' => ['left' => '西', 'right' => '东']],
    18 => ['book' => '中山经', 'name' => '中次四经（釐山）', 'gym' => 18, 'toward' => 'right', 'compass' => ['left' => '东', 'right' => '西']],
    19 => ['book' => '中山经', 'name' => '中次五经（薄山）', 'gym' => 19, 'toward' => 'right', 'compass' => ['left' => '西', 'right' => '东']],
    20 => ['book' => '中山经', 'name' => '中次六经（缟羝山）', 'gym' => 20, 'toward' => 'right', 'compass' => ['left' => '西', 'right' => '东']],
    21 => ['book' => '中山经', 'name' => '中次七经（苦山）', 'gym' => 21, 'toward' => 'right', 'compass' => ['left' => '西', 'right' => '东']],
];
