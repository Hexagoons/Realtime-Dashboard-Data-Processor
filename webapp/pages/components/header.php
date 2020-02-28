<div class="wrapper">
    <nav class="bg-primary">
        <div class="nav-wrapper">
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="full-width hide-on-med-and-down">
                <!-- Left -->
                <li><a class="link-logo" href="<?= route_link('/'); ?>"><img class="logo" src="https://www.lajm.lt/uploads/images/PAGELAYOUT/LAJM_logo-en.png" alt="Logo"></a></li>
                <li><a class="main" href="<?= route_link('/'); ?>">Realtime Weather Dashboard</a></li>
                
                <!-- Right -->
                <?php if(is_logged_in()): ?>
                    <li class="pull-right"><a href="<?= route_link('/logout') ?>">Log out</a></li>
                    <li class="pull-right"><a href="<?= route_link('/dashboard/my-account') ?>">My account</a></li>
                    <li class="pull-right"><a href="<?= route_link('/dashboard') ?>">Dashboard</a></li>
                <?php endif; ?>
                <?php if(is_role(ADMIN)): ?>
                    <li class="pull-right"><a href="<?= route_link('/admin/users') ?>">Users</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <ul class="sidenav" id="mobile-demo">
        <?php if(is_logged_in()): ?>
            <li><a href="<?= route_link('/logout') ?>">Log out</a></li>
            <li><a href="<?= route_link('/dashboard') ?>">Dashboard</a></li>
            <li><a href="<?= route_link('/dashboard/my-account') ?>">My account</a></li>
        <?php endif; ?>
        <?php if(is_role(ADMIN)): ?>
            <li><a href="<?= route_link('/admin/users') ?>">Users</a></li>
        <?php endif; ?>
    </ul>
    
    <?php if(!isset($container) || ($container === true)): ?>
    <div class="container">
    <?php endif; ?>
    
    <?php show_alerts() ?>