<?php

namespace BusinessCentral\Commands;

use BusinessCentral\API\HttpClient;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;

class PushToBusinessCentral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:push {action} {type} {id?} {--all : Whether all items should be run} {--v}';


    protected $mapping = [
        '\Pionect\Backoffice\Models\Product\Product'      => 'item',
        '\Pionect\Backoffice\Models\Order\Order'          => 'salesOrder',
        '\Pionect\Backoffice\Models\Customer\Customer'    => 'customer',
        '\Pionect\Backoffice\Models\ProductAttribute\Set' => 'itemCategory'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push items to BusinessCentral';

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
        if ($this->option('all')) {
            $this->publishAllItems($this->argument('action'), $this->argument('type'));
        } else {
            $this->publishItem($this->argument('action'), $this->argument('type'), $this->argument('id'));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['action', InputArgument::REQUIRED, 'The action that should be performed.'],
            ['type', InputArgument::REQUIRED, 'The type: Order|Product.'],
            ['id', InputArgument::REQUIRED, 'The id (of ids) of the type.']
        ];
    }

    private function publishItem($action, $type, $ids)
    {
        $ids = explode(',', $ids);
        $this->output->progressStart(count($ids));

        foreach ($ids as $id) {
            $this->info(
                'Action: '.$action.'; '.
                'Type: '.$type.'; '.
                'Id: '.$id.'; ');

            /* @var $handler Model */
            $handler = resolve($type);

            //$item = Product::find($id);
            try {
                $item = $handler->newQuery()
                    ->withTrashed()
                    ->where('id', $id)
                    ->first();
            } catch (\Exception $e) {
                $item = $handler->newQuery()
                    ->where('id', $id)
                    ->first();
            }

            //dd($item);
            $this->sendPayload($type, $action, $item);

        }
    }

    /**
     * @param $action
     * @param $type
     */
    private function publishAllItems($action, $type)
    {
        /* @var $handler Model */
        $handler = resolve($type);

        try {
            $this->output->progressStart($handler->newQuery()->whereNull('deleted_at')->count());
            $handler->newQuery()->whereNull('deleted_at')->chunk(1000, function ($items) use ($type, $action) {

                foreach ($items as $item) {
                    $this->sendPayload($type, $action, $item);

                }
            });
            $this->output->progressFinish();
        } catch (\Exception $e) {
            $this->error('Could not retrieve '.$type);
            exit;
        }

    }

    /**
     * @param $type
     * @param $action
     * @param $item
     */
    private function sendPayload($type, $action, $item)
    {
        try {
            if ($item) {
                /** @var HttpClient $client */
                $client = App::make(HttpClient::class);
                $client->{$this->mapping[$type]}()->{$action}($item);
                $this->output->progressAdvance();
            } else {
                $this->error('No item found.');
            }

        } catch (\Exception $exception) {
            $this->error('Exception: '.$exception->getMessage().' in '.$exception->getFile().':'.$exception->getLine());
            $this->error($exception->getTraceAsString());
        }
    }
}
