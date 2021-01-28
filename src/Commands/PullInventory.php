<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Commands;

use Daalder\BusinessCentral\Jobs\Product\PullProducts;
use Illuminate\Console\Command;

class PullInventory extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bc:pull-inventory {sku?}';

    /**
     * The console command description.
     */
    protected $description = 'Pull inventory from Business Central';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $productSku = $this->argument('sku');
        PullProducts::dispatchNow($productSku);
    }
}
