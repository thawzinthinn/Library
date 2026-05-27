<?php

namespace App\Model;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(
        ?int $id = null,
        string $name = '',
        string $email = '',
        string $password = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }

    public function setPassword(string $password): void
    {
        // hash password before save
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? ''
        );
    }
}