<?php

namespace Ades4827\Sprintflow\Traits;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Mpdf\MpdfException;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Symfony\Component\HttpFoundation\Response;

trait ToPdfTrait
{
    public const string PDF_STORE = 'store';
    public const string PDF_DOWNLOAD = 'download';
    public const string PDF_BROWSER = 'browser';
    public const string PDF_STRING = 'string';
    public const string BLADE_VIEW = 'view';

    public static float $default_cell_height = 5.6;

    private static function checkDependency() {
        if (!class_exists('Mccarlosen\\LaravelMpdf\\LaravelMpdf')) {
            throw new \RuntimeException(
                'The package "carlos-meneses/laravel-mpdf" is not installed. ' .
                'Install it with: composer require carlos-meneses/laravel-mpdf'
            );
        }
    }

    /**
     * @param string $view
     * @param array $datas
     * @param string $mode
     * @param array $configuration ['filename' => null, 'directory' => '', 'disk_name' => null]
     * @return Application|Factory|View|\Illuminate\View\View|mixed|string|Response|void|null
     * @throws MpdfException
     */
    protected static function generate_pdf(string $view, array $datas, string $mode = self::PDF_BROWSER, array $configuration = [])
    {
        self::checkDependency();

        ini_set('memory_limit', '300M');

        // mode configuration
        $filename = $configuration['filename'] ?? null;
        $directory = $configuration['directory'] ?? '';
        $disk_name = $configuration['disk_name'] ?? null;
        // pdf configuration
        $format = $configuration['format'] ?? 'A4'; // [80, 40]
        $orientation = $configuration['orientation'] ?? 'portrait'; //'landscape'
        $margin_left = $configuration['margin_left'] ?? config('pdf.margin_left', 10);
        $margin_right = $configuration['margin_right'] ?? config('pdf.margin_right', 10);
        $margin_top = $configuration['margin_top'] ?? config('pdf.margin_top', 10);
        $margin_bottom = $configuration['margin_bottom'] ?? config('pdf.margin_bottom', 10);
        $margin_header = $configuration['margin_header'] ?? config('pdf.margin_header', 0);
        $margin_footer = $configuration['margin_footer'] ?? config('pdf.margin_footer', 0);

        if ($mode === self::BLADE_VIEW) {
            return view($view, $datas);
        }

        // load built css file from public folder manifest.json
        if (isset($configuration['inject_css']) && $configuration['inject_css'] === true) {
            if (!isset($configuration['css_path'])) {
                $configuration['css_path'] = 'resources/css/pdf.css';
            }
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true, 512, JSON_THROW_ON_ERROR);
            $css = file_get_contents(public_path('build/' . $manifest[$configuration['css_path']]['file']));

            $datas = array_merge($datas, [
                'css' => $css
            ]);
        }

        // share data to view
        foreach ($datas as $key => $value) {
            view()->share($key, $value);
        }
        $pdf = PDF::loadView($view, [], [], [
            'format' => $format,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_header' => $margin_header,
            'margin_footer' => $margin_footer,
            'setAutoTopMargin' => 'pad',
            'setAutoBottomMargin' => 'pad',
            'orientation' => $orientation,
        ]);

        // show PDF inline
        if ($mode === self::PDF_STRING) {
            return $pdf->getMpdf()->OutputBinaryData();
        }

        // show PDF in browser
        if ($mode === self::PDF_BROWSER) {
            return $pdf->stream('output.pdf');
        }

        // download PDF file with download method
        if ($mode === self::PDF_DOWNLOAD) {
            if ($filename === null) {
                throw new \RuntimeException('File name missing');
            }

            return $pdf->download($filename);
        }

