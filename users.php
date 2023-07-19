<?php
session_start();
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
        else if (isValidPhoneNumber($phone) == false)
            $errors['phone'] = 'Invalid phone number';
        if (empty($passowrd))
            $errors['password'] = 'You must provide a password';
        if (empty($role))
            $errors['role'] = 'You must provide a role';
        
        if (count($errors))
        {
            $_SESSION['errors'] = $errors;
            header("location: login.php");
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
        if (is_string(($result)) && strpos($result, "Duplicate") !== false) {
            $key = explode(" ", $result)[1]; // Duplicated field name
            $errors[$key] =  $result;
            $_SESSION['errors'] = $errors;
            header("location: login.php");
            return null;
        }

        # Get User Data
        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query);
        var_dump(!$result->num_rows);
        if (!$result->num_rows) {
            return null;
        }
        $userData = $result->fetch_assoc();
        $user = new User($userData);
        return $user;
    }

    static public function login($email, $passowrd)
    {
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
        $result = db_exec_query($query);
        $result = $result->fetch_assoc();
        $password = $result['password'];
        if ($hash != $password)
            $errors['email'] = 'Invalid Email and/or Password';
        $user = new User($result);
        return $user;
    }
}
