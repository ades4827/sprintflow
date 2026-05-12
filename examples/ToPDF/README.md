# Make PDF from blade

Adds functionality for working with permissions of [Laravel-permission](https://spatie.be/docs/laravel-permission)

## Usage

First, run this command in the terminal to add the package: 

```
composer require carlos-meneses/laravel-mpdf
php artisan vendor:publish --tag=mpdf-config
```

In your controller add Trait and use it:

```
<?php

namespace App\Http\Controllers;

use Ades4827\Sprintflow\Traits\ToPdfTrait;

class ExampleController
{
    use ToPdfTrait;
    
    public function pdf(Request $request, Model $entity) {
        return self::generate_pdf('pdf.template', ['entity' => $entity], self::PDF_BROWSER, ['filename' => 'name.pdf']);
    }
}
    
```

Use different mode to generate pdf:

```
PDF_STORE
PDF_DOWNLOAD
PDF_BROWSER
PDF_STRING
BLADE_VIEW
```

To generate pdf from existing pdf template:

```
public static function example(Model $model, $destination = \Mpdf\Output\Destination::INLINE) {

    $pdf = self::makePdf(['model' => $model]);
    $pageCount = $pdf->SetSourceFile(storage_path('app/template/example.pdf'));
    self::set_default_style($pdf);

    self::import_page($pdf, 1);
    // add simple string in position
    self::add_string($pdf, 'NOME', [52,94]);
    
    // use mpdf native function
    $pdf->SetFontSize(8);
    $pdf->SetXY(80,195);
    $pdf->Cell(w:75, h:5.6, txt:'String', border:0, ln:0, align:'L');
            
    $signature = self::base64ToImage($model->base64_signature);
    $width = 200;
    $pdf->Image($signature['image'],x:100, y:200, w:$width, h:0, $signature['type']);

    // add debug grid
    //self::debug_grid($pdf, 10);

    self::import_page($pdf, 2);

    return $pdf->Output(
        'name_'.Carbon::now()->format('Y-m-d').'_'.$model->id.'.pdf',
        $destination
    );
}
```

To generate pdf from blade and from existing pdf:

```
public static function generateContract(Contract $contract, $saveFileOnS3 = true, $output_method = 'I') {
    
    // Crea una nuova pagina in memoria con mPDF
    $mpdf = \PDF::loadView('pdf.contract', ['contract' => $contract], [], [
        'setAutoTopMargin' => 'pad',
        'setAutoBottomMargin' => 'pad',
        'orientation' => 'portrait',
    ]);
    $pdfContent = $mpdf->Output('', 'S'); // Output to string

    // Scrivi il contenuto in un file temporaneo
    $tempPath = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tempPath, $pdfContent);

    // Crea il PDF finale unendo l'originale e la nuova pagina
    $fpdi = new Fpdi();

    // Importa l'originale
    /*$originalPdfPath = storage_path('app/public/privacy.pdf');
    $pageCount = $fpdi->setSourceFile($originalPdfPath);
    for ($i = 1; $i <= $pageCount; $i++) {
        $template = $fpdi->importPage($i);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $fpdi->useTemplate($template);
    }*/

    // Importa la nuova pagina dal file temporaneo
    $pageCount = $fpdi->setSourceFile($tempPath);
    for ($i = 1; $i <= $pageCount; $i++) {
        $template = $fpdi->importPage($i);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $fpdi->useTemplate($template);
    }

    // Elimina il file temporaneo
    unlink($tempPath);

    if($saveFileOnS3) {
        // store su S3
        try {
            $s3_path = S3Controller::contract_path($contract);
            return Storage::disk('s3')->put($s3_path, $fpdi->Output('', 'S'));

        } catch (Exception $e) {
            report($e);
            return false;
        }
    } elseif($output_method == 'I') {
        // Output finale al browser for DEBUG
        return response($fpdi->Output( $output_method, 'contract.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    } else {
        // Output finale al browser for DEBUG
        return $fpdi->Output( $output_method, 'contract.pdf');
    }
}
```
