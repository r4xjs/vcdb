class LanguageManager {
    public function loadLanguage() {
        $lang = $this->getBrowserLanuage();
        $sanitizedLang = $this->sanitizeLanguage($lang);
        require_once("/lang/$sanitizedlang");
    }

    private function getBrowserLanuage() {
        $lang = $_SERVER['HTTP_ACCETPT_LANGUAGE'] ?? 'en';
        return $lang;
    }

    private function sanitizeLanguage($language) {
        return str_replace('../', '', $language);
    }
}
(new LanguageManager())->loadLanguage();
