<?php
session_start();
require_once "server.php";

if(isset($_SESSION["username"]) ){
    if($_SESSION["username"] == 'admin'){
      header("location:admin.php");
    }else{
      header("location:account.php");
    }
       
}


switch(isset($_POST)){
  case isset($_POST['signup']) : signup($db);// function in the server
  break;
  case isset($_POST["login"]) : login($db);// function in the server
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Caveat:wght@600&family=Raleway:wght@600&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/index.css?v=<?php echo time(); ?>">
    <script defer src="js/index.js" ></script>
</head>
<body>

<!-----
    header of the page
    
    ----->
<header><h1>BankOffice</h1></header>
<!-----
    main of the page
    
    ----->

<main>
    <section class="thecontainer">
        <section id="logorregsec" class="enablemode">
         <p class="loginheader">Log In</p>
         <form action="" method="POST"> 
         <input placeholder="enter you username here!" class="usernameinput" name="username" type="text" required>
         <input placeholder="enter your password" class="passwordinput" id="passwordlog" name="password" type="password" required>
         <span id="firsteye"><i aria-hidden="true" class="fa fa-eye"></i></span>
         <input type="submit" name="login" value="Log In" >
         <a id="movetosignup" href="">Sign Up</a>
         </form>
        </section>
        <section id="regsec" class="disablemode" >
            <p class="headerreg">regiester</p>
            <form action="" id="formreg" method="POST"  >
                <label for="firstname">enter firstname</label>
            <input  class="signupuser" name="firstname" type="text" >
               <label for="lastname">enter lastname</label>
            <input class="signuppass" name="lastname" type="text" >
               <label for="personalid"> personalid</label>
            <input placeholder="personalid must be 9 digits!" class="signuppass" name="personalid" type="text" >
               <label for="city">city</label>
            <input class="personalid" name="city" type="text" >
             <label for="email">enter Email</label>
            <input  name="email" type="text" >
             <label for="phone">enter phone</label>
            <input placeholder="phone must be 10 digits!" name="phone" type="text" >
             <label for="username">enter username</label>
            <input  name="username" type="text" >
             <label for="password">enter password</label>
            <input  id="passwordreg1" name="password" type="password" >
            <span id="thirdeye"><i aria-hidden="true" class="fa fa-eye"></i></span>
            <label for="password2" class="passwordcon">Confirm password</label>
            <input id="passwordreg2"  name="password2" type="password" >
            <span id="secondeye"><i aria-hidden="true" class="fa fa-eye"></i></span>


            
            <input type="submit" class="signupp" id="signupbtn" name="signup" value="Sign Up">
            <button class="signupp" id="cancelsignup" name="cancelsignup">cancel</button>

            </form>


        </section>
    </section>
    
    <h2>welcome to BankOffice</h2>
</main>
<!-----
    footer of the page
    
    ----->
    <footer>$Ameer && $Ziki</footer>
    
</body>
</html>