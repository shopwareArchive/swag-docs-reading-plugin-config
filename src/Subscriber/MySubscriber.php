<?php declare(strict_types=1);

namespace Swag\ReadingPluginConfig\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MySubscriber implements EventSubscriberInterface
{
    public const CONFIG_EXAMPLE_EXTENSION_NAME = 'exampleExtensionName';

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded',
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $exampleConfig = $this->systemConfigService->get('ReadingPluginConfig.config.example');

        $config = new ConfigStruct();
        $config->setExample($exampleConfig);

       $event->getPage()->addExtension(
            self::CONFIG_EXAMPLE_EXTENSION_NAME,
            $config
        );
    }
}
