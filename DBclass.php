<?php
class DBclass
{
private $host;
private $db;
private $charset;
private $user;
private $pass;
private $opt = array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
private $connection;


public function __construct(string $host= "localhost", string $db = "bankoffice",
string $charset = "utf8", string $user = "root", string $pass = "123456")
{
$this->host = $host;
$this->db = $db;
$this->charset = $charset;
$this->user = $user;
$this->pass = $pass;
}


// start connection with database
private function connect()
{
$dns = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
$this->connection = new PDO($dns, $this->user, $this->pass, $this->opt);
}

// stop connection with database
public function disconnect()
{
$this->connection = null;
}


// function that do select specefic object by username and return an array with details about the user
function selectusernamefetch($username)
{
    $this->connect(); 
    $sql = 'SELECT * FROM accounts WHERE username = "'.$username.'"';
    $result = $this->connection->prepare($sql);
    $result->execute();
    $row=$result->fetch(PDO::FETCH_ASSOC);
    $this->disconnect();
    return $row;
}

// function that do select  by username and return an array with details about the user
function selectusernamefetchall($username):array
{
    $this->connect(); 
    $sql = 'SELECT * FROM accounts WHERE username = "'.$username.'"';
    $result = $this->connection->prepare($sql);
    $result->execute();
    $row=$result->fetchAll();
    $this->disconnect();
    return $row;
    
}
// function that do select  by id and return an array with details about the user
function selectbyidfetch($tablename,$key,$id)
{
    $this->connect(); 
    $sql = "SELECT * FROM $tablename WHERE $key =$id ";
    $result = $this->connection->prepare($sql);
    $result->execute();
    $row=$result->fetch(PDO::FETCH_ASSOC);
    $this->disconnect();
    return $row;
}


// function that insert into person table a new user
function insertforperson($personalid,$firstname,$lastname,$city,$Email, $phone){
          $this->connect();
          $sql = "INSERT INTO person ( `personalid` , `firstname` , `lastname` , `city` , `Email` , `phone`) VALUES ('$personalid','$firstname','$lastname','$city','$Email', '$phone')";
          $this->connection->query($sql);
          $this->disconnect();
}
// function that insert into accounts table a new user

function insertforaccount($username,$password , $personalid){
          $this->connect();
          $sql = "INSERT INTO accounts (  `username` , `password`, `personalid` , `amount` ) VALUES ('$username','$password' , $personalid , 0)";
          $this->connection->query($sql);
          $this->disconnect();
}

// function that insert into transfration table a new user

function insertfortrasferation($senderid, $id , $amounttosend){
  $this->connect();
  $sql = "INSERT INTO transferation ( `accountnumsender` , `accountnumreciever` , `amounttosend` ) VALUES ($senderid, $id , $amounttosend)";
  $result = $this->connection->query($sql);    
  $this->disconnect();

}



// function that do update in one table
function updatesomethingInTable($tablename,$nameofkey , $value , $id){
  $this->connect();
  $sql = "UPDATE $tablename SET $nameofkey= '$value' WHERE personalid= $id";
  $this->connection->query($sql);
  $this->disconnect();
}
// function that do update in one table the amount
function updateamountInTable($value , $id){
  $this->connect();
  $sql = "UPDATE accounts SET amount=$value WHERE accountid =$id";
  $this->connection->query($sql);
  $this->disconnect();
}

// function that get all the transfration for specefic user
function sendalltransferations($accountnum)
{
    $this->connect();
    $sql = "SELECT * FROM transferation WHERE accountnumsender = $accountnum || accountnumreciever = $accountnum";   
    $result = $this->connection->prepare($sql);
    $result->execute();
    $row=$result->fetchAll();
    $this->disconnect();
    return $row;

}

// function that delete specefic user
function deleteuser($username,$id){
  $this->connect();
  $sql = "DELETE FROM `accounts` WHERE username = '$username'";
  $result = $this->connection->query($sql);
   $sql = "DELETE FROM `person` WHERE personalid = $id";
  $result = $this->connection->query($sql);
  $this->disconnect();
}

// function that select and get all the transfration to display in admin page.
function selectalltransferations():array
{ $this->connect();
  $sql = "SELECT * FROM transferation";
  $result = $this->connection->prepare($sql);
  $result->execute();
  $row=$result->fetchAll();
  $this->disconnect();
  return $row;
}


}
