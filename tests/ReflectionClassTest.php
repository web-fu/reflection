<?php

declare(strict_types=1);

/**
 * This file is part of web-fu/reflection
 *
 * @copyright Web-Fu <info@web-fu.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebFu\Reflection\Tests;

use ArrayObject;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionExtension;
use stdClass;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionClassConstant;
use WebFu\Reflection\ReflectionException;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionUseStatement;
use WebFu\Reflection\Tests\data\AbstractClass;
use WebFu\Reflection\Tests\data\ClassFinal;
use WebFu\Reflection\Tests\data\ClassNonClonable;
use WebFu\Reflection\Tests\data\ClassReadOnly;
use WebFu\Reflection\Tests\data\ClassWithAttributes;
use WebFu\Reflection\Tests\data\ClassWithConstants;
use WebFu\Reflection\Tests\data\ClassWithDocComments;
use WebFu\Reflection\Tests\data\ClassWithInterfaces;
use WebFu\Reflection\Tests\data\ClassWithMethods;
use WebFu\Reflection\Tests\data\ClassWithProperties;
use WebFu\Reflection\Tests\data\ClassWithReadOnly;
use WebFu\Reflection\Tests\data\ClassWithUseStatements;
use WebFu\Reflection\Tests\data\EnumClass;
use WebFu\Reflection\Tests\data\GenericClass;
use WebFu\Reflection\Tests\data\GenericInterface;
use WebFu\Reflection\Tests\data\GenericTrait;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionClass
 */
class ReflectionClassTest extends TestCase
{
    /**
     * @covers ::getAnnotations
     */
    public function testGetAnnotation(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithDocComments::class);

