<?php

class Mail {

    protected $to;
    protected $from;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $attachments = array();
    public $protocol = 'mail';
    public $smtp_hostname;
    public $smtp_username;
    public $smtp_password;
    public $smtp_port = 25;
    public $smtp_timeout = 5;
    public $verp = false;
    public $parameter = '';

    public function __construct($config = array()) {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setTo($to) {
        $this->to = $to;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function setReplyTo($reply_to) {
        $this->reply_to = $reply_to;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setHtml($html) {
        $this->html = $html;
    }

    public function addAttachment($filename) {
        $this->attachments[] = $filename;
    }

    public function send() {
        if (!$this->to) {
            exit('Error: E-Mail to required!');
        }

        if (!$this->from) {
            exit('Error: E-Mail from required!');
        }

        if (!$this->sender) {
            exit('Error: E-Mail sender required!');
        }

        if (!$this->subject) {
            exit('Error: E-Mail subject required!');
        }

        if ((!$this->text) && (!$this->html)) {
            //debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            exit('Error: E-Mail message required!');
        }

        if (is_array($this->to)) {
            $to = implode(',', $this->to);
        } else {
            $to = $this->to;
        }


        $mail = new PHPMailer(True); //True parameter tells PHPMailer to throw errors, remove for production once you have it working.
        $mail->CharSet = 'utf-8';


        $boundary = '----=_NextPart_' . md5(time());

        $header = '';

        if ($this->protocol != 'mail') {
            $header .= 'To: <' . $to . '>' . PHP_EOL;
            $header .= 'Subject: =?UTF-8?B?' . base64_encode($this->subject) . '?=' . PHP_EOL;
            $mail->AddAddress($to);
            $mail->Subject = $this->subject;
        }

        $header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
        $mail->SetFrom($this->from, $this->sender);

        if (!$this->reply_to) {
            $header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
        } else {
            $header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . PHP_EOL;
        }

        $mail->AddReplyTo($this->from, $this->sender);

        $header .= 'Return-Path:  ' . $this->from . PHP_EOL;
        $header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;
        $header .= 'MIME-Version:  1.0' . PHP_EOL;
        $header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;


        if (!$this->html) {
            $message = '--' . $boundary . PHP_EOL;
            $message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $message .= $this->text . PHP_EOL;
            $mail->Body = $this->text;
            $mail->AltBody = $this->html;
            //$mail->AltBody = $this->text;
        } else {
            $message = '--' . $boundary . PHP_EOL;
            $message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
            $message .= '--' . $boundary . '_alt' . PHP_EOL;
            $message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

            if ($this->text) {
                $message .= $this->text . PHP_EOL;
            } else {
                $message .= 'This is a HTML email and your email client software does not support HTML email!' . PHP_EOL;
            }

            $message .= '--' . $boundary . '_alt' . PHP_EOL;
            $message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $message .= $this->html . PHP_EOL;
            $message .= '--' . $boundary . '_alt--' . PHP_EOL;
            $mail->Body = $this->text;
            $mail->AltBody = $this->html;
            //$mail->AltBody = $this->text;
            $mail->MsgHTML($this->html);
        }

        foreach ($this->attachments as $attachment) {
            if (file_exists($attachment['file'])) {
                $mail->AddAttachment($attachment['file']);
                $handle = fopen($attachment['file'], 'r');
                $content = fread($handle, filesize($attachment['file']));

                fclose($handle);

                $message .= '--' . $boundary . PHP_EOL;
                $message .= 'Content-Type: application/octetstream' . PHP_EOL;
                $message .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
                $message .= 'Content-Disposition: attachment; filename="' . basename($attachment['filename']) . '"' . PHP_EOL;
                $message .= 'Content-ID: <' . basename($attachment['filename']) . '>' . PHP_EOL . PHP_EOL;
                $message .= chunk_split(base64_encode($content));
            }
        }

        $message .= '--' . $boundary . '--' . PHP_EOL;

        if ($this->protocol == 'mail') {
            ini_set('sendmail_from', $this->from);

            if ($this->parameter) {
                mail($to, $this->subject, $message, $header, $this->parameter);
            } else {
                mail($to, $this->subject, $message, $header);
            }
        } elseif ($this->protocol == 'smtp') {
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            $mail->Port = $this->smtp_port;
            if (strpos($this->smtp_hostname, 'gmail')) {
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth = true;
            }
            if (strpos($this->smtp_hostname, 'smtp')) {
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth = true;
            }
            $mail->Host = $this->smtp_hostname;
            $mail->SMTPSecure = "ssl";
            $mail->SMTPAuth = true;
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            //$mail->SMTPDebug = 2;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';

            $mail->IsSMTP();
            if (!$mail->Send()) {
                error_log($mail->ErrorInfo);
            }
        }
    }

}

/*public function send() {
		if (!$this->to) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (!$this->from) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (!$this->sender) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (!$this->subject) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if ((!$this->text) && (!$this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}

		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header = 'MIME-Version: 1.0' . PHP_EOL;

		if ($this->protocol != 'mail') {
			$header .= 'To: <' . $to . '>' . PHP_EOL;
			$header .= 'Subject: =?UTF-8?B?' . base64_encode($this->subject) . '?=' . PHP_EOL;
		}

		$header .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
		
		if (!$this->reply_to) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . PHP_EOL;
		}
		
		$header .= 'Return-Path: ' . $this->from . PHP_EOL;
		$header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;
		$header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;

		if (!$this->html) {
			$message  = '--' . $boundary . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
			$message .= $this->text . PHP_EOL;
		} else {
			$message  = '--' . $boundary . PHP_EOL;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

			if ($this->text) {
				$message .= $this->text . PHP_EOL;
			} else {
				$message .= 'This is a HTML email and your email client software does not support HTML email!' . PHP_EOL;
			}

			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
			$message .= $this->html . PHP_EOL;
			$message .= '--' . $boundary . '_alt--' . PHP_EOL;
		}

		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');

				$content = fread($handle, filesize($attachment));

				fclose($handle);

				$message .= '--' . $boundary . PHP_EOL;
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . PHP_EOL;
				$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . PHP_EOL;
				$message .= 'Content-ID: <' . basename(urlencode($attachment)) . '>' . PHP_EOL;
				$message .= 'X-Attachment-Id: ' . basename(urlencode($attachment)) . PHP_EOL . PHP_EOL;
				$message .= chunk_split(base64_encode($content));
			}
		}

		$message .= '--' . $boundary . '--' . PHP_EOL;

		if ($this->protocol == 'mail') {
			ini_set('sendmail_from', $this->from);

			if ($this->parameter) {
				mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
			} else {
				mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
			}
		} elseif ($this->protocol == 'smtp') {
			if (substr($this->smtp_hostname, 0, 3) == 'tls') {
				$hostname = substr($this->smtp_hostname, 6);
			} else {
				$hostname = $this->smtp_hostname;
			}

			$handle = fsockopen($hostname, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);

			if (!$handle) {
				throw new \Exception('Error: ' . $errstr . ' (' . $errno . ')');
			} else {
				if (substr(PHP_OS, 0, 3) != 'WIN') {
					socket_set_timeout($handle, $this->smtp_timeout, 0);
				}
	
		
				while ($line = fgets($handle, 515)) {
					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					throw new \Exception('Error: EHLO not accepted from server!');
				}

				if (substr($this->smtp_hostname, 0, 3) == 'tls') {
					fputs($handle, 'STARTTLS' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 220) {
						throw new \Exception('Error: STARTTLS not accepted from server!');
					}

					stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
				}

				if (!empty($this->smtp_username)  && !empty($this->smtp_password)) {
					fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 250) {
						throw new \Exception('Error: EHLO not accepted from server!');
					}

					fputs($handle, 'AUTH LOGIN' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 334) {
						throw new \Exception('Error: AUTH LOGIN not accepted from server!');
					}

					fputs($handle, base64_encode($this->smtp_username) . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 334) {
						throw new \Exception('Error: Username not accepted from server!');
					}

					fputs($handle, base64_encode($this->smtp_password) . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 235) {
						throw new \Exception('Error: Password not accepted from server!');
					}
				} else {
					fputs($handle, 'HELO ' . getenv('SERVER_NAME') . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 250) {
						throw new \Exception('Error: HELO not accepted from server!');
					}
				}

				if ($this->verp) {
					fputs($handle, 'MAIL FROM: <' . $this->from . '>XVERP' . "\r\n");
				} else {
					fputs($handle, 'MAIL FROM: <' . $this->from . '>' . "\r\n");
				}

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
                                }
				

                                
				if (substr($reply, 0, 3) != 250) {
					throw new \Exception('Error: MAIL FROM not accepted from server!');
				}

				if (!is_array($this->to)) {
					fputs($handle, 'RCPT TO: <' . $this->to . '>' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
						throw new \Exception('Error: RCPT TO not accepted from server!');
					}
				} else {
					foreach ($this->to as $recipient) {
						fputs($handle, 'RCPT TO: <' . $recipient . '>' . "\r\n");

						$reply = '';

						while ($line = fgets($handle, 515)) {
							$reply .= $line;

							if (substr($line, 3, 1) == ' ') {
								break;
							}
						}

						if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
							throw new \Exception('Error: RCPT TO not accepted from server!');
						}
					}
				}

				fputs($handle, 'DATA' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 354) {
					throw new \Exception('Error: DATA not accepted from server!');
				}

				// According to rfc 821 we should not send more than 1000 including the CRLF
				$message = str_replace("\r\n", "\n", $header . $message);
				$message = str_replace("\r", "\n", $message);

				$lines = explode("\n", $message);

				foreach ($lines as $line) {
					$results = str_split($line, 998);

					foreach ($results as $result) {
						if (substr(PHP_OS, 0, 3) != 'WIN') {
							fputs($handle, $result . "\r\n");
						} else {
							fputs($handle, str_replace("\n", "\r\n", $result) . "\r\n");
						}
					}
				}

				fputs($handle, '.' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					throw new \Exception('Error: DATA not accepted from server!');
				}

				fputs($handle, 'QUIT' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 221) {
					throw new \Exception('Error: QUIT not accepted from server!');
				}

				fclose($handle);
			}
		}
	}
}
         * 
         */