<?php

	namespace LiftKit\Tests\Email;

	use LiftKit\Tests\Helpers\Email\TestCase;
	use LiftKit\Email\Email;
	use PHPMailer;


	class LegacyTest extends TestCase
	{


		public function testConstructor ()
		{
			new Email(
				false,
				null,
				null,
				null,
				null,
				new PHPMailer()
			);

			$this->assertTrue(true);
		}
	}
