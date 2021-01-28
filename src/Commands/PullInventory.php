<?php

namespace BusinessCentral\Commands;

use Illuminate\Console\Command;
use BusinessCentral\Jobs\Product\PullProducts;

class PullInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:pull-inventory {sku?}';

    /**
     * The console command description.
     *
     * @var string
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

    /**
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function handle()
    {
        $productSku = $this->argument('sku');
        PullProducts::dispatchNow($productSku);
    }
}
