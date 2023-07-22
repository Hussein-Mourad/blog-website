<?php
if (!isset($_SESSION))
    session_start();

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../utils.php';


class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $picture;
    private $role;
    public $createdAt;

    public function __construct($id, $firstName, $lastName, $email, $phone, $role = 'regular', $picture = null)
    {
        if (empty($picture))
            $picture = "/assets/imgs/default-avatar.jpg";
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->picture = $picture;
        $this->role = $role;
    }

    static function getAllUsers()
    {
        $query = "SELECT
                    id,
                    firstName,
                    lastName,
                    email,
                    phone,
                    picture as avatar,
                    createdAt,
                    role
                FROM users;";
        $result = db_exec_query($query, "SELECT");
        if (!$result)
            return null;
        if (!$result->num_rows)
            return null;
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user =  new User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $row['phone'], $row['role'], $row['avatar']);
            $user->setCreatedAt($row['createdAt']);
            $users[$row['id']] = $user;
        }
        return  $users;
    }


    static function delete($id)
    {
        $query = "DELETE FROM users WHERE id = $id";
        $result = db_exec_query($query, "DELETE");
        if (!$result)
            return false;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
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

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
            redirect("/login.php");
    }

    static public function AdminOnly()
    {
        // Prevent non admins user from entering some page
        if (isset($_SESSION["user"])) {
            $user = unserialize($_SESSION["user"]);
            if ($user->getRole() != 'admin')
                redirect("/index.php");
            return $user;
        } else
            redirect("/login.php");
    }

    static public function preventAuth()
    {
        // Prevent authenticated user from entering signup pages
        if (isset($_SESSION["user"]))
            redirect("/index.php");
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
            redirect("/signup.php");
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
            redirect("/login.php");
            return null;
        }

        $id = $result;
        $user = new User($id, $firstName, $lastName, $email, $phone, $role);
        $_SESSION["user"] = serialize($user);
        unset($_SESSION['errors']);
        redirect("/index.php");
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
            redirect("/login.php");
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
            redirect("/login.php");
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
        redirect("/index.php");
        return $user;
    }

    static private function isValidPhoneNumber($phoneNumber)
    {
        $pattern = "/^\+?[0-9]{1,3}-?[0-9]{3,14}$/"; // Define the regex pattern for phone number validation
        return preg_match($pattern, $phoneNumber);
    }
}
