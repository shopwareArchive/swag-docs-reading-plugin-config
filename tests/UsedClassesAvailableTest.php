<?php declare(strict_types=1);

namespace Swag\ReadingPluginConfigTests;

use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class UsedClassesAvailableTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testClassesAreInstantiable(): void
    {
        $namespace = str_replace('Tests', '', __NAMESPACE__);

        $files = $this->getPluginClasses();

        foreach ($files as $class) {
            if (!preg_match('/.*.php$/', $class->getRelativePathname())) {
                continue;
            }

            $classRelativePath = str_replace(['.php', '/'], ['', '\\'], $class->getRelativePathname());

            $this->getMockBuilder($namespace . '\\' . $classRelativePath)
                ->disableOriginalConstructor()
                ->getMock();
        }

        // Nothing broke so far, classes seem to be instantiable
        static::assertCount(5, $files);
    }

    private function getPluginClasses(): Finder
    {
        $finder = new Finder();
        $finder->in(realpath(__DIR__ . '/../src'));
        $finder->exclude('Test');

        return $finder->files()->name('/.*.(php|xml)$/');
    }
}
