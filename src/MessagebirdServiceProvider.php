<?php

namespace NotificationChannels\Messagebird;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Messagebird\MessagebirdChannel;
use NotificationChannels\Messagebird\Exceptions\InvalidConfiguration;

class MessagebirdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(MessagebirdChannel::class)
            ->needs(MessagebirdClient::class)
            ->give(function () {
                $this->getClient();
            });
			
		$this->app->bind('messagebird', function($app){
			return new MessagebirdChannel($this->getClient());
		});
    }
	
	/**
	 *  @brief Get the messagebird client instance.
	 *  
	 *  @return MessagebirdClient
	 */
	protected function getClient()
	{
		$config = config('services.messagebird');

		if (is_null($config)) {
			throw InvalidConfiguration::configurationNotSet();
		}

		return new MessagebirdClient(new Client(), $config['access_key']);
	}
}
