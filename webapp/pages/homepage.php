<!doctype html>
<html lang="nl">
    <head>
        <?php require_component('metadata'); ?>
    
        <style>input:focus {border-bottom: 1px solid white !important;box-shadow: 0 1px 0 0 white !important;} label.active, .input-field > label, .input-field input:focus + label {color: white !important;}input {color: white !important;}
            .alert{margin:0!important;border-radius: 0!important;}</style>
        
        <title>Maritime Academy | Realtime Weather Dashboard</title>
    </head>
    <body>
        <?php require_component('header', ['container' => false]); ?>
        
        <div class="content header-image" style="background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('<?= APP['url'] . '/img/main-background.jpg' ?>') no-repeat scroll bottom">
            <div class="content white-text">
                <h1 class="center-align">Lithuanian<br> Maritime Academy</h1>
    
                <?php if(!is_logged_in()): ?>
                    <form action="<?= route_link('/login') ?>" method="POST">
                        <div class="input-field">
                            <input id="email" type="email" name="email" required
                                   <?= input_value('email') ?>>
                            <label for="email">Email</label>
                        </div>
            
                        <div class="input-field">
                            <input id="password" type="password" name="password" required>
                            <label for="password">Password</label>
                        </div>
            
                        <div class="center-align">
                            <input type="submit" value="Login" class="btn half-width">
                        </div>
                    </form>
                <?php else: ?>
                    <form action="<?= route_link('/logout') ?>" method="GET">
                        <input type="submit" value="Logout" class="btn full-width">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </body>
</html>