<?php
class Member {

    //private data member
    private $dbCon;

    //properties
    //idmember, fname, lname, username, password, profile
    public int $idmember;
    public string $fname;
    public string $lname;
    public string $username;
    public string $password;
    public string $profile;
    
    // constructor
    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }
    public function getUsername() {
        return $this->username;
    }
    public function login($uName, $uPass) {
        $q = "SELECT * FROM member WHERE username='$uName' AND password='$uPass'";
            
        $resultSet = $this->dbCon->query($q);
        if (mysqli_num_rows($resultSet) > 0) { 
            $row = $resultSet->fetch_array();
            $this-> idmember = $row['idmember'];
            $this->fname = $row['fname'];
            $this->lname = $row['lname'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->profile = $row['profile'];
            return true;
            
        } else { 
            return false;
        }
    }
    
    public function Registrasi($uName, $uPass,$fName,$lName) {
        $q = "SELECT * FROM member WHERE username='$uName'";
            
        $resultSet = $this->dbCon->query($q);
        if (mysqli_num_rows($resultSet) > 0) { 
            return false;
            
        } else { 
            $q = "INSERT INTO member (fname, lname, username, password, profile) VALUES ('$fName', '$lName', '$uName', '$uPass','member')";
            if ($this->dbCon->query($q) === TRUE) {
                return true;
            }
            else{
                return false;
            }

        }
    }
    // destructor
    function __destruct() {        
    }
}
?>