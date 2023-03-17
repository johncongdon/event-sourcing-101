<?php

namespace ES101\Product;

class ProductService
{
    private const PRODUCTS = [
        1 => ['name' => 'Magazine', 'price' => 499],
        2 => ['name' => 'Book', 'price' => 1999],
        3 => ['name' => 'Elephant', 'price' => 3000],
        4 => ['name' => 'php[tek]', 'price' => 70000],
    ];

    public function findById(int $id): Product
    {
        return new Product($id, self::PRODUCTS[$id]['name'], self::PRODUCTS[$id]['price']);
    }
}
