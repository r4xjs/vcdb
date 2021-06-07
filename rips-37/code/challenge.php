class Mailer {
    private function sanitize($mail) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return '';
        }

        return escapeshellarg($email);
    }

    public function send($data) {
        if(!isset($data['to'])) {
            $data['to'] = 'none@riptech.com';
        } else {
            $data['to'] = $this->sanitize($data['to']));
        }
        if(!isset($data['from'])) {
            $data['from'] = 'none@riptech.com';
        } else {
            $data['from'] = $this->sanitize($data['from']));
        }
        if(!isset($data['subject'])) {
            $data['subject'] = 'No Subject';
        }
        if((!isset($data['message'])) {
                $data['message'] = '';
        }
        mail($data['to'], $data['subject'], $data['message'],
            '', "-f" . $data['from']);
    }
}
