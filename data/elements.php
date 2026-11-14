<?php
// The nine elements. A move of an element hits the elements in its 'strong' list harder,
// and the elements that list it less hard: the chart is these lists alone.
declare(strict_types=1);

return [
    'flame'   => ['name' => '焰', 'strong' => ['wood', 'frost']],
    'tide'    => ['name' => '潮', 'strong' => ['flame', 'rock']],
    'wood'    => ['name' => '木', 'strong' => ['tide', 'rock']],
    'rock'    => ['name' => '岩', 'strong' => ['flame', 'thunder']],
    'wind'    => ['name' => '风', 'strong' => ['wood', 'shadow']],
    'thunder' => ['name' => '雷', 'strong' => ['tide', 'wind']],
    'frost'   => ['name' => '霜', 'strong' => ['wind', 'wood']],
    'shadow'  => ['name' => '影', 'strong' => ['thunder', 'radiance']],
    'radiance' => ['name' => '辉', 'strong' => ['shadow', 'frost']],
];
