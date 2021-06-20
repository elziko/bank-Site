
<?php
require_once "DBclass.php";
$db = new DBclass();

/*************
 * 
 * functions that don't need any connection with database.
 *
 *
 ********/




// this function we use in account.php
//accepttoreceiveouremails(): after press ok in accept our Emails we  start sending emails to the user for information about us.
function accepttoreceiveEmail()
{
  echo "we send";
  $to = 'ameer0233@gmail.com'; // write here TO address instead somebody@to_mail
  $subject = 'hiii';
  $message = 'we are hackers';
  // write here FROM address instead from_another@from_mail
  $headers = 'From: ameer0233@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
  mail($to, $subject, $message, $headers);
}

//this function we use in account.php
//sendreporttoUs():function that get report from reportinput and write this in our reports file.
function sendreporttoUs()
{
  $fp = fopen("reports.txt", "a");
  if ($fp != false) {
    $message = "Account Number -> " . $_SESSION["accountid"] . " added a report : " . $_POST['reportinput'];
    fputs($fp, "$message\n");
    fclose($fp);
  }
}


// we use this also in account.php.
//2.logout: we just destroy the session and send user to main page (index.php).
function logout()
{

  session_unset();
  session_destroy();
  header("location:index.php");
  exit();
}



/****************************
 * 
 * functions that we use in index.php
 * 1.signup:we define variables with $_POST values and check if username already exists we display error message else we start the regestraion and give the $_SESSION array values. 
 * 2.login: we define variables with $_POST values amd check if username  not exists in database we display error message else we check the passowrd and if they equal we take the user to his account.
 * 3.goadmin:  we check if the vaalues that we get from $_POST is correct for admin account.
 * 
 * 
 */



//  1.signup:we define variables with $_POST values and check if username already exists we display error message else we start the regestraion and give the $_SESSION array values. 
function signup($db)
{


  unset($error);
  // define variables that we get from the post
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $personalid = $_POST["personalid"];
  $city = $_POST["city"];
  $Email = $_POST["email"];
  $phone = $_POST["phone"];

  // connect to database to check if this username is already exists.
  $row = $db->selectusernamefetchall($username);



  if ($row != null) { //some checks
    echo "This username is already taken!<br>";
  } else if ($_POST['password'] != $_POST['password2']) {  //some checks
    echo "The Password Is Not confirmed!!! <br>";
  } else if (strlen((string)$_POST["personalid"]) != 9) { //some checks
    echo "the personal ID is inncorrect <br>";
  } else if (strlen((string)$_POST["phone"]) != 10) { //some checks
    echo "the Phone is incorrect";
  } else {
    // success signup..
    $db->insertforperson($personalid, $firstname, $lastname, $city, $Email, $phone);
    $db->insertforaccount($username, $password, $personalid);

    // get data from database
    $row = $db->selectusernamefetch($username);

    // update the $_SESSION with new values
    $_SESSION["login"] = TRUE;
    $_SESSION["username"] = $username;
    $_SESSION["password"] = $password;
    $_SESSION["firstname"] = $firstname;
    $_SESSION["personalid"] = $personalid;
    $_SESSION["accountid"] = $row["accountid"];
    $_SESSION['Email'] = $Email;
    $_SESSION['amount'] = $row["amount"];

    // take the user to account.php (mainpage).
    header("location:account.php");
  }
}

//-----------------------------------------------------------------------------------------------------------------------------//




//2.login: we define variables with $_POST values amd check if username  not exists in database we display error message else we check the passowrd and if they equal we take the user to his account.
function login($db)
{

  $username = $_POST["username"];
  $password = $_POST["password"];

  // check if the user exist in DB    
  $row = $db->selectusernamefetch($username);


  // not exist
  if ($row == null) {
    $error2 = "There is no account associated with this username!<br>";
    echo $error2;
  } else {
    $hash = $row['password'];
    if (password_verify($password, $hash)) { // the user exists we check the PASSWORD;password_verify($password,$hash)


      // update the $_SESSION with new values

      $_SESSION["login"] = TRUE;
      $_SESSION["username"] = $row["username"];
      $_SESSION["personalid"] = $row["personalid"];
      $_SESSION["amount"] = $row["amount"];
      $_SESSION["accountid"] = $row["accountid"];

      // new connect to select from person table
      $row = $db->selectbyidfetch("person", "personalid", $_SESSION['personalid']);
      $_SESSION["firstname"] = $row["firstname"];

      if ($username == 'admin') {
        header("location:admin.php");
      } else {
        header("location:account.php");
      }
    } else {
      echo  "Invalid username or password!<br>";
    }
  }
}



//-----------------------------------------------------------------------------------------------------------------------------//

/**********************************
 * 
 * function that we use in account.php
 * 1.transmoney(): here we update the  values and do the transfration.
 * 2.updatedetails(): here we connect with database and update the details of the user.
 * 3.buildinsidetrans():here we build table of transfration for specifec user
 * 4.deleteAccount():function that delete the account from database and return the user to mainpage.
 * 
 * 
 * 
 */


