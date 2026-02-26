<?php

	if (! class_exists('PHPMailer')) {
		class_alias(
			\PHPMailer\PHPMailer\PHPMailer::class,
			'PHPMailer'
		);

		class_alias(
			\PHPMailer\PHPMailer\Exception::class,
			'phpmailerException'
		);
	}
