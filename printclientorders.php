<?php
// Include Composer's autoloader
require 'vendor/autoload.php';
use Dompdf\Dompdf;

if(isset($_POST['generate_pdf'])){
    // Get the HTML content from the transactions page
    ob_start();
    include 'clientsorders.php';
    $html = ob_get_clean();

    // Remove or hide the dashboard and logout buttons from the HTML content
    $html = preg_replace('/<a href="admindash.php" class="btn btn-primary">Dashboard<\/a>/', '', $html);
    $html = preg_replace('/<a href="adminhome.php" class="btn btn-danger">Logout<\/a>/', '', $html);
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
    $dompdf->stream('client orders.pdf', array('Attachment' => 1));

    // Update the status of completed laundry to "paid" after generating the PDF
    require_once 'clientsorders.php'; // Include transactions.php to execute any necessary queries
}
?>
