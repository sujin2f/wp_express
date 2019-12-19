<?php
/**
 * Abstract_Argument Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

use Sujin\Wordpress\WP_Express\Arguments\Abstract_Argument;

class Argument_Inherited extends Abstract_Argument {
	protected $test_argument;

	protected function set_test_argument( string $value ): void {}
}

class Abstract_Argument_Test extends Test_Case {
	public function test_all(): void {
		// Setter and Getter
		$value    = 'Sujin';
		$argument = new Argument_Inherited();
		$argument->set( 'test_argument', $value );
		$actual = $argument->get( 'test_argument' );

		$this->assertEquals(
			$value,
			$actual,
			'😡 Argument setter and getter has failed.',
		);

		// to_array();
		$actual = $argument->to_array();
		$this->assertEquals(
			array( 'test_argument' => $value ),
			$actual,
			'😡 Argument to_array() has failed.',
		);

		// has();
		$actual = $argument->has( 'test_argument' );
		$this->assertTrue(
			$actual,
			'😡 Argument has() has failed.',
		);

		$actual = $argument->has( 'test_argument_2' );
		$this->assertFalse(
			$actual,
			'😡 Argument has() has failed.',
		);
	}

}
