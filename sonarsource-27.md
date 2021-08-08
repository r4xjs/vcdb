---

title: sonarsource-27
author: raxjs
tags: [php, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1341775576986742784" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
<?php 
class email_output_html {
    protected function express($expression) {
        $expression = preg_replace(
            array('/env:([a-z0-9_]+)/i',
                '/config:([a-z0-9_]+)(:(\S+))?/i',
            ),
            array("(isset(\$this->env['\\1']) ? \$this->env['\\1'] : null)",
                "\$this->config->get('\\1', '\\3')",
            ),
            $expression
        );
        return eval("return ($expression);");
    }
    protected function parse_template() {
        $attributes  = html::parse_attrib_string($_POST['_mail_body']);
        foreach($attributes as $attrib) {
            if (!empty($attrib['express'])) {
                $attrib['c'] = $this->express($attrib['express']);
            }
            if (!empty($attrib['name']) || !empty($attrib['command'])) {
                $attrib['c'] = $this->button($attrib);
            }
        }
    }
}
class html {
    public static function parse_attrib_string($str) {	
        $attrib = array();
        preg_match_all('/\s*([-_a-z]+)=(["\'])??(?(2)([^\2]*)\2|(\S+?))/Ui', $str, $regs, PREG_SET_ORDER);
		
        if ($regs) {
            foreach ($regs as $attr) {
                $attrib[strtolower($attr[1])] = html_entity_decode($attr[3] . $attr[4]);
            }
        }
        return $attrib;
    }
}

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}