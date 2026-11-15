<?php
declare(strict_types=1);

use MyPHP\{App, PathName, Window, WindowOptions};
use MyPHP\UI\Content;
use Shanhai\{Data, Play};

require __DIR__ . '/boot.php';
require __DIR__ . '/src/load.php';

App::setName('山海');
// The saved game is in the app's own folder, where the system keeps each app's data.
$play = new Play(Data::newWorld(random_int(0, 0x7fffffff), App::path(PathName::UserData) . '/save.json'), Data::items());

App::whenReady(function () use ($play) {
    new Window(new WindowOptions(title: '山海', width: 960, height: 720,
        content: Content::file(__DIR__ . '/view.php', $play)));
});
App::run();
