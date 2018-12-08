<?php
use Cake\Event\EventManager;
use WyriHaximus\TwigView\Event\ExtensionsListener;
use WyriHaximus\TwigView\Event\TokenParsersListener;

EventManager::instance()->on(new ExtensionsListener());
EventManager::instance()->on(new TokenParsersListener());
