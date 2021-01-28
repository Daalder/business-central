<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Pionect\Backoffice\Models\Order\Order;
use Pionect\Backoffice\Models\Payment\Payment;

/**
 * Class PushNewOrdersToBusinessCentral
 *
 * @package App\Console\Commands
 */
class PushNewOrdersToBusinessCentral extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'bc:push-new-orders';

    /**
     * The console command description.
     */
    protected string $description = 'Push all orders of the past 24 hours that don\'t have existing relation with Business Central';

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
        $ids = Order::query()->PaymentState(Payment::OK)->whereDate('date', Carbon::today())->pluck('id');
        $this->call('bc:push', ['Create \\Pionect\\Backoffice\\Models\\Order\\Order '.implode(',', $ids->toArray())]);
    }
}
