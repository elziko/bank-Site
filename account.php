<?php
session_start();
require_once "server.php";

if (!isset($_SESSION['login'])){
    header("location:index.php");
}


switch(isset($_POST)){

    case isset($_POST['logout']): logout();
    break;
    case isset($_POST["sendmoney"]): transmoney($db);
    break;
    case isset($_POST['update']): updatedetails($db);
    break;
    case isset($_POST['accept']): accepttoreceiveEmail();
    break;
    case isset($_POST['sendreport']): sendreporttoUs();
    break;
    case isset($_POST['del']): deleteAccount($db);

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
    
    <link rel="stylesheet" href="styles/account.css?v=<?php echo time(); ?>">
    <script defer src="js/account.js"></script>
</head>
<body>
    <!-----
    header of the page
    
    ----->
    <header>
        <h1>BankOffice</h1> 
        <form action="" method="POST">
        <button type="submit" id="logoutbtn" name="logout">log Out</button>
        </form>
        
       
       
        
    </header>
    <!-----
    main of the page
    
    ----->
    <main>
        <nav>
        <h2>
            <?php
            echo "Hello ".$_SESSION["firstname"];
            ?>
        </h2>
        <p class="infor">
            <?php
        echo "your amount ->  ".$_SESSION["amount"]."<br> your account id -> ".$_SESSION["accountid"]; 
        
        ?>
        </p>
        <section>
            <ul>
            <li><a href="" id="transfer">Transfer Money</a></li>
            <li><a href="" id="details">update details</a></li>           
            <li><a href=""  id="sendemail">accept our emails</a></li>
            <li><a href="" id="report">report</a></li> 
            <li><a href="" id="delete">delete account</a></li> 
            
            </ul>
        </section>
        <section>
            <p></p>
        </section>
        </nav>
        <section class="pages" id="pages">
        <section id="translist" class="enablemode">
        <h3>Your Transferation List</h3>
             <?php
             $db = new DBclass();
               buildinsidetable($db);      
             ?>
        </section>





            <form action="" class="disablemode" id="transferform" method="POST">
                <h4>Transfer Money </h4>
            <label for="amounttosend">amount=</label>
             <input type="text" name="amounttosend">
             <label for="receiverid">receiver account ID</label>
             <input type="text" name="receiverid">
            <button type="submit" name="sendmoney">send</button>
            <button  id="cancel">cancel</button>
          
            </form>
            <form action="" method="POST" class="disablemode" id="detailsform" >     
                 <h5>Update your Details</h5>       

                  <label for="newusername">enter new username</label>
            <input class="signupuser" name="newusername" type="text"  >
               <label for="password">enter new password</label>
            <input class="signuppass" name="password" type="text" >
               <label for="password2"> Confirm Password</label>
            <input class="signuppass" name="password2" type="text" >        
             <label for="email">enter Email</label>
            <input  name="email" type="text" >
              <label for="newphone">enter new phone number</label>
            <input class="signupuser" name="newphone" type="text" >
              <label for="newcity">enter new city</label>
            <input class="signupuser" name="newcity" type="text" >


            <button name="update" type="submit">save</button>
            <button  id="cancel">cancel</button>
            </form>
            <form action="" method="POST" class="disablemode" id="emailform">
               <h6>Send Email</h6>
            <button type="submit" name="accept"  id="cancel">ok</button>
            
            </form>
            <form action="" class="disablemode" id="reportform" method="POST">
                <p class="h7">Report Us</p>
            <label for="reportinput">type your report here -></label>
            <input type="text" name="reportinput" >
            <button name="sendreport" type="submit">send</button>
            </form>
            <form action="" class="disablemode" id="deletform" method="POST">
            <p class="h8">delete account</p>
            <label for="usernametodel">enter username</label>
            <input class="signupuser" name="usernametodel" type="text" >
            <label for="passwordtodel">enter password</label>
            <input class="signupuser" name="passwordtodel" type="text" >


            <button name="del"type="submit">delete</button>
            <button  id="cancel">cancel</button>
            
            </form>

        </section>

    </main>
    <!-----
    footer of the page
    
    ----->
    <footer>ziki + amer</footer>
   
</body>
</html>