<?php

class User {
    private string $nom;
    private string $email;
    private string $password;
    private string $role;
    private ?string $niveau;
    public function __construct(string $nom, string $email, string $password, string $role, ?string $niveau = null) {
    $this->nom = $nom;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
    $this->niveau = $niveau;
}
public function getNom() {
    return $this->nom;
}

public function getEmail() {
    return $this->email;
}

public function getPassword() {
    return $this->password;
}

public function getRole() {
    return $this->role;
}

public function getNiveau() {
    return $this->niveau;
}
public function setNom(string $nom) {
    $this->nom = $nom;
}

public function setEmail(string $email) {
    $this->email = $email;
}

public function setPassword(string $password) {
    $this->password = $password;
}

public function setRole(string $role) {
    $this->role = $role;
}

public function setNiveau(string $niveau) {
    $this->niveau = $niveau;
}




}
