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
    name: 'cart:item:add',
    description: 'Add an item to the cart.',
    hidden: false
)]
class ShoppingCartAddItemCommand extends Command
{
    public function __construct(private readonly EventSourcedAggregateRootRepository $repository, private readonly ProductService $product_service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('cart_id', InputArgument::REQUIRED, 'cart id')
            ->addArgument('product_id', InputArgument::REQUIRED, 'product id')
            ->addArgument('qty', InputArgument::OPTIONAL, 'qty')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cart_id = new ShoppingCartId($input->getArgument('cart_id'));
        $product_id = $input->getArgument('product_id');
        $qty = $input->getArgument('qty') ?? 1;

        $product = $this->product_service->findById($product_id);

        $cart = $this->repository->retrieve($cart_id);
        $command = new AddItem($product, $qty);
        
        $cart->process($command);
        
        $this->repository->persist($cart);

        return Command::SUCCESS;
    }
}