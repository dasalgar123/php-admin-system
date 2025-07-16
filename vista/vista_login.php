<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-shield-alt"></i>
                <h1>Panel de Administración</h1>
                <p>Ingresa tus credenciales para continuar</p>
            </div>
            
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($data['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Usuario (correo)
                    </label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Ingresa tu correo" 
                           value="<?php echo htmlspecialchars($data['username']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password"> <i class="fas fa-lock"></i> Contraseña </label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Recordarme
                    </label>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
    
    <script src="../js/dark-mode.js"></script>
</body>
</html> 