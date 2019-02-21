<?php
function listPosts(int $page = 1) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require("view/frontend/listPostsView.php");
}