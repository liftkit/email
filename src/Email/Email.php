<?php

	namespace LiftKit\Email;

	use PHPMailer;
	use LiftKit\Email\Exception\Email as EmailException;


	/**
	 * Class Email
	 *
	 * Wrapper around PHPMailer
	 *
	 * @package Iris\Libraries
	 */
	class Email
	{
		/**
		 * @var PhpMailer
		 */
		protected $phpMailer;

		protected $originalPhpMailer;


		public function __construct ($smtp = false, $host = null, $port = null, $username = null, $password = null, PHPMailer $phpMailer = null)
		{
			$this->phpMailer = $phpMailer ?: new PHPMailer;

			if ($smtp) {
				$this->phpMailer->Mailer   = 'smtp';
				$this->phpMailer->SMTPAuth = true;

				$this->phpMailer->Host     = $host;
				$this->phpMailer->Port     = $port;
				$this->phpMailer->Username = $username;
				$this->phpMailer->Password = $password;
			}

			$this->originalPhpMailer = clone $this->phpMailer;
		}


		public function clear ()
		{
			$this->phpMailer = clone $this->originalPhpMailer;

			return $this;
		}


		public function header ($name, $value)
		{
			$this->phpMailer->addCustomHeader($name, $value);

			return $this;
		}


		public function body ($string)
		{
			$this->phpMailer->Body = $string;

			return $this;
		}


		public function attachment ($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
		{
			$this->phpMailer->AddAttachment($path, $name, $encoding, $type, $disposition);

			return $this;
		}


		public function from ($address, $name = '')
		{
			$this->phpMailer->SetFrom($address, $name);

			return $this;
		}


		public function html ($bool = true)
		{
			$this->phpMailer->IsHTML($bool);

			return $this;
		}


		public function reply ($address, $name = '')
		{
			$this->phpMailer->AddReplyTo($address, $name);

			return $this;
		}


		public function send ()
		{
			if (! $this->phpMailer->Send()) {
				throw new EmailException($this->phpMailer->ErrorInfo);
			}
		}


		public function subject ($string)
		{
			$this->phpMailer->Subject = $string;

			return $this;
		}


		public function to ($addresses, $name = '')
		{
			if (! is_array($addresses)) {
				$this->phpMailer->AddAddress($addresses, $name);
			} else {
				foreach ($addresses as $address) {
					if (is_array($address)) {
						$sendAddress = $address[0];
						$name = $address[1];
					} else {
						$sendAddress = $address;
						$name = '';
					}

					$this->phpMailer->AddAddress($sendAddress, $name);
				}
			}

			return $this;
		}


		public function cc ($addresses, $name = '')
		{
			if (! is_array($addresses)) {
				$this->phpMailer->AddCC($addresses, $name);
			} else {
				foreach ($addresses as $address) {
					if (is_array($address)) {
						$sendAddress = $address[0];
						$name = $address[1];
					} else {
						$sendAddress = $address;
						$name = '';
					}

					$this->phpMailer->AddCC($sendAddress, $name);
				}
			}

			return $this;
		}


		public function bcc ($addresses, $name = '')
		{
			if (! is_array($addresses)) {
				$this->phpMailer->AddBCC($addresses, $name);
			} else {
				foreach ($addresses as $address) {
					if (is_array($address)) {
						$sendAddress = $address[0];
						$name = $address[1];
					} else {
						$sendAddress = $address;
						$name = '';
					}

					$this->phpMailer->AddBCC($sendAddress, $name);
				}
			}

			return $this;
		}


		public function isValidEmail ($address)
		{
			return preg_match('/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/', $address);
		}
	}