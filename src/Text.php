<?php
declare(strict_types=1);

namespace Shanhai;

/** Text as the dialog box shows it. */
final class Text
{
    /** The dialog box: its width and its text's size in points, and how many lines it shows. */
    public const BOX_WIDTH = 640, BOX_PADDING = 20, SIZE = 18, LINES = 3;

    /** How many columns a line of the dialog box holds: a CJK character takes two, a Latin one. */
    public static function columns(): int
    {
        return intdiv((self::BOX_WIDTH - 2 * self::BOX_PADDING) * 2, self::SIZE);
    }

    /**
     * Breaks $s into lines of at most $columns columns.
     * @return list<string>
     */
    public static function wrap(string $s, int $columns): array
    {
        $lines = [];
        $line = '';
        $used = 0;
        foreach (mb_str_split($s) as $ch) {
            $w = mb_strwidth($ch);
            if ($ch === "\n" || $used + $w > $columns) {
                $lines[] = $line;
                [$line, $used] = ['', 0];
                if ($ch === "\n") {
                    continue;
                }
            }
            $line .= $ch;
            $used += $w;
        }
        $lines[] = $line;
        return $lines;
    }
}
