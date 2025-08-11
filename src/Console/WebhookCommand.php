<?php

namespace Chargebee\Cashier\Console;

use Illuminate\Console\Command;
use Chargebee\Cashier\Cashier;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cashier:webhook')]
class WebhookCommand extends Command
{
    public const DEFAULT_EVENTS = [
        'customer_deleted',
        'customer_changed',
        'subscription_created',
        'subscription_changed',
        'subscription_renewed'
    ];
    public const DEFAULT_NAME = "cashier-webhook-endpoint";

    public const  DEFAULT_AUTH_PASSWORD = "default_password";

    public const  DEFAULT_AUTH_USERNAME = "default_username";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:webhook
            {--disabled : Immediately disable the webhook after creation}
            {--url= : The full URL of the webhook endpoint.}
            {--api-version= : The Chargebee API version the webhook should use}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the Chargebee webhook to interact with Cashier';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $webhookEndpoints = Cashier::chargebee()->webhookEndpoint();

            $endpoint = $webhookEndpoints->create([
                'enabled_events' => config('cashier.webhook.events') ?: self::DEFAULT_EVENTS,
                'url' => $this->option('url') ?: route('chargebee.webhook'),
                'api_version' => $this->option('api-version') ?: "v2",
                'name' =>  config('cashier.webhook.events') ?: self::DEFAULT_NAME . $this->generateShortTimestampSuffix(),
                'basic_auth_password' => config('cashier.webhook.password') ?: self::DEFAULT_AUTH_PASSWORD,
                'basic_auth_username' => config('cashier.webhook.username') ?: self::DEFAULT_AUTH_USERNAME,
            ])->webhook_endpoint;

            $this->components->info('✅ The Chargebee webhook was created successfully.');
            $this->components->info('🔐 Add the basic auth password and username in environment variables.');

            if ($this->option('disabled')) {
                $webhookEndpoints->update($endpoint->id, ['disabled' => true]);

                $this->components->info('The Chargebee webhook was disabled as requested. You may enable the webhook via the Chargebee dashboard when needed.');
            }
        } catch (\Exception $e) {
            $this->error('Failed to create the Chargebee webhook: ' . $e->getMessage());
            return;
        }
    }

    protected function generateShortTimestampSuffix(): string
    {
        return substr(now()->format('His'), -4);
    }
}