        $this->assertEquals(['@template Test'], $reflectionClass->getAnnotations());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertEmpty($reflectionClass->getAnnotations());
    }

    /**
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithAttributes::class);
        $this->assertCount(1, $reflectionClass->getAttributes());
        $this->assertEquals('WebFu\Reflection\Tests\data\Attribute', $reflectionClass->getAttributes()[0]->getName());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertEmpty($reflectionClass->getAttributes());
    }

    /**
     * @covers ::getConstant
     *
     * @dataProvider constantProvider
     */
    public function testGetConstant(int $expected, string $name): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);
        $this->assertEquals($expected, $reflectionClass->getConstant($name));

        $reflectionClass = new ReflectionClass(new class() {
            public const PUBLIC    = 1;
            public const PROTECTED = 2;
            public const PRIVATE   = 3;
        });
        $this->assertEquals($expected, $reflectionClass->getConstant($name));
    }

    /**
     * @return iterable<array{expected:int, name:string}>
     */
    public function constantProvider(): iterable
    {
        yield 'public' => [
            'expected' => 1,
            'name'     => 'PUBLIC',
        ];
        yield 'protected' => [
            'expected' => 2,
            'name'     => 'PROTECTED',
        ];
        yield 'private' => [
            'expected' => 3,
            'name'     => 'PRIVATE',
        ];
    }

    /**
     * @covers ::getConstant
     */
    public function testGetConstantFail(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Undefined constant name: FOO');

        $reflectionClass->getConstant('FOO');
    }

    /**
     * @covers ::getConstant
     */
    public function testGetConstantFailAnonymousClass(): void
    {
        $reflectionClass = new ReflectionClass(new class() {
            public const PUBLIC    = 1;
            public const PROTECTED = 2;
            public const PRIVATE   = 3;
        });

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Undefined constant name: FOO');

        $reflectionClass->getConstant('FOO');
    }

    /**
     * @covers ::getConstants
     */
    public function testGetConstants(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertEquals([
            'PUBLIC'                  => 1,
            'PROTECTED'               => 2,
            'PRIVATE'                 => 3,
            'PUBLIC_WITH_ATTRIBUTE'   => 4,
            'PUBLIC_WITH_DOC_COMMENT' => 5,
        ], $reflectionClass->getConstants());

        $reflectionClass = new ReflectionClass(new class() {
            public const PUBLIC    = 1;
            public const PROTECTED = 2;
            public const PRIVATE   = 3;
        });

        $this->assertEquals([
            'PUBLIC'    => 1,
            'PROTECTED' => 2,
            'PRIVATE'   => 3,
        ], $reflectionClass->getConstants());
    }

    /**
     * @covers ::getConstructor
     */
    public function testGetConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals(new ReflectionMethod(ClassWithMethods::class, '__construct'), $reflectionClass->getConstructor());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getConstructor());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertNull($reflectionClass->getConstructor());

        $reflectionClass = new ReflectionClass(new class() {
            public function __construct()
            {
            }
        });

        $constructor = $reflectionClass->getConstructor();
        $this->assertInstanceOf(ReflectionMethod::class, $constructor);
        $this->assertSame('__construct', $constructor->getName());
        $this->assertStringContainsString('class@anonymous', $constructor->getDeclaringClass()->getName());
    }

    /**
     * @covers ::getDefaultProperties
     */
    public function testGetDefaultProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            'public'          => 1,
            'protected'       => 2,
            'private'         => 3,
            'staticPublic'    => 1,
            'staticProtected' => 2,
            'staticPrivate'   => 3,
        ], $reflectionClass->getDefaultProperties());

        $reflectionClass = new ReflectionClass(new class() {
            public int $public                    = 1;
            protected int $protected              = 2;
            private int $private                  = 3;
            public static int $staticPublic       = 1;
            protected static int $staticProtected = 2;
            private static int $staticPrivate     = 3;
        });

        $this->assertEquals([
            'public'          => 1,
            'protected'       => 2,
            'private'         => 3,
            'staticPublic'    => 1,
            'staticProtected' => 2,
            'staticPrivate'   => 3,
        ], $reflectionClass->getDefaultProperties());
    }

    /**
     * @covers ::getDocComment
     */
    public function testDocComment(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithDocComments::class);

        $this->assertEquals('/**'.PHP_EOL.' * @template Test'.PHP_EOL.' */', $reflectionClass->getDocComment());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getDocComment());

        $this->assertNull($reflectionClass->getDocComment());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertNull($reflectionClass->getDocComment());
    }

    /**
     * @covers ::getEndLine
     */
    public function testGetEndLine(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(9, $reflectionClass->getEndLine());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertIsInt($reflectionClass->getEndLine());
        $this->assertGreaterThanOrEqual($reflectionClass->getStartLine(), $reflectionClass->getEndLine());
    }

    /**
     * @covers ::getExtension
     */
    public function testExtension(): void
    {
        $reflectionClass = new ReflectionClass(\ReflectionClass::class);

        $this->assertEquals(new ReflectionExtension('Reflection'), $reflectionClass->getExtension());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getExtension());
    }

    /**
     * @covers ::getExtensionName
     */
    public function testExtensionName(): void
    {
        $reflectionClass = new ReflectionClass(\ReflectionClass::class);

        $this->assertEquals('Reflection', $reflectionClass->getExtensionName());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getExtensionName());
    }

    /**
     * @covers ::getFileName
     */
    public function testGetFileName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertStringContainsString('/data/GenericClass.php', $reflectionClass->getFileName());

        $reflectionClass = new ReflectionClass(DateTime::class);

        $this->assertNull($reflectionClass->getFileName());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertStringContainsString('/tests/ReflectionClassTest.php', $reflectionClass->getFileName());
    }

    /**
     * @covers ::getInstance
     */
    public function testGetInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getInstance());

        $reflectionClass = new ReflectionClass(new GenericClass());

        $this->assertInstanceOf(GenericClass::class, $reflectionClass->getInstance());

        $anonymousClass  = $this->anonymousClassFixture();
        $reflectionClass = new ReflectionClass($anonymousClass);

        $this->assertSame($anonymousClass, $reflectionClass->getInstance());
    }

    /**
     * @covers ::getInterfaceNames
     */
    public function testGetInterfaceNames(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals([GenericInterface::class], $reflectionClass->getInterfaceNames());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertEquals([GenericInterface::class], $reflectionClass->getInterfaceNames());
    }

    /**
     * @covers ::getInterfaces
     */
    public function testGetInterfaces(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals([GenericInterface::class => new ReflectionClass(GenericInterface::class)], $reflectionClass->getInterfaces());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertEquals([GenericInterface::class => new ReflectionClass(GenericInterface::class)], $reflectionClass->getInterfaces());
    }

    /**
     * @covers ::getMethod
     */
    public function testGetMethod(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals(new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters'), $reflectionClass->getMethod('methodWithoutParameters'));

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals(new ReflectionMethod($anonymousClassName, 'anonymousMethod'), $reflectionClass->getMethod('anonymousMethod'));
    }

    /**
     * @covers ::getMethods
     */
    public function testGetMethods(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals([
            new ReflectionMethod(ClassWithMethods::class, '__construct'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'protectedMethod'),
            new ReflectionMethod(ClassWithMethods::class, 'privateMethod'),
            new ReflectionMethod(ClassWithMethods::class, 'staticMethod'),
            new ReflectionMethod(ClassWithMethods::class, '__destruct'),
        ], $reflectionClass->getMethods());

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);
        $methods            = array_map(fn (ReflectionMethod $method) => $method->getName(), $reflectionClass->getMethods());

        $this->assertContains('publicFunction', $methods);
        $this->assertContains('anonymousMethod', $methods);
        $this->assertContains('publicTraitFunction', $methods);
    }

    /**
     * @covers ::getModifiers
     */
    public function testGetModifiers(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(0, $reflectionClass->getModifiers());

        $reflectionClass = new ReflectionClass(AbstractClass::class);

        $this->assertEquals(\ReflectionClass::IS_EXPLICIT_ABSTRACT, $reflectionClass->getModifiers());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertEquals(0, $reflectionClass->getModifiers());
    }

    /**
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(GenericClass::class, $reflectionClass->getName());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertStringContainsString('class@anonymous', $reflectionClass->getName());
    }

    /**
     * @covers ::getNamespaceName
     */
    public function testGetNamespaceName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals('WebFu\Reflection\Tests\data', $reflectionClass->getNamespaceName());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertEquals('WebFu\Reflection\Tests\data', $reflectionClass->getNamespaceName());
    }

    /**
     * @covers ::getParentClass
     */
    public function testGetParentClass(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getParentClass());

        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals(new ReflectionClass(AbstractClass::class), $reflectionClass->getParentClass());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertEquals(new ReflectionClass(AbstractClass::class), $reflectionClass->getParentClass());
    }

    /**
     * @covers ::getProperties
     */
    public function testGetProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'public'),
            new ReflectionProperty(ClassWithProperties::class, 'protected'),
            new ReflectionProperty(ClassWithProperties::class, 'private'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithoutDefault'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPublic'),
            new ReflectionProperty(ClassWithProperties::class, 'staticProtected'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPrivate'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPropertyWithoutDefault'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithAttribute'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment'),
        ], $reflectionClass->getProperties());

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals([
            new ReflectionProperty($anonymousClassName, 'public'),
            new ReflectionProperty($anonymousClassName, 'protected'),
            new ReflectionProperty($anonymousClassName, 'private'),
            new ReflectionProperty($anonymousClassName, 'staticPublic'),
            new ReflectionProperty($anonymousClassName, 'staticProtected'),
            new ReflectionProperty($anonymousClassName, 'staticPrivate'),
        ], $reflectionClass->getProperties());
    }

    public function testGetPropertiesWithFilter(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'public'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithoutDefault'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPublic'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPropertyWithoutDefault'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithAttribute'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC));

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'protected'),
            new ReflectionProperty(ClassWithProperties::class, 'staticProtected'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED));

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'private'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPrivate'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE));

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'staticPublic'),
            new ReflectionProperty(ClassWithProperties::class, 'staticProtected'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPrivate'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPropertyWithoutDefault'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_STATIC));

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals([
            new ReflectionProperty($anonymousClassName, 'public'),
            new ReflectionProperty($anonymousClassName, 'staticPublic'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC));

        $this->assertEquals([
            new ReflectionProperty($anonymousClassName, 'protected'),
            new ReflectionProperty($anonymousClassName, 'staticProtected'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED));

        $this->assertEquals([
            new ReflectionProperty($anonymousClassName, 'private'),
            new ReflectionProperty($anonymousClassName, 'staticPrivate'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE));

        $this->assertEquals([
            new ReflectionProperty($anonymousClassName, 'staticPublic'),
            new ReflectionProperty($anonymousClassName, 'staticProtected'),
            new ReflectionProperty($anonymousClassName, 'staticPrivate'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_STATIC));
    }

    public function testGetPropertiesReadOnly(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('The IS_READONLY filter is not available for PHP versions higher than 8.2.0');

            $reflectionClass = new ReflectionClass(ClassWithProperties::class);
            $reflectionClass->getProperties(ReflectionProperty::IS_READONLY);

            return;
        }

        $reflectionClass = new ReflectionClass(ClassWithReadOnly::class);

        $this->assertEquals([
            new ReflectionProperty(ClassWithReadOnly::class, 'public'),
        ], $reflectionClass->getProperties(ReflectionProperty::IS_READONLY));
    }

    /**
     * @covers ::getProperty
     *
     * @dataProvider propertyProvider
     */
    public function testGetProperty(string $name, ReflectionProperty|null $expected): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals($expected, $reflectionClass->getProperty($name));
    }

    /**
     * @covers ::getProperty
     */
    public function testGetPropertyAnonymousClass(): void
    {
        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals(new ReflectionProperty($anonymousClassName, 'public'), $reflectionClass->getProperty('public'));
        $this->assertEquals(new ReflectionProperty($anonymousClassName, 'staticPublic'), $reflectionClass->getProperty('staticPublic'));
        $this->assertNull($reflectionClass->getProperty('doesNotExist'));
    }

    /**
     * @return iterable<array{name:string, expected:ReflectionProperty|null}>
     */
    public function propertyProvider(): iterable
    {
        yield ['name' => 'public', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'public')];
        yield ['name' => 'protected', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'protected')];
        yield ['name' => 'private', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'private')];
        yield ['name' => 'propertyWithoutDefault', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'propertyWithoutDefault')];
        yield ['name' => 'staticPublic', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticPublic')];
        yield ['name' => 'staticProtected', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticProtected')];
        yield ['name' => 'staticPrivate', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticPrivate')];
        yield ['name' => 'iDoNotExist', 'expected' => null];
    }

    /**
     * @dataProvider dynamicPropertyProvider
     */
    public function testDynamicProperties(object $object): void
    {
        $reflectionClass = new ReflectionClass($object);

        $reflectionProperty = $reflectionClass->getProperty('dynamic');

        $this->assertInstanceOf(ReflectionProperty::class, $reflectionProperty);
        $this->assertTrue($reflectionProperty->isDynamic());

        $dynamicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_DYNAMIC);
        $this->assertCount(1, $dynamicProperties);
        $this->assertEquals($reflectionProperty, $dynamicProperties[0]);
    }

    public function dynamicPropertyProvider(): iterable
    {
        $stdClass          = new stdClass();
        $stdClass->dynamic = 'dynamic';

        $existingClass          = new ClassWithProperties();
        $existingClass->dynamic = 'dynamic';

        yield 'stdClass' => [
            'object' => $stdClass,
        ];
        yield 'object' => [
            'object' => (object) ['dynamic' => 'dynamic'],
        ];
        yield 'existingClass' => [
            'object' => $existingClass,
        ];
    }

    /**
     * @covers ::getReflectionConstant
     *
     * @dataProvider reflectionConstantProvider
     */
    public function testGetReflectionConstant(string $name, ReflectionClassConstant|null $expected): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertEquals($expected, $reflectionClass->getReflectionConstant($name));
    }

    /**
     * @covers ::getReflectionConstant
     */
    public function testGetReflectionConstantAnonymousClass(): void
    {
        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals(new ReflectionClassConstant($anonymousClassName, 'PUBLIC'), $reflectionClass->getReflectionConstant('PUBLIC'));
        $this->assertEquals(new ReflectionClassConstant($anonymousClassName, 'PROTECTED'), $reflectionClass->getReflectionConstant('PROTECTED'));
        $this->assertEquals(new ReflectionClassConstant($anonymousClassName, 'PRIVATE'), $reflectionClass->getReflectionConstant('PRIVATE'));
        $this->assertNull($reflectionClass->getReflectionConstant('iDoNotExist'));
    }

    /**
     * @return iterable<array{name: string, expected: ReflectionClassConstant|null}>
     */
    public function reflectionConstantProvider(): iterable
    {
        yield ['name' => 'PUBLIC', 'expected' => new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC')];
        yield ['name' => 'PROTECTED', 'expected' => new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED')];
        yield ['name' => 'PRIVATE', 'expected' => new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE')];
        yield ['name' => 'iDoNotExist', 'expected' => null];
    }

    /**
     * @covers ::getReflectionConstants
     */
    public function testGetReflectionConstants(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertEquals([
            new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC'),
            new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED'),
            new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE'),
            new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_ATTRIBUTE'),
            new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_DOC_COMMENT'),
        ], $reflectionClass->getReflectionConstants());

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertEquals([
            new ReflectionClassConstant($anonymousClassName, 'PUBLIC'),
            new ReflectionClassConstant($anonymousClassName, 'PROTECTED'),
            new ReflectionClassConstant($anonymousClassName, 'PRIVATE'),
        ], $reflectionClass->getReflectionConstants());
    }

    /**
     * @covers ::getShortName
     */
    public function testGetShortName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals('GenericClass', $reflectionClass->getShortName());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertStringContainsString('class@anonymous', $reflectionClass->getShortName());
    }

    /**
     * @covers ::getStartLine
     */
    public function testGetStartLine(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(7, $reflectionClass->getStartLine());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertIsInt($reflectionClass->getStartLine());
        $this->assertGreaterThan(0, $reflectionClass->getStartLine());
        $this->assertLessThanOrEqual($reflectionClass->getEndLine(), $reflectionClass->getStartLine());
    }

    /**
     * @covers ::getStaticProperties
     */
    public function testGetStaticProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            'staticPublic'    => 1,
            'staticProtected' => 2,
            'staticPrivate'   => 3,
        ], $reflectionClass->getStaticProperties());

        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->assertEquals([
            'staticPublic'    => 1,
            'staticProtected' => 2,
            'staticPrivate'   => 3,
        ], $reflectionClass->getStaticProperties());
    }

    /**
     * @covers ::getStaticPropertyValue
     */
    public function testGetStaticPropertyValue(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals(1, $reflectionClass->getStaticPropertyValue('staticPublic'));
        $this->assertEquals(2, $reflectionClass->getStaticPropertyValue('staticProtected'));
        $this->assertEquals(3, $reflectionClass->getStaticPropertyValue('staticPrivate'));

        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->assertEquals(1, $reflectionClass->getStaticPropertyValue('staticPublic'));
        $this->assertEquals(2, $reflectionClass->getStaticPropertyValue('staticProtected'));
        $this->assertEquals(3, $reflectionClass->getStaticPropertyValue('staticPrivate'));
    }

    /**
     * @covers ::getStaticPropertyValue
     */
    public function testGetStaticPropertyValueException(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Undefined static property: WebFu\Reflection\Tests\data\ClassWithProperties::$iDoNotExist');

        $reflectionClass->getStaticPropertyValue('iDoNotExist');
    }

    /**
     * @covers ::getStaticPropertyValue
     */
    public function testGetStaticPropertyValueExceptionAnonymousClass(): void
    {
        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('::$iDoNotExist');

        $reflectionClass->getStaticPropertyValue('iDoNotExist');
    }

    /**
     * @covers ::getTraitAliases
     */
    public function testGetTraitAliases(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals(
            [
                'traitFunction' => GenericTrait::class.'::publicTraitFunction',
            ],
            $reflectionClass->getTraitAliases()
        );
    }

    /**
     * @covers ::getTraitNames
     */
    public function testGetTraitNames(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals([
            GenericTrait::class,
        ], $reflectionClass->getTraitNames());

        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->assertEquals([
            GenericTrait::class,
        ], $reflectionClass->getTraitNames());
    }

    /**
     * @covers ::getTraits
     */
    public function testGetTraits(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals([
            GenericTrait::class => new ReflectionClass(GenericTrait::class),
        ], $reflectionClass->getTraits());

        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->assertEquals([
            GenericTrait::class => new ReflectionClass(GenericTrait::class),
        ], $reflectionClass->getTraits());
    }

    /**
     * @covers ::getUseStatements
     */
    public function testGetUseStatements(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithUseStatements::class);
        $this->assertEquals([
            new ReflectionUseStatement(GenericClass::class, GenericClass::class),
            new ReflectionUseStatement(DateTime::class, 'DT'),
        ], $reflectionClass->getUseStatements());
    }

    /**
     * @covers ::hasConstant
     */
    public function testHasConstant(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertTrue($reflectionClass->hasConstant('PUBLIC'));
        $this->assertFalse($reflectionClass->hasConstant('DOES_NOT_EXIST'));

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertTrue($reflectionClass->hasConstant('PUBLIC'));
        $this->assertFalse($reflectionClass->hasConstant('DOES_NOT_EXIST'));
    }

    /**
     * @covers ::hasInstance
     */
    public function testHasInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->hasInstance());

        $reflectionClass = new ReflectionClass(new GenericClass());
        $this->assertTrue($reflectionClass->hasInstance());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());
        $this->assertTrue($reflectionClass->hasInstance());
    }

    /**
     * @covers ::hasMethod
     */
    public function testHasMethod(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertTrue($reflectionClass->hasMethod('methodWithoutParameters'));
        $this->assertFalse($reflectionClass->hasMethod('doesNotExist'));

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertTrue($reflectionClass->hasMethod('anonymousMethod'));
        $this->assertTrue($reflectionClass->hasMethod('publicTraitFunction'));
        $this->assertFalse($reflectionClass->hasMethod('doesNotExist'));

        $reflectionClass = new ReflectionClass(new class {
            public function test(): void {}
        });

        $this->assertTrue($reflectionClass->hasMethod('test'));
    }

    public function hasMethodProvider(): iterable
    {
        yield [
            'class' => ClassWithMethods::class,
            'method' => 'methodWithoutParameters',
            'expected' => true,
        ];
    }

    /**
     * @covers ::hasProperty
     */
    public function testHasProperty(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertTrue($reflectionClass->hasProperty('public'));
        $this->assertFalse($reflectionClass->hasProperty('doesNotExist'));

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertTrue($reflectionClass->hasProperty('public'));
        $this->assertTrue($reflectionClass->hasProperty('staticPublic'));
        $this->assertFalse($reflectionClass->hasProperty('doesNotExist'));
    }

    /**
     * @covers ::implementsInterface
     */
    public function testImplementsInterface(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithInterfaces::class);

        $this->assertTrue($reflectionClass->implementsInterface(GenericInterface::class));
        $this->assertFalse($reflectionClass->implementsInterface(DateTimeInterface::class));

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertTrue($reflectionClass->implementsInterface(GenericInterface::class));
        $this->assertFalse($reflectionClass->implementsInterface(DateTimeInterface::class));
    }

    /**
     * @covers ::inNamespace
     */
    public function testInNamespace(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->inNamespace());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        $this->assertTrue($reflectionClass->inNamespace());
    }

    /**
     * @covers ::isAbstract
     */
    public function testIsAbstract(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isAbstract());

        $reflectionClass = new ReflectionClass(AbstractClass::class);

        $this->assertTrue($reflectionClass->isAbstract());
    }

    /**
     * @covers ::isAnonymous
     */
    public function testIsAnonymous(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isAnonymous());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertTrue($reflectionClass->isAnonymous());
    }

    /**
     * @covers ::isCloneable
     */
    public function testIsCloneable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isCloneable());

        $reflectionClass = new ReflectionClass(ClassNonClonable::class);

        $this->assertFalse($reflectionClass->isCloneable());
    }

    /**
     * @covers ::isEnum
     */
    public function testIsEnum(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isEnum() is not available for PHP versions lower than 8.1.0');

            $reflectionClass = new ReflectionClass(GenericClass::class);
            $reflectionClass->isEnum();

            $this->markTestSkipped('Enum are not available for PHP versions lower than 8.1.0');
        }

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isEnum());

        $reflectionClass = new ReflectionClass(EnumClass::class);

        $this->assertTrue($reflectionClass->isEnum());
    }

    /**
     * @covers ::isFinal
     */
    public function testIsFinal(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isFinal());

        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    /**
     * @covers ::isInstance
     */
    public function testIsInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isInstance(new GenericClass()));
        $this->assertFalse($reflectionClass->isInstance(new stdClass()));

        $anonymousClass  = $this->anonymousClassFixture();
        $reflectionClass = new ReflectionClass(get_class($anonymousClass));

        $this->assertTrue($reflectionClass->isInstance($anonymousClass));
        $this->assertFalse($reflectionClass->isInstance(new stdClass()));
    }

    /**
     * @covers ::isInstantiable
     */
    public function testIsInstantiable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isInstantiable());

        $reflectionClass = new ReflectionClass(AbstractClass::class);

        $this->assertFalse($reflectionClass->isInstantiable());
    }

    /**
     * @covers ::isInterface
     */
    public function testIsInterface(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isInterface());

        $reflectionClass = new ReflectionClass(GenericInterface::class);

        $this->assertTrue($reflectionClass->isInterface());
    }

    /**
     * @covers ::isInternal
     */
    public function testIsInternal(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isInternal());

        $reflectionClass = new ReflectionClass(DateTime::class);

        $this->assertTrue($reflectionClass->isInternal());
    }

    /**
     * @covers ::isIterable
     */
    public function testIsIterable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isIterable());

        $reflectionClass = new ReflectionClass(ArrayObject::class);

        $this->assertTrue($reflectionClass->isIterable());
    }

    /**
     * @covers ::isReadOnly
     */
    public function testIsReadOnly(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isReadOnly() is not available for PHP versions lower than 8.2.0');

            $reflectionClass = new ReflectionClass(GenericClass::class);
            $reflectionClass->isReadOnly();

            $this->markTestSkipped('isReadOnly() is not available for PHP versions lower than 8.2.0');
        }

        $reflectionClass = new ReflectionClass(GenericClass::class);
        $this->assertFalse($reflectionClass->isReadOnly());

        $reflectionClass = new ReflectionClass(ClassReadOnly::class);
        $this->assertTrue($reflectionClass->isReadOnly());
    }

    /**
     * @covers ::isSubclassOf
     */
    public function testIsSubclassOf(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertTrue($reflectionClass->isSubclassOf(AbstractClass::class));
        $this->assertFalse($reflectionClass->isSubclassOf(DateTime::class));

        $reflectionClass = new ReflectionClass(get_class($this->anonymousClassFixture()));

        $this->assertTrue($reflectionClass->isSubclassOf(AbstractClass::class));
        $this->assertFalse($reflectionClass->isSubclassOf(DateTime::class));
    }

    /**
     * @covers ::isTrait
     */
    public function testIsTrait(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isTrait());

        $reflectionClass = new ReflectionClass(GenericTrait::class);

        $this->assertTrue($reflectionClass->isTrait());
    }

    /**
     * @covers ::isUserDefined
     */
    public function testIsUserDefined(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isUserDefined());

        $reflectionClass = new ReflectionClass(DateTime::class);

        $this->assertFalse($reflectionClass->isUserDefined());
    }

    /**
     * @covers ::newInstance
     */
    public function testNewInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertInstanceOf(GenericClass::class, $reflectionClass->newInstance());

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertInstanceOf($anonymousClassName, $reflectionClass->newInstance());
    }

    /**
     * @covers ::newInstanceArgs
     */
    public function testNewInstanceArgs(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertInstanceOf(ClassWithMethods::class, $reflectionClass->newInstanceArgs([1, 'foo']));

        $anonymousClassName = $this->anonymousClassWithConstructorFixture();
        $reflectionClass    = new ReflectionClass($anonymousClassName);
        $instance           = $reflectionClass->newInstanceArgs([2, 'bar']);

        $this->assertInstanceOf($anonymousClassName, $instance);
        $this->assertSame(2, $instance->integer);
        $this->assertSame('bar', $instance->string);
    }

    /**
     * @covers ::newInstanceWithoutConstructor
     */
    public function testNewInstanceWithoutConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertInstanceOf(ClassWithMethods::class, $reflectionClass->newInstanceWithoutConstructor());

        $anonymousClassName = $this->anonymousClassWithConstructorFixture();
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $this->assertInstanceOf($anonymousClassName, $reflectionClass->newInstanceWithoutConstructor());
    }

    /**
     * @covers ::setStaticPropertyValue
     */
    public function testSetStaticPropertyValue(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $reflectionClass->setStaticPropertyValue('staticPublic', 2);
        $this->assertEquals(2, ClassWithProperties::$staticPublic);

        $anonymousClassName = get_class($this->anonymousClassFixture());
        $reflectionClass    = new ReflectionClass($anonymousClassName);

        $reflectionClass->setStaticPropertyValue('staticPublic', 4);
        $this->assertEquals(4, $reflectionClass->getStaticPropertyValue('staticPublic'));
    }

    /**
     * @covers ::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            'name'        => ClassWithProperties::class,
            'attributes'  => [],
            'annotations' => [],
        ], $reflectionClass->__debugInfo());

        $reflectionClass = new ReflectionClass($this->anonymousClassFixture());

        // $this->assertStringContainsString('class@anonymous', $reflectionClass->__debugInfo()['name']);
        $this->assertSame([], $reflectionClass->__debugInfo()['attributes']);
        $this->assertSame([], $reflectionClass->__debugInfo()['annotations']);
    }

    private function anonymousClassFixture(): object
    {
        return new class() extends AbstractClass implements GenericInterface {
            use GenericTrait;

            public const PUBLIC       = 1;
            protected const PROTECTED = 2;
            private const PRIVATE     = 3;

            public int $public       = 1;
            protected int $protected = 2;
            private int $private     = 3;

            public static int $staticPublic       = 1;
            protected static int $staticProtected = 2;
            private static int $staticPrivate     = 3;

            public function publicFunction(): void
            {
            }

            public function anonymousMethod(): void
            {
            }
        };
    }

    /**
     * @return class-string
     */
    private function anonymousClassWithConstructorFixture(): string
    {
        return get_class(new class(1, 'foo') {
            public function __construct(
                public int $integer,
                public string $string,
            ) {
            }
        });
    }
}
