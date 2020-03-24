<?php
use Cake\Event\EventManager;
use Cake\TwigView\Event\ExtensionsListener;
use Cake\TwigView\Event\TokenParsersListener;

EventManager::instance()->on(new ExtensionsListener());
EventManager::instance()->on(new TokenParsersListener());
