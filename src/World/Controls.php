<?php
declare(strict_types=1);

namespace Shanhai\World;

/** What the player asks of one update: a direction held; the confirm, cancel and menu buttons pressed. */
final class Controls
{
    public function __construct(
        public readonly ?Dir $dir = null,
        public readonly bool $confirm = false,
        public readonly bool $cancel = false,
        public readonly bool $menu = false,
    ) {}
}
