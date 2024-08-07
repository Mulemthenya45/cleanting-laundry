<?php
// Include Composer's autoloader
require 'vendor/autoload.php';

// Use the Dompdf namespace
use Dompdf\Dompdf;

// Instantiate Dompdf
$dompdf = new Dompdf();

// Load HTML content
$html = '<h1>Hello, Dompdf!</h1>';

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// Render PDF (optional: set paper size and orientation)
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF to browser (optional: save to file)
$dompdf->stream('example.pdf');
