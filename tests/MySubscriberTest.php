<?php declare(strict_types=1);

namespace Swag\ReadingPluginConfigTests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPage;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Swag\ReadingPluginConfig\Subscriber\ConfigStruct;
use Swag\ReadingPluginConfig\Subscriber\MySubscriber;
use Symfony\Component\HttpFoundation\Request;

class MySubscriberTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function test_onProductPageLoaded()
    {
        /** @var SystemConfigService $configService */
        $configService = $this->getContainer()->get(SystemConfigService::class);
        $configService->set('ReadingPluginConfig.config.example', 'this is a simple test');

        $salesChannelContextFactory = $this->getContainer()->get(SalesChannelContextFactory::class);
        $salesChannelContext = $salesChannelContextFactory->create(
            Uuid::randomHex(),
            Defaults::SALES_CHANNEL
        );

        $event = new ProductPageLoadedEvent(
            new ProductPage(),
            $salesChannelContext,
            new Request()
        );

        $subscriber = $this->getSubscriber();
        $subscriber->onProductPageLoaded($event);

        /** @var ConfigStruct $result */
        $result = $event->getPage()->getExtension(MySubscriber::CONFIG_EXAMPLE_EXTENSION_NAME);

        static::assertSame('this is a simple test', $result->getExample());
    }

    private function getSubscriber(): MySubscriber
    {
        return new MySubscriber(
            $this->getContainer()->get(SystemConfigService::class)
        );
    }
}
