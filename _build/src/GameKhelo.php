<?php

declare(strict_types = 1);

namespace GameKhelo\Games;

use Exception;

use function chdir;
use function count;
use function file_get_contents;
use function file_put_contents;
use function htmlentities;
use function in_array;
use function is_dir;
use function is_readable;
use function is_string;
use function realpath;
use function str_replace;
use function strlen;
use function system;

class GameKhelo
{

    private $title;
    private $headline;
    private $enableInstall;
    private $enableUpdate;
    private $enableEmbed;
    private $buildDirectory;
    private $homeDirectory;
    private $logoDirectory;
    private $templatesDirectory;
    private $customDirectory;
    private $games;
    private $menu;
    private $css;
    private $header;
    private $footer;

    public function __construct()
    {
        global $argc;

        $this->title = ' Games Khelo';
        if ($argc === 1) {
            return;
        }
        $this->initOptions();
        $this->initDirectories();
        $this->initConfig();
        $this->initGamesList();
        if ($this->enableInstall) {
            $this->installGames();
        }
        if ($this->enableUpdate) {
            $this->updateGames();
        }
        $this->initTemplates();
        $this->buildMenu();
        $this->buildIndex();
    }

    private function initOptions()
    {
        global $argv;

        $this->enableInstall = in_array('install', $argv) ? true : false;
        $this->enableUpdate = in_array('update', $argv) ? true : false;
        $this->enableEmbed = in_array('embed', $argv) ? true : false;
    }

    private function initDirectories()
    {
        $this->buildDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR;
        if (!is_dir($this->buildDirectory)) {
            throw new Exception('BUILD DIRECTORY NOT FOUND: ' . $this->buildDirectory);
        }

        $this->templatesDirectory = $this->buildDirectory . 'templates' . DIRECTORY_SEPARATOR;
        $this->customDirectory = $this->buildDirectory . 'custom' . DIRECTORY_SEPARATOR;
        $this->homeDirectory = realpath($this->buildDirectory . '..') . DIRECTORY_SEPARATOR;
        if (!is_dir($this->homeDirectory)) {
            throw new Exception('HOME DIRECTORY NOT FOUND: ' . $this->homeDirectory);
        }

        $this->logoDirectory = $this->homeDirectory . '_logo' . DIRECTORY_SEPARATOR;
    }

    private function initConfig()
    {
        global $title, $headline;
        $configFile = 'config.php';

        $configuration = is_readable($this->customDirectory . $configFile)
            ? $this->customDirectory . $configFile
            : $this->buildDirectory . $configFile;
        if (!is_readable($configuration)) {
            throw new Exception('CONFIGURATION NOT FOUND: ' . $configuration);
        }
        require_once $configuration;

        $this->title = (!empty($title) && is_string($title))
            ? $title
            : $this->title;
        $this->headline = (!empty($headline) && is_string($headline))
            ? $headline
            : $this->title;
    }

    private function initGamesList()
    {
        global $games;

        $gamesFile = 'games.php';
        $gamesList = is_readable($this->customDirectory . $gamesFile)
            ? $this->customDirectory . $gamesFile
            : $this->buildDirectory . $gamesFile;
        if (!is_readable($gamesList)) {
            throw new Exception('GAMES LIST NOT FOUND: ' . $gamesList);
        }
        require_once $gamesList;

        $this->games = (!empty($games) && is_array($games))
            ? $games
            : [];
    }

    private function installGames()
    {
        foreach ($this->games as $gameIndex => $game) {
            $gameDirectory = $this->homeDirectory . $gameIndex;
            if (is_dir($gameDirectory)) {
                continue;
            }
            chdir($this->homeDirectory);
            $this->syscall('git clone ' . $game['git'] . ' ' . $gameIndex);
            if (!empty($game['branch'])) {
                chdir($gameDirectory);
                $this->syscall('git checkout ' . $game['branch']);
            }
            $this->buildSteps($gameIndex, $game);
        }
    }

    private function buildSteps(string $gameIndex, array $game)
    {
        $gameDirectory = $this->homeDirectory . $gameIndex;
        if (!chdir($gameDirectory)) {
            return;
        }
        if (empty($game['build'])) {
            return;
        }
        foreach ($game['build'] as $step) {
            $this->syscall($step);
        }
    }

    private function updateGames()
    {
        foreach ($this->games as $gameIndex => $game) {
            $gameDirectory = $this->homeDirectory . $gameIndex;
            if (!chdir($gameDirectory)) {
                continue;
            }
            $this->syscall('git pull');
            $this->buildSteps($gameIndex, $game);
        }
    }

    private function initTemplates()
    {
        $this->css = $this->getTemplate('style.css');
        $this->header = $this->getTemplate('header.html');
        $this->header = $this->transposeTemplate($this->header);
        $this->footer = $this->getTemplate('footer.html');
        $this->footer = $this->transposeTemplate($this->footer);
    }

    private function getTemplate(string $file): string
    {
        $custom = is_readable($this->customDirectory . $file)
            ? file_get_contents($this->customDirectory . $file)
            : file_get_contents($this->templatesDirectory . $file);

        return $custom ?? '';
    }

    private function transposeTemplate(string $template): string
    {
        $template = str_replace('{{CSS}}', $this->css, $template);
        $template = str_replace('{{TITLE}}', $this->title, $template);
        $template = str_replace('{{HEADLINE}}', $this->headline, $template);
        $template = str_replace('{{DATETIME_UTC}}', gmdate('Y-m-d H:i:s'), $template);

        return $template;
    }

    private function buildMenu()
    {
        $this->menu = '<div class="list">';
        foreach ($this->games as $gameIndex => $game) {
            $this->menu .= $this->getGameMenu($gameIndex, $game);
        }
        $this->menu .= '</div><br>';

        if ($this->enableEmbed) {
            $this->write(
                $this->homeDirectory . 'games.html',
                "<style>{$this->css}</style>{$this->menu}\n"
            );
        }
    }

    private function getGameMenu(string $gameIndex, array $game): string
    {
        $link = empty($game['index'])
            ? $gameIndex . '/'
            : $gameIndex . '/' . $game['index'];
        $mobile = empty($game['mobile'])
            ? ''
            : '&#128241;'; // 📱
        $desktop = empty($game['desktop'])
            ? ''
            : '&#9000;'; // ⌨
        $logo = is_readable($this->logoDirectory . $gameIndex . '.png')
            ? $gameIndex . '.png'
            : 'game.png';

        return '<a href="' . $link . '"><div class="game"><img src="_logo/' . $logo
            . '" width="100" height="100" alt="' . $game['name'] . '"><br />' . $game['name']
            . '<br /><small>' . $game['tag'] . '</small>'
            . '<br /><div class="platform">' . $desktop . ' ' . $mobile . '</div>'
            . '</div></a>';
    }

    private function buildIndex()
    {
        $this->write(
            $this->homeDirectory . 'index.php',
            $this->header . $this->menu . $this->footer
        );
    }

    private function syscall(string $command)
    {
        system($command);
    }

    private function write(string $filename, string $contents)
    {
        file_put_contents($filename, $contents);
    }

    private function verbose(string $message)
    {
        print $message . "\n";
    }
}
