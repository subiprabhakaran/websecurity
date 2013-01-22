<?php
error_reporting(0);
define('ok',1);
$page = "home.php";
mysql_connect("localhost", "root", "amma123") or die('Error');
mysql_select_db("task");
 
if (isset($_POST['submit'])) {
        $q = mysql_query("SELECT * FROM users WHERE login = '".mysql_real_escape_string($_POST['login'])."' AND password = '".md5($_POST['pwd'])."'");
        if (mysql_num_rows($q) == 0) {
                echo '<h1>Wrong login or password!</h1>';
        } else {
                setcookie('auth', base64_encode($_POST['login'].'|'.$_POST['pwd']));
                header("Location: ./index.php");
        }
        die();
}
 
if (isset($_COOKIE['auth'])) {
        $auth = base64_decode($_COOKIE['auth']);
        list($login, $password) = explode('|', $auth);
        $q = mysql_query("SELECT * FROM users WHERE login = '".$login."' AND password = '".md5($password)."'");
        if (mysql_num_rows($q) != 0) {
                $r = mysql_fetch_assoc($q); 
                $q = mysql_query("SELECT * FROM ".$r['status']."_info") or die('Error');
                $r = mysql_fetch_assoc($q);
                extract($r);
                echo 'Hello, '.$login.'!<br>Your country: '.$country.'<br>City: '.$city.'<br>Phone number: '.$phone.'<br>Secret PIN-code: '.$pin.'<br>';
                echo '<br><a href="?page=home.php">home</a>';
                echo '<br><a href="?page=news.php">news</a>';
                echo '<br><a href="?page=download.php">download</a><br><br>';
 
                $whitelist = array('news.php', 'home.php', 'download.php');
                if (isset($_GET['page'])) {
                        if (in_array($_GET['page'], $whitelist))
                                $page = $_GET['page'];
                } 
                include("./inc/".$page);
                echo "<br><br>Designed by Bithin";
                die();
        } else {
                setcookie('auth',"");
        }
}
?>
 
<html>
        <body>  
        <form method="post" action="index.php">
                Login:<br><input type="text" name="login" /><br>
                Password:<br><input type="text" name="pwd" /><br>
                <input type="submit" name="submit" value="Go" />
        </form>
        </body>
</html>
