<?php

namespace Framework;

use Framework\Validator\ValidationError;

class Validator
{

    /**
     * @var array
     */
    private $params;

    /**
     * @var tableau de chaine de caractère
     */
    private $errors = [];


    public function __construct(array $params)
    {

        $this->params = $params;
    }

    /**
     * vérifie si le champ est renseigné
     * @param string[] ...$keys
     * @return Validator
     */
    public function required(string ...$keys): self
    {

        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }

        return $this;
    }

    /**
     * vérifie que le champ n'est pas vide
     * @param string[] ...$keys
     * @return $this
     */
    public function isNotEmpty(string ...$keys)
    {

        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }

        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);

        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) &&
            $length < $min
        ) {
            $this->addError($key, 'minlength', [$min]);
            return $this;
        }
        if (!is_null($max) &&
            $length > $max
        ) {
            $this->addError($key, 'maxlength', [$max]);
        }
        return $this;
    }
    /**
     * vérifie que le slug est bien la bonne forme
     * @param string $key
     * @param string $slug
     */
    public function slug(string $key): self
    {
        $pattern = '/^[a-z09]+(-[a-z0-9]+)*$/';
        $value = $this->getValue($key);

        if (is_null($value)) {
            return $this;
        }

        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }

        return $this;
    }

    /**
     * récupère les erreur
     * @return tableau
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function dateTime(string $key, string $format = "Y-m-d H:i:s"): self
    {
        $value =  $this->getValue($key);
        $dateTime = \DateTime::createFromFormat($format, $value);

        $errors = \DateTime::getLastErrors();

        if ($errors['errors_count'] > 0 && $errors['warning_count'] > 0 || $dateTime === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
    /**
     * ajoute une erreur
     * @param string $key
     * @param string $rule
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }
}
