<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Commands;

use Daalder\BusinessCentral\API\Jobs\SubscriptionKeep;
use Daalder\BusinessCentral\API\Repositories\SubscriptionRepository;
use Daalder\BusinessCentral\API\Services\NamespaceTranslations;
use Daalder\BusinessCentral\Models\Subscription;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class BusinessCentralSubscription extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'bc:subscription {action : Action create, delete, renew} {resource? : Resource name or comma-separated list}';

    /**
     * The console command description.
     */
    protected string $description = 'Manage Business Central Subscriptions';

    protected SubscriptionRepository $repository;

    protected string $notificationUrl;

    protected string $resourceUrl;

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
    public function handle(): void
    {
        $resources = $this->argument('resource') ? explode(',', $this->argument('resource'))
            : array_keys(NamespaceTranslations::$NAMESPACES);

        $functionName = 'handle' . ucfirst($this->argument('action'));

        foreach ($resources as $resource) {
            $plural = Str::plural($resource);
            if (! in_array($plural, array_keys(NamespaceTranslations::$NAMESPACES))) {
                continue;
            }
            $this->{$functionName}($resource);
        }
    }

    /**
     * @throws Exception
     */
    protected function handleCreate(string $resource): void
    {
        $subscription = $this->repository->firstOrCreate([
            'subscriptionId' => $resource,
            'notificationUrl' => $this->notificationUrl.'/'.$resource,
            'resourceUrl' => $this->resourceUrl.$resource,
        ]);
        $this->repository->create($subscription);
    }

    /**
     * @throws Exception
     */
    protected function handleDelete(string $resource): void
    {
        $this->repository->delete(Subscription::where([
            'subscriptionId' => $resource,
        ])->first());
    }

    /**
     * @throws Exception
     */
    protected function handleRenew(string $resource): void
    {
        $subscription = $this->repository->firstOrCreate([
            'subscriptionId' => $resource,
            'notificationUrl' => $this->notificationUrl.'/'.$resource,
            'resourceUrl' => $this->resourceUrl.$resource,
        ]);

        if (! $subscription->wasRecentlyCreated) {
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
    protected function getArguments(): array
    {
        return [
            ['action', InputArgument::REQUIRED, 'The desired action: create, delete or renew.'],
            ['resource', InputArgument::OPTIONAL,
                'The resource or comma-separated list of ' . implode(',', array_keys(NamespaceTranslations::$NAMESPACES)),
            ],
        ];
    }
}
