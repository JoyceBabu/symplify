<?php declare(strict_types=1);

namespace Symplify\Autodiscovery\Tests\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\EntityManager;
use Symplify\Autodiscovery\Tests\Source\HttpKernel\AudiscoveryTestingKernel;
use Symplify\Autodiscovery\Tests\Source\KernelProjectDir\Entity\Product;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @covers \Symplify\Autodiscovery\Doctrine\DoctrineEntityMappingAutodiscoverer
 */
final class DoctrineEntityAutodiscoverTest extends AbstractKernelTestCase
{
    /**
     * @var MappingDriver
     */
    private $mappingDriver;

    protected function setUp(): void
    {
        static::bootKernel(AudiscoveryTestingKernel::class);

        /** @var Registry $registry */
        $registry = static::$container->get('doctrine');

        /** @var EntityManager $entityManager */
        $entityManager = $registry->getManager();
        $configuration = $entityManager->getConfiguration();

        $this->mappingDriver = $configuration->getMetadataDriverImpl();
    }

    public function test(): void
    {
        $entityClasses = [
            Product::class,
            'Kedlubna\Component\Tagging\Context\Context',
            'Kedlubna\Component\Tagging\Tag\Tag',
        ];

        $this->assertSame($entityClasses, $this->mappingDriver->getAllClassNames());
    }
}
