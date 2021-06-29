<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Commands;

use Daalder\BusinessCentral\API\HttpClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;

class PullFromBusinessCentral extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'bc:pull {type} {top=20000} {skip=0}';

    protected $mapping = [
        '\Pionect\Daalder\Models\Product\Product' => 'item',
        '\Pionect\Daalder\Models\Order\Order' => 'salesOrder',
        '\Pionect\Daalder\Models\Customer\Customer' => 'customer',
        '\Pionect\Daalder\Models\ProductAttribute\Set' => 'itemCategory',
    ];

    /**
     * The console command description.
     */
    protected string $description = 'Pull from BusinessCentral';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pullAllItems($this->argument('type'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['type', InputArgument::REQUIRED, 'The type: Order|Product.'],
            ['id', InputArgument::REQUIRED, 'The id (of ids) of the type.'],
        ];
    }

    private function pullAllItems($type): void
    {
        try {
            $this->sendPayload($type, $this->argument('top'), $this->argument('skip'));
        } catch (\Exception $e) {
            $this->error('Could not retrieve '.$type);
            exit;
        }
    }

    private function sendPayload($type, $top, $skip): void
    {
        try {
            /** @var HttpClient $client */
            $client = App::make(HttpClient::class);
            $client->{$this->mapping[$type]}()->pullReferences($this, $top, $skip);
        } catch (\Exception $exception) {
            $this->error('Exception: '.$exception->getMessage().' in '.$exception->getFile().':'.$exception->getLine());
            $this->error($exception->getTraceAsString());
        }
    }
}
