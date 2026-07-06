<?php

namespace App\Domain\ValueObject;

/**
 * Value Object — slug musí být validní, nemění se po vytvoření.
 */
final readonly class TopicSlug
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromString(string $raw): self
    {
        $value = strtolower(trim($raw));
        if ($value === '' || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Neplatný slug: "%s"', $raw));
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
