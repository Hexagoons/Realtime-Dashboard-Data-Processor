<!doctype html>
<html lang="nl">

<head>
    <?php require_component('metadata'); ?>

    <title>Users</title>
</head>

<body>
    <?php require_component('header'); ?>

    <div class="row">
        <h3>Users</h3>
    </div>

    <div class="row">
        <?php //require_component('dashboard.sidenav'); 
        ?>

        <div class="col s12">
            <div class="col s12 m5 left-align">
                <form method="POST">
                    <h5>Create new user</h5>
                    <?php require_component('forms.editUser'); ?>
                </form>

            </div>
            <div class="col s12 m1"></div>
            <div class="col s12 m6 left-align">

                <div class="row">
                    <?php require_component('dashboard.searchbar', ['route' => '/admin/users', 'col' => 's12']); ?>

                    <div class="col s12">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < count($users); $i++) :
                                    if ($users[$i]['id'] != $_SESSION["user"]["id"])
                                        continue;
                                ?>
                                    <tr>
                                        <td><?= $users[$i]['first_name'] ?></td>
                                        <td><?= $users[$i]['last_name'] ?></td>
                                        <td><?= $users[$i]['email'] ?></td>
                                        <td>
                                            <?php
                                            switch ($users[$i]['role']) {
                                                case ADMIN:
                                                    echo "Admin";
                                                    break;
                                                case RESEARCHER:
                                                    echo "Researcher";
                                                    break;
                                                case STUDENT:
                                                    echo "Student";
                                                    break;
                                            }
                                            ?></td>
                                        </td>
                                    </tr>
                                <?php endfor; ?>

                                <?php for ($i = 0; $i < count($users); $i++) : 
                                if ($users[$i]['id'] == $_SESSION["user"]["id"])
                                continue;?>
                                    <tr>
                                        <td><?= $users[$i]['first_name'] ?></td>
                                        <td><?= $users[$i]['last_name'] ?></td>
                                        <td><?= $users[$i]['email'] ?></td>
                                        <td>
                                            <?php
                                            switch ($users[$i]['role']) {
                                                case ADMIN:
                                                    echo "Admin";
                                                    break;
                                                case RESEARCHER:
                                                    echo "Researcher";
                                                    break;
                                                case STUDENT:
                                                    echo "Student";
                                                    break;
                                            }
                                            ?></td>
                                        <td>
                                            <?php if ($users[$i]['id'] != $_SESSION["user"]["id"]) : ?>
                                                <a href="/admin/users/edit?id=<?= $users[$i]['id'] ?>" class="btn btn-action" type="submit"> <i class="fas fa-edit"></i> </button>
                                                <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($users[$i]['id'] != $_SESSION["user"]["id"]) : ?>
                                                <form method="POST" action="/admin/users/delete?id=<?= $users[$i]['id'] ?>">
                                                    <button class="btn  btn-action" type="submit" onclick="return confirm('Are you sure you want to delete: <?= $users[$i]['first_name'] ?> <?= $users[$i]['last_name'] ?>?')"> <i class="fas fa-trash-alt"></i> </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?= paginate_links($pageCount, $_GET['page'] ?? 1); ?>

            </div>
            <br>
        </div>
    </div>

    <?php require_component('footer'); ?>
</body>

</html>