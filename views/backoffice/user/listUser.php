<?php
require_once __DIR__ . '/../../../controllers/UserController.php';


$userC = new UserController();
$liste = $userC->getAllUsers();
?>

<style>
    /* CSS original de listUser.php */
    .container {
        width: 85%;
        margin: 60px auto;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    h2 {
        color: #0b6e7c;
        text-align: center;
        margin-bottom: 20px;
        font-size: 26px;
    }

    .btn-add {
        background: #16a3c7;
        color: white;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        margin-bottom: 20px;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-add:hover {
        background: #138aa8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }

    th {
        background: #1abc9c;
        color: white;
        padding: 12px;
        font-size: 16px;
    }

    td {
        background: #f8fdfd;
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #d9eeee;
    }

    tr:hover td {
        background: #e1f7f7;
    }

    a.action {
        color: #138aa8;
        font-weight: bold;
        text-decoration: none;
    }

    a.action:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <h2>Liste des Utilisateurs</h2>

    <!-- Lien Ajouter -->
    <a class="btn-add" href="user/addUser.php">Ajouter un utilisateur</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php if (!empty($liste)) { ?>
            <?php foreach ($liste as $u) { ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= $u['nom'] ?></td>
                    <td><?= $u['prenom'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['role'] ?></td>
                    <td>
                        <!-- Modifier -->
                        <a class="action" href="updateUser.php?id=<?= $u['id'] ?>">✏ Modifier</a> |

                        <!-- Supprimer -->
                        <a class="action" 
                           href="user/deleteUser.php?id=<?= $u['id'] ?>"
                           onclick="return confirm('Supprimer cet utilisateur ?');">
                           🗑 Supprimer
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" style="padding:20px; color:red; font-weight:bold;">
                    Aucun utilisateur trouvé.
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
