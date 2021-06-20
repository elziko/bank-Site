<?php
session_start();
require_once "server.php";


// if user try to enter admin page from the (url)
if(!isset($_SESSION['username']) || $_SESSION['username'] !="admin"){
  header("location:index.php");
}
// actions in this page

switch(isset($_POST)){
    case isset($_POST['logout']):logout();
    break;
    case isset($_POST['send']):depositmoneyasadmin($db);

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/admin.css?v=<?php echo time(); ?>">
    <script defer src="js/admin.js"></script>
</head>
<body>
<!-----
header of the page
--->
<header>
<h1>Admin Only</h1>
<form id="logoutadmin" action="" method="POST">
        <button type="submit" id="logoutbtn" name="logout">log Out</button>
        </form>

        <ul>
    <li><a href="" id="deposit">deposit</a></li>
    <li><a href="" id="transbtn" >transferation System</a></li>
</ul>

</header>
<!-----
main of the page
--->
<main>
<section id="transtable" class="disablemode"><?php
alltranstable($db);

?>
<button type="cancel" name="cancel" id="cancel">cancel</button>
</section>


<form action="" method="POST" class="disablemode" id="depositform" >            

                  <label for="accountnumber">enter account number</label>
            <input class="signupuser" name="accountnumber" type="text"  >
                   
         
              <label for="amount">enter amount</label>
            <input class="signupuser" name="amount" type="text" >
              


            <button class="depositformbuttons" name="send" type="submit">send </button>
            <button class="depositformbuttons"  id="cancel">cancel</button>
</form>



</main>


<!-----
footer of the page!
-------->
<footer>
$Ameer && $Ziki

</footer>




    
</body>
</html>