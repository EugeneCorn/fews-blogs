<?php
    include("auth_session.php");
    require('db_connection.php');
    include('mysql_secure_query_functions.php');
    include('website_functions.php');

    $username = $_SESSION['username'];

    $article_query = mysqli_prepare($db_connection,
        "SELECT *
        FROM `articles`
        WHERE username= ?
        ORDER BY create_datetime 
        DESC");

    $params = array($username);
    $articles = secureMysqliQuerySelectForLoop($article_query, $params);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Fews Blogs</title>
    <!-- <link href="styles/reset.css" rel="stylesheet" /> -->
    <link href="styles/main.css" rel="stylesheet" />
    <link href="styles/dashboard.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
    <!-- Icons kit. -->
    <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
</head>
<header class="user_panel">
    <?php include("user_panel.php") ?>
</header>
<body>
    <h1>Hey, <?php echo $_SESSION['username']; ?>!</h1>
    <h2>What will you share with us today?</h2>
    <div class='above_the_table'>
        <p class='your_articles'>Your Articles</p>
        <p><a class='add_article' href="/article/add">Add Article</a></p>
    </div>
<?php

    // Show user's articles if their quantity is bigger than zero.
    if ($articles) {
?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Topic</th>
                <th>Create Time</th>
                <th>Edit Time</th>
                <th>Published</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
<?php
        $i = 1;
        foreach ($articles as $article_item) {
            print "
                <tr>
                    <td align='center'>".$i++."</td>
            ";  
                // Show article preview depends on it's "publish" status.
                if ($article_item['public'] == "no"){
                    print "
                        <td>
                            <a href='/article/".$article_item['article_id']."/preview'>".newlines2br($article_item['topic'])."</a>
                        </td>
                    "; 
                } else {
                    print "
                        <td>
                            <a href='/article/".$article_item['article_id']."'>".newlines2br($article_item['topic'])."</a>
                        </td>
                    "; 
                }
                print "<td align='center'>".$article_item['create_datetime']."</td>";
                print "<td align='center'>";
                    // Print edit date and time if an article was edited.
                    if ($article_item['edit_datetime'] != null){
                        print $article_item['edit_datetime'];
                    } else {
                        print "Not edited";
                    }
                print "</td>";
                print '<td align="center">'.$article_item['public']."</td>";
                print "
                    <td align='center'>
                        <a class='table_button' href='/article/".$article_item['article_id']."/edit'><i class='fa-solid fa-pen'></i></a>
                    </td>
                ";
                print "
                    <td align='center'>
                        <a class='table_button' href='#' onclick='delete_article(".$article_item['article_id'].")'><i class='fa-solid fa-trash'></i></a>
                    </td>";
            print "</tr>";
        }
    } else {
        print "<p class='do_not_have_articles'>You didn't make any articles yet. :(</p>";
    }
?>
        </tbody>
    </table>
    <script>
            // Function for articles deleting.
            function delete_article(article_id) {
                var r = confirm("Are you sure you want to delete this article?");
                if (r === true) {
                    window.location.assign("/article/" + article_id + "/delete");
                }
            }
    </script>
</body>
<footer class="footer">
    <?php include("footer.php") ?>
</footer>
</html>
