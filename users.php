<?php
require "db.php";

function isValidPhoneNumber($phoneNumber)
{
    $pattern = "/^\+?[0-9]{1,3}-?[0-9]{3,14}$/"; // Define the regex pattern for phone number validation
    return preg_match($pattern, $phoneNumber);
}


class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $picture;
    private $role;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->picture = $data['picture'];
        $this->role = $data['role'];
    }

    static public function register($firstName, $lastName, $email, $phone, $passowrd, $role = 'regular')
    {
        # Validation
        if (empty($firstName)) {
            header("location:signup.php?error=Empty First Name Field");
            return null;
        }
        if (empty($lastName)) {
            header("location:signup.php?error=Empty Last Name Field");
            return null;
        }
        if (empty($email)) {
            header("location:signup.php?error=Empty Email Field");
            return null;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            header("location:signup.php?error=Invalid Email Address");
            return null;
        }
        if (empty($phone)) {
            header("location:signup.php?error=Empty Phone Number Field");
            return null;
        }
        if (isValidPhoneNumber($phone) == false) {
            header("location:signup.php?error=Invalid Phone Number");
            return null;
        }
        if (empty($passowrd)) {
            header("location:signup.php?error=Empty Password Field");
            return null;
        }
        if (empty($role)) {
            header("location:signup.php?error=No Role");
            return null;
        }

        # Insert User into DB
        $hash = md5($passowrd);
        $query = "INSERT INTO users
                (`firstName`, `lastName`, `email`, `password`, `phone`) 
                VALUES ('$firstName', '$lastName', '$email', '$hash','$phone');";
        $result = db_exec_query($query);

        if (!$result)
            return null;
        if (is_string(($result))) {
            if (strpos($result, "Duplicate") !== false) {
                header("location:signup.php?error=$result");
                return null;
            }
            header("location:500.php");
            return null;
        }

        # Get User Data
        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query);
        if (!$result) {
            header("location:500.php");
            return null;
        }
        $userData = $result->fetch_assoc();
        $user = new User($userData);
        return $user;
    }

    static public function login($email, $passowrd)
    {
        if (empty($email)) {
        }

        if (empty($passowrd)) {
        }

        $hash = md5($passowrd);

        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query);
        $result = $result->fetch_assoc();
        return $result;
    }
}
