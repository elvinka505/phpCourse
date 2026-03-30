<?php

class Product
{
    private int $id;
    private float $price;
    private string $title;

    public function __construct(
        int $id,
        string $title,
        float $price
    ) {
        $this->title = $title;
        $this->price = $price;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2, '.', ' ') . ' руб.';
    }
}