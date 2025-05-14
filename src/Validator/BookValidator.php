<?php

namespace App\Validator;

use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(Book $book): array
    {
        $errors = $this->validator->validate($book);
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}