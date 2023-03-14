<?php

namespace ES101\CliCommand;

use ES101\ShoppingCart\ShoppingCartId;
use ES101\ShoppingCart\ShoppingCart;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cart:init',
    description: 'Initialize a new cart.',
    hidden: false
)]
class ShoppingCartInitCommand extends Command
{
    public function __construct(private readonly EventSourcedAggregateRootRepository $repository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cart_id = new ShoppingCartId();
        $cart = ShoppingCart::make($cart_id);

        $this->repository->persist($cart);

        $output->writeln("$cart_id created");

        return Command::SUCCESS;
    }
}