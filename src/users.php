<?php
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
        if (empty($firstName)) {
        }
        if (empty($lastName)) {
        }
        if (empty($email)) {
        }
        if (empty($phone)) {
        }
        if (empty($passowrd)) {
        }
        if (empty($role)) {
        }

        $hash = md5($passowrd);

        $query = "INSERT INTO users
                (`firstName`, `lastName`, `email`, `password`, `phone`) 
                VALUES ('$firstName', '$lastName', '$email', '$hash','$phone');";
        $result = db_exec_query($query);
        if (!$result)
            return null;

        if (is_string(($result)))
            return $result;

        $query = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $result = db_exec_query($query);
        if (!$result)
            return null;
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

$result = User::register("hussein", "kassem", "test5@gmail.com", "01009880434", "123456");
// $result = User::login("test3@gmail.com", "134");
// $query = "UPDATE users
//         SET picture = '/mnt/d/test/test.png'
//         WHERE id = '1'";
// $query = "DELETE FROM users 
//         where id = '12'
//         limit 1";
// $result = db_exec_query($query);
var_dump($result);