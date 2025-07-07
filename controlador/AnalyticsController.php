<?php
require_once __DIR__ . '/../modelo/Analytics.php';

$stats = Analytics::getStats();
$recent_orders = Analytics::getRecentOrders();

require __DIR__ . '/../vista/vista_analytics.php'; 