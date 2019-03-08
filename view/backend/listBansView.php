<?php
namespace DartAlex;
/**
 * Gère l'affichage des bannissements
 */
ob_start();
?>
<h2 class="title is-3">Liste des bannissements</h2>
<div class="box container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
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
                <td>
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" name="ip" required/>
                        </div>
                    </div>
                </td>
                <td><?= Ban::rNow() ?></td>
                <td><div class="select"><select name="type" required>
<?php
foreach(Ban::TYPES as $type => $display) {
?>
                    <option value="<?= $type ?>"><?= $display ?></option>
<?php
}
?>
                </select></div></td>
                <td><textarea class="textarea" name="content"></textarea></td>
                <td><input class="button is-primary" type="submit" value="Ajouter"/></td>
                <td></td>
            </form></tr>
<?php
foreach($bans as $ban) {
?>
            <tr class="ban-row" id="ban-row-<?= $ban->id ?>"><form method="post" action="<?= PATH ?>" class="form-modify-ban" id="form-modify-ban-<?= $ban->id ?>"><input type="hidden" name="action" value="modifyBan"/><input type="hidden" name="id" value="<?= $ban->id ?>"/>
                <td>
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" name="ip" value="<?= $ban->ip ?>" required/>
                        </div>
                    </div>
                </td>
                <td><?= $ban->rDate('date_ban') ?></td>
                <td><div class="select"><select name="type" required>
<?php
foreach(Ban::TYPES as $type => $display) {
?>
                    <option value="<?= $type ?>"<?= (($type == $ban->type)?" selected": "") ?>><?= $display ?></option>
<?php
}
?>
                </select></div></td>
                <td><textarea class="textarea" name="content"><?= $ban->content ?></textarea></td>
                <td><input class="button is-warning" type="submit" value="Modifier"/></td>
                <td><a title="Supprimer le ban sur <?= $ban->ip ?>" class="fas fa-trash ban-delete-link has-text-danger" id="ban-delete-link-<?= $ban->id ?>" href="<?= PATH ?>delete/<?= $ban->id ?>/"> Supprimer</a></td>
            </form></tr>
<?php
}
?>
        </tbody>
    </table>
    <?= $pageSelector ?>
</div>
<?php
$content = ob_get_clean();

require('template.php');

