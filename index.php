<?php

declare(strict_types=1);

interface DbManager
{
    public function insert(string $table, object $data): string;
}

class MySql implements DbManager
{
    public function insert(string $table, object $data): string
    {
        return 'insert';
    }
}
class UserRepository
{
    public function __construct(
        protected DbManager $dbManager,
        protected User $user,
    ) {
    }
    public function registerUser(User $userData)
    {
        $this->dbManager->insert('users', $this->user);
        echo 'user register ';
    }
}

interface SendManager
{
    public function send(string $data): void;
}


class EmailService implements SendManager
{
    public function send(string $email): void
    {
        echo 'send email ';
    }
}

class SMSService implements SendManager
{
    public function send(string $phone): void
    {
        echo 'send sms ';
    }

}

class User
{
    public function __construct(
        private string $email,
        private string $name,
        private string $phoneNumber,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

}

class UserService
{
    public function __construct(
        protected User $user,
        protected UserRepository $userRepository,
        protected SendManager $sendSMS,
        protected SendManager $sendEmail,
    ) {
        $this->userRepository->registerUser($user);
        $this->sendSMS->send($this->user->getPhoneNumber());
        $this->sendEmail->send($this->user->getEmail());
    }
}


$user = new User(
    'email@gmai.com',
    'name',
    '80987733345'
);

$userRepository = new UserRepository(new MySql(), $user);

$userService = new UserService(
    $user,
    $userRepository,
    new SMSService(),
    new EmailService()
);