        // download PDF file with download method
        if ($mode === self::PDF_STORE) {
            if ($filename === null) {
                throw new \RuntimeException('File name missing');
            }
            if ($directory !== '') {
                Storage::disk($disk_name)->makeDirectory($directory);
            }
            if ($disk_name === null) {
                $pdf->save(Storage::path($directory.'/'.$filename));
            } else {
                $pdf->save(Storage::disk($disk_name)->path($directory.'/'.$filename));
            }

            return $filename;
        }
    }

    protected static function base64ToImage($base64): array
    {
        $img = explode(',', $base64, 2);
        $pic = 'data:text/plain;base64,'.$img[1];
        // get the image type
        $type = explode('/', explode(':', substr($base64, 0, strpos($base64, ';')))[1])[1];
        if ($type === 'png' || $type === 'jpeg' || $type === 'gif') {
            return [
                'image' => $pic,
                'type' => $type,
            ];
        }
        throw new \RuntimeException('Image type not valid');
    }

    protected static function debug_grid($pdf, $font_size_after): void
    {
        $pdf->SetFont('Arial');
        $pdf->SetFontSize(4.5);
        $pdf->SetAutoPageBreak(false);

        // altezza cella
        $cellaH = 3;
        $border = 0;

        // grid
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetDrawColor(255, 0, 0);

        $grid = [];
        if ($pdf->CurOrientation === 'P') {
            for ($y = 0; $y <= 297; $y = $y + 5) {
                for ($x = 0; $x <= 210; $x = $x + 5) {
                    $grid[$y][] = $x;
                }
            }
        } else {
            for ($y = 0; $y <= 210; $y = $y + 5) {
                for ($x = 0; $x <= 297; $x = $x + 5) {
                    $grid[$y][] = $x;
                }
            }
        }
        foreach ($grid as $row_text => $row) {
            foreach ($row as $col_text) {
                // text x
                $pdf->SetXY($col_text, $row_text + 1.6);
                $pdf->Cell(10, $cellaH, 'y:'.$row_text, $border, 0, 'L');
                // text y
                $pdf->SetXY($col_text, $row_text);
                $pdf->Cell(10, $cellaH, 'x:'.$col_text, $border, 0, 'L');
            }
        }

        $pdf->SetFontSize($font_size_after);
        $pdf->SetTextColor(0, 0, 0);
    }

    public static function makePdf(array $pdf_data = []) {
        $pdf = new Mpdf([
            'margin_left'              => 6,
            'margin_right'             => 6,
            'margin_top'               => 0,
            'margin_bottom'            => 3,
            'margin_header'            => 0,
            'margin_footer'            => 3,
            'fontDir' => array_merge((new ConfigVariables())->getDefaults()['fontDir'], [
                config('pdf.custom_font_dir'),
            ]),
            'fontdata' => (new FontVariables())->getDefaults()['fontdata'] + config('pdf.custom_font_data'),
            'default_font' => 'lato'
        ]);
        $pdf->WriteHTML(self::defaultCss(),\Mpdf\HTMLParserMode::HEADER_CSS);
        $pdf->SetHTMLHeader( view('pdf.layouts._top', ['pdf_data' => $pdf_data]) );
        $pdf->SetHTMLFooter( view('pdf.layouts._footer', ['pdf_data' => $pdf_data]).'<div class="page-number">Pagina {PAGENO}/{nbpg}</div>' );

        //$pdf->debug = true;
        return $pdf;
    }

    public static function defaultCss(): string
    {
        return "body {
            font-size: 0.88rem;
            font-family: 'lato', sans-serif;
        }
        .font-small {
            font-size: 0.7rem;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .header {
            margin-left: 6mm;
            margin-right: 6mm;
        }
        .footer {
            margin-left: 6mm;
            margin-right: 6mm;
        }
        .page-number {
            font-size: 10;
            padding-top: 10px;
            text-align: right;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table .table-label, .strong, .font-weight-bold {
            font-weight: bold !important;
        }";
    }

    public static function tracking(string $text, int $letter_spacing = 2): string
    {
        $text = strtoupper($text);

        // Crea la "spaziatura" usando &nbsp; (per HTML) oppure spazi normali
        $spacing = str_repeat(' ', $letter_spacing);

        // Suddivide in caratteri Unicode e li unisce con la spaziatura
        return implode($spacing, preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY));
    }

    public static function set_default_style(Mpdf $pdf): void
    {
        $pdf->SetFont('Arial');
        $pdf->SetFontSize(10);
    }

    public static function import_page(Mpdf $pdf, int $page_number): void
    {
        $pdf->AddPage('P');
        $tplIdx = $pdf->ImportPage($page_number);
        $pdf->UseTemplate($tplIdx, 0, 0, 210);
    }

    public static function validate_position(array $position): array
    {
        if (count($position) !== 2) {
            throw new \InvalidArgumentException("Position deve contenere esattamente [x, y]");
        }
        return $position;
    }

    public static function add_string(Mpdf $pdf, string $string, array $position, array $options = [])
    {
        [$x, $y] = self::validate_position($position);

        // override this method to use custom options

        $pdf->SetXY($x, $y);
        $pdf->Cell(55, self::$default_cell_height, $string, 0, 0, 'L');
    }
}
