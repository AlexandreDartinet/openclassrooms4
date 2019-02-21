<?php


function listPosts(int $page = 1) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require("view/frontend/listPostsView.php");
}

function viewPost(int $id) {
    $postManager = new PostManager();
    $post = $postManager->getPostById($id);

    require("view/frontend/postView.php");
}