<?php
namespace DartAlex;
/**
 * Gère l'affichage du footer de la partie frontend
 */
ob_start();
?>
<footer class="footer is-dark">
    <div class="content has-text-centered">
        bla bla
    </div>
</footer>
<?php
$footer = ob_get_clean();