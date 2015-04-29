<?php

	namespace LiftKit\Tests\Email;

	use LiftKit\Tests\Helpers\Email\TestCase;
	use PHPUnit_Framework_MockObject_MockObject;
	use LiftKit\Email\Email;
	use PHPMailer;


	class EmailTest extends TestCase
	{
		/**
		 * @var Email
		 */
		protected $email;


		/**
		 * @var PHPUnit_Framework_MockObject_MockObject
		 */
		protected $mockMailer;


		public function setUp ()
		{
			$this->mockMailer = $this->getMockBuilder('\PHPMailer')
				->getMock();
		}


		public function testConstructor ()
		{
			$this->createEmail();
			$this->assertEquals($this->mockMailer->SMTPAuth, false);
		}


		public function testSMTPCredentials ()
		{
			new Email(
				true,
				'localhost',
				'port',
				'user',
				'password',
				$this->mockMailer
			);

			$this->assertEquals($this->mockMailer->SMTPAuth, true);
			$this->assertEquals($this->mockMailer->Mailer, 'smtp');
			$this->assertEquals($this->mockMailer->Host, 'localhost');
			$this->assertEquals($this->mockMailer->Port, 'port');
			$this->assertEquals($this->mockMailer->Username, 'user');
			$this->assertEquals($this->mockMailer->Password, 'password');
		}


		public function testBody ()
		{
			$this->createEmail()
				->body('body');

			$this->assertEquals(
				'body',
				$this->mockMailer->Body
			);
		}


		public function testAttachment ()
		{
			$this->mockMailer->expects($this->once())
				->method('AddAttachment')
				->with(
					$this->equalTo('path'),
					$this->equalTo('name'),
					$this->equalTo('encoding'),
					$this->equalTo('type'),
					$this->equalTo('disposition')
				);

			$this->createEmail()
				->attachment('path', 'name', 'encoding', 'type', 'disposition');
		}


		public function testFrom ()
		{
			$this->mockMailer->expects($this->once())
				->method('SetFrom')
				->with(
					$this->equalTo('email'),
					$this->equalTo('name')
				);

			$this->createEmail()
				->from('email', 'name');
		}


		public function testHtml ()
		{
			$this->mockMailer->expects($this->exactly(2))
				->method('IsHtml')
				->withConsecutive(
					[
						$this->equalTo(true)
					],
					[
						$this->equalTo(false)
					]
				);

			$this->createEmail()
				->html()
				->html(false);
		}


		public function testReply ()
		{
			$this->mockMailer->expects($this->exactly(2))
				->method('AddReplyTo')
				->withConsecutive(
					[
						$this->equalTo('email'),
						''
					],
					[
						$this->equalTo('email'),
						$this->equalTo('name')
					]
				);

			$this->createEmail()
				->reply('email')
				->reply('email', 'name');
		}


		/**
		 * @expectedException \LiftKit\Email\Exception\Email
		 */
		public function testSend ()
		{
			$this->mockMailer->expects($this->once())
				->method('Send');

			$this->createEmail()
				->send();
		}


		public function testTo ()
		{
			$this->mockMailer->expects($this->exactly(2))
				->method('AddAddress')
				->withConsecutive(
					[
						$this->equalTo('email'),
						$this->equalTo('')
					],
					[
						$this->equalTo('email'),
						$this->equalTo('name')
					]
				);

			$this->createEmail()
				->to('email')
				->to('email', 'name');
		}


		public function testToMultiple ()
		{
			$recipients = [
				'test1@examples.com',
				[
					'test2@examples.com',
					'Test 2'
				]
			];

			$this->mockMailer->expects($this->exactly(2))
				->method('AddAddress')
				->withConsecutive(
					[
						$this->equalTo('test1@examples.com'),
						$this->equalTo('')
					],
					[
						$this->equalTo('test2@examples.com'),
						$this->equalTo('Test 2')
					]
				);

			$this->createEmail()
				->to($recipients);
		}


		public function testCC ()
		{
			$this->mockMailer->expects($this->exactly(2))
				->method('AddCC')
				->withConsecutive(
					[
						$this->equalTo('email'),
						$this->equalTo('')
					],
					[
						$this->equalTo('email'),
						$this->equalTo('name')
					]
				);

			$this->createEmail()
				->cc('email')
				->cc('email', 'name');
		}


		public function testCCMultiple ()
		{
			$recipients = [
				'test1@examples.com',
				[
					'test2@examples.com',
					'Test 2'
				]
			];

			$this->mockMailer->expects($this->exactly(2))
				->method('AddCC')
				->withConsecutive(
					[
						$this->equalTo('test1@examples.com'),
						$this->equalTo('')
					],
					[
						$this->equalTo('test2@examples.com'),
						$this->equalTo('Test 2')
					]
				);

			$this->createEmail()
				->cc($recipients);
		}


		public function testBcc ()
		{
			$this->mockMailer->expects($this->exactly(2))
				->method('AddBCC')
				->withConsecutive(
					[
						$this->equalTo('email'),
						$this->equalTo('')
					],
					[
						$this->equalTo('email'),
						$this->equalTo('name')
					]
				);

			$this->createEmail()
				->bcc('email')
				->bcc('email', 'name');
		}


		public function testBccMultiple ()
		{
			$recipients = [
				'test1@examples.com',
				[
					'test2@examples.com',
					'Test 2'
				]
			];

			$this->mockMailer->expects($this->exactly(2))
				->method('AddBCC')
				->withConsecutive(
					[
						$this->equalTo('test1@examples.com'),
						$this->equalTo('')
					],
					[
						$this->equalTo('test2@examples.com'),
						$this->equalTo('Test 2')
					]
				);

			$this->createEmail()
				->bcc($recipients);
		}


		protected function createEmail ()
		{
			return
				new Email(
					false,
					null,
					null,
					null,
					null,
					$this->mockMailer
				);
		}
	}