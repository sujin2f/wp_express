<?php
/**
	* Unit Test of Schema Base
	*
	* @author     Sujin 수진 Choi <http://www.sujinc.com/>
	* @package    WP Express
	* @since      4.0.0
	* @subpackage Schema
	*/

use Sujin\Wordpress\WP_Express\Helpers\Schema;
use org\bovigo\vfs\vfsStream;

class Temp_Schema extends Schema {
	private $file_system = '';

	protected function get_base_dir(): string {
		if ( $this->file_system ) {
			return $this->file_system->url() . '/schema';
		}

		$items       = file_get_contents( WPEX_PHPUNIT__DIR . '/schema/simple.json' );
		$reference   = file_get_contents( WPEX_PHPUNIT__DIR . '/schema/reference.json' );
		$definitions = file_get_contents( WPEX_PHPUNIT__DIR . '/schema/definitions.json' );
		$recursive   = file_get_contents( WPEX_PHPUNIT__DIR . '/schema/recursive-reference.json' );

		$directory = array(
			'schema' => array(
				'simple.json'              => $items,
				'reference.json'           => $reference,
				'definitions.json'         => $definitions,
				'recursive-reference.json' => $recursive,
			),
		);

		// setup and cache the virtual file system
		$this->file_system = vfsStream::setup( 'root', 444, $directory );
		return $this->file_system->url() . '/schema';
	}
}

class Simple implements JsonSerializable {
	public $title  = 'Title';
	public $url    = 'http://test.com';
	public $number = '1';
	public $object = array(
		'child' => 'http://test.com',
	);

	public function jsonSerialize(): array {
		return Temp_Schema::from_file( 'simple.json' )->filter( $this );
	}
}

class Definitions extends Simple {
	public function jsonSerialize(): array {
		return Temp_Schema::from_file( 'definitions.json' )->filter( $this );
	}
}

class Reference_Item implements JsonSerializable {
	public $object = array(
		'child' => array(
			'title'  => 'Title',
			'url'    => 'http://test.com',
			'number' => '1',
			'object' => array(
				'child' => 'http://test.com',
			),
		),
	);

	public function jsonSerialize(): array {
		return Temp_Schema::from_file( 'reference.json' )->filter( $this );
	}
}

class Recursive_Reference implements JsonSerializable {
	public $strings  = array( 'Sujin', 'Choi' );
	public $children = array(
		array(
			'strings'  => array( 'Sujin', 'Choi', 'sujin.2f@gmail.com' ),
			'children' => null,
		),
	);

	public function jsonSerialize(): array {
		return Temp_Schema::from_file( 'recursive-reference.json' )->filter( $this );
	}
}

class Schema_Test extends Test_Case {
	public function test_simple_schema() {
		$actual = json_decode( wp_json_encode( new Simple() ), true );

		$this->assertEquals( 'Title', $actual['title'] );
		$this->assertEquals( 'http://test.com', $actual['url'] );
		$this->assertEquals( 1, $actual['number'] );
		$this->assertEquals( array( 'child' => 'http://test.com' ), $actual['object'] );
	}

	public function test_definitions_schema() {
		$actual = json_decode( wp_json_encode( new Definitions() ), true );

		$this->assertEquals( 'Title', $actual['title'] );
		$this->assertEquals( 'http://test.com', $actual['url'] );
		$this->assertEquals( 1, $actual['number'] );
		$this->assertEquals( array( 'child' => 'http://test.com' ), $actual['object'] );
	}

	public function test_reference_schema() {
		$actual = json_decode( wp_json_encode( new Reference_Item() ), true );

		$this->assertEquals( 'Title', $actual['object']['child']['title'] );
		$this->assertEquals( 'http://test.com', $actual['object']['child']['url'] );
		$this->assertEquals( 1, $actual['object']['child']['number'] );
		$this->assertEquals( array( 'child' => 'http://test.com' ), $actual['object']['child']['object'] );
	}

	public function test_reference_schema_nested_item() {
		$reference                                     = new Reference_Item();
		unset( $reference->object['child']['object']['child'] );

		try {
			json_decode( wp_json_encode( $reference ), true );
		} catch ( InvalidArgumentException $_ ) {
			$this->assertTrue( true );
			return;
		}

		$this->assertTrue( false );
	}

	public function test_recursive_reference() {
		$actual = json_decode( wp_json_encode( new Recursive_Reference() ), true );

		$this->assertEquals( array( 'Sujin', 'Choi' ), $actual['strings'] );
		$this->assertEquals( array( 'Sujin', 'Choi', 'sujin.2f@gmail.com' ), $actual['children'][0]['strings'] );
	}
}
