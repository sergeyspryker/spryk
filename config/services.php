<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PhpParser\Lexer;
use PhpParser\Parser;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGenerator;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReader;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ClassParser;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\FileParser;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\JsonParser;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\ParserInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\XmlParser;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Parser\YmlParser;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Loader\SprykConfigurationLoader;
use SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumper;
use SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutor;
use SprykerSdk\Spryk\SprykConfig;
use SprykerSdk\Spryk\SprykFactory;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->load('SprykerSdk\\', '../src/')
        ->exclude('../src/{DependencyInjection,Tests,Kernel.php}');

    // Make SprykConfig public for testing
    $services->get(SprykConfig::class)->public();

    $services->load('PhpParser\\', '../vendor/nikic/php-parser/lib/PhpParser');
    $services->set(FilesystemAdapter::class, '../vendor/nikic/php-parser/lib/PhpParser');

    // Make the facade services lazy
    // https://symfony.com/doc/current/service_container/lazy_services.html
    $services->set(SprykExecutor::class)->lazy();
    $services->set(SprykDefinitionDumper::class)->lazy();
    $services->set(ArgumentListGenerator::class)->lazy();
    $services->set(ArgumentListReader::class)->lazy();
    $services->set(SprykConfigurationLoader::class)->lazy();

    // Does not work with interfaces
//    $services->set(SprykExecutorInterface::class)->lazy();
//    $services->set(SprykDefinitionDumperInterface::class)->lazy();
//    $services->set(ArgumentListGeneratorInterface::class)->lazy();
//    $services->set(ArgumentListReaderInterface::class)->lazy();
//    $services->set(SprykConfigurationLoaderInterface::class)->lazy();

    // Named aliases
    // $container->addCompilerPass(new NamedAliasPass());
    // https://symfony.com/doc/current/service_container/autowiring.html#dealing-with-multiple-implementations-of-the-same-type
    // TODO either refactor to use delegating service or automate with a CompilerPass
    $services->alias(ParserInterface::class . ' $classParser', ClassParser::class);
    $services->alias(ParserInterface::class . ' $fileParser', FileParser::class);
    $services->alias(ParserInterface::class . ' $jsonParser', JsonParser::class);
    $services->alias(ParserInterface::class . ' $ymlParser', YmlParser::class);
    $services->alias(ParserInterface::class . ' $xmlParser', XmlParser::class);

    // https://symfony.com/doc/current/service_container/factories.html
    $services->set(Parser::class)
        ->factory([service(SprykFactory::class), 'createParser'])
        ->args([service(SprykConfig::class)]);

    $services->set(Lexer::class)
        ->factory([service(SprykFactory::class), 'createLexer'])
        ->args([service(SprykConfig::class)]);

    $services->set(FilesystemAdapter::class)
        ->factory([service(SprykFactory::class), 'createFilesystemCacheAdepter'])
        ->args([service(SprykConfig::class)]);
};