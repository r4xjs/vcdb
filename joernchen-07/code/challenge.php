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
