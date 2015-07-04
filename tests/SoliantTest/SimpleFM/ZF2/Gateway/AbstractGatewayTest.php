<?php
namespace SoliantTest\SimpleFM\ZF2\Gateway;

use Mockery;
use Soliant\SimpleFM\HostConnection;
use Soliant\SimpleFM\Loader\Mock as MockLoader;
use Soliant\SimpleFM\ZF2\Entity\AbstractEntity;
use Soliant\SimpleFM\ZF2\Gateway\AbstractGateway;
use Soliant\SimpleFM\Adapter as SimpleFMAdapter;
use Soliant\SimpleFM\ZF2\Authentication\Mapper\Identity;
use Doctrine\Common\Collections\ArrayCollection;
use Soliant\SimpleFM\Exception\FileMakerException;
use Soliant\SimpleFM\Exception\HttpException;
use Soliant\SimpleFM\Exception\XmlException;
use Soliant\SimpleFM\Exception\ErrorException;
use Soliant\SimpleFM\Result\FmResultSet;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-06-28 at 11:25:28.
 */
class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockLoader
     */
    protected $mockLoaderInstance;

    /**
     * @var AbstractEntity
     */
    protected $mockEntityInstance;

    /**
     * @var AbstractGateway
     */
    protected $mockGatewayInstance;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        /***********************************************************************************************************
         * Mock Loader
         */
        $testXmlFile = file_get_contents(__DIR__ . '/../../TestAssets/sample_fmresultset.xml');
        $this->mockLoaderInstance = new MockLoader();
        $this->mockLoaderInstance->setTestXml($testXmlFile);

        /***********************************************************************************************************
         * Mock Entity
         */
        $originalClassName = 'Soliant\SimpleFM\ZF2\Entity\AbstractEntity';
        $arguments = [];
        $mockClassName = 'MockEntity';
        $callOriginalConstructor = true;
        $callOriginalClone = true;
        $callAutoload = true;
        $mockedMethods = [
            'getPropertyName',
            'getPropertyName2',
            'getFieldMapWriteable',
            'getFieldMapReadonly',
            'getDefaultWriteLayoutName',
            'getDefaultControllerRouteSegment'
        ];
        $cloneArguments = false;
        $this->mockEntityInstance = $this->getMockForAbstractClass(
            $originalClassName,
            $arguments,
            $mockClassName,
            $callOriginalConstructor,
            $callOriginalClone,
            $callAutoload,
            $mockedMethods,
            $cloneArguments
        );
        $this->mockEntityInstance->expects($this->any())
            ->method('getPropertyName')
            ->will($this->returnValue('value'));

        $this->mockEntityInstance->expects($this->any())
            ->method('getPropertyName2')
            ->will($this->returnValue('value2'));

        $this->mockEntityInstance->expects($this->any())
            ->method('getFieldMapWriteable')
            ->will($this->returnValue(['propertyName' => 'fieldName']));

        $this->mockEntityInstance->expects($this->any())
            ->method('getFieldMapReadonly')
            ->will($this->returnValue(['propertyName2' => 'fieldName2']));

        $this->mockEntityInstance->expects($this->any())
            ->method('getDefaultWriteLayoutName')
            ->will($this->returnValue('LayoutName'));

        $this->mockEntityInstance->expects($this->any())
            ->method('getDefaultControllerRouteSegment')
            ->will($this->returnValue('route-segment'));

        /***********************************************************************************************************
         * Mock Gateway
         */
        $originalClassName = 'Soliant\SimpleFM\ZF2\Gateway\AbstractGateway';
        $arguments = [
            $this->mockEntityInstance,
            new SimpleFMAdapter(
                new HostConnection(
                    'hostName',
                    'dbName',
                    'userName',
                    'password'
                ),
                $this->mockLoaderInstance
            ),
            new Identity(),
            'strongEncryptionKey'
        ];
        $mockClassName = 'MockGateway';
        $callOriginalConstructor = true;
        $callOriginalClone = true;
        $callAutoload = true;
        $mockedMethods = ['rowToEntity'];
        $cloneArguments = false;
        $this->mockGatewayInstance = $this->getMockForAbstractClass(
            $originalClassName,
            $arguments,
            $mockClassName,
            $callOriginalConstructor,
            $callOriginalClone,
            $callAutoload,
            $mockedMethods,
            $cloneArguments
        );
        $this->mockGatewayInstance->expects($this->any())
            ->method('rowToEntity')
            ->will($this->returnValue($this->mockEntityInstance));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::__construct
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::getEntityName
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::setEntityName
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::getSimpleFMAdapter
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::setSimpleFMAdapter
     */
    public function testConstruct()
    {
        $this->assertEquals($this->mockGatewayInstance, $this->mockGatewayInstance->setEntityName('FooBar'));
        $this->assertEquals($this->mockGatewayInstance->getEntityName(), 'FooBar');

        $newAdapter = new SimpleFMAdapter(
            new HostConnection(
                'hostNameAlternate',
                'dbName',
                'userName',
                'password'
            ),
            new MockLoader()
        );

        $this->assertEquals($this->mockGatewayInstance->setSimpleFMAdapter($newAdapter), $this->mockGatewayInstance);
        $this->assertEquals($this->mockGatewayInstance->getSimpleFMAdapter(), $newAdapter);
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::resolveEntity
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::getEntityLayout
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::setEntityLayout
     */
    public function testResolveEntity()
    {
        $this->assertEquals($this->mockGatewayInstance->resolveEntity(
            $this->mockEntityInstance
        ), $this->mockEntityInstance);
        $this->assertEquals($this->mockGatewayInstance->getEntityLayout(), 'LayoutName');
        $this->assertEquals($this->mockGatewayInstance->resolveEntity(
            $this->mockEntityInstance,
            'AlternateLayoutName'
        ), $this->mockEntityInstance);
        $this->assertEquals($this->mockGatewayInstance->getEntityLayout(), 'AlternateLayoutName');
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::find
     */
    public function testFind()
    {
        $this->assertEquals($this->mockGatewayInstance->find(
            $this->mockEntityInstance->getRecid()
        ), $this->mockEntityInstance);
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::findOneBy
     */
    public function testFindOneBy()
    {
        $this->assertEquals($this->mockGatewayInstance->findOneBy(
            [
                'field' => 'value@test.com',
                'field2' => 3,
            ]
        ), $this->mockEntityInstance);

        $this->mockGatewayInstance
            ->getSimpleFMAdapter()
            ->getLoader()
            ->setTestXml(
                file_get_contents(__DIR__ . '/../../TestAssets/sample_fmresultset_empty.xml')
            );

        $this->assertEquals($this->mockGatewayInstance->findOneBy(
            [
                'field' => 'value@test.com',
                'field2' => 3,
            ]
        ), null);
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::findAll
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::maxSkipToCommandArray
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::rowsToArrayCollection
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::handleAdapterResult
     */
    public function testFindAll()
    {
        $sort = ['foo' => 'asc'];
        $max = 10;
        $skip = 10;

        $arrayCollection = $this->mockGatewayInstance->findAll(
            $sort,
            $max,
            $skip
        );
        $this->assertInstanceOf(ArrayCollection::class, $arrayCollection);
        $this->assertEquals(17, $arrayCollection->count());

        $this->mockGatewayInstance
            ->getSimpleFMAdapter()
            ->getLoader()
            ->setTestXml(
                file_get_contents(__DIR__ . '/../../TestAssets/sample_fmresultset_empty.xml')
            );
        $arrayCollection = $this->mockGatewayInstance->findAll();
        $this->assertInstanceOf(ArrayCollection::class, $arrayCollection);
        $this->assertEquals(0, $arrayCollection->count());

        $this->setExpectedException(FileMakerException::class);
        $this->mockGatewayInstance
            ->getSimpleFMAdapter()
            ->getLoader()
            ->setTestXml(
                file_get_contents(__DIR__ . '/../../TestAssets/sample_fmresultset_fmerror4.xml')
            );
        $this->mockGatewayInstance->findAll();
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::findBy
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::sortArrayToCommandArray
     */
    public function testFindBy()
    {
        $search = ['foo' => 'Bar'];
        $sort = [
            'foo' => 'dsc',
            'bar' => 'desc',
            'baz' => 'descend',
            'bat' => 'asc',
            'fiz' => 'ascend',
            'zaz' => '',
            'but' => null,
            'tub' => 'invalid',
            'oof' => 'invalid',
            'rab' => 'invalid',
            'rat' => 'invalid',
        ];
        $max = 10;
        $skip = 10;

        $arrayCollection = $this->mockGatewayInstance->findBy(
            $search,
            $sort,
            $max,
            $skip
        );
        $this->assertInstanceOf(ArrayCollection::class, $arrayCollection);
        $this->assertEquals(17, $arrayCollection->count());

        $this->mockGatewayInstance
            ->getSimpleFMAdapter()
            ->getLoader()
            ->setTestXml(
                file_get_contents(__DIR__ . '/../../TestAssets/sample_fmresultset_empty.xml')
            );
        $arrayCollection = $this->mockGatewayInstance->findAll();
        $this->assertInstanceOf(ArrayCollection::class, $arrayCollection);
        $this->assertEquals(0, $arrayCollection->count());
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::create
     */
    public function testCreate()
    {
        $this->assertEquals($this->mockGatewayInstance->create(
            $this->mockEntityInstance
        ), $this->mockEntityInstance);
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::handleAdapterResult
     */
    public function testUnexpectedResultErrors()
    {
        $result = new FmResultSet(
            'http://fail',
            7,
            'Unexpected Error',
            ''
        );
        $this->setExpectedException(ErrorException::class);
        $this->mockGatewayInstance->handleAdapterResult($result->toArray());
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::handleAdapterResult
     */
    public function testHttpResultErrors()
    {
        $result = new FmResultSet(
            'http://fail',
            404,
            '404 Not Found',
            'HTTP'
        );
        $this->setExpectedException(HttpException::class);
        $this->mockGatewayInstance->handleAdapterResult($result->toArray());
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::handleAdapterResult
     */
    public function testXmlResultErrors()
    {
        $result = new FmResultSet(
            'http://fail',
            1,
            'Some XML Error',
            'XML'
        );
        $this->setExpectedException(XmlException::class);
        $this->mockGatewayInstance->handleAdapterResult($result->toArray());
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::edit
     */
    public function testEdit()
    {
        $this->assertEquals($this->mockGatewayInstance->edit(
            $this->mockEntityInstance
        ), $this->mockEntityInstance);
    }

    /**
     * @covers Soliant\SimpleFM\ZF2\Gateway\AbstractGateway::delete
     */
    public function testDelete()
    {
        $this->assertEquals($this->mockGatewayInstance->delete(
            $this->mockEntityInstance
        ), true);
    }
}
