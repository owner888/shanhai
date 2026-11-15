<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\Battle\Rng;
use Shanhai\World\{Dir, Map, World};

/** Reads the game's data files (data/). */
final class Data
{
    public const DIR = __DIR__ . '/../data';

    /** @return array<string, Map> by id */
    public static function maps(): array
    {
        $maps = [];
        foreach (glob(self::DIR . '/maps/*.php') as $file) {
            $map = Map::fromArray(require $file);
            $maps[$map->id] = $map;
        }
        return $maps;
    }

    /** @return array<string, array> element id => its entry */
    public static function elements(): array
    {
        return require self::DIR . '/elements.php';
    }

    /** @return array<int, array> tier => its bands */
    public static function tiers(): array
    {
        return require self::DIR . '/tiers.php';
    }

    /** @return array<string, array> move id => its entry */
    public static function moves(): array
    {
        return require self::DIR . '/moves.php';
    }

    /** @return array<string, list<array{0: int, 1: string}>> beast id => its moves by level */
    public static function learnsets(): array
    {
        return require self::DIR . '/learnsets.php';
    }

    /** @return array<int, array> the range's number => its entry */
    public static function ranges(): array
    {
        return require self::DIR . '/ranges.php';
    }

    /** @return array<string, array> god id => its entry */
    public static function gods(): array
    {
        return require self::DIR . '/gods.php';
    }

    /** @return array<string, array> item id => its entry */
    public static function items(): array
    {
        return require self::DIR . '/items.php';
    }

    /** @return array<string, array> beast id => its entry */
    public static function beasts(): array
    {
        return require self::DIR . '/beasts.php';
    }

    /**
     * A new game's world, the player where data/game.php starts them; its battles' dice from $seed. With $saveFile it
     * can be saved there, and when a game is saved there already it begins by asking whether to go on with it (data/game.php's resume).
     */
    public static function newWorld(int $seed, ?string $saveFile = null): World
    {
        $game = require self::DIR . '/game.php';
        $start = $game['start'];
        $book = ['beasts' => self::beasts(), 'learnsets' => self::learnsets(), 'moves' => self::moves(), 'elements' => self::elements(), 'items' => self::items(), 'gods' => self::gods(), 'float' => $game['float'], 'rest' => $game['rest']];
        $world = new World(self::maps(), $start['map'], $start['x'], $start['y'], Dir::from($start['dir']), $book, Rng::seeded($seed), $saveFile);
        if ($saveFile !== null && Save::read($saveFile) !== null) {
            $world->run($game['resume']);
        }
        return $world;
    }
}
