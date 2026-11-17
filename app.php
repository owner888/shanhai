<?php
declare(strict_types=1);

use MyPHP\{App, PathName, Window, WindowOptions};
use MyPHP\UI\Content;
use Shanhai\{Data, Play};

require __DIR__ . '/boot.php';
require __DIR__ . '/src/load.php';

App::setName('山海驭兽录');
// Times are shown in the system's time zone (when a save was made), as ICU finds it: PHP's own is UTC unless php.ini says otherwise.
date_default_timezone_set(IntlTimeZone::createDefault()->toDateTimeZone()->getName());
// The saved games are in the app's own folder, where the system keeps each app's data.
$play = new Play(Data::newWorld(random_int(0, 0x7fffffff), App::path(PathName::UserData)), Data::items());

App::whenReady(function () use ($play) {
    new Window(new WindowOptions(title: '山海驭兽录', width: 960, height: 720,
        content: Content::file(__DIR__ . '/view.php', $play)));
});
App::run();
