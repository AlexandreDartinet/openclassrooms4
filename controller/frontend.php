<?php


function listPosts(int $page = 1) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require("view/frontend/listPostsView.php");
}

function viewPost(int $id, $page = 1) {
    $postManager = new PostManager();
    $post = $postManager->getPostById($id);
    if($post->comments_nbr != 0) {
        $commentManager = new CommentManager();
        $comments = $commentManager->getComments($id, $page);
    }

    require("view/frontend/postView.php");
}

function commentPost(int $id_post, string $name, string $content, int $reply_to) {
    $commentManager = new CommentManager();
    $comment = Comment::default();
    $comment->id_post = $id_post;
    $comment->name = $name;
    $comment->content = $content;
    $comment->reply_to = $reply_to;
    $commentManager->setComment($comment);
    header("Location: /post/$id_post/");
}

function login($name, $password, $path) {
    $userManager = new UserManager();
    if($userManager->login($name, $password)) {
        header("Location: $path");
    }
}

function logout() {
    $_SESSION['user'] = User::default();
    header("Location: /");
}
