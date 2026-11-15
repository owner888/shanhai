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
12. **The rules never draw, the drawing never rules**: `src/World`, `src/Script`, `src/Battle`, `src/Data.php`, `src/Elements.php`, `src/Text.php` and
    `data/` are PHP alone, with no MyPHP class, so the tests need no window; `src/Render` and `src/Play.php` read the
    World and decide nothing.
13. Reuse MyPHP's own (`Plugins\Scene`, `Plugins\Game`) before writing anything beside it; what it lacks is fixed
    there, after asking. Read its `AGENTS.md`, `docs/ui/scene3d.md` and `docs/plugins/game.md` before touching the
    drawing — use its built-in `Animator3D` (`IDLE`/`WALK`/`LUNGE`/`HURT` + `addLimb`), `ParticleEmitter3D`, `Tween3D`,
    `Camera3D::shake`, `Scene::addSkyDome`, `Mesh::terrain`/`cylinder`/`cone`/`torus`/`capsule`/`sceneFromGlbFile`,
    `Material::pbr`/`unlit` (`$emissive`), `Game::hitStop`, `ScreenTransition` (`FADE`/`SHUTTER`/`BATTLE_BLINDS`),
    `VirtualPad` (mobile D-Pad + A/B/MENU key injection), `SpriteSheet`/`SpriteAnimation`, and `GameAudio` (SFX pool,
    `registerTone` retro synth, BGM cross-fade) rather than rolling your own.
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
                   Actor, Controls, World (step(): walking, turning, what blocks, warps, triggers, talking; the party,
                   its battles, rest), Menu (what the menu button opens: the party's screen, the bag), PartyMenu (the beasts in
                   order, two change places)
