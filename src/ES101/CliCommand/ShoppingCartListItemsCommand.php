<?php

namespace ES101\CliCommand;

use ES101\Product\ProductService;
use ES101\ShoppingCart\Command\AddItem;
use ES101\ShoppingCart\ShoppingCartId;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cart:item:list',
    description: 'List a cart\'s contents',
    hidden: false
)]
class ShoppingCartListItemsCommand extends Command
{
    public function __construct(private readonly EventSourcedAggregateRootRepository $repository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('cart_id', InputArgument::REQUIRED, 'cart id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cart_id = new ShoppingCartId($input->getArgument('cart_id'));

        $cart = $this->repository->retrieve($cart_id);

        /** @var \ES101\ShoppingCart\LineItem[] $items */
        $items = $cart->getItems();
        foreach ($items as $item) {

            $currencies = new ISOCurrencies();

            $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

            print "Product #{$item->product->id}    Qty: {$item->qty}: " . $moneyFormatter->format($item->price) . "\n";
        }

        return Command::SUCCESS;
    }
}