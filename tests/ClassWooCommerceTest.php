<?php

namespace WPSL\WooCommerce;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use wpCloud\StatelessMedia\WPStatelessStub;

/**
 * Class ClassWooCommerceTest
 */

class ClassWooCommerceTest extends TestCase {
  // Adds Mockery expectations to the PHPUnit assertions count.
  use MockeryPHPUnitIntegration;

  public static $backtraceClass = ''; 

  public function setUp(): void {
		parent::setUp();
		Monkey\setUp();

    self::$backtraceClass = 'WC_CSV_Exporter'; 
  }
	
  public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

  public function testShouldInitHooks() {
    $wooCommerce = new WooCommerce();

    $wooCommerce->module_init([]);

    self::assertNotFalse( has_filter('stateless_skip_cache_busting', [ $wooCommerce, 'skip_cache_busting' ]) );
  }

  public function testShouldSkipCacheBusting() {
    $wooCommerce = new WooCommerce();

    $this->assertEquals(
      'test',
      $wooCommerce->skip_cache_busting(null, 'test') 
    );
  }

  public function testShouldNotSkipCacheBusting() {
    $wooCommerce = new WooCommerce();

    self::$backtraceClass = '';

    $this->assertEquals(
      'test',
      $wooCommerce->skip_cache_busting('test', null) 
    );
  }
}

function debug_backtrace() {
  return [
    7 => [
      'class' => ClassWooCommerceTest::$backtraceClass,
    ]
  ];
}