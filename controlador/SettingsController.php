<?php
require_once __DIR__ . '/../modelo/Settings.php';

$settings = Settings::getSettings();

require __DIR__ . '/../vista/vista_settings.php'; 