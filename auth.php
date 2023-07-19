<?php
session_start();
require "db.php";



class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $picture;
    private $role;

    public function __construct($id, $firstName, $lastName, $email, $phone, $role = 'regular', $picture = null)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->picture = $picture;
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }
}


class Auth
{
    static public function register($firstName, $lastName, $email, $phone, $passowrd, $role = 'regular')
    {
        session_unset();
        session_destroy();
        $errors = [];
        # Validation
        if (empty($firstName))
            $errors['firstName'] = 'You must provide a first name';
        if (empty($lastName))
            $errors['lastName'] = 'You must provide a last name';
        if (empty($email))
            $errors['email'] = 'You must provide an email';
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
            $errors['email'] = 'Invalid email Address';
        if (empty($phone))
            $errors['phone'] = 'You must provide a phone number';
        else if (Auth::isValidPhoneNumber($phone) == false)
            $errors['phone'] = 'Invalid phone number';
        if (empty($passowrd))
            $errors['password'] = 'You must provide a password';
        if (empty($role))
            $errors['role'] = 'You must provide a role';

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            // header("location: login.php");
            return null;
        }

        # Insert User into DB
        $hash = md5($passowrd);
        $query = "INSERT INTO users
                (`firstName`, `lastName`, `email`, `password`, `phone`) 
                VALUES ('$firstName', '$lastName', '$email', '$hash','$phone');";
        $result = db_exec_query($query, "INSERT");

        var_dump($result);
        if (!$result)
            return null;
        if (is_string(($result)) && strpos($result, "Duplicate") !== false) {
            $key = explode(" ", $result)[1]; // Duplicated field name
            $errors[$key] =  $result;
            $_SESSION['errors'] = $errors;
            // header("location: login.php");
            return null;
        }

        $id = $result;
        $user = new User($id, $firstName, $lastName, $email, $phone, $role);
        return $user;
    }

    static public function login($email, $passowrd)
    {
        session_unset();
        session_destroy();
        $errors = [];
        if (empty($email))
            $errors['email'] = 'You must provide an email';
        if (empty($passowrd))
            $errors['password'] = 'You must provide a password';
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
            $errors['email'] = 'Invalid Email Address';

        $hash = md5($passowrd);
        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query, "SELECT");
        $result = $result->fetch_assoc();
        if (!$result)
            return null;
        $password = $result['password'];
        if ($hash != $password)
            $errors['email'] = 'Invalid Email and/or Password';
        $user = new User(
            $result['id'],
            $result['firstName'],
            $result['lastName'],
            $result['email'],
            $result['phone'],
            $result['role'],
            $result['picture']
        );
        return $user;
    }

    static private function isValidPhoneNumber($phoneNumber)
    {
        $pattern = "/^\+?[0-9]{1,3}-?[0-9]{3,14}$/"; // Define the regex pattern for phone number validation
        return preg_match($pattern, $phoneNumber);
    }
}