//  1.transmoney(): here we update the  values and do the transfration.
function transmoney($db)
{


  if ($_POST['amounttosend'] > 0 && $_POST['amounttosend'] <= $_SESSION['amount'] && $_POST['receiverid'] != "") {

    $id = $_POST["receiverid"];
    $row = $db->selectbyidfetch("accounts", "accountid", $id);
    // not exist
    if ($row == null) {
      $error = "CHECK THE RECIEVER ID";
      echo $error;
    } else {
      // do the action of transfration 
      $amoutofthereciever = $row['amount'];
      $amoutofthereciever += $_POST["amounttosend"];
      $newsenderamount = $_SESSION["amount"] - $_POST["amounttosend"];
      $_SESSION["amount"] = $newsenderamount;
      $senderid = $_SESSION["accountid"];
      $amounttosend = $_POST['amounttosend'];


      // connect to db and update new values
      $db->updateamountInTable($amoutofthereciever, $id);
      $db->updateamountInTable($newsenderamount, $senderid);
      $db->insertfortrasferation($senderid, $id, $amounttosend);
    }
  } else {
    echo "CHECK AMOUNT!!";
  }
}


//-----------------------------------------------------------------------------------------------------------------------------//



//2.updatedetails(): here we connect with database and update the details of the user.
function updatedetails($db)
{
  $idforupdate = $_SESSION['personalid'];

  if ($_POST["newusername"] != "") {
    $username = $_POST["newusername"];
    $db->updatesomethingInTable("accounts", "username", $username, $idforupdate);
    $_SESSION["username"] = $username;
  }
  if ($_POST["password"] != "" && $_POST["password2"] != "" && $_POST["password"] == $_POST["password2"]) {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $db->updatesomethingInTable("accounts", "password", $password, $idforupdate);
  }
  if ($_POST["email"] != "") {
    $Email = $_POST["email"];
    $db->updatesomethingInTable("person", "email", $Email, $idforupdate);
  }
  if ($_POST["newphone"] != "") {
    $newphone = $_POST["newphone"];
    $db->updatesomethingInTable("person", "phone", $newphone, $idforupdate);
  }
  if ($_POST["newcity"] != "") {
    $newcity = $_POST["newcity"];
    $db->updatesomethingInTable("person", "city", $newcity, $idforupdate);
  }
}


//-----------------------------------------------------------------------------------------------------------------------------//



//3.buildinsidetrans():here we build table of transfration for specifec user
function buildinsidetable($db)
{
  $accountnum = $_SESSION["accountid"];
  $arraykeys = ["Transfer Number", "Sender Account Number", "Reciever Account Number", "Amount of Transfer"];

  $row = $db->sendalltransferations($accountnum);
  if (count($row) != 0) {
    $tbody = array_reduce($row, function ($a, $b) {
      return $a .= "<tr><td>" . implode("</td><td>", $b) . "</td></tr>";
    });
    for ($i = 0; $i < count($row); $i++) {
      $thead = "<tr><th>" . implode("</th><th>", array_values($arraykeys)) . "</th></tr>";
    }
    echo "<table border='1'>\n$thead\n$tbody\n</table>";
  } else {
    echo "No Transferation Yet";
  }
}

//-----------------------------------------------------------------------------------------------------------------------------//


//4.deleteAccount():function that delete the account from database and return the user to mainpage.
function deleteAccount($db)
{

  // define variables
  $usernametodel = $_POST["usernametodel"];
  $passwordtodel = $_POST["passwordtodel"];

  // connect to db to select username
  $row = $db->selectusernamefetch($usernametodel);

  // define variables
  $hash = $row["password"];
  $id = $_SESSION["personalid"];


  // start check
  if ($row != 0) {
    if ((password_verify($passwordtodel, $hash))) {
      // connect to database and delete user
      $db->deleteuser($usernametodel, $id);
      session_unset();
      session_destroy();
      header("location:index.php");
    } else {
      echo "Check Password";
    }
  } else {
    echo "Cant Delete The Account Check Username";
  }
}

//-----------------------------------------------------------------------------------------------------------------------------//



/*************
 * 
 * functions that we use in admin.php
 * 
 * 1.depositasadmin(): here admin can deposit money in all the account. 
 * 2.alltranstable(): here we get all the values in transfration table in database.
 * 
 * ********* */


// 1.depositasadmin: here admin can deposit money in all the account. 
function depositmoneyasadmin($db)
{
  // connect to database to select user
  $row = $db->selectbyidfetch("accounts", "accountid", $_POST['accountnumber']);


  // start check
  if ($row == null) {
    $error = "CHECK THE RECIEVER ID";
    echo $error;
  } else {
    $amoutofthereciever = $row['amount'];
    $amounttosend = $_POST["amount"];
    $amoutofthereciever += $amounttosend;
    $id= $_POST['accountnumber'];
    // connect to database and update new values
    $db->updateamountInTable($amoutofthereciever,$id);
    $db->insertfortrasferation(1019, $id, $amounttosend);
  }
}


//-----------------------------------------------------------------------------------------------------------------------------//




//2.alltranstable(): here we get all the values in transfration table in database.

function alltranstable($db)
{
  $arraykeys = ["Transfer Number", "Sender Account Number", "Reciever Acount Number", "Amount to send"];
  $row = $db->selectalltransferations();
  if (count($row) != 0) {
    $tbody = array_reduce($row, function ($a, $b) {
      return $a .= "<tr><td>" . implode("</td><td>", $b) . "</td></tr>";
    });
    for ($i = 0; $i < count($row); $i++) {
      $thead = "<tr><th>" . implode("</th><th>", $arraykeys) . "</th></tr>";
    }
    echo "<table border='1' class='alltrantable'>\n$thead\n$tbody\n</table>";
  } else {
    echo "No Transferation Yet";
  }
}














?>