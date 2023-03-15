<?php

namespace ES101\CliCommand;

use ES101\Product\ProductService;
use ES101\ShoppingCart\Command\AddItem;
use ES101\ShoppingCart\ShoppingCartId;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use Money\Money;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cart:item:remove',
    description: 'Remove an item from the cart.',
    hidden: false
)]
class ShoppingCartRemoveItemCommand extends Command
{
    public function __construct(private readonly EventSourcedAggregateRootRepository $repository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('cart_id', InputArgument::REQUIRED, 'cart id')
            ->addArgument('product_id', InputArgument::REQUIRED, 'product id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cart_id = new ShoppingCartId($input->getArgument('cart_id'));
        $product_id = $input->getArgument('product_id');
        $qty = $input->getArgument('qty') ?? 1;

        $prod = ProductService::products[$product_id];
        $product = new ProductService($product_id, $prod[0], $qty, Money::USD($prod[1]));

        $cart = $this->repository->retrieve($cart_id);
        $command = new AddItem($product);
        
        $cart->process($command);
        
        $this->repository->persist($cart);

        return Command::SUCCESS;
    }
}