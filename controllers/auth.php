<?php
if (!isset($_SESSION))
    session_start();
require_once("./db.php");


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
    // Privilages
    static public function isAuth()
    {
        // Returns the user it he is authenticated
        if (isset($_SESSION["user"]))
            return unserialize($_SESSION["user"]);
        else
            return null;
    }

    static public function AuthOnly()
    {
        // Prevent not authenticated user from entering some page
        if (!isset($_SESSION["user"]))
            header("location: ../login.php");
    }

    static public function AdminOnly()
    {
        // Prevent non admins user from entering some page
        if (isset($_SESSION["user"]))
            $user = unserialize($_SESSION["user"]);
        if ($user['role'] != 'admin')
            header("location: ../index.php");
        else
            header("location: ../login.php");
    }

    static public function preventAuth()
    {
        // Prevent authenticated user from entering signup pages
        if (isset($_SESSION["user"]))
            header("location: ../index.php");
    }



    static public function register($firstName, $lastName, $email, $phone, $passowrd, $role = 'regular')
    {
        Auth::preventAuth();
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
            header("location: ../signup.php");
            return null;
        }

        # Insert User into DB
        $hash = password_hash($passowrd, PASSWORD_DEFAULT);
        $query = "INSERT INTO users
                (`firstName`, `lastName`, `email`, `password`, `phone`) 
                VALUES ('$firstName', '$lastName', '$email', '$hash','$phone');";
        $result = db_exec_query($query, "INSERT");

        if (!$result)
            return null;
        if (is_string(($result)) && strpos($result, "Duplicate") !== false) {
            $key = explode(" ", $result)[1]; // Duplicated field name
            $errors[$key] =  $result;
            $_SESSION['errors'] = $errors;
            header("location: ../login.php");
            return null;
        }

        $id = $result;
        $user = new User($id, $firstName, $lastName, $email, $phone, $role);
        $_SESSION["user"] = serialize($user);
        unset($_SESSION['errors']);
        header("location: ../index.php");
        return $user;
    }

    static public function login($email, $password)
    {
        Auth::preventAuth();
        $errors = [];
        $_SESSION['errors'] = $errors;
        if (empty($email))
            $errors['email'] = 'You must provide an email';
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
            $errors['email'] = 'Invalid Email Address';
        if (empty($password))
            $errors['password'] = 'You must provide a password';

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            header("location: ../login.php");
            return null;
        }

        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query, "SELECT");
        $result = $result->fetch_assoc();
        if (!$result || !password_verify($password, $result['password'])) {
            $errors['email_password'] = 'Invalid Email and/or Password';
            $_SESSION['errors'] = $errors;
            header("location: ../login.php");
            return null;
        }

        $user = new User(
            $result['id'],
            $result['firstName'],
            $result['lastName'],
            $result['email'],
            $result['phone'],
            $result['role'],
            $result['picture']
        );
        $_SESSION["user"] = serialize($user);
        unset($_SESSION['errors']);
        header("location: ../index.php");
        return $user;
    }

    static private function isValidPhoneNumber($phoneNumber)
    {
        $pattern = "/^\+?[0-9]{1,3}-?[0-9]{3,14}$/"; // Define the regex pattern for phone number validation
        return preg_match($pattern, $phoneNumber);
    }
}
