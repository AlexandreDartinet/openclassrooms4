<?php
/**
 * Gère l'affichage backend de la liste des utilisateurs
 */
ob_start();
?>
<h2>Liste des utilisateurs</h2>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>IP</th>
            <th>Inscription</th>
            <th>Dernière connexion</th>
            <th>Nombre de posts</th>
            <th>Nombre de commentaires</th>
            <th>Nombre de signalements</th>
            <th>Niveau</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($users as $user) {
?>
        <tr class="user-admin-table-row" id="user-admin-table-row-<?= $user->id ?>">
            <td><?= $user->displayName() ?></td>
            <td><?= htmlspecialchars($user->email) ?></td>
            <td><?= $user->ip ?></td>
            <td><?= $user->rDate('date_inscription') ?></td>
            <td><?= $user->rDate('last_seen') ?></td>
            <td><?= $user->posts_nbr ?></td>
            <td><?= $user->comments_nbr ?></td>
            <td><?= $user->reports_nbr ?></td>
            <td>
                <form method="post" action="/admin/users/" class="user-level-form" id="user-level-form-<?= $user->id ?>">
                    <input type="hidden" name="action" value="modifyUserLevel"/>
                    <input type="hidden" name="id" value="<?= $user->id ?>"/>
                    <select name="level" required>
<?php
    foreach(User::LEVELS as $level => $display) {
?>
                        <option value="<?= $level ?>"<?= (($level == $user->level)?" selected":"") ?>><?= $display ?></option>
<?php
    }
?>
                    </select>
                    <input type="submit" class="user-level-submit"/>
                </form>
            </td>
            <td><a title="Supprimer l'utilisateur <?= htmlspecialchars($user->name_display) ?>" class="fas fa-trash user-delete-link" id="user-delete-link-<?= $user->id ?>" href="/admin/users/delete/<?= $user->id ?>/"></a></td>
        </tr>      
<?php
}
?>
    </tbody>
</table>
<?= $pageSelector ?>
<?php

$content = ob_get_clean();

require('template.php');