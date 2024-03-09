<?php

namespace App\Domain\ApplePay\ApplePayLib\Entities;

use App\Domain\ApplePay\ApplePayLib\DTO\ApplePayAuth;
use App\Domain\ApplePay\ApplePayLib\Exceptions\InvalidSettingsException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

/**
 * Class Settings.
 * @property ApplePayAuth $auth
 * @property int $timeout
 * @property int $connect_timeout
 * @property array $headers
 * @property Client $httpClient
 */
class Settings
{
    /**
     * @throws InvalidSettingsException
     */
    public function __construct(public array $settings)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        try {
            $this->settings = $resolver->resolve($this->settings);
        } catch (Throwable $exception) {
            throw new InvalidSettingsException($exception->getMessage());
        }
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('auth')->allowedTypes(ApplePayAuth::class)->default(10);
        $resolver->define('timeout')->allowedTypes('int')->default(10);
        $resolver->define('connect_timeout')->allowedTypes('int')->default(5);
        $resolver->define('headers')->allowedTypes('array')->default([]);
        $resolver->setNormalizer(
            'baseUrl', fn (Options $options, $value) => rtrim((string) $value, '/').'/'
        );
        $resolver->define('httpClient')->allowedTypes(ClientInterface::class)
            ->default(function (Options $options): Client {
                $headers = array_merge(['User-Agent' => 'Apple Pay Client'], $options['headers']);
                $handler = HandlerStack::create();

                /** @var ApplePayAuth $aut */
                $aut = $options['auth'];

                $stack = [
                    'cert' => $aut->cert,
                    'ssl_key' => $aut->sslKey,
                    'ssl_key_password' => $aut->sslKeyPassword,
                    'headers' => $headers,
                    'connect_timeout' => $options['connect_timeout'],
                    'timeout' => $options['timeout'],
                    'http_errors' => true,
                ];

                $stack['handler'] = $handler;

                return new Client($stack);
            });
    }
}