src/Script/Vm.php  the event script interpreter: say, ask (a line with choices), flags, jumps, face, move, warp, give, battle, trial, rest, load; check()
src/Battle/        the battle, with no drawing: Rng (bytes, from a seed or a test's script), Formulas (docs/numbers.md as code,
                   a function a rule), Fighter, Battle (turn(): order, what stops a beast, a move, statuses, weather, switching),
                   Screen (a wild battle as the player goes through it: lines, the menu, its lists, the bag's seal, fleeing),
                   Simulator (battles with no player, to weigh the tables)
src/Text.php       the dialog box's measures and line breaking
src/Cursor.php     a list's cursor: moves on a press, not while held
src/Save.php       the one saved game, a file of JSON in the app's own folder: World::state() written and read
src/Data.php       reads data/
src/Elements.php   what a move of one element does against a beast of others (double, even, half, in tenths)
src/Render/Stage.php  a map's 3D scene: the ground and the walls as meshes of the faces that can be seen (faces()),
                   instanced trees, grass, rocks, signs, the actors' figures, the fixed camera
src/Render/Arena.php  a battle's 3D scene: the ground, the two beasts' figures on MyPHP's Animator3D, its camera shake
                   and particles for what the Screen's cue tells of; a camera that does not otherwise move
src/Play.php       MyPHP's Game: keys and gamepad into Controls, World::step, draws the Stage and the dialog box,
                   or a battle's Arena, boxes, line and lists; MyPHP's ScreenTransition between them, its VirtualPad,
                   its GameAudio's tones
data/game.php      where a new game starts, where the player wakes after a battle is lost, what is asked when a game is saved
data/ranges.php    the ranges of mountains by number: the classic's book and name, the reference's gym they are measured from
data/gods.php      the gods that keep the ranges: what a beast's entry has to fight with, and the classic's line
data/items.php     the things a bag holds: the seal that binds a wild beast
data/elements.php  the five elements: what each overcomes and feeds, physical or special, its region
data/tiers.php     the five tiers a beast's numbers are built in: bands measured from the reference's distribution
data/moves.php     the moves: element (or none), power, accuracy, uses, effect, and what in the classic each comes from
data/learnsets.php the moves each beast learns, by level
data/beasts.php    the beasts, as the classic has them (kind, range, mountain, looks, sound, omen, lore), and the game's own
                   (elements with why, tier, stats, catch rate, base experience, growth)
docs/bestiary.md   the catalogue: what is in, what is still to be checked, what is still to be read
data/maps/*.php    a map each: rows of tiles, exits to the maps beside it, warps, signs, NPCs and triggers with their
                   scripts, what its tall grass and its water hold
tools/             simulate.php (every beast against every other: win rates by beast, tier, element), matchups.php (element against
                   element), parties.php (random parties against one another, with switching), effects.php (what each effect that does no
                   damage is worth); add `think` for sides that think ahead
docs/balance.md    what the simulator shows, what was changed for it, what is still open
tests/             php tests/run.php [name]: world, script, data, formulas (each rule against a value worked out by hand),
                   battle (turns with scripted rolls), screen (a battle played through), stage (the faces of ground and walls), save; check()/same(), no PHPUnit;
                   each file runs in a scope of its own
docs/rules.md      the rule of the game
docs/roadmap.md    the order of the work, and when each step is done
docs/numbers.md    the numbers' baseline: each formula and constant, and where it was read
docs/atlas.md      every range, mountain, map, beast and level, and each trial: written by `php tools/atlas.php`, never by hand
docs/todo.md       what is not yet as the reference has it, to come back to; what was written before it was read; what is open
docs/elements.md   the elements' table and how a beast's elements are settled
```

## How it works

- **Fixed steps**: Play updates 60 times a second; `World::step(Controls)` is one update. A step between two
  tiles takes `World::WALK_STEPS` updates; a tap turns, holding walks (`TURN_STEPS`).
- **A script holds the controls** until it ends: a line showing waits for confirm, a question for a choice, a `move` for the walk.
  A scripted walk into something is an error, not a walk through it.
- **Maps are text**: one character a tile (`Tile::KINDS`), a map's y the scene's z. What stands on tiles
  (signs, NPCs) blocks them; warps and triggers act when the player arrives on theirs.
- **A battle holds the controls** like a script: `World::$battle` is its Screen, which `World::step` steps until it is
  done; the script that began it then goes on. The Screen shows a turn line by line: hp changes with the line that tells it.
- **The party is its Fighters**: a battle is fought by the party's own beasts, so their hp, status and moves' uses are
  what it left them; stages and confusion end with it. Lost, the player wakes at `data/game.php`'s rest, the party whole.
- **A beast grows in its Fighter**: experience, each stat's experience, the level they make, the moves it comes to
  (`gain()`, `learnable()`, `learn()`); the Screen gives them when the wild beast falls and asks which move to forget.
- **No face lies hidden against another block** (`Stage::faces`): the ground was once a cube a tile, and the sides
  between tiles, exactly as near as the tops' shared edge, came through the depth test now and then as a dark line
  across the view while walking. Blocks on the grid are built as faces; things that stand apart are instanced.
- **Tall grass holds wild beasts**, and scree on the mountains the classic gives no grass and in caves (`World::grass`, docs/numbers.md 13): a step onto it, after the first four on a map
  or after a battle, rolls against the map's rate, then for one of its seven; each lives on the map's own mountain.
- **A battle's line has a cue** (`Screen::$cue`: use, hit, hurt, faint, caught, ...): the rules say what happened, and
  Play passes it once to the Arena and the tones. The drawing never reads the lines' words.
- **A trial** (`World::trial`, the script's `trial`) is a battle against the beasts of the one who sets it, a god
  among them fighting as a beast does: not fled, none bound, experience at each fall and half as much again.
- **A range is a gym** (docs/numbers.md 14): its wild beasts' levels and its god's trial are those of the reference's
  gym of the same number and the areas before it; a map says its `range`, and a test holds the trial to the measure.
- **Water carries** the player who has a beast of the water element that can fight, once the fourth range's trial is
  passed (`World::float`, `data/game.php`'s `float`): afloat, water is
  walked on, the map's `water` holds three wild beasts, and stepping onto land ends it. A game is not saved afloat.
- **A saved game** is `World::state()`: the map and place, flags, bag, and each beast's `Fighter::state()`. What does not
  fit the data it is read with throws; a new field means a new `Save::VERSION`.
- **A map change** is counted in `World::$mapChanges`; Play builds a new Stage when it sees one.
- **Drawing interpolates**: `Actor::place($alpha)` is between two updates' places, so motion is smoother than its steps.

## Working on it

- PHP ≥ 8.3. Before handing back: `php -l` on changed files, `php tests/run.php`.
- A new map, NPC or script: add the data, run `php tests/run.php data`; it says what is wrong with it.
- A new tile kind: `Tile::KINDS`, `Stage::GROUND` (the test asks for both), then how the Stage draws it.
- A new beast: read its passage first (rule 4), then the entry.
- After changing a map, a wild table, a god or a trial: `php tools/atlas.php`, which writes `docs/atlas.md` anew (a test
  fails while it is stale).
- Leaving something not as the reference has it (rule 2): a line in `docs/todo.md` then, saying what the reference
  does and what we do; when it is done, the line is deleted.

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
