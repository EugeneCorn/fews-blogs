<?php

// Statement for page routing.
if (empty($_GET["q"])) {
    include ("homepage.php");
} else {
    $url = explode("/", $_GET["q"]);

    // Articles managing section.
    if ($url[0] == "article") {
        if ($url[1] == "add") {
            include("add_article.php");
        } else {                                        // Too many "ifs". Is it stupid? Yes. But we won't optimize it.
            if (empty($url[2])) {
                include("article.php");
            } elseif ($url[2] == "edit") {
                include("edit_article.php");
            } elseif ($url[2] == "preview") {
                include("article_preview.php");
            } elseif ($url[2] == "delete") {
                include("article_delete.php");
            }
        }
    }

    // User managing section.
    if ($url[0] == "user") {
        if ($url[1] == "edit") {
            include("user_profile_edit.php");
        } else {
            include("user_profile.php");
        }
    }

    // Comments managing section.
    if ($url[0] == "comment") {
        if (empty($url[1]) || $url[1] == "list" ) {
            include("my_comments.php");
        } elseif ($url[2] == "edit") {
            include("comment_edit.php");
        } elseif ($url[2] == "delete") {
            include("comment_delete.php");
        }
    }

    // Other website pages.
    if ($url[0] == "dashboard") {
        include("dashboard.php");
    }
    if ($url[0] == "about_us") {
        include("about_us.php");
    }
    if ($url[0] == "login") {
        include("login.php");
    }
    if ($url[0] == "logout") {
        include("logout.php");
    }
    if ($url[0] == "registration") {
        include("registration.php");
    }
}
