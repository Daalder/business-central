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
    protected string $signature = 'bc:pull-inventory {sku?}';

    /**
     * The console command description.
     */
    protected string $description = 'Pull inventory from Business Central';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function handle(): void
    {
        $productSku = $this->argument('sku');
        PullProducts::dispatchNow($productSku);
    }
}
