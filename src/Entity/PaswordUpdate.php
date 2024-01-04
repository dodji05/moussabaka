<?php

namespace App\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;


class PaswordUpdate
{



    private $newPassword;

    #[Assert\EqualTo(propertyPath:"newPassword", message:"Vous n'avez pas correctement confirmé votre mot de passe !")]
    public $passwordConfirm;

    /**
     * @return mixed
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @param mixed $passwordConfirm
     */
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;
    }

    /**
     * @return mixed
     */
    public function getNewPassword():?string
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword( string $newPassword)
    {
        $this->newPassword = $newPassword;
        return $this;
    }







}
