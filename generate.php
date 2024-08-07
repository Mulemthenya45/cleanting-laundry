<?php
// Include Composer's autoloader
require 'vendor/autoload.php';
use Dompdf\Dompdf;

if(isset($_POST['generate_pdf'])){
    // Get the HTML content from the receipt page
    ob_start();
    include 'receipt.php';
    $html = ob_get_clean();

    // Remove the button for printing
    $html = preg_replace('/<form.*?>.*?<\/form>/s', '', $html);

    // Use the Dompdf namespace

    // Instantiate Dompdf
    $dompdf = new Dompdf();

    // Load HTML content
    $dompdf->loadHtml($html);

    // Render PDF (optional: set paper size and orientation)
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output PDF to browser (optional: save to file)
    $dompdf->stream('payment_receipt.pdf', array('Attachment' => 1));

    // Update the status of completed laundry to "paid" after generating the PDF
    require_once 'receipt.php'; // Include receipt.php to execute the update status query
}
?>
