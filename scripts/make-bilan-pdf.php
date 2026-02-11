<?php

declare(strict_types=1);

use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../vendor/autoload.php';

$htmlPath = realpath(__DIR__ . '/../docs/bilan.html');

if (! $htmlPath || ! file_exists($htmlPath)) {
    fwrite(STDERR, "Fichier docs/bilan.html introuvable.\n");
    exit(1);
}

$html = file_get_contents($htmlPath);

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../docs'));

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$outputPath = __DIR__ . '/../docs/bilan.pdf';
file_put_contents($outputPath, $dompdf->output());

fwrite(STDOUT, "PDF généré : {$outputPath}\n");
