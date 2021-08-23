---

title: rips-40
author: raxjs
tags: [php]

---

A nice LanguageManger class in PHP.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1104050392202018816" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="php" highlight="5,7,12,18" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class LanguageManager {
    public function loadLanguage() {
        $lang = $this->getBrowserLanuage();
        // 2) $lang is user input
        $sanitizedLang = $this->sanitizeLanguage($lang);
        // 4) file inclusion here
        require_once("/lang/$sanitizedlang");
    }

    private function getBrowserLanuage() {
        // 1) $lang is user input
        $lang = $_SERVER['HTTP_ACCETPT_LANGUAGE'] ?? 'en';
        return $lang;
    }

    private function sanitizeLanguage($language) {
        // 3) is not recursive --> can be bypassed
        return str_replace('../', '', $language);
    }
}
(new LanguageManager())->loadLanguage();


// example:
// echo str_replace('../', '', 'aaa/../..././bbb');
// aaa/../bbb



{{< /code >}}
