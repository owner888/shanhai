<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\Render\{Arena, Sprites};

/**
 * The record of every beast as a page to read outside the game (docs/bestiary.html, made by tools/bestiary.php):
 * where each is found, its name, its kind and elements, how rare it is, whether its sprite is drawn, and the moves it
 * learns at what level. The page is tools/bestiary.html with the data put in it; the sprites are PNGs in it, encoded
 * here (PHP and zlib alone). A test holds docs/bestiary.html to what this makes now: a beast drawn, a move changed, and
 * the page is made again.
 */
final class Bestiary
{
    public const TEMPLATE = __DIR__ . '/../tools/bestiary.html', PAGE = __DIR__ . '/../docs/bestiary.html';

    /** The page: the template, its data put where it says. */
    public static function page(): string
    {
        $json = json_encode(self::data(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        return str_replace('/*DATA*/null', $json, file_get_contents(self::TEMPLATE));
    }

    /** What the page shows. */
    public static function data(): array
    {
        $elements = Data::elements();
        $learnsets = Data::learnsets();
        $out = [
            'ranges' => array_map(fn(int $k, array $r) => ['id' => $k, 'book' => $r['book'], 'name' => $r['name']], array_keys(Data::ranges()), Data::ranges()),
            'tiers' => array_map(fn(array $t) => $t['name'], Data::tiers()),
            'moves' => array_map(fn(array $m) => [$m['name'], $m['element'] === null ? '无' : $elements[$m['element']]['name'], $m['power'], $m['accuracy'], $m['pp']], Data::moves()),
            'placeholders' => array_map(fn(string $c) => self::sprite('beasts/placeholder', ['c' => $c]), Arena::ELEMENTS),
            'beasts' => [],
        ];
        foreach (Data::beasts() as $id => $b) {
            $out['beasts'][] = [
                'id' => $id, 'name' => $b['name'], 'range' => $b['range'], 'mountain' => $b['mountain'],
                'kind' => $b['kind'] === 'strange' ? '异兽' : '常兽',
                'el' => array_map(fn(string $e) => $elements[$e]['name'], $b['elements']), 'el0' => $b['elements'][0],
                'tier' => $b['tier'], 'looks' => $b['looks'], 'starter' => (bool)($b['starter'] ?? false),
                'learn' => $learnsets[$id] ?? [],
                'sprite' => Sprites::has("beasts/$id") ? self::sprite("beasts/$id", []) : null,
            ];
        }
        return $out;
    }

    /** A sprite facing left as a PNG, a data: URI. @param array<string, string> $colors */
    public static function sprite(string $name, array $colors): string
    {
        [$w, $h] = Sprites::load($name)['size'];
        return 'data:image/png;base64,' . base64_encode(self::png(Sprites::rgba($name, 'left', 0, $colors), $w, $h));
    }

    /** RGBA pixels, rows top first, as a PNG file: eight bits a channel, no filter, the same bytes each time. */
    public static function png(string $rgba, int $w, int $h): string
    {
        $raw = '';
        for ($y = 0; $y < $h; $y++) {
            $raw .= "\0" . substr($rgba, $y * $w * 4, $w * 4);
        }
        $chunk = fn(string $type, string $data) => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        return "\x89PNG\r\n\x1a\n" . $chunk('IHDR', pack('NNCCCCC', $w, $h, 8, 6, 0, 0, 0)) . $chunk('IDAT', gzcompress($raw, 9)) . $chunk('IEND', '');
    }
}
