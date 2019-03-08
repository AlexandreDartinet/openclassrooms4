<?php
namespace DartAlex;
/**
 * Gère l'affichage du footer de la partie frontend
 */
ob_start();
?>
<footer class="footer">
    <div class="content has-text-centered">
        <a href="/polconf/">Politique de confidentialité.</a><br/>
        
        Site développé par <a href="https://github.com/Dart-Alex/" target="_blank">Dart-Alex</a>.<br/>
        <a href="https://bulma.io/made-with-bulma/" target="_blank">
            <img src="https://bulma.io/images/made-with-bulma.png" alt="Made with Bulma" width="128" height="24">
        </a>
    </div>
</footer>
<?php
$footer = ob_get_clean();