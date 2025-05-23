<?php

namespace Aries\MiniFrameworkStore\Models;

use Aries\MiniFrameworkStore\Includes\Database;
use PDO; // Add this line to ensure PDO is available if not globally scoped

class User extends Database {
    private $db;

    public function __construct() {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function login($data) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $data['email'],
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Use PDO::FETCH_ASSOC for clarity
    }

    public function register($data) {
        // Corrected SQL: Use a placeholder for role_id and bind it
        $sql = "INSERT INTO users (role_id, name, email, password, created_at, updated_at) VALUES (:role_id, :name, :email, :password, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);

        // Bind the role_id from the $data array
        $stmt->execute([
            'role_id' => $data['account_type'], // <-- CRITICAL FIX: Get role_id from $data
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($data) {
        $sql = "UPDATE users SET name = :name, email = :email, address = :address, phone = :phone, birthdate = :birthdate WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'birthdate' => $data['birthdate']
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
    }   
}