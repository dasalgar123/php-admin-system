<?php
require_once __DIR__ . '/../modelo/User.php';

// Obtener todos los usuarios
$users = User::all();

// Filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';

// Filtrar usuarios
$filtered_users = array_filter($users, function($user) use ($search, $role_filter) {
    $matches_search = empty($search) || 
                     stripos($user->name, $search) !== false || 
                     stripos($user->email, $search) !== false;
    $matches_role = $role_filter === 'all' || $user->role === $role_filter;
    return $matches_search && $matches_role;
});

// Pasar datos a la vista
require __DIR__ . '/../vista/vista_users.php'; 