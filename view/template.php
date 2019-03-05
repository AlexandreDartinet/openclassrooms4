<?php 
namespace DartAlex;
/**
 * Template d'affichage de la page.
 * On doit lui fournir $title pour le titre de la page, $menu pour son menu et $content pour son contenu.
 */
require('header.php');
require('footer.php');
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex,nofollow"/>
        <meta name="author" content="Alexandre Dartinet"/>
        <link rel="Shortcut Icon" href="/public/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <link href="/public/css/style.css" rel="stylesheet" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="mobile-web-app-capable" content="yes">
        <title>Mon site - <?= $title ?></title>
    </head>
    <body>
        <?= $header ?>
<?php
if(RETRY != '') {
?>
        <section id="retry">
<?php
    if(isset(RETRY_TABLE[RETRY])) {
        echo RETRY_TABLE[RETRY];
    }
    else {
?>
            <?= RETRY ?> : <?= RETRY_TABLE["default"] ?>
<?php
    }
?>
        </section>
<?php
}
if(SUCCESS != '') {
?>
        <section id="success">
<?php
    if(isset(SUCCESS_TABLE[SUCCESS])) {
        echo SUCCESS_TABLE[SUCCESS];
    }
    else {
?>
            <?= SUCCESS ?> : <?= SUCCESS_TABLE["default"] ?>
<?php
    }
?>
        </section>
<?php
}
?>
        <main>
        <?= $content ?>
        </main>
        <?= $footer ?>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script>
            const siteUrl = 'https://<?= SITE_URL ?>/';
            const path = '<?= preg_replace('/page-\d+\//', '', PATH) ?>';
            let curPage = <?= getPage(PATH) ?>;
            setTimeout(() => {
                $('#retry').fadeOut(1000, () => {
                    $('#retry').remove();
                });
            }, 5000);
            setTimeout(() => {
                $('#success').fadeOut(1000, () => {
                    $('#success').remove();
                });
            }, 5000);

            function responsiveMenu() {
                let nav = $('#nav');
                if(nav.hasClass("responsive")) {
                    nav.removeClass("responsive");
                }
                else {
                    nav.addClass("responsive");
                }
            }
            $(document).ready(() => {
                let images = [
                    '/public/images/header-1.jpg',
                    '/public/images/header-2.jpg',
                    '/public/images/header-3.jpg'
                ];
                let randIndex = Math.floor(Math.random() * images.length);
                let background = "url('"+images[randIndex]+"')";
                $('header').css({ 'background-image':background });
            });
        </script>
<?php
if(isset($scripts)) {
    foreach($scripts as $script) {
        echo $script;
    }
}
?>
    </body>
</html>