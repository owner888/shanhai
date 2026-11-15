<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\World\Map;

/**
 * docs/atlas.md, written from the data: which book and range, which mountain, which map, what beasts at what levels,
 * what trial. Nothing in it is kept by hand, and a test holds the file to what this writes.
 */
final class Atlas
{
    public static function render(): string
    {
        [$maps, $beasts, $gods, $ranges] = [Data::maps(), Data::beasts(), Data::gods(), Data::ranges()];
        $elements = Data::elements();
        // a beast or a god by its name, with its elements
        $name = function (string $id) use ($beasts, $gods, $elements): string {
            $b = $beasts[$id] ?? $gods[$id];
            return $b['name'] . '（' . implode('', array_map(fn(string $e) => $elements[$e]['name'], $b['elements'])) . '）';
        };
        $out = "# 山海地图总表\n\n这份表由 `php tools/atlas.php` 从 `data/` 生成，不要手改；数据变了而没有重新生成，测试会报错。\n\n"
            . "等级按 `docs/numbers.md` 第 14 节：一列山系对参考作的一座道馆。水里的等级是表里写的再加 0 到 4 级；渡水要先通过第四列山系的试炼。\n";
        $placed = [];
        foreach ($ranges as $n => $range) {
            $out .= "\n## {$range['book']} · {$range['name']}\n\n";
            $own = self::inOrder(array_filter($maps, fn(Map $m) => $m->range === $n));
            if ($own === []) {
                $out .= "还没有地图。\n";
            } else {
                $out .= self::trial($own, $gods, $n, $range['gym'], $name);
                $out .= "| 山、地图 | `data/maps/` | 原文 | 草里、碎石、洞里 | 水里 | 通向 |\n|---|---|---|---|---|---|\n";
                foreach ($own as $m) {
                    foreach ([$m->wild, $m->water] as $table) {
                        foreach ($table['slots'] ?? [] as [, $id]) {
                            $placed[$id] = true;
                        }
                    }
                    $out .= sprintf("| %s | `%s` | %s | %s | %s | %s |\n", $m->name, $m->id, self::signs($m), self::held($m->wild, $name), self::held($m->water, $name), self::ways($m, $maps));
                }
            }
            $missing = array_filter($beasts, fn(array $b, string $id) => $b['range'] === $n && !isset($placed[$id]), ARRAY_FILTER_USE_BOTH);
            if ($missing !== []) {
                $out .= "\n这一列里还没有放进任何地图的异兽：" . implode('；', array_map(fn(array $b, string $id) => $name($id) . ' 在' . $b['mountain'], $missing, array_keys($missing))) . "。\n";
            }
        }
        return $out . "\n## 其余各经\n\n西山经、北山经、东山经、中山经和海经各篇还没有地图；各篇的山数和异兽名录在 `docs/bestiary.md`。\n";
    }

    /**
     * A range's maps as they are walked: from the one with no way west, east by the exits; a map entered by a door
     * after the one its door is in.
     * @param array<string, Map> $maps @return list<Map>
     */
    private static function inOrder(array $maps): array
    {
        $out = [];
        $add = function (Map $m) use (&$out, &$add, $maps): void {
            if (in_array($m, $out, true)) {
                return;
            }
            $out[] = $m;
            foreach ($m->warps as $w) {
                if (isset($maps[$w[2]])) {
                    $add($maps[$w[2]]);
                }
            }
        };
        foreach ($maps as $m) {
            if ($m->exits !== [] && !isset($m->exits['left'])) {
                for ($at = $m; $at !== null; $at = isset($at->exits['right']) ? ($maps[$at->exits['right'][0]] ?? null) : null) {
                    $add($at);
                }
            }
        }
        foreach ($maps as $m) {
            $add($m);
        }
        return $out;
    }

    /** The range's levels and its trial, in a paragraph. */
    private static function trial(array $maps, array $gods, int $n, int $gym, \Closure $name): string
    {
        $means = $levels = [];
        foreach ($maps as $m) {
            if ($m->wild !== null) {
                $own = array_column($m->wild['slots'], 0);
                $means[] = array_sum($own) / count($own);
                $levels = [...$levels, ...$own];
            }
        }
        $out = '对参考作第 ' . $gym . ' 座道馆。' . count($maps) . ' 张地图';
        $out .= $levels === [] ? '。' : sprintf('，草里、碎石、洞里 %d 到 %d 级，各图均值的平均 %.1f。', min($levels), max($levels), array_sum($means) / count($means));
        foreach ($maps as $m) {
            foreach ([...$m->npcs, ...$m->signs] as $who) {
                foreach (end($who) as $cmd) {
                    if ($cmd[0] === 'trial' && ($gods[end($cmd[2])[0]]['range'] ?? null) === $n) {
                        $god = $gods[end($cmd[2])[0]];
                        $team = implode('、', array_map(fn(array $t) => $name($t[0]) . ' ' . $t[1] . ' 级', $cmd[2]));
                        $out .= "\n\n**试炼**：{$god['name']}，{$god['looks']}在{$m->name}。队伍：{$team}。";
                    }
                }
            }
        }
        return $out . "\n\n";
    }

    /** What a wild table holds: each beast with the levels it is met at and its share of a hundred, and the rate. */
    private static function held(?array $table, \Closure $name): string
    {
        if ($table === null) {
            return '';
        }
        $shares = count($table['slots']) === 3 ? [60, 30, 10] : [30, 30, 20, 10, 5, 4, 1];
        $by = [];
        foreach ($table['slots'] as $i => [$level, $id]) {
            $by[$id]['levels'][] = $level;
            $by[$id]['share'] = ($by[$id]['share'] ?? 0) + $shares[$i];
        }
        $parts = [];
        foreach ($by as $id => $b) {
            [$low, $high] = [min($b['levels']), max($b['levels'])];
            $parts[] = sprintf('%s %s 级%s', $name($id), $low === $high ? $low : "{$low}～{$high}", count($by) > 1 ? "（{$b['share']}%）" : '');
        }
        return implode('，', $parts) . '；遇敌率 ' . $table['rate'] . '%';
    }

    /** What the map's signs say, after the dash: the classic's words about the mountain. */
    private static function signs(Map $m): string
    {
        $lines = [];
        foreach ($m->signs as $sign) {
            foreach (end($sign) as $cmd) {
                if ($cmd[0] === 'say') {
                    $lines[] = trim(explode('——', $cmd[1])[1] ?? $cmd[1]);
                }
            }
        }
        return implode('；', $lines);
    }

    /** Where the map leads: its exits, its warps, and where those on it send the player. */
    private static function ways(Map $m, array $maps): string
    {
        $to = [];
        foreach ($m->exits as $way => [$id]) {
            $to[] = ['left' => '西', 'right' => '东', 'up' => '北', 'down' => '南'][$way] . '：' . $maps[$id]->name;
        }
        foreach ($m->warps as $w) {
            $to[] = '门：' . $maps[$w[2]]->name;
        }
        foreach ([...$m->npcs, ...$m->signs] as $who) {
            foreach (end($who) as $cmd) {
                if ($cmd[0] === 'warp') {
                    $to[] = '送往：' . $maps[$cmd[1]]->name;
                }
            }
        }
        return implode('，', array_unique($to));
    }
}
