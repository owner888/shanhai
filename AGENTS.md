# AGENTS.md — 山海 (Shanhai)

Entry point for coding agents and contributors. `docs/rules.md` is the rule of the game.

## Rules

1. **This file stays under 200 lines** (`wc -l AGENTS.md`): near the limit, condense first; only then move detail to `docs/`.
2. **The rule of the game** (the developer's rule): how it plays and how it is shown follow the classic handheld
   monster-collecting RPGs of the late 1990s — `docs/rules.md` lists what that means, and v1 keeps to it without
   exception; changes are considered only after a version is out.
3. **All content is the Classic of Mountains and Seas' (山海经) or our own**: beasts, names, moves, maps, text,
   number tables. No other game's names, creatures, maps, text or tables anywhere — code, data, comments, docs.
4. **A beast is written from the classic's text, not from memory**: `mountain`, `looks` and `lore` in
   `data/beasts.php` restate what the text says, checked against it; what is still to be checked against a second
   edition goes in the entry's `unchecked`, never guessed.
5. No third-party packages without asking the developer; the game needs MyPHP and PHP alone.
6. Git is the developer's: change files, run checks, report which changed; never commit, push or tag.
7. Files in git never carry the developer's name or a path of their machine: "the developer" when one is needed
   (`myphp.json` names the framework by a path from this folder for that reason).
8. GUI runs only on the developer's machine (ask for what it shows): `myphp run -d macos`, as for every platform.
   `php tests/run.php` runs anywhere.
9. Small and literal: no speculative abstractions, no silent fallbacks, no number that makes a symptom go away.
   Data that is wrong throws where it is read. Unclear? Ask before changing.
10. **A rule that can be a test is a test** (`tests/data_test.php` checks every map, script and beast as written):
    add the check with the rule, not a note to remember it.
11. Delete dead code when you touch it; never comment it out.
12. **The rules never draw, the drawing never rules**: `src/World`, `src/Script`, `src/Data.php`, `src/Text.php` and
    `data/` are PHP alone, with no MyPHP class, so the tests need no window; `src/Render` and `src/Play.php` read the
    World and decide nothing.
13. Reuse MyPHP's own (`Plugins\Scene`, `Plugins\Game`) before writing anything beside it; what it lacks is fixed
    there, after asking. Read its `AGENTS.md` and `docs/ui/scene3d.md` before touching the drawing.
14. File modes are left as they are: after editing, `git diff --summary` shows no mode change.
15. **Numbers come first** (the developer's rule): the formulas and their constants — damage, stats from base
    values, experience, what strong and weak multiply by, critical hits, catching — are the reference games'
    second generation's exactly, each read from its source, never recalled, and listed in `docs/numbers.md`
    (written with M2) with where it was read; none is retuned by feel. The tables of our own beasts and moves
    are built inside the same ranges. A question of balance is settled by the numbers before anything else.

## Layout

```
app.php            the app: makes the World and its Play, opens the window
view.php           the window's view (Content::file): SpriteView of the Play
boot.php           loads MyPHP (myphp.json's framework, or MYPHP_HOME)
src/load.php       the autoloader of Shanhai\ (src/)
src/World/         the game outside battle, with no drawing: Dir, Tile (the characters maps are written in), Map,
                   Actor, Controls, World (step(): walking, turning, what blocks, warps, triggers, talking)
src/Script/Vm.php  the event script interpreter: say, flags, jumps, face, move, warp, give; check() as written
src/Text.php       the dialog box's measures and line breaking
src/Data.php       reads data/
src/Render/Stage.php  a map's 3D scene: instanced tiles, trees, rocks, walls, the actors' figures, the fixed camera
src/Play.php       MyPHP's Game: keys and gamepad into Controls, World::step, draws the Stage and the dialog box
data/game.php      where a new game starts
data/elements.php  the nine elements and what each is strong against
data/beasts.php    the beasts: from the classic (mountain, looks, lore), and the game's own (element, stats)
data/maps/*.php    a map each: rows of tiles, warps, signs, NPCs and triggers with their scripts
tests/             php tests/run.php [name]: world, script, data (check()/same(), no PHPUnit)
docs/rules.md      the rule of the game
docs/roadmap.md    the order of the work, and when each step is done
```

## How it works

- **Fixed steps**: Play updates 60 times a second; `World::step(Controls)` is one update. A step between two
  tiles takes `World::WALK_STEPS` updates; a tap turns, holding walks (`TURN_STEPS`).
- **A script holds the controls** until it ends: a line showing waits for confirm, a `move` for the walk.
  A scripted walk into something is an error, not a walk through it.
- **Maps are text**: one character a tile (`Tile::KINDS`), a map's y the scene's z. What stands on tiles
  (signs, NPCs) blocks them; warps and triggers act when the player arrives on theirs.
- **A map change** is counted in `World::$mapChanges`; Play builds a new Stage when it sees one.
- **Drawing interpolates**: `Actor::place($alpha)` is between two updates' places, so motion is smoother than its steps.

## Working on it

- PHP ≥ 8.3. Before handing back: `php -l` on changed files, `php tests/run.php`.
- A new map, NPC or script: add the data, run `php tests/run.php data`; it says what is wrong with it.
- A new tile kind: `Tile::KINDS`, `Stage::GROUND` (the test asks for both), then how the Stage draws it.
- A new beast: read its passage first (rule 4), then the entry.

## Roadmap

`docs/roadmap.md` has the order, what each step makes and when it is done. In short:

- **Decided**: no evolution; v0.1's beasts are the whole Southern Mountains (南山经); the elements are settled
  after the catalogue of the classic's beasts, by what they are.
- **Phase 0, foundations** (documents and data): M1 accepted on the developer's machine → the numbers'
  baseline (`docs/numbers.md`) → the catalogue of beasts → the elements' table → the tiers of stats and moves.
- **Phase 1, battle** (M2): the engine in PHP alone with tests → a simulator that checks the tables → its scene and menus.
- **Phase 2, the loop** (M3): wild beasts, catching, the party, the bag, growth, saving, `choose` in scripts.
- **Phase 3, v0.1** (M4): the Southern Mountains' maps, a cave, the first trial.
- **Phase 4**: the other ranges, phones, models, sound.
