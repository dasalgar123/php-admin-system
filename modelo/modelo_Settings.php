<?php
require_once __DIR__ . '/../config/database.php';

class Settings {
    public static function getSettings() {
        return [
            'language' => 'es',
            'timezone' => 'America/Bogota',
            'notifications' => true,
            'theme' => 'light'
        ];
    }
} 