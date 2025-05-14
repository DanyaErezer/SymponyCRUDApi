<?php

namespace App\Validator;

use App\Entity\Author;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(Author $author): array
    {
        $errors = $this->validator->validate($author);
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}