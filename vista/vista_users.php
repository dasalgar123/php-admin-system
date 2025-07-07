<?php
// Datos simulados de usuarios
$users = [
    [
        'id' => 1,
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@email.com',
        'role' => 'Admin',
        'status' => 'Activo',
        'last_login' => '2024-01-15 10:30',
        'avatar' => 'JP'
    ],
    [
        'id' => 2,
        'name' => 'María García',
        'email' => 'maria.garcia@email.com',
        'role' => 'Usuario',
        'status' => 'Activo',
        'last_login' => '2024-01-14 15:45',
        'avatar' => 'MG'
    ],
    [
        'id' => 3,
        'name' => 'Carlos López',
        'email' => 'carlos.lopez@email.com',
        'role' => 'Editor',
        'status' => 'Inactivo',
        'last_login' => '2024-01-10 09:15',
        'avatar' => 'CL'
    ],
    [
        'id' => 4,
        'name' => 'Ana Rodríguez',
        'email' => 'ana.rodriguez@email.com',
        'role' => 'Usuario',
        'status' => 'Activo',
        'last_login' => '2024-01-15 14:20',
        'avatar' => 'AR'
    ],
    [
        'id' => 5,
        'name' => 'Luis Martínez',
        'email' => 'luis.martinez@email.com',
        'role' => 'Editor',
        'status' => 'Activo',
        'last_login' => '2024-01-13 11:30',
        'avatar' => 'LM'
    ]
];

// Filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';

// Filtrar usuarios
$filtered_users = array_filter($users, function($user) use ($search, $role_filter) {
    $matches_search = empty($search) || 
                     stripos($user['name'], $search) !== false || 
                     stripos($user['email'], $search) !== false;
    $matches_role = $role_filter === 'all' || $user['role'] === $role_filter;
    return $matches_search && $matches_role;
});

$getStatusColor = function($status) {
    return $status === 'Activo' ? 'success' : 'danger';
};

$getRoleColor = function($role) {
    switch ($role) {
        case 'Admin': return 'danger';
        case 'Editor': return 'warning';
        case 'Usuario': return 'primary';
        default: return 'secondary';
    }
};
?>

<link rel="stylesheet" href="css/users.css">

<div class="card">
    <div class="card-header">
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar usuarios..." 
                       value="<?php echo htmlspecialchars($search); ?>"
                       onchange="window.location.href='?page=users&search=' + this.value + '&role=<?php echo $role_filter; ?>'">
            </div>
            
            <select class="filter-select" onchange="window.location.href='?page=users&search=<?php echo urlencode($search); ?>&role=' + this.value">
                <option value="all" <?php echo $role_filter === 'all' ? 'selected' : ''; ?>>Todos los roles</option>
                <option value="Admin" <?php echo $role_filter === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="Editor" <?php echo $role_filter === 'Editor' ? 'selected' : ''; ?>>Editor</option>
                <option value="Usuario" <?php echo $role_filter === 'Usuario' ? 'selected' : ''; ?>>Usuario</option>
            </select>
        </div>
        
        <a href="?page=users&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Nuevo Usuario
        </a>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filtered_users as $user): ?>
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <?php echo $user['avatar']; ?>
                            </div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                <div class="user-id">ID: <?php echo $user['id']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $getRoleColor($user['role']); ?>">
                            <?php echo $user['role']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $getStatusColor($user['status']); ?>">
                            <?php echo $user['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $user['last_login']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?page=users&action=view&id=<?php echo $user['id']; ?>" 
                               class="btn btn-secondary btn-sm" data-tooltip="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?page=users&action=edit&id=<?php echo $user['id']; ?>" 
                               class="btn btn-primary btn-sm" data-tooltip="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?page=users&action=delete&id=<?php echo $user['id']; ?>" 
                               class="btn btn-danger btn-sm btn-delete" data-tooltip="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="table-footer">
        <div class="table-info">
            Mostrando <?php echo count($filtered_users); ?> de <?php echo count($users); ?> usuarios
        </div>
        <div class="pagination">
            <button class="btn btn-secondary">Anterior</button>
            <span class="page-info">Página 1 de 1</span>
            <button class="btn btn-secondary">Siguiente</button>
        </div>
    </div>
</div> 