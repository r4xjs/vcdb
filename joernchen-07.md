---

title: joernchen-07
author: raxjs
tags: [php]

---

Provides PDF generation of user supplied input.

<!--more-->
{{< reference src="https://code-audit-training.gitlab.io/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_POST['html']) {

  $options = new Options();
  $options->setIsPhpEnabled(true);

  // instantiate and use the dompdf class
  $dompdf = new Dompdf($options);

  // e.g.:

  $dompdf->loadHtml($_POST['html']);

  // (Optional) Setup the paper size and orientation
  //$dompdf->setPaper('A3', 'landscape');

  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser
  $dompdf->stream('pdfservice');
}
?>

{{< /code >}}

# Solution
{{< code language="php" highlight="13,14,20,26,27" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_POST['html']) {

  $options = new Options();
  // 1) php is enabled this means the evaluate function
  //    (see below) will pass php code to eval()
  $options->setIsPhpEnabled(true);

  // instantiate and use the dompdf class
  $dompdf = new Dompdf($options);

  // 2) user input is passed to loadHtml method
  $dompdf->loadHtml($_POST['html']);

  // (Optional) Setup the paper size and orientation
  //$dompdf->setPaper('A3', 'landscape');

  // 3) render will probably trigger evaluate at some point
  //    which will lead to php code exection on user supplied input
  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser
  $dompdf->stream('pdfservice');
}
?>




// from: https://github.com/dompdf/dompdf/blob/4c65810c797674d0ceb8918859c645f298b5e358/src/PhpEvaluator.php#L38
public function evaluate($code, $vars = [])
    {
        if (!$this->_canvas->get_dompdf()->getOptions()->getIsPhpEnabled()) {
            return;
        }

        // Set up some variables for the inline code
        $pdf = $this->_canvas;
        $fontMetrics = $pdf->get_dompdf()->getFontMetrics();
        $PAGE_NUM = $pdf->get_page_number();
        $PAGE_COUNT = $pdf->get_page_count();

        // Override those variables if passed in
        foreach ($vars as $k => $v) {
            $$k = $v;
        }

        eval($code);
    }


// form https://github.com/dompdf/dompdf/blob/4c65810c797674d0ceb8918859c645f298b5e358/src/PhpEvaluator.php#L59
public function render(Frame $frame)
{
    $this->evaluate($frame->get_node()->nodeValue);
}



{{< /code >}}
