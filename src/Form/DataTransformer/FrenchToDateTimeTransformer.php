<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface {

    public function transform($date){
        if ($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate) {
        if ($frenchDate === null) {
            throw new TransformationFailedException("Vous devez fournir une dâte");
        }

        $date = \Datetime::createFromFormat('d/m/Y', $frenchDate);

        if ($date === false) {
            throw new TransformationFailedException("Le format de votre date n'est pas la bon");
        }

        return $date;
    }

}