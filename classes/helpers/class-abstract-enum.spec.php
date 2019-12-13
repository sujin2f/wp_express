<?php
/**
 * Enum Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

class Enum_Sample extends Abstract_Enum {
	public const STRING = 'string';
	public const ARRAY  = array( 'option1', 'option2' );
}

class Enum_Another extends Abstract_Enum {
	public const STRING = 'string';
}

class Enum_Test extends Test_Case {
	/**
	 * Test enum creation
	 */
	public function test_enum(): void {
		$enums = array(
			Enum_Sample::option1(),
			Enum_Sample::string(),
			Enum_Sample::option2(),
		);

		$actual = array();

		foreach ( $enums as $enum ) {
			switch ( $enum->case() ) {
				case Enum_Sample::STRING:
					$actual[] = 'string';
					break;

				case Enum_Sample::ARRAY:
					$actual[] = 'array';
					break;
			}
		}

		$this->assertEquals(
			array(
				'array',
				'string',
				'array',
			),
			$actual,
			'😡 Switch / Case test failed.',
		);
	}

	/**
	 * Test same enum key in different class
	 */
	public function test_two_enuma(): void {
		$string1 = Enum_Sample::string();
		$string2 = Enum_Another::string();
		$this->assertTrue(
			get_class( $string1 ) !== get_class( $string2 ),
			'😡 Two enum values should be different.',
		);
	}
}
