<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/loginForm.css">
    <title>Se connecter</title>
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen_content">
                <form action="../controllers/logControllers/loginController.php" method="post" class="login">
                    <div class="login_field">
                        <i class="login_icon fas fa-user"></i>
                        <input type="text" class="login_input" placeholder="Email" name="email">
                    </div>
                    <div class="login_field">
                        <i class="login_icon fas fa-lock"></i>
                        <input type="password" class="login_input" placeholder="Mot de Passe" name="password">
                    </div>
                    <button class="button login_submit">
                        <span class="button_text">Se connecter</span>
                        <i class="button_icon fas fa-chevron-right"></i>
                    </button>				
                </form>
            </div>
            <div class="screen_background">
                <span class="screen_background_shape screen_background_shape4"></span>
                <span class="screen_background_shape screen_background_shape3"></span>		
                <span class="screen_background_shape screen_background_shape2"></span>
                <span class="screen_background_shape screen_background_shape1"></span>
            </div>		
        </div>
    </div>
</body>
</html>