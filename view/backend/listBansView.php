<?php
/**
 * Gère l'affichage des bannissements
 */
ob_start();
?>
<h2>Liste des bannissements</h2>
<table>
    <thead>
        <tr>
            <th>IP</th>
            <th>Date du ban</th>
            <th>Type du ban</th>
            <th>Commentaire</th>
            <th>Action</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
        <tr><form method="post" action="<?= PATH ?>" id="form-add-ban"><input type="hidden" name="action" value="addBan"/>
            <td><input type="text" name="ip" required/></td>
            <td><?= Ban::rNow() ?></td>
            <td><select name="type" required>
<?php
foreach(Ban::TYPES as $type => $display) {
?>
                <option value="<?= $type ?>"><?= $display ?></option>
<?php
}
?>
            </select></td>
            <td><textarea name="content"></textarea></td>
            <td><input type="submit" value="Ajouter"/></td>
            <td></td>
        </form></tr>
<?php
foreach($bans as $ban) {
?>
        <tr class="ban-row" id="ban-row-<?= $ban->id ?>"><form method="post" action="<?= PATH ?>" class="form-modify-ban" id="form-modify-ban-<?= $ban->id ?>"><input type="hidden" name="action" value="modifyBan"/><input type="hidden" name="id" value="<?= $ban->id ?>"/>
            <td><input type="text" name="ip" value="<?= $ban->ip ?>" required/></td>
            <td><?= $ban->rDate('date_ban') ?></td>
            <td><select name="type" required>
<?php
foreach(Ban::TYPES as $type => $display) {
?>
                <option value="<?= $type ?>"<?= (($type == $ban->type)?" selected": "") ?>><?= $display ?></option>
<?php
}
?>
            </select></td>
            <td><textarea name="content"><?= $ban->content ?></textarea></td>
            <td><input type="submit" value="Modifier"/></td>
            <td><a title="Supprimer le ban sur <?= $ban->ip ?>" class="fas fa-trash ban-delete-link" id="ban-delete-link-<?= $ban->id ?>" href="<?= PATH ?>delete/<?= $ban->id ?>/"></a></td>
        </form></tr>
<?php
}
?>
    </tbody>
</table>
<?= $pageSelector ?>
<?php
$content = ob_get_clean();

require('template.php');

