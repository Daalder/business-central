<?php

namespace BusinessCentral\Commands;

use BusinessCentral\API\Jobs\SubscriptionKeep;
use BusinessCentral\API\Repositories\SubscriptionRepository;
use BusinessCentral\API\Services\NamespaceTranslations;
use BusinessCentral\Models\Subscription;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class BusinessCentralSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:subscription {action : Action create, delete, renew} {resource? : Resource name or comma-separated list}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Business Central Subscriptions';

    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $notificationUrl;

    /**
     * @var string
     */
    protected $resourceUrl;

    /**
     * Create a new command instance.
     *
     * @param  \BusinessCentral\API\Repositories\SubscriptionRepository  $repository
     */
    public function __construct(SubscriptionRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->notificationUrl = config('app.url').'business-central/webhook/subscription';
        $this->resourceUrl = '/api/v1.0/companies('.config('business-central.companyId').')/';
    }

    /**
     * Handle command.
     */
    public function handle()
    {
        $resources = $this->argument('resource') ? explode(',', $this->argument('resource'))
            : array_keys(NamespaceTranslations::$NAMESPACES);

        $functionName = 'handle' . ucfirst($this->argument('action'));

        foreach($resources as $resource) {
            $plural = Str::plural($resource);
            if(!in_array($plural, array_keys(NamespaceTranslations::$NAMESPACES))) {
                continue;
            }
            $this->{$functionName}($resource);
        }
    }

    /**
     * @param string $resource
     * @throws Exception
     */
    protected function handleCreate(string $resource)
    {
        $subscription = $this->repository->firstOrCreate([
            'subscriptionId' => $resource,
            'notificationUrl' => $this->notificationUrl.'/'.$resource,
            'resourceUrl' => $this->resourceUrl.$resource
        ]);
        $this->repository->create($subscription);
    }

    /**
     * @param string $resource
     * @throws Exception
     */
    protected function handleDelete(string $resource)
    {
        $this->repository->delete(Subscription::where([
            'subscriptionId' => $resource,
        ])->first());
    }

    /**
     * @param string $resource
     * @throws Exception
     */
    protected function handleRenew(string $resource)
    {
        $subscription = $this->repository->firstOrCreate([
            'subscriptionId' => $resource,
            'notificationUrl' => $this->notificationUrl.'/'.$resource,
            'resourceUrl' => $this->resourceUrl.$resource
        ]);

        if(!$subscription->wasRecentlyCreated) {
            SubscriptionKeep::dispatch($subscription);
        } else {
            $this->repository->create($subscription);
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
            ['action', InputArgument::REQUIRED, 'The desired action: create, delete or renew.'],
            ['resource', InputArgument::OPTIONAL,
                'The resource or comma-separated list of ' . implode(',', array_keys(NamespaceTranslations::$NAMESPACES))]
        ];
    }
}
