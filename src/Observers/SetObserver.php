<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Observers;

use Daalder\BusinessCentral\Jobs\ProductAttribute\CreateSet;
use Daalder\BusinessCentral\Jobs\ProductAttribute\DeleteSet;
use Daalder\BusinessCentral\Jobs\ProductAttribute\UpdateSet;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pionect\Daalder\Models\ProductAttribute\Set;

class SetObserver
{
    use DispatchesJobs;

    public function updated(Set $set): void
    {
        $this->dispatch(new UpdateSet($set));
    }

    /**
     * Listen to the Product created event.
     */
    public function created(Set $set): void
    {
        $this->dispatch(new CreateSet($set));
    }

    /**
     * Listen to the Product deleted event.
     */
    public function deleted(Set $set): void
    {
        $this->dispatch(new DeleteSet($set));
    }
}
