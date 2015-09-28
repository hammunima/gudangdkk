<?php

class ReportController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function index()
    {
        return View::make('hello');
    }

    public function cetak_nota($id)
    {
        $data = DB::table('tbkeluar')->where('cNomor', $id)->first();
        $dtl = DB::table('tbkeluardtl')->where('cNomor', $id)->orderBy('cNoID', 'asc')->get();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Cetak Nota');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetAutoPageBreak(TRUE, 8);

        // CONTENT-------------------------------------------
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('times', 'B', 10);
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, 'TANDA TERIMA BARANG', '', 0, 'C', true, 0, false, false, 0);
        $pdf->ln();
        $tbl = '<table>
            <tr>
                <td style="width: 15%">Diserahkan kepada</td>
                <td>:  ' . $data->cBidang . ' ' . $data->cPuskesmas . '</td>
                <td style="width: 40%"></td>
                <td style="width: 10%">Nomor</td>
                <td>:  ' . $id . '</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:  ' . $data->dTanggal . '</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            </table>';
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->SetFont('helvetica', '', 8);
        $header = '
            <table cellpadding="2">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width: 10%;border-bottom: solid;border-top: solid">No</th>
                        <th style="width: 10%;border-bottom: solid;border-top: solid">Kode</th>
                        <th style="width: 40%;text-align: left;border-bottom: solid;border-top: solid">Nama Barang</th>
                        <th style="width: 10%;text-align: right;border-bottom: solid;border-top: solid">Jumlah</th>
                        <th style="width: 12%;border-bottom: solid;border-top: solid">Satuan</th>
                        <th style="border-bottom: solid;border-top: solid">Keterangan</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
        ';
        $footer = "</table>";
        $content = '';
        for ($i = 0; $i < count($dtl); $i++) {
            if ($i == 0) {
                $content .= '
                <tr style="text-align: center;">
                    <td style="width: 10%;">' . $dtl[$i]->cNoID . '</td>
                    <td style="width: 10%;">' . $dtl[$i]->cKode . '</td>
                    <td style="width: 40%;text-align: left">' . $dtl[$i]->cNama . '</td>
                    <td style="width: 10%;text-align: right">' . $dtl[$i]->nQty . '</td>
                    <td style="width: 12%;">' . $dtl[$i]->cSatuan . '</td>
                    <td>' . $dtl[$i]->cKeterangan . '</td>
                </tr>
            ';
            }
            $content .= '
            <tr style="text-align: center;">
                <td style="width: 10%;">' . $dtl[$i]->cNoID . '</td>
                <td style="width: 10%;">' . $dtl[$i]->cKode . '</td>
                <td style="width: 40%;text-align: left">' . $dtl[$i]->cNama . '</td>
                <td style="width: 10%;text-align: right">' . $dtl[$i]->nQty . '</td>
                <td style="width: 12%;">' . $dtl[$i]->cSatuan . '</td>
                <td>' . $dtl[$i]->cKeterangan . '</td>
            </tr>
            ';
        }
        for ($i = 0; $i < (11 - count($dtl)); $i++) {
            $content .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        $ttd = '
        <table style="text-align: center;">
            <tr>
                <td>&nbsp;</td>
                <td>Surabaya, ' . date('d-F-Y', strtotime($data->dTanggal)) . '</td>
            </tr>
            <tr>
                <td>Yang menerima</td>
                <td>Penerima</td>
            </tr>
            <tr>
                <td><br><br><br>(........................................................)</td>
                <td><br><br><br>(........................................................)</td>
            </tr>
            <tr>
                <td>Tanda tangan dan nama lengkap</td>
                <td>Petugas gudang</td>
            </tr>
        </table>';
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Ln();
        $pdf->writeHTML($ttd, true, false, false, false, '');
        $pdf->Output('Nota_' . $id . '.pdf', 'I');
    }

    public function cetak_nota1($id)
    {
        $data = DB::table('tbkeluar')->where('cNomor', $id)->first();
        $dtl = DB::table('tbkeluardtl')->where('cNomor', $id)->orderBy('cNoID', 'asc')->get();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Cetak Nota');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetAutoPageBreak(TRUE, 8);

        // CONTENT-------------------------------------------
        $pdf->AddPage('L', array(210, 140));
        $pdf->SetFont('times', 'B', 10);
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, 'TANDA TERIMA BARANG', '', 0, 'C', true, 0, false, false, 0);
        $pdf->ln();
        $tbl = '<table>
            <tr>
                <td style="width: 15%">Diserahkan kepada</td>
                <td>:  ' . $data->cBidang . ' ' . $data->cPuskesmas . '</td>
                <td style="width: 40%"></td>
                <td style="width: 10%">Nomor</td>
                <td>:  ' . $id . '</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:  ' . date('d F Y', strtotime($data->dTanggal)) . '</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            </table>';
        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->SetFont('helvetica', '', 9);
        $header = '
            <table cellpadding="2">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width: 10%;border-bottom: solid;border-top: solid">No</th>
                        <th style="width: 10%;border-bottom: solid;border-top: solid">Kode</th>
                        <th style="width: 40%;text-align: left;border-bottom: solid;border-top: solid">Nama Barang</th>
                        <th style="width: 10%;text-align: right;border-bottom: solid;border-top: solid">Jumlah</th>
                        <th style="width: 10%;text-align: left;border-bottom: solid;border-top: solid">Satuan</th>
                        <th style="width: 10%;text-align: right;border-bottom: solid;border-top: solid">Harga Total</th>
                        <th style="width: 10%;border-bottom: solid;border-top: solid">Keterangan</th>
                    </tr>

                </thead>
        ';
        $footer = "</table>";
        $content = '';
        $n = 1;
        $tot = 0;
        for ($i = 0; $i < count($dtl); $i++) {
            $inv = DB::table('tbinventori')->where('id', $dtl[$i]->inventori)->select('harga')->first();
            if (count($inv) > 0) {
                $harga = $inv->harga;
            } else {
                $harga = 0;
            }
            if ($i < (count($dtl) - 1)) {
                if ($dtl[$i]->cKode == $dtl[$i + 1]->cKode) {
                    $tot += ($harga * $dtl[$i]->nQty);
                } else {
                    $tot += ($harga * $dtl[$i]->nQty);
                    $content .= '
                    <tr style="text-align: center;">
                        <td style="width: 10%;">' . $n . '</td>
                        <td style="width: 10%;">' . $dtl[$i]->cKode . '</td>
                        <td style="width: 40%;text-align: left">' . $dtl[$i]->cNama . '</td>
                        <td style="width: 10%;text-align: right">' . $dtl[$i]->nQty . '</td>
                        <td style="width: 10%;text-align: left">' . $dtl[$i]->cSatuan . '</td>
                        <td style="width: 10%;text-align: right">' . Terbilang::format_no_sign($tot) . '</td>
                        <td style="width: 10%;">' . $dtl[$i]->cKeterangan . '</td>
                    </tr>';
                    $tot = 0;
                    $n++;
                }
            } else {
                $tot += ($harga * $dtl[$i]->nQty);
                $content .= '
                <tr style="text-align: center;">
                    <td style="width: 10%;">' . $n . '</td>
                    <td style="width: 10%;">' . $dtl[$i]->cKode . '</td>
                    <td style="width: 40%;text-align: left">' . $dtl[$i]->cNama . '</td>
                    <td style="width: 10%;text-align: right">' . $dtl[$i]->nQty . '</td>
                    <td style="width: 10%;text-align: left">' . $dtl[$i]->cSatuan . '</td>
                    <td style="width: 10%;text-align: right">' . Terbilang::format_no_sign($tot) . '</td>
                    <td style="width: 10%;">' . $dtl[$i]->cKeterangan . '</td>
                </tr>';
                $n++;
            }
        }
        for ($i = 0; $i < (9 - count($dtl)); $i++) {
            $content .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        $ttd = '
        <table style="text-align: center;">
            <tr>
                <td>&nbsp;</td>
                <td>Surabaya, ' . date('d-F-Y', strtotime($data->dTanggal)) . '</td>
            </tr>
            <tr>
                <td>Yang menerima</td>
                <td>Penerima</td>
            </tr>
            <tr>
                <td><br><br><br>(........................................................)</td>
                <td><br><br><br>(........................................................)</td>
            </tr>
            <tr>
                <td>Tanda tangan dan nama lengkap</td>
                <td>Petugas gudang</td>
            </tr>
        </table>';
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Ln();
        $pdf->writeHTML($ttd, true, false, false, false, '');
        $pdf->Output('Nota_' . $id . '.pdf', 'I');
    }

    public function cetak_nota2($id)
    {
        $data = DB::table('tbkeluar')->where('cNomor', $id)->first();
        $dtl = DB::table('tbkeluardtl')->where('cNomor', $id)->orderBy('cNoID', 'asc')->get();

        $tbl = '<table cellpadding="0" cellspacing="0" style="font-size: 8pt;">
            <tr>
                <td colspan="7" style="text-align: center;font-weight: bold;">PEMERINTAH KOTA SURABAYA</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: center;font-weight: bold">DINAS KESEHATAN KOTA SURABAYA</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: center;font-weight: bold">JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: center;font-weight: bold">TANDA TERIMA BARANG</td>
            </tr>
            <tr>
                <td style="width: 20%">Diserahkan kepada</td>
                <td style="width: 20%">:  ' . $data->cBidang . ' ' . $data->cPuskesmas . '</td>
                <td colspan="3" style="width: 30%"></td>
                <td colspan="2" style="width: 40%;text-align: right">Nomor :  ' . $id . '</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:  ' . date('d F Y', strtotime($data->dTanggal)) . '</td>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            </table>';
        $header = '
            <table style="font-size: 8pt;" cellpadding="0" cellspacing="0">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width: 3%;border-bottom: solid;border-top: solid">No</th>
                        <th style="width: 7%;border-bottom: solid;border-top: solid">Kode</th>
                        <th style="width: 50%;text-align: left;border-bottom: solid;border-top: solid">Nama Barang</th>
                        <th style="width: 12%;text-align: right;border-bottom: solid;border-top: solid">Jumlah&nbsp;</th>
                        <th style="width: 8%;text-align: left;border-bottom: solid;border-top: solid">&nbsp;Satuan</th>
                        <th style="width: 15%;text-align: right;border-bottom: solid;border-top: solid">Harga Total&nbsp;</th>
                        <th style="width: 12%;border-bottom: solid;border-top: solid">&nbsp;Keterangan</th>
                    </tr>
                </thead>
        ';
        $footer = "</table>";
        $content = '';
        $n = 1;
        $tot = 0;
        for ($i = 0; $i < count($dtl); $i++) {
            if ($dtl[$i]->inventori == 0) {
                $inv = DB::table('tbinventori')->where('cKode', $dtl[$i]->cKode)->where('urutan', 1)->select('harga')->first();
            } else {
                $inv = DB::table('tbinventori')->where('id', $dtl[$i]->inventori)->select('harga')->first();
            }
            if (count($inv) > 0) {
                $harga = $inv->harga;
            } else {
                $harga = 0;
            }
            if ($i < (count($dtl) - 1)) {
                if ($dtl[$i]->cKode == $dtl[$i + 1]->cKode) {
                    $tot += ($harga * $dtl[$i]->nQty);
                } else {
                    $tot += ($harga * $dtl[$i]->nQty);
                    $content .= '
                    <tr style="text-align: center;">
                        <td style="width: 5%;">' . $n . '</td>
                        <td style="width: 5%;">' . $dtl[$i]->cKode . '</td>
                        <td style="width: 50%;text-align: left">' . $dtl[$i]->cNama . '</td>
                        <td style="width: 12%;text-align: right">' . $dtl[$i]->nQty . '&nbsp;</td>
                        <td style="width: 8%;text-align: left">&nbsp;' . $dtl[$i]->cSatuan . '</td>
                        <td style="width: 15%;text-align: right">' . Terbilang::format_no_sign($tot) . '&nbsp;</td>
                        <td style="width: 12%;">&nbsp;' . $dtl[$i]->cKeterangan . '</td>
                    </tr>';
                    $tot = 0;
                    $n++;
                }
            } else {
                $tot += ($harga * $dtl[$i]->nQty);
                $content .= '
                <tr style="text-align: center;">
                    <td style="width: 5%;">' . $n . '</td>
                    <td style="width: 5%;">' . $dtl[$i]->cKode . '</td>
                    <td style="width: 50%;text-align: left">' . $dtl[$i]->cNama . '</td>
                    <td style="width: 12%;text-align: right">' . $dtl[$i]->nQty . '&nbsp;</td>
                    <td style="width: 8%;text-align: left">&nbsp;' . $dtl[$i]->cSatuan . '</td>
                    <td style="width: 15%;text-align: right">' . Terbilang::format_no_sign($tot) . '&nbsp;</td>
                    <td style="width: 12%;">&nbsp;' . $dtl[$i]->cKeterangan . '</td>
                </tr>';
                $n++;
            }
            if (count($dtl) > 10 && $i == 9) {
                for ($j = 0; $j < 7; $j++) {
                    $content .= '<tr><td colspan="7">&nbsp;</td></tr>';
                }
                $content .= '
                    <tr style="text-align: center;font-weight: bold">
                        <td style="border-bottom: solid;border-top: solid">No</td>
                        <td style="border-bottom: solid;border-top: solid">Kode</td>
                        <td style="text-align: left;border-bottom: solid;border-top: solid">Nama Barang</td>
                        <td style="text-align: right;border-bottom: solid;border-top: solid">Jumlah&nbsp;</td>
                        <td style="text-align: left;border-bottom: solid;border-top: solid">&nbsp;Satuan</td>
                        <td style="text-align: right;border-bottom: solid;border-top: solid">Harga Total&nbsp;</td>
                        <td style="border-bottom: solid;border-top: solid">&nbsp;Keterangan</td>
                    </tr>';
            }
        }
        if (count($dtl) > 10) {
            $tmp = count($dtl) - 10;
            for ($i = 0; $i < (16 - $tmp); $i++) {
                $content .= '<tr><td colspan="7">&nbsp;</td></tr>';
            }
        } else {
            for ($i = 0; $i < (10 - count($dtl)); $i++) {
                $content .= '<tr><td colspan="7">&nbsp;</td></tr>';
            }
        }
        $content .= '
            <tr style="text-align: center">
                <td colspan="3">&nbsp;</td>
                <td colspan="4">Surabaya, ' . date('d F Y', strtotime($data->dTanggal)) . '</td>
            </tr>
            <tr style="text-align: center">
                <td colspan="3" style="text-align: left;padding-left: 18%">Yang menerima</td>
                <td colspan="4">Yang menyerahkan</td>
            </tr>
            <tr style="text-align: center">
                <td colspan="3" style="text-align: left;padding-left: 10%"><br><br>(........................................................)</td>
                <td colspan="4"><br><br>(........................................................)</td>
            </tr>
            <tr style="text-align: center">
                <td colspan="3" style="text-align: left;padding-left: 11%">Tanda tangan dan nama lengkap</td>
                <td colspan="4">Petugas gudang</td>
            </tr>';
        $tabel = $tbl . $header . $content . $footer;
        return View::make('NotaKeluar', compact('tabel'));
    }


    public function report_sesuai()
    {
        set_time_limit(60);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $barang = DB::table('tbadj')->join('tbadjdtl', 'tbadj.cNomor', '=', 'tbadjdtl.cNomor')
            ->where('dTanggal', '>=', $awal)
            ->where('dTanggal', '<=', $akhir)
            ->orderBy('dTanggal', 'asc')
            ->orderBy('tbadj.cNomor', 'asc')
            ->get();
        $data = 'LAPORAN DATA PENYESUAIAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s/d ' . date('d-m-Y', strtotime($akhir));

        $header = '
            <table cellpadding="10">
                <thead>
                    <tr style="font-weight: bold;text-align: center">
                        <th style="width: 3%;border-top: solid">No</th>
                        <th style="width: 15%;border-top: solid">Nomor</th>
                        <th style="width: 10%;border-top: solid">Tanggal</th>
                        <th style="width: 52%;border-top: solid;text-align: left">Keterangan</th>
                        <th style="width: 20%;border-top: solid;text-align: right">&nbsp;</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center">
                        <th style="width: 3%;border-bottom: solid">&nbsp;</th>
                        <th style="width: 5%;border-bottom: solid">NoID</th>
                        <th style="width: 40%;border-bottom: solid;text-align: left">Nama Barang</th>
                        <th style="width: 10%;border-bottom: solid;text-align: right">Qty</th>
                        <th style="width: 12%;border-bottom: solid">Satuan</th>
                        <th style="width: 30%;border-bottom: solid;text-align: right">&nbsp;</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
        ';
        $content = '';
        $jenis = '';
        $n = 1;
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            if ($jenis != $barang[$i]->cNomor) {
                $sum = 0;
                $content .= '
                     <tr style="font-weight: bold;text-align: center">
                        <td style="width: 3%;">' . $n . '</td>
                        <td style="width: 15%;">' . $barang[$i]->cNomor . '</td>
                        <td style="width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->dTanggal)) . '</td>
                        <td style="width: 52%;text-align: left">' . $barang[$i]->cKeterangan . '</td>
                        <td style="width: 20%;text-align: right">&nbsp;</td>
                    </tr>';
                $jenis = $barang[$i]->cNomor;
                $m = 1;
                $n++;
            }
            $content .= '
                <tr style="text-align: center">
                    <td style="width: 3%">&nbsp;</td>
                    <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                    <td style="width: 40%;text-align: left">' . $barang[$i]->cNama . '</td>
                    <td style="width: 10%;text-align: right">' . $barang[$i]->nQty . '</td>
                    <td style="width: 12%">' . $barang[$i]->cSatuan . '</td>
                    <td style="width: 30%text-align: right">&nbsp;</td>
                </tr>';
            $m++;
        }
        $footer = "</table>";
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Stok Barang');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // CONTENT-------------------------------------------
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, $data, '', 0, 'C', true, 0, false, false, 0);

        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output('laporan_penyesuaian' . date('d-m-Y') . ' . pdf', 'I');
        //return '';
    }

    public function report_keluar()
    {
        set_time_limit(300);
        $kat = array('cKdBidang' => 'BIDANG', 'cKdPuskesmas' => 'PUSKESMAS', 'cKode' => 'BARANG');
        $field = array('cKdBidang' => 'cBidang', 'cKdPuskesmas' => 'cPuskesmas', 'cKode' => 'cNama');
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        $val = array();
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        if (Input::get('p_bidang') != '0') {
            $j = explode('-', Input::get('p_bidang'));
            $tmp = array('cKdBidang' => $j[0]);
            $val += $tmp;
            //array_push($val, $tmp);
        }
        if (Input::get('p_puskesmas') != '0') {
            $j = explode('-', Input::get('p_puskesmas'));
            $tmp = array('cKdPuskesmas' => $j[0]);
            $val += $tmp;//array_push($val, $tmp);
        }
        if (Input::get('p_barang') != '0') {
            $j = explode('-', Input::get('p_barang'));
            $tmp = array('cKode' => $j[0]);
            $val += $tmp;//array_push($val, $tmp);
        }
        if (Input::get('jns') != '0') {
            $barang = DB::table('tbkeluar')->join('tbkeluardtl', 'tbkeluar.cNomor', '=', 'tbkeluardtl.cNomor')
                ->where($val)
                ->where($field[Input::get('jns')], '<>', '')
                ->where('tbkeluar.dTanggal', '>=', $awal)
                ->where('tbkeluar.dTanggal', '<=', $akhir)
                ->orderBy(Input::get('jns'), 'asc')
                ->orderBy('tbkeluar.dTanggal', 'asc')
                ->get();
            $data = 'LAPORAN DATA PENGELUARAN BARANG PER ' . $kat[Input::get('jns')] . ' PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
            if (Input::get('jns') == 'cKode') {
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Tujuan Alokasi </th>
                        <th style = "width: 10%;text-align: right">Qty</th>
                        <th style = "width: 12%;text-align: left">Satuan</th>
                        <th style = "width: 12%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">&nbsp;</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            } else {
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang </th>
                        <th style = "width: 10%;text-align: right">Qty</th>
                        <th style = "width: 12%;text-align: left">Satuan</th>
                        <th style = "width: 12%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">&nbsp;</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            }
        } else {
            $barang = DB::table('tbkeluar')->join('tbkeluardtl', 'tbkeluar.cNomor', '=', 'tbkeluardtl.cNomor')
                ->where($val)
                ->where('tbkeluar.dTanggal', '>=', $awal)
                ->where('tbkeluar.dTanggal', '<=', $akhir)
                ->orderBy('tbkeluar.dTanggal', 'asc')
                ->orderBy('tbkeluar.cNomor', 'asc')
                ->get();
            $data = 'LAPORAN DATA PENGELUARAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
            $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th rowspan="2" style = "width: 5%;vertical-align: middle">No</th>
                        <th rowspan="2" style = "width: 15%;text-align: left">Nomor</th>
                        <th rowspan="2" style = "width: 10%;">Tanggal</th>
                        <th colspan="2" style = "width: 40%;">Tujuan Alokasi</th>
                        <th rowspan="2" style = "width: 20%;text-align: left">Keterangan</th>
                        <th rowspan="2" style = "width: 10%;text-align: right">&nbsp;</th>
                    </tr>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 20%;">Bidang</th>
                        <th style = "width: 20%;">Puskesmas</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
        }
        $content = '';
        $jenis = '';
        $n = 1;
        $sum = 0;
        $nharga = 0;
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            $inv = DB::table('tbinventori')->where('id', $barang[$i]->inventori)->select('harga')->first();
            if (count($inv) > 0) {
                $harga = $inv->harga;
            } else {
                $harga = 0;
            }
            if (Input::get('jns') != '0') {
                if (Input::get('jns') != '0' && $jenis != $barang[$i]->$field[Input::get('jns')]) {
                    if ($i != '0') {
                        $content .= '
                        <tr style = "vertical-align: middle;font-weight: bold">
                            <td style = "width: 3%;">&nbsp;</td>
                            <td style = "width: 5%;">&nbsp;</td>
                            <td style = "width: 10%;">&nbsp;</td>
                            <td style = "width: 40%;text-align: right">Jumlah  </td>
                            <td style = "width: 10%;text-align: right"> ' . number_format($sum) . '</td>
                            <td style = "width: 12%;text-align: left">&nbsp;</td>
                            <td style = "width: 12%;text-align: right">' . Terbilang::format_no_sign($nharga) . '</td>
                            <td style = "width: 8%;text-align: right">&nbsp;</td>
                        </tr> ';
                    }
                    $sum = 0;
                    $nharga = 0;
                    $content .= '
                        <tr style = "vertical-align: middle">
                            <td style = "width: 3%;text-align: center;font-weight: bold"> ' . $n . ' </td>
                            <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->$field[Input::get('jns')] . ' </td>
                        </tr> ';
                    $jenis = $barang[$i]->$field[Input::get('jns')];
                    $m = 1;
                    $n++;
                }
                if (Input::get('jns') == 'cKode') {
                    if ($barang[$i]->cBidang == '') {
                        $alokasi = $barang[$i]->cPuskesmas;
                    } else {
                        $alokasi = $barang[$i]->cBidang;
                    }
                } else {
                    $alokasi = $barang[$i]->cNama;
                }
                $content .= '
                <tr style = "vertical-align: middle;text-align: center">
                    <td style = "width: 3%;">&nbsp;</td>
                    <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                    <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->dTanggal)) . '</td>
                    <td style = "width: 40%;text-align: left">' . $alokasi . '</td>
                    <td style = "width: 10%;text-align: right">' . number_format($barang[$i]->nQty) . '</td>
                    <td style = "width: 12%;">' . $barang[$i]->cSatuan . '</td>
                    <td style = "width: 12%;text-align: right">' . Terbilang::format_no_sign($harga) . '</td>
                    <td style = "width: 8%;text-align: right">&nbsp;</td>
                </tr>
                ';
                $m++;
                $sum += $barang[$i]->nQty;
                $nharga += $harga;
            } else {
                if ($jenis != $barang[$i]->cNomor) {
                    $sum = 0;
                    $content .= '
                    <tr style = "font-weight: bold;text-align: center">
                        <td style = "width: 5%;vertical-align: middle">' . $n . '</td>
                        <td style = "width: 15%;text-align: left">' . $barang[$i]->cNomor . '</td>
                        <td style = "width: 10%">' . date('d-m-Y', strtotime($barang[$i]->dTanggal)) . '</td>
                        <td style = "width: 20%;">' . $barang[$i]->cBidang . '</td>
                        <td style = "width: 20%;">' . $barang[$i]->cPuskesmas . '</td>
                        <td style = "width: 20%;text-align: left">' . $barang[$i]->cKeterangan . '</td>
                        <td style = "width: 10%;text-align: right">&nbsp;</td>
                    </tr>';
                    $jenis = $barang[$i]->cNomor;
                    $m = 1;
                    $n++;
                }
                $content .= '
                <tr style="text-align: center">
                    <td style="width: 10%">' . sprintf("%03d", $m) . '</td>
                    <td style = "width: 25%;text-align: left">' . $barang[$i]->cNama . '</td>
                    <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->nQty) . '</td>
                    <td style = "width: 10%;text-align: left">' . $barang[$i]->cSatuan . '</td>
                    <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($harga) . '</td>
                </tr>';
                $m++;
            }
        }
        if (Input::get('jns') != '0') {
            $content .= '
            <tr style = "vertical-align: middle;font-weight: bold">
                <td style = "width: 3%;">&nbsp;</td>
                <td style = "width: 5%;">&nbsp;</td>
                <td style = "width: 10%;">&nbsp;</td>
                <td style = "width: 40%;text-align: right"> Jumlah  </td>
                <td style = "width: 10%;text-align: right"> ' . number_format($sum) . '</td>
                <td style = "width: 12%;">&nbsp;</td>
                <td style = "width: 12%;text-align: right">' . Terbilang::format_no_sign($nharga) . '</td>
                <td style = "width: 8%;text-align: right">&nbsp;</td>
            </tr> ';
        }
        $footer = "</table>";
        $format = Input::get('format');
        switch ($format) {
            case "0":
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor(PDF_AUTHOR);
                $pdf->SetTitle('Gudang DKK');
                $pdf->SetSubject('Laporan Stok Barang');

                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                $pdf->SetAutoPageBreak(TRUE, 8);

                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


                // CONTENT-------------------------------------------
                $pdf->AddPage('P', 'A4');
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'U', 10);
                $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Write(0, $data, '', 0, 'C', true, 0, false, false, 0);

                $pdf->ln();
                $pdf->SetFont('helvetica', '', 8);
                $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
                $pdf->Output('laporan_pengeluaran' . date('d-m-Y') . ' . pdf', 'I');
                break;
            case "1":
                $header1 = '
                <table cellpadding = "10" border="1">
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">PEMERINTAH KOTA SURABAYA</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">DINAS KESEHATAN KOTA SURABAYA</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">' . $data . '</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7"></td></tr>
                </table>
                ';
                $tabel = $header1 . $header . $content . $footer;
                //return View::make('excel', compact('tabel'));
                Excel::create('LapKeluar_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

        //return '';
    }

    public function report_terima()
    {
        set_time_limit(60);
        $kat = array('cKdSupplier' => 'SUPPLIER', 'cKdSumber' => 'SUMBER ANGGARAN', 'cTahunPengadaan' => 'TAHUN', 'cKode' => 'Barang');
        $field = array('cKdSupplier' => 'cSupplier', 'cKdSumber' => 'cSumber', 'cTahunPengadaan' => 'cTahunPengadaan', 'cKode' => 'cNama');
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        $val = array();
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        if (Input::get('p_sup') != '0') {
            $j = explode('-', Input::get('p_sup'));
            $tmp = array('cKdSupplier' => $j[0]);
            $val += $tmp;
            //array_push($val, $tmp);
        }
        if (Input::get('p_sumber') != '0') {
            $j = explode('-', Input::get('p_sumber'));
            $tmp = array('cKdSumber' => $j[0]);
            $val += $tmp;//array_push($val, $tmp);
        }
        if (Input::get('p_tahun') != '') {
            $tmp = array('cTahunPengadaan' => Input::get('p_tahun'));
            $val += $tmp;//array_push($val, $tmp);
        }
        if (Input::get('p_barang') != '0') {
            $j = explode('-', Input::get('p_barang'));
            $tmp = array('cKode' => $j[0]);
            $val += $tmp;//array_push($val, $tmp);
        }
        if (Input::get('jns') != '0') {
            $barang = DB::table('tbterima')->join('tbterimadtl', 'tbterima.cNomor', '=', 'tbterimadtl.cNomor')
                ->where($val)
                ->where($field[Input::get('jns')], '<>', '')
                ->where('dTanggal', '>=', $awal)
                ->where('dTanggal', '<=', $akhir)
                ->orderBy(Input::get('jns'), 'asc')
                ->orderBy('dTanggal', 'asc')
                ->get();
            $data = 'LAPORAN DATA' . count($barang) . ' PENGELUARAN BARANG PER ' . $kat[Input::get('jns')] . ' PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
            if (Input::get('jns') == 'cKode') {
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 40%;">Supplier Barang</th>
                        <th style = "width: 11%;">Harga</th>
                        <th style = "width: 8%;">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            } else {
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 40%;">Nama Barang</th>
                        <th style = "width: 11%;">Harga</th>
                        <th style = "width: 8%;">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            }
        } else {
            $barang = DB::table('tbterima')->join('tbterimadtl', 'tbterima.cNomor', '=', 'tbterimadtl.cNomor')
                ->where($val)
                ->where('dTanggal', '>=', $awal)
                ->where('dTanggal', '<=', $akhir)
                ->orderBy('dTanggal', 'asc')
                ->orderBy('tbterima.cNomor', 'asc')
                ->get();
            $data = 'LAPORAN PENERIMAAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
            $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center;">
                        <th rowspan="2" style = "width: 3%;">No</th>
                        <th rowspan="2" style = "width: 8%;border-bottom: solid;border-top: solid;border-left: solid;border-right: solid">Nomor</th>
                        <th rowspan="2" style = "width: 6%">Tanggal</th>
                        <th rowspan="2" style = "width: 22%;text-align: left">  Supplier</th>
                        <th colspan="2" style = "width: 18%">Bukti Penerimaan</th>
                        <th colspan="3" style = "width: 26%">Dasar Penerimaan</th>
                        <th colspan="2" style = "width: 18%">Berita Acara</th>
                    </tr>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 12%;">Nomor</th>
                        <th style = "width: 6%">Tanggal</th>
                        <th style = "width: 8%">Jenis</th>
                        <th style = "width: 12%">Nomor</th>
                        <th style = "width: 6%">Tanggal</th>
                        <th style = "width: 12%;">Nomor</th>
                        <th style = "width: 6%;">Tanggal</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
        }
        $content = '';
        $jenis = '';
        $n = 1;
        $sum = array(0, 0, 0);
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            if (Input::get('jns') != '0') {
                if ($jenis != $barang[$i]->$field[Input::get('jns')]) {
                    if ($i != 0) {
                        $content .= '
                        <tr style = "vertical-align: middle;font-weight: bold">
                            <td style = "width: 3%;">&nbsp;</td>
                            <td style = "width: 5%;">&nbsp;</td>
                            <td style = "width: 10%;">&nbsp;</td>
                            <td style = "width: 40%;text-align: right"> Jumlah  </td>
                            <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($sum[0]) . '</td>
                            <td style = "width: 8%;text-align: right">' . number_format($sum[1]) . '</td>
                            <td style = "width: 12%;">&nbsp;</td>
                            <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($sum[2]) . '</td>
                        </tr> ';
                    }
                    $sum = array(0, 0, 0);
                    $content .= '
                    <tr style = "vertical-align: middle">
                        <td style = "width: 3%;text-align: center;font-weight: bold"> ' . $n . ' </td>
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->$field[Input::get('jns')] . ' </td>
                    </tr> ';
                    $jenis = $barang[$i]->$field[Input::get('jns')];
                    $m = 1;
                    $n++;
                }
                if (Input::get('jns') == 'cKode') {
                    $alokasi = $barang[$i]->cSupplier;
                } else {
                    $alokasi = $barang[$i]->cNama;
                }
                $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->dTanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $alokasi . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->nHarga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->nQty) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->cSatuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->nSTotal) . '</td>
                    </tr>';
                $m++;
                $sum = array($sum[0] + $barang[$i]->nHarga, $sum[1] + $barang[$i]->nQty, $sum[2] + $barang[$i]->nSTotal);
            } else {
                if ($jenis != $barang[$i]->cNomor) {
                    $sum = 0;
                    $content .= '
                     <tr style = "font-weight: bold;text-align: center">
                        <td style = "width: 3%;">' . $n . '</td>
                        <td style = "width: 8%;">' . $barang[$i]->cNomor . '</td>
                        <td style = "width: 6%;">' . date('d-m-Y', strtotime($barang[$i]->dTanggal)) . '</td>
                        <td style = "width: 22%;text-align: left">' . $barang[$i]->cSupplier . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->cNoBukti . '</td>
                        <td style = "width: 6%;">' . date('d-m-Y', strtotime($barang[$i]->dTglBukti)) . '</td>
                        <td style = "width: 8%;">' . $barang[$i]->cJnsSurat . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->cNoSurat . '</td>
                        <td style = "width: 6%;">' . date('d-m-Y', strtotime($barang[$i]->dTglSurat)) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->cNoAcara . '</td>
                        <td style = "width: 6%;">' . date('d-m-Y', strtotime($barang[$i]->dTglAcara)) . '</td>
                    </tr>';
                    $jenis = $barang[$i]->cNomor;
                    $m = 1;
                    $n++;
                }
                $content .= '
                <tr style="text-align: center">
                    <td style="width: 3%">&nbsp;</td>
                    <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                    <td style = "width: 30%;text-align: left">' . $barang[$i]->cNama . '</td>
                    <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->nHarga) . '</td>
                    <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->nQty) . '</td>
                    <td style = "width: 10%;text-align: left">' . $barang[$i]->cSatuan . '</td>
                    <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->nSTotal) . '</td>
                </tr>';
                $m++;
            }

        }
        if (Input::get('jns') != '0') {
            $content .= '
            <tr style = "vertical-align: middle;font-weight: bold">
                <td style = "width: 3%;">&nbsp;</td>
                <td style = "width: 5%;">&nbsp;</td>
                <td style = "width: 10%;">&nbsp;</td>
                <td style = "width: 40%;text-align: right"> Jumlah  </td>
                <td style = "width: 11%;text-align: right"> ' . Terbilang::format_no_sign($sum[0]) . '</td>
                <td style = "width: 8%;text-align: right"> ' . number_format($sum[1]) . '</td>
                <td style = "width: 12%;">&nbsp;</td>
                <td style = "width: 11%;text-align: right"> ' . Terbilang::format_no_sign($sum[2]) . '</td>
            </tr> ';
        }
        $footer = "</table>";
        $format = Input::get('format');
        switch ($format) {
            case "0":
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor(PDF_AUTHOR);
                $pdf->SetTitle('Gudang DKK');
                $pdf->SetSubject('Laporan Stok Barang');

                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                $pdf->SetAutoPageBreak(TRUE, 8);

                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


                // CONTENT-------------------------------------------
                $pdf->SetFont('helvetica', 'B', 10);
                if (Input::get('jns') != '0') {
                    $pdf->AddPage('P', 'A4');
                } else {
                    $pdf->AddPage('L', 'A4');
                }
                $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'U', 10);
                $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Write(0, $data, '', 0, 'C', true, 0, false, false, 0);

                $pdf->ln();
                $pdf->SetFont('helvetica', '', 8);
                $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
                $pdf->Output('laporan_penerimaan' . date('d-m-Y') . ' . pdf', 'I');
                break;
            case "1":
                $header1 = '
                <table cellpadding = "10" border="1">
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">PEMERINTAH KOTA SURABAYA</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">DINAS KESEHATAN KOTA SURABAYA</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">' . $data . '</td></tr>
                    <tr style="font-weight: bold;"><td colspan="7"></td></tr>
                </table>
                ';
                $tabel = $header1 . $header . $content . $footer;
                //return View::make('excel', compact('tabel'));
                Excel::create('LapTerima_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Penerimaan', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

        //return '';
    }

    public function report_stok()
    {
        set_time_limit(300);
        //$time_start = microtime(true);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        if (Input::get('jns') == 1) {
            if (Input::get('p_jns') != 0) {
                $j = explode('-', Input::get('p_jns'));
                $barang = DB::table('tbstock')->join('tbbarang', 'tbstock.cKode', '=', 'tbbarang.cKode')->where('cKdJenis', $j[0])->select('tbstock.cKode', 'tbstock.cNama', 'cKdJenis', 'tbstock.cSatuan', 'cJenis', DB::raw('sum(nQtyReal) as sum'))->orderBy('cKdJenis', 'asc')->orderBy('tbstock.cNama', 'asc')->groupBy('tbstock.cKode')->get();
            } else {
                $barang = DB::table('tbstock')->join('tbbarang', 'tbstock.cKode', '=', 'tbbarang.cKode')->select('tbstock.cKode', 'tbstock.cNama', 'tbstock.cSatuan', 'cKdJenis', 'cJenis', DB::raw('sum(nQtyReal) as sum'))->orderBy('cKdJenis', 'asc')->orderBy('tbstock.cNama', 'asc')->groupBy('tbstock.cKode')->get();
            }
            $data = 'LAPORAN DATA STOCK BARANG PER JENIS BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s/d ' . date('d-m-Y', strtotime($akhir));
        } else {
            if (Input::get('p_jns') != 0) {
                $j = explode('-', Input::get('p_jns'));
                $barang = DB::table('tbstock')->join('tbbarang', 'tbstock.cKode', '=', 'tbbarang.cKode')->where('cKdJenis', $j[0])->select('tbstock.cKode', 'tbstock.cNama', 'tbstock.cSatuan', 'cJenis', DB::raw('sum(nQtyReal) as sum'))->orderBy('tbstock.cNama', 'asc')->groupBy('tbstock.cKode')->get();
            } else {
                $barang = DB::table('tbstock')->join('tbbarang', 'tbstock.cKode', '=', 'tbbarang.cKode')->select('tbstock.cKode', 'tbstock.cNama', 'tbstock.cSatuan', 'cJenis', DB::raw('sum(nQtyReal) as sum'))->orderBy('tbstock.cNama', 'asc')->groupBy('tbstock.cKode')->get();
            }
            $data = 'LAPORAN DATA STOCK BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . 's/d ' . date('d-m-Y', strtotime($akhir));
        }
        //opsi 1
        //$s_awal = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '<', $awal)->select(DB::raw('cKode,sum(nQtyReal) as sum'))->get();
        //$s_msk = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->where('cNomor', 'NOT LIKE', 'KLR%')->select(DB::raw('cKode,sum(nQtyReal) as sum'))->get();
        //$s_klr = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->where('cNomor', 'LIKE', 'KLR%')->select(DB::raw('cKode,sum(nQtyReal) as sum'))->get();
        $content = '';
        $jenis = '';
        $sum = array(0, 0, 0, 0);
        for ($i = 0; $i < count($barang); $i++) {
            if (Input::get('jns') == 1 && $jenis != $barang[$i]->cJenis) {
                if ($i > 0) {
                    $content .= '
                <tr style = "vertical-align: middle;text-align: right;font-weight: bold">
                        <td style="width: 10%;text-align: right;">&nbsp;</td>
                        <td style="width: 38%;font-weight: bold">&nbsp;</td>
                        <td style="width: 12%;">Jumlah </td>
                        <td style="width: 10%;">' . number_format($sum[0]) . '</td>
                        <td style="width: 10%;">' . number_format($sum[1]) . '</td>
                        <td style="width: 10%;">' . number_format($sum[2]) . '</td>
                        <td style="width: 10%;">' . number_format($sum[3]) . '</td>
                    </tr> ';
                }
                $sum = array(0, 0, 0, 0);
                $content .= '
                    <tr style = "vertical-align: middle">
                        <td style = "width: 10%;text-align: right;font-weight: bold"> Jenis </td>
                        <td colspan = "6" style = "font-weight: bold"> ' . $barang[$i]->cJenis . '</td>
                    </tr> ';
                $jenis = $barang[$i]->cJenis;
            }
            //opsi 1
            /*$a = 0;
            $b = 0;
            $c = 0;
            for ($j = 0; $j < count($s_awal); $j++) {
                if ($s_awal[$j]->cKode == $barang[$i]->cKode) {
                    $a = $s_awal[$j]->sum;
                    break;
                }
            }
            for ($j = 0; $j < count($s_msk); $j++) {
                if ($s_msk[$j]->cKode == $barang[$i]->cKode) {
                    $b = $s_msk[$j]->sum;
                    break;
                }
            }
            for ($j = 0; $j < count($s_klr); $j++) {
                if ($s_klr[$j]->cKode == $barang[$i]->cKode) {
                    $c = $s_klr[$j]->sum;
                    break;
                }
            }*/
            //opsi 2
            //$s_awal = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '<', $awal)->where('cKode', $barang[$i]->cKode)->sum('nQtyReal');
            //$s_msk = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->where('cKode', $barang[$i]->cKode)->where('cNomor', 'NOT LIKE', 'KLR%')->sum('nQtyReal');
            //$s_klr = DB::table('tbstock')->groupBy('cKode')->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->where('cKode', $barang[$i]->cKode)->where('cNomor', 'LIKE', 'KLR%')->sum('nQtyReal');
            $s_awal0 = DB::table('tbadjdtl')->join('tbadj', 'tbadjdtl.cNomor', '=', 'tbadj.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '<', $awal)->sum('nQty');
            $s_awal1 = DB::table('tbterimadtl')->join('tbterima', 'tbterimadtl.cNomor', '=', 'tbterima.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '<', $awal)->sum('nQty');
            $s_awal2 = DB::table('tbkeluardtl')->join('tbkeluar', 'tbkeluardtl.cNomor', '=', 'tbkeluar.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '<', $awal)->sum('nQty');
            $s_msk1 = DB::table('tbterimadtl')->join('tbterima', 'tbterimadtl.cNomor', '=', 'tbterima.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->sum('nQty');
            $s_msk0 = DB::table('tbadjdtl')->join('tbadj', 'tbadjdtl.cNomor', '=', 'tbadj.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->sum('nQty');
            $s_klr = DB::table('tbkeluardtl')->join('tbkeluar', 'tbkeluardtl.cNomor', '=', 'tbkeluar.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', 0)->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->sum('nQty');
            $a = $s_awal0 + $s_awal1 - $s_awal2;
            $b = $s_msk0 + $s_msk1;
            $c = $s_klr * (-1);
            $content .= '
                <tr style="vertical-align: middle;text-align: right">
                <td style="width: 10%;text-align: center"> ' . $barang[$i]->cKode . '</td>
                <td style="width: 38%;text-align: left"> ' . $barang[$i]->cNama . '</td>
                <td style="width: 12%;text-align: center"> ' . $barang[$i]->cSatuan . '</td>
                <td style="width: 10%">' . number_format($a) . '</td>
                <td style="width: 10%">' . number_format($b) . '</td>
                <td style="width: 10%">' . number_format($c) . '</td>
                <td style="width: 10%;">' . number_format($a + $b + $c) . '</td>
            </tr>';
            $sum = array($sum[0] + $a, $sum[1] + $b, $sum[2] + $c, $sum[3] + ($a + $b + $c));
        }
        $content .= '
            <tr style = "vertical-align: middle;text-align: right;font-weight: bold">
                <td style="width: 10%;text-align: right;">&nbsp;</td>
                <td style="width: 38%;font-weight: bold">&nbsp;</td>
                <td style="width: 12%;text-align: right"> Jumlah </td>
                <td style="width: 10%;text-align: right">' . number_format($sum[0]) . '</td>
                <td style="width: 10%;text-align: right">' . number_format($sum[1]) . '</td>
                <td style="width: 10%;text-align: right">' . number_format($sum[2]) . '</td>
                <td style="width: 10%;text-align: right">' . number_format($sum[3]) . '</td>
            </tr> ';
        $footer = "</table>";
        if (Input::get('format') == 1) {
            $header = '
                <table cellpadding = "10" border="1">
                <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">PEMERINTAH KOTA SURABAYA</td></tr>
                <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">DINAS KESEHATAN KOTA SURABAYA</td></tr>
                <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965</td></tr>
                <tr style="font-weight: bold;"><td colspan="7" style="text-align: center">' . $data . '</td></tr>
                <tr style="font-weight: bold;"><td colspan="7"></td></tr>
                <thead>
                    <tr style="font-weight: bold;text-align: center">
                        <th style="width: 10%;">Kode</th>
                        <th style="width: 38%;text-align: left">Nama Barang </th>
                        <th style="width: 12%;">Satuan</th>
                        <th style="width: 10%;">Stok Awal </th>
                        <th style="width: 10%;">Stok Masuk </th>
                        <th style="width: 10%;">Stok Keluar </th>
                        <th style="width: 10%;">Stok Akhir </th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            $tabel = $header . $content . $footer;
            //return View::make('excel', compact('tabel'));
            Excel::create('LaporanStok_' . date('d-m-Y'), function ($excel) use ($tabel) {

                $excel->sheet('Stok Barang', function ($sheet) use ($tabel) {

                    $sheet->loadView('excel', array('tabel' => $tabel));
                });
            })->export('xls');
        } else {
            $header = '
                <table cellpadding = "3">
                <thead>
                    <tr style="font-weight: bold;text-align: center">
                        <th style="width: 10%;border-bottom: solid">Kode</th>
                        <th style="width: 38%;border-bottom: solid;text-align: left">Nama Barang </th>
                        <th style="width: 12%;border-bottom: solid">Satuan</th>
                        <th style="width: 10%;border-bottom: solid;">Stok Awal </th>
                        <th style="width: 10%;border-bottom: solid;">Stok Masuk </th>
                        <th style="width: 10%;border-bottom: solid;">Stok Keluar </th>
                        <th style="width: 10%;border-bottom: solid;">Stok Akhir </th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(PDF_AUTHOR);
            $pdf->SetTitle('Gudang DKK');
            $pdf->SetSubject('Laporan Stok Barang');

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);


            // CONTENT-------------------------------------------
            $pdf->AddPage('P', 'A4');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'U', 10);
            $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Write(0, $data, '', 0, 'C', true, 0, false, false, 0);

            $pdf->ln();
            $pdf->SetFont('helvetica', '', 8);
            $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
            $pdf->Output('Stock_' . date('d-m-Y') . ' . pdf', 'I');
        }
        //$time_end = microtime(true);
        //$execution_time = ($time_end - $time_start);
        //echo '<b>Total Execution Time:</b> '.$execution_time.' Sec';

    }


    public function exp()
    {
        $data1 = DB::table('tbjenis')->get();
        Excel::create('DataBarang_' . date('Y-m-d'), function ($excel) use ($data1) {

            $excel->sheet('Stok Barang', function ($sheet) use ($data1) {

                $sheet->fromArray(json_decode(json_encode($data1), true));

            });
        })->export('pdf');
    }

    public function exp_excel($id)
    {
        $data1 = DB::table('tb' . $id)->get();
        Excel::create('Data' . $id . '_' . date('Y-m-d'), function ($excel) use ($data1) {
            $excel->sheet('Data', function ($sheet) use ($data1) {

                $sheet->prependRow(array('prepended', 'prepended'));
                $sheet->fromArray(json_decode(json_encode($data1), true));
            });
        })->export('xls');
    }

    public function exp_pdf($id)
    {
        if ($id == 'barang') {
            $field = 'nama';
        } else {
            $field = $id;
        }
        $data1 = DB::table('tb' . $id)->get();
        $header = '
                <table cellpadding = "5">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 20%;border-bottom: solid;"> Kode</th>
                        <th style = "width: 80%;border-bottom: solid;text-align: left"> ' . ucfirst($id) . '</th>
                    </tr>
                    <tr><td></td><td></td></tr>
                </thead>
                ';
        $content = '';
        for ($i = 0; $i < count($data1); $i++) {
            $content .= '
                <tr style = "vertical-align: middle">
                <td style = "width: 20%;text-align: center"> ' . $data1[$i]->id . '</td>
                <td style = "width: 80%"> ' . $data1[$i]->$field . '</td>
            </tr>
                ';
        }
        $footer = "</table>";
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // CONTENT-------------------------------------------
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, 'LAPORAN DATA MASTER ' . strtoupper($id), '', 0, 'C', true, 0, false, false, 0);

        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output(ucfirst($id) . '_' . date('d-m-Y') . ' . pdf', 'I');
    }
}
