<?php

namespace BusinessCentral\Observers;

use BusinessCentral\Jobs\ProductAttribute\CreateSet;
use BusinessCentral\Jobs\ProductAttribute\DeleteSet;
use BusinessCentral\Jobs\ProductAttribute\UpdateSet;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pionect\Backoffice\Models\ProductAttribute\Set;

class SetObserver
{
    use DispatchesJobs;

    /**
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Set  $set
     */
    public function updated(Set $set)
    {
        $this->dispatch(new UpdateSet($set));
    }

    /**
     * Listen to the Product created event.
     *
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Set  $set
     */
    public function created(Set $set)
    {
        $this->dispatch(new CreateSet($set));
    }

    /**
     * Listen to the Product deleted event.
     *
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Set  $set
     */
    public function deleted(Set $set)
    {
        $this->dispatch(new DeleteSet($set));
    }


}
