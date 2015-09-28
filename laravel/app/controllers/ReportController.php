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

    public function sbbk($id)
    {
        $data = DB::table('pkm_alokasi')->where('nomor', $id)->first();
        $dtl = DB::table('pkm_alokasidtl')->where('nomor', $id)->orderBy('id', 'asc')->get();
        if ($data->tujuan == 'intern') {
            $tjn = $data->nama_unit;
        } else {
            $tjn = $data->nama_unit;
        }

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
                <td style="width: 20%">:  ' . $data->nama_unit . '</td>
                <td colspan="3" style="width: 30%"></td>
                <td colspan="2" style="width: 40%;text-align: right">Nomor :  ' . $id . '</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:  ' . date('d F Y', strtotime($data->tanggal)) . '</td>
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
        $sm=0;
        for ($i = 0; $i < count($dtl); $i++) {
            if ($dtl[$i]->harga != '') {
                $harga = $dtl[$i]->harga;
            } else {
                $harga = 0;
            }
            if ($dtl[$i]->tipe == 'hp') {
                $brg = DB::table('pkm_inventori')->where('id', $dtl[$i]->id_inventori)->first();
                if ($i < (count($dtl) - 1) && $dtl[$i+1]->tipe == 'hp') {
                    $brg2 = DB::table('pkm_inventori')->where('id', $dtl[$i+1]->id_inventori)->first();
                    if ($brg->id_barang == $brg2->id_barang) {
                        $tot += ($harga * $dtl[$i]->jumlah);
                        $sm+=$dtl[$i]->jumlah;
                    } else {
                        $tot += ($harga * $dtl[$i]->jumlah);
                        $sm+=$dtl[$i]->jumlah;
                        $content .= '
                    <tr style="text-align: center;">
                        <td style="width: 5%;">' . $n . '</td>
                        <td style="width: 5%;">' . $brg->id_barang . '</td>
                        <td style="width: 50%;text-align: left">' . $brg->nama_barang . '</td>
                        <td style="width: 12%;text-align: right">' . $sm . '&nbsp;</td>
                        <td style="width: 8%;text-align: left">&nbsp;' . $brg->nama_satuan . '</td>
                        <td style="width: 15%;text-align: right">' . Terbilang::format_no_sign($tot + ($tot / 100 * $brg->ppn)) . '&nbsp;</td>
                        <td style="width: 12%;">&nbsp;' . $dtl[$i]->keterangan . '</td>
                    </tr>';
                        $tot = 0;
                        $sm=0;
                        $n++;
                    }
                } else {
                    $tot += ($harga * $dtl[$i]->jumlah);
                    $sm+=$dtl[$i]->jumlah;
                    $content .= '
                <tr style="text-align: center;">
                    <td style="width: 5%;">' . $n . '</td>
                    <td style="width: 5%;">' . $brg->id_barang . '</td>
                    <td style="width: 50%;text-align: left">' . $brg->nama_barang . '</td>
                    <td style="width: 12%;text-align: right">' . $sm . '&nbsp;</td>
                    <td style="width: 8%;text-align: left">&nbsp;' . $brg->nama_satuan . '</td>
                    <td style="width: 15%;text-align: right">' . Terbilang::format_no_sign($tot + ($tot / 100 * $brg->ppn)) . '&nbsp;</td>
                    <td style="width: 12%;">&nbsp;' . $dtl[$i]->keterangan . '</td>
                </tr>';
                    $n++;
                    $tot = 0;
                    $sm=0;
                }
            } else {
                $brg = DB::table('aset_data')->where('id', $dtl[$i]->id_inventori)->first();
                $ttl = ($brg->h_satuan * $dtl[$i]->jumlah);
                $content .= '
                <tr style="text-align: center;">
                    <td style="width: 5%;">' . $n . '</td>
                    <td style="width: 5%;">' . $brg->id_aset . '</td>
                    <td style="width: 50%;text-align: left">' . $brg->nama . '</td>
                    <td style="width: 12%;text-align: right">' . $dtl[$i]->jumlah . '&nbsp;</td>
                    <td style="width: 8%;text-align: left">&nbsp;' . $brg->satuan . '</td>
                    <td style="width: 15%;text-align: right">' . Terbilang::format_no_sign($ttl + ($ttl / 100 * $brg->ppn)) . '&nbsp;</td>
                    <td style="width: 12%;">&nbsp;' . $dtl[$i]->keterangan . '</td>
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
                <td colspan="4">Surabaya, ' . date('d F Y', strtotime($data->tanggal)) . '</td>
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
                ->where('lPosted', '<>', 0)
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
                ->where('lPosted', '<>', 0)
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
                if (is_numeric($inv->harga)) {
                    $harga = $inv->harga;
                } else {
                    $harga = 0;
                }
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
                ->where('lPosted', '<>', 0)
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
                ->where('lPosted', '<>', 0)
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
                $barang = DB::table('tbbarang')->where('cKdJenis', $j[0])->orderBy('cKdJenis', 'asc')->orderBy('cNama', 'asc')->remember(60)->get();
            } else {
                $barang = DB::table('tbbarang')->orderBy('cKdJenis', 'asc')->orderBy('cNama', 'asc')->remember(60)->get();
            }
            $data = 'LAPORAN DATA STOCK BARANG PER JENIS BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s/d ' . date('d-m-Y', strtotime($akhir));
        } else {
            if (Input::get('p_jns') != 0) {
                $j = explode('-', Input::get('p_jns'));
                $barang = DB::table('tbbarang')->where('cKdJenis', $j[0])->orderBy('cNama', 'asc')->remember(60)->get();
            } else {
                $barang = DB::table('tbbarang')->orderBy('cNama', 'asc')->remember(60)->get();
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
            $s_msk0 = DB::table('tbadjdtl')->join('tbadj', 'tbadjdtl.cNomor', '=', 'tbadj.cNomor')->groupBy('cKode')->where('cKode', $barang[$i]->cKode)->where('lPosted', '<>', '0')->where('dTanggal', '>=', $awal)->where('dTanggal', '<=', $akhir)->sum('nQty');
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
        if ($id == 'pkm_supplier') {
            $data1 = DB::table('pkm_supplier')->where('id_puskesmas', Auth::user()->id_puskesmas)->get();
        } else {
            $data1 = DB::table('tb' . $id)->get();
        }
        $data = json_decode(json_encode($data1), true);
        $key = array_keys($data[0]);
        //$columns = Schema::getColumnListing($id);
        $header = '
                <table cellpadding = "10" border="1">
                <tr style="font-weight: bold;"><td colspan="' . count($key) . '" style="text-align: center">PEMERINTAH KOTA SURABAYA</td></tr>
                <tr style="font-weight: bold;"><td colspan="' . count($key) . '" style="text-align: center">DINAS KESEHATAN KOTA SURABAYA</td></tr>
                <tr style="font-weight: bold;"><td colspan="' . count($key) . '" style="text-align: center">JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965</td></tr>
                <tr style="font-weight: bold;"><td colspan="' . count($key) . '" style="text-align: center">DATA ' . strtoupper($id) . '</td></tr>
                <tr style="font-weight: bold;"><td colspan="' . count($key) . '"></td></tr>
                <tr style="font-weight: bold;text-align: center">
                ';
        for ($i = 0; $i < count($key); $i++) {
            $header .= '<th>' . $key[$i] . '</th>';
        }
        $header .= '</tr>';
        $content = '';
        //$tabel = dd($columns);
        for ($i = 0; $i < count($data); $i++) {
            $content .= '<tr>';
            for ($j = 0; $j < count($key); $j++) {
                $t = $key[$j];
                $content .= '<td>' . $data[$i][$t] . '</td>';
            }
            $content .= '</tr>';
        }
        $footer = '</table>';
        $tabel = $header . $content . $footer;
        //
        //return View::make('excel',compact('tabel'));
        Excel::create('Data' . $id . '_' . date('Y-m-d'), function ($excel) use ($tabel) {
            $excel->sheet('Data', function ($sheet) use ($tabel) {
                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function exp_pdf($id)
    {
        set_time_limit(60);
        if ($id == 'pkm_supplier') {
            $data1 = DB::table('pkm_supplier')->where('id_puskesmas', Auth::user()->id_puskesmas)->get();
        } else {
            $data1 = DB::table('tb' . $id)->get();
        }
        $data = json_decode(json_encode($data1), true);
        $key = array_keys($data[0]);
        //$columns = Schema::getColumnListing($id);
        $header = '
                <table cellpadding = "10" border="0">
                <thead>
                <tr style="font-weight: bold;text-align: center">
                ';
        for ($i = 0; $i < count($key); $i++) {
            $header .= '<th>' . $key[$i] . '</th>';
        }
        $header .= '</tr></thead>';
        $content = '';
        //$tabel = dd($columns);
        for ($i = 0; $i < count($data); $i++) {
            $content .= '<tr>';
            for ($j = 0; $j < count($key); $j++) {
                $t = $key[$j];
                $content .= '<td>' . $data[$i][$t] . '</td>';
            }
            $content .= '</tr>';
        }
        $footer = '</table>';
        $tabel = $header . $content . $footer;
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
        $pdf->writeHTML($tabel, true, false, false, false, '');
        $pdf->Output(ucfirst($id) . '_' . date('d-m-Y') . ' . pdf', 'I');
        //return View::make('excel',compact('tabel'));
    }

    //Aplikasi Gudang
    //Puskesmas
    public function cetak_notapkm($id)
    {
        $data = DB::table('pkm_alokasi')->where('nomor', $id)->first();
        $dtl = DB::table('pkm_alokasidtl')->where('nomor', $id)->get();

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
        $pdf->AddPage('P', 'F4');
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
                <td>:  ' . $data->nama_unit . '</td>
                <td style="width: 40%"></td>
                <td style="width: 10%">Nomor</td>
                <td>:  ' . $id . '</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:  ' . date('d F Y', strtotime($data->tanggal)) . '</td>
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
            $inv = DB::table('pkm_inventori')->where('id', $dtl[$i]->id_inventori)->select('id_barang', 'nama_barang', 'nama_satuan', 'harga')->first();
            if (count($inv) > 0) {
                $harga = $inv->harga;
            } else {
                $harga = 0;
            }
            if ($i < (count($dtl) - 1)) {
                if ($dtl[$i]->id_barang == $dtl[$i + 1]->id_barang) {
                    $tot += ($harga * $dtl[$i]->jumlah);
                } else {
                    $tot += ($harga * $dtl[$i]->jumlah);
                    $content .= '
                    <tr style="text-align: center;">
                        <td style="width: 10%;">' . $n . '</td>
                        <td style="width: 10%;">' . $inv->id_barang . '</td>
                        <td style="width: 40%;text-align: left">' . $inv->nama_barang . '</td>
                        <td style="width: 10%;text-align: right">' . $dtl[$i]->jumlah . '</td>
                        <td style="width: 10%;text-align: left">' . $inv->nama_satuan . '</td>
                        <td style="width: 10%;text-align: right">' . Terbilang::format_no_sign($tot) . '</td>
                        <td style="width: 10%;">' . $dtl[$i]->keterangan . '</td>
                    </tr>';
                    $tot = 0;
                    $n++;
                }
            } else {
                $tot += ($harga * $dtl[$i]->jumlah);
                $content .= '
                <tr style="text-align: center;">
                    <td style="width: 10%;">' . $n . '</td>
                    <td style="width: 10%;">' . $inv->id_barang . '</td>
                    <td style="width: 40%;text-align: left">' . $inv->nama_barang . '</td>
                    <td style="width: 10%;text-align: right">' . $dtl[$i]->jumlah . '</td>
                    <td style="width: 10%;text-align: left">' . $inv->nama_satuan . '</td>
                    <td style="width: 10%;text-align: right">' . Terbilang::format_no_sign($tot) . '</td>
                    <td style="width: 10%;">' . $dtl[$i]->keterangan . '</td>
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
                <td>Surabaya, ' . date('d-F-Y', strtotime($data->tanggal)) . '</td>
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
        $space = '
        <table>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        </table>';
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Ln();
        $pdf->writeHTML($ttd, true, false, false, false, '');
        $pdf->writeHTML($space, true, false, false, false, '');
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, 'TANDA TERIMA BARANG', '', 0, 'C', true, 0, false, false, 0);
        $pdf->ln();
        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Ln();
        $pdf->writeHTML($ttd, true, false, false, false, '');
        $pdf->Output('Nota_' . $id . '.pdf', 'I');
    }

    public function lap_terima_pkm()
    {
        set_time_limit(300);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('status', '1')
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center;">
                        <th style = "width: 5%;">No</th>
                        <th style = "width: 15%;">Nomor</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 35%;text-align: left">  Puskesmas</th>
                        <th style = "width: 35%;text-align: left">  Supplier</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nomor) {
                        $content .= '
                        <tr style = "font-weight: bold;text-align: left">
                            <td style = "width: 5%;text-align: center">' . $n . '</td>
                            <td style = "width: 15%;">' . $barang[$i]->nomor . '</td>
                            <td style = "width: 10%;text-align: center">' . $barang[$i]->tanggal . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_puskesmas . '-' . $barang[$i]->nama_puskesmas . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_supplier . '-' . $barang[$i]->nama_supplier . '</td>
                        </tr>';
                        $jenis = $barang[$i]->nomor;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style="text-align: center">
                        <td style="width: 5%">&nbsp;</td>
                        <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 30%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 10%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 10%;text-align: left">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                }
                break;
            case "id_supplier":
                $sup = explode('-', Input::get('p_sup'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_supplier', $op, $sup[0])
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.id_supplier', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER SUPPLIER PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_supplier) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_supplier . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_supplier;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_sumber":
                $sup = explode('-', Input::get('p_sumber'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_sumber', $op, $sup[0])
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.id_sumber', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER SUMBER PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_sumber) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_sumber . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_sumber;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_barang":
                $sup1 = Input::get('p_jb');
                if ($sup1 == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $sup = Input::get('p_b');
                if ($sup == '') {
                    $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('jenis', $op, $sup1)
                        ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                        ->where('pkm_masuk.tanggal', '>=', $awal)
                        ->where('pkm_masuk.tanggal', '<=', $akhir)
                        ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'id_barang', 'nama_barang', 'nama_satuan')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_masuk.tanggal', 'asc')
                        ->get();
                } else {
                    $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('id_barang', $sup)
                        ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                        ->where('pkm_masuk.tanggal', '>=', $awal)
                        ->where('pkm_masuk.tanggal', '<=', $akhir)
                        ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'id_barang', 'nama_barang', 'nama_satuan')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_masuk.tanggal', 'asc')
                        ->get();
                }
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Supplier</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_barang) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_barang . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_barang;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_supplier . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "tahun":
                $sup = Input::get('p_tahun');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tahun', $op, $sup)
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.tahun', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER TAHUN PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->tahun) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->tahun . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->tahun;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = '</table>';
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
                Excel::create('LaporanStok_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok Barang', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

    }

    public function lap_keluar_pkm()
    {
        set_time_limit(300);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_alokasi.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENGELUARAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center;">
                        <th style = "width: 5%;">No</th>
                        <th style = "width: 15%;">Nomor</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 70%;text-align: left">  Unit</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nomor) {
                        $content .= '
                        <tr style = "font-weight: bold;text-align: left">
                            <td style = "width: 5%;text-align: center">' . $n . '</td>
                            <td style = "width: 15%;">' . $barang[$i]->nomor . '</td>
                            <td style = "width: 10%;text-align: center">' . $barang[$i]->tanggal . '</td>
                            <td style = "width: 70%;">' . $barang[$i]->id_unit . '-' . $barang[$i]->nama_unit . '</td>
                        </tr>';
                        $jenis = $barang[$i]->nomor;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style="text-align: center">
                        <td style="width: 5%">&nbsp;</td>
                        <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 30%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 10%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 10%;text-align: left">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                }
                break;
            case "id_unit":
                $sup = Input::get('p_unit');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.id_unit', $op, $sup)
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_alokasi.id_unit', 'asc')
                    ->orderBy('pkm_alokasi.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENGELUARAN PUSKESMAS PER UNIT PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_unit) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_unit . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_unit;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_barang":
                $sup1 = Input::get('p_jb');
                if ($sup1 == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $sup = Input::get('p_b');
                if ($sup == '') {
                    $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('jenis', $op, $sup1)
                        ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                        ->where('pkm_alokasi.tanggal', '>=', $awal)
                        ->where('pkm_alokasi.tanggal', '<=', $akhir)
                        ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'id_barang', 'nama_barang', 'nama_satuan')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_alokasi.tanggal', 'asc')
                        ->get();
                } else {
                    $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('id_barang', $sup)
                        ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                        ->where('pkm_alokasi.tanggal', '>=', $awal)
                        ->where('pkm_alokasi.tanggal', '<=', $akhir)
                        ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'id_barang', 'nama_barang', 'nama_satuan')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_alokasi.tanggal', 'asc')
                        ->get();
                }
                $data = 'LAPORAN PENGELUARAN PUSKESMAS PER BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Puskesmas</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_barang) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_barang . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_barang;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_unit . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = '</table>';
        switch ($format) {
            case "0":
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor(PDF_AUTHOR);
                $pdf->SetTitle('Gudang DKK');
                $pdf->SetSubject('Laporan Pengeluaran Barang');

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
                    $pdf->AddPage('P', 'A4');
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
                $pdf->Output('lap_keluar_all' . date('d-m-Y') . ' . pdf', 'I');
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
                Excel::create('Lapkeluarall_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Pengeluaran', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

    }

    public function lap_stok_pkm()
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
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_inventori')->groupBy('id_barang')->where('id_puskesmas', Auth::user()->id_puskesmas)->get();
                $data = 'LAPORAN DATA STOCK BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . 's/d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "3">
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
                $s_awal1 = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '<', $awal)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_awal2 = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.tanggal', '<', $awal)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_msk = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_klr = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $content = '';
                $sum = array(0, 0, 0, 0);
                for ($i = 0; $i < count($barang); $i++) {
                    $a = 0;
                    $a1 = 0;
                    $b = 0;
                    $c = 0;
                    for ($j = 0; $j < count($s_awal1); $j++) {
                        if ($s_awal1[$j]->id_barang == $barang[$i]->id_barang) {
                            $a = $s_awal1[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_awal2); $j++) {
                        if ($s_awal2[$j]->id_barang == $barang[$i]->id_barang) {
                            $a1 = $s_awal2[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_msk); $j++) {
                        if ($s_msk[$j]->id_barang == $barang[$i]->id_barang) {
                            $b = $s_msk[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_klr); $j++) {
                        if ($s_klr[$j]->id_barang == $barang[$i]->id_barang) {
                            $c = $s_klr[$j]->sum * (-1);
                            break;
                        }
                    }
                    $content .= '
                        <tr style="vertical-align: middle;text-align: right">
                            <td style="width: 10%;text-align: center"> ' . $barang[$i]->id_barang . '</td>
                            <td style="width: 38%;text-align: left"> ' . $barang[$i]->nama_barang . '</td>
                            <td style="width: 12%;text-align: center"> ' . $barang[$i]->nama_satuan . '</td>
                            <td style="width: 10%">' . number_format($a - $a1) . '</td>
                            <td style="width: 10%">' . number_format($b) . '</td>
                            <td style="width: 10%">' . number_format($c) . '</td>
                            <td style="width: 10%;">' . number_format($a - $a1 + $b + $c) . '</td>
                        </tr>';
                    $sum = array($sum[0] + $a - $a1, $sum[1] + $b, $sum[2] + $c, $sum[3] + ($a - $a1 + $b + $c));
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
                break;
            case "1":
                $sup = Input::get('p_jb');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_inventori')->where('jenis', $op, $sup)->where('id_puskesmas', Auth::user()->id_puskesmas)->groupBy('id_barang')->orderBy('jenis', 'asc')->get();
                $data = 'DATA STOCK BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . 's/d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "3">
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
                $s_awal1 = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '<', $awal)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_awal2 = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.tanggal', '<', $awal)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_msk = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_klr = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', Auth::user()->id_puskesmas)
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $content = '';
                $cjenis = '';
                $m = 1;
                $n = 1;
                $sum = array(0, 0, 0, 0);
                for ($i = 0; $i < count($barang); $i++) {
                    if ($cjenis != $barang[$i]->jenis) {
                        if ($i != 0) {
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
                        }
                        $sum = array(0, 0, 0, 0);
                        $content .= '
                        <tr style = "vertical-align: middle">
                            <td style = "width: 3%;text-align: center;font-weight: bold"> ' . $n . ' </td>
                            <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->jenis . ' </td>
                        </tr> ';
                        $cjenis = $barang[$i]->jenis;
                        $m = 1;
                        $n++;
                    }
                    $a = 0;
                    $a1 = 0;
                    $b = 0;
                    $c = 0;
                    for ($j = 0; $j < count($s_awal1); $j++) {
                        if ($s_awal1[$j]->id_barang == $barang[$i]->id_barang) {
                            $a = $s_awal1[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_awal2); $j++) {
                        if ($s_awal2[$j]->id_barang == $barang[$i]->id_barang) {
                            $a1 = $s_awal2[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_msk); $j++) {
                        if ($s_msk[$j]->id_barang == $barang[$i]->id_barang) {
                            $b = $s_msk[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_klr); $j++) {
                        if ($s_klr[$j]->id_barang == $barang[$i]->id_barang) {
                            $c = $s_klr[$j]->sum * (-1);
                            break;
                        }
                    }
                    $content .= '
                        <tr style="vertical-align: middle;text-align: right">
                            <td style="width: 10%;text-align: center"> ' . $barang[$i]->id_barang . '</td>
                            <td style="width: 38%;text-align: left"> ' . $barang[$i]->nama_barang . '</td>
                            <td style="width: 12%;text-align: center"> ' . $barang[$i]->nama_satuan . '</td>
                            <td style="width: 10%">' . number_format($a - $a1) . '</td>
                            <td style="width: 10%">' . number_format($b) . '</td>
                            <td style="width: 10%">' . number_format($c) . '</td>
                            <td style="width: 10%;">' . number_format($a - $a1 + $b + $c) . '</td>
                        </tr>';
                    $sum = array($sum[0] + ($a - $a1), $sum[1] + $b, $sum[2] + $c, $sum[3] + ($a - $a1 + $b + $c));
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
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = "</table>";
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
                    $pdf->AddPage('P', 'A4');
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
                $pdf->Output('lap_stok_all' . date('d-m-Y') . ' . pdf', 'I');
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
                Excel::create('Lapstokall_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }
    }

    //DKK Admin
    public function lap_terima()
    {
        set_time_limit(300);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('status', '1')
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->orderBy('id_puskesmas', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center;">
                        <th style = "width: 5%;">No</th>
                        <th style = "width: 15%;">Nomor</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 35%;text-align: left">  Puskesmas</th>
                        <th style = "width: 35%;text-align: left">  Supplier</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nomor) {
                        $content .= '
                        <tr style = "font-weight: bold;text-align: left">
                            <td style = "width: 5%;text-align: center">' . $n . '</td>
                            <td style = "width: 15%;">' . $barang[$i]->nomor . '</td>
                            <td style = "width: 10%;text-align: center">' . $barang[$i]->tanggal . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_puskesmas . '-' . $barang[$i]->nama_puskesmas . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_supplier . '-' . $barang[$i]->nama_supplier . '</td>
                        </tr>';
                        $jenis = $barang[$i]->nomor;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style="text-align: center">
                        <td style="width: 5%">&nbsp;</td>
                        <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 30%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 10%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 10%;text-align: left">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                }
                break;
            case "id_supplier":
                $sup = explode('-', Input::get('p_sup'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_supplier', $op, $sup[0])
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.id_supplier', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER SUPPLIER PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_supplier) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_supplier . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_supplier;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_sumber":
                $sup = explode('-', Input::get('p_sumber'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_sumber', $op, $sup[0])
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.id_sumber', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER SUMBER PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_sumber) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_sumber . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_sumber;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_puskesmas":
                $sup = explode('-', Input::get('p_pkm'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.id_puskesmas', $op, $sup[0])
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.id_puskesmas', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER PUSKESMAS PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_puskesmas) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_puskesmas . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_puskesmas;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_barang":
                $sup1 = Input::get('p_jb');
                if ($sup1 == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $sup = Input::get('p_b');
                if ($sup == '') {
                    $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('jenis', $op, $sup1)
                        ->where('pkm_masuk.tanggal', '>=', $awal)
                        ->where('pkm_masuk.tanggal', '<=', $akhir)
                        ->select('pkm_masuk.*', 'pkm_masukdtl.harga', DB::raw('sum(pkm_masukdtl.jumlah) as ttl'), 'id_barang', 'nama_barang', 'nama_satuan')
                        ->groupBy('pkm_masuk.id_puskesmas')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_masuk.tanggal', 'asc')
                        ->get();
                } else {
                    $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('id_barang', $sup)
                        ->where('pkm_masuk.tanggal', '>=', $awal)
                        ->where('pkm_masuk.tanggal', '<=', $akhir)
                        ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'id_barang', 'nama_barang', 'nama_satuan', DB::raw('sum(pkm_masukdtl.jumlah) as ttl'))
                        ->groupBy('pkm_masuk.id_puskesmas')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_masuk.tanggal', 'asc')
                        ->get();
                }
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Puskesmas</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_barang) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_barang . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_barang;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_puskesmas . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->ttl) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->ttl) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->ttl, $sum[2] + ($barang[$i]->harga * $barang[$i]->ttl));

                }
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
                break;
            case "tahun":
                $sup = Input::get('p_tahun');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tahun', $op, $sup)
                    ->select('pkm_masuk.*', 'pkm_masukdtl.harga', 'pkm_masukdtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_masuk.tahun', 'asc')
                    ->orderBy('pkm_masuk.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENERIMAAN PUSKESMAS PER TAHUN PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->tahun) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->tahun . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->tahun;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = '</table>';
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
                Excel::create('LaporanStok_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok Barang', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

    }

    public function lap_keluar()
    {
        set_time_limit(300);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_alokasi.tanggal', 'asc')
                    ->orderBy('id_puskesmas', 'asc')
                    ->get();
                $data = 'LAPORAN PENGELUARAN BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center;">
                        <th style = "width: 5%;">No</th>
                        <th style = "width: 15%;">Nomor</th>
                        <th style = "width: 10%;">Tanggal</th>
                        <th style = "width: 35%;text-align: left">  Puskesmas</th>
                        <th style = "width: 35%;text-align: left">  Unit</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nomor) {
                        $content .= '
                        <tr style = "font-weight: bold;text-align: left">
                            <td style = "width: 5%;text-align: center">' . $n . '</td>
                            <td style = "width: 15%;">' . $barang[$i]->nomor . '</td>
                            <td style = "width: 10%;text-align: center">' . $barang[$i]->tanggal . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_puskesmas . '-' . $barang[$i]->nama_puskesmas . '</td>
                            <td style = "width: 35%;">' . $barang[$i]->id_unit . '-' . $barang[$i]->nama_unit . '</td>
                        </tr>';
                        $jenis = $barang[$i]->nomor;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style="text-align: center">
                        <td style="width: 5%">&nbsp;</td>
                        <td style="width: 5%">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 30%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 10%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 10%;text-align: left">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 10%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                }
                break;
            case "id_unit":
                $sup = Input::get('p_unit');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_unit', $op, $sup)
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_alokasi.id_unit', 'asc')
                    ->orderBy('pkm_alokasi.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENGELUARAN PUSKESMAS PER UNIT PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_unit) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_unit . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_unit;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_puskesmas":
                $sup = explode('-', Input::get('p_pkm'));
                if ($sup[0] == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.id_puskesmas', $op, $sup[0])
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', 'pkm_alokasidtl.jumlah', 'nama_barang', 'nama_satuan')
                    ->orderBy('pkm_alokasi.id_puskesmas', 'asc')
                    ->orderBy('pkm_alokasi.tanggal', 'asc')
                    ->get();
                $data = 'LAPORAN PENGELUARAN PUSKESMAS PER PUSKESMAS PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 40%;text-align: left">Nama Barang</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_puskesmas) {
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_puskesmas . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_puskesmas;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 40%;text-align: left">' . $barang[$i]->nama_barang . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->jumlah) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->jumlah) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->jumlah, $sum[2] + ($barang[$i]->harga * $barang[$i]->jumlah));

                }
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
                break;
            case "id_barang":
                $sup1 = Input::get('p_jb');
                if ($sup1 == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $sup = Input::get('p_b');
                if ($sup == '') {
                    $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('jenis', $op, $sup1)
                        ->where('pkm_alokasi.tanggal', '>=', $awal)
                        ->where('pkm_alokasi.tanggal', '<=', $akhir)
                        ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', DB::raw('sum(pkm_masukdtl.jumlah) as ttl'), 'id_barang', 'nama_barang', 'nama_satuan')
                        ->groupBy('pkm_masuk.id_puskesmas')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_alokasi.tanggal', 'asc')
                        ->get();
                } else {
                    $barang = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                        ->where('id_barang', $sup)
                        ->where('pkm_alokasi.tanggal', '>=', $awal)
                        ->where('pkm_alokasi.tanggal', '<=', $akhir)
                        ->select('pkm_alokasi.*', 'pkm_alokasidtl.harga', DB::raw('sum(pkm_masukdtl.jumlah) as ttl'), 'id_barang', 'nama_barang', 'nama_satuan')
                        ->groupBy('pkm_masuk.id_puskesmas')
                        ->orderBy('id_barang', 'asc')
                        ->orderBy('pkm_alokasi.tanggal', 'asc')
                        ->get();
                }
                $data = 'LAPORAN PENGELUARAN PUSKESMAS PER BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . ' s / d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "10">
                <thead>
                    <tr style = "font-weight: bold;text-align: center">
                        <th style = "width: 3%;">&nbsp;</th>
                        <th style = "width: 5%;">NoID</th>
                        <th style = "width: 10%">Tanggal</th>
                        <th style = "width: 20%;text-align: left">Puskesmas</th>
                        <th style = "width: 20%;text-align: left">Unit</th>
                        <th style = "width: 11%;text-align: right">Harga</th>
                        <th style = "width: 8%;text-align: right">Qty</th>
                        <th style = "width: 12%;">Satuan</th>
                        <th style = "width: 11%;text-align: right">Total</th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </thead>
                ';
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = array(0, 0, 0);
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->id_barang) {
                        if ($i != 0) {
                            $content .= '
                                <tr style = "vertical-align: middle;font-weight: bold">
                                    <td style = "width: 3%;">&nbsp;</td>
                                    <td style = "width: 5%;">&nbsp;</td>
                                    <td style = "width: 10%;">&nbsp;</td>
                                    <td style = "width: 20%;text-align: right"> &nbsp;</td>
                                    <td style = "width: 20%;text-align: right"> Jumlah  </td>
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
                        <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->nama_barang . ' </td>
                    </tr> ';
                        $jenis = $barang[$i]->id_barang;
                        $m = 1;
                        $n++;
                    }
                    $content .= '
                    <tr style = "vertical-align: middle;text-align: center">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">' . sprintf("%03d", $m) . '</td>
                        <td style = "width: 10%;">' . date('d-m-Y', strtotime($barang[$i]->tanggal)) . '</td>
                        <td style = "width: 20%;text-align: left">' . $barang[$i]->nama_puskesmas . '</td>
                        <td style = "width: 20%;text-align: left">' . $barang[$i]->nama_unit . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga) . '</td>
                        <td style = "width: 8%;text-align: right">' . number_format($barang[$i]->ttl) . '</td>
                        <td style = "width: 12%;">' . $barang[$i]->nama_satuan . '</td>
                        <td style = "width: 11%;text-align: right">' . Terbilang::format_no_sign($barang[$i]->harga * $barang[$i]->ttl) . '</td>
                    </tr>';
                    $m++;
                    $sum = array($sum[0] + $barang[$i]->harga, $sum[1] + $barang[$i]->ttl, $sum[2] + ($barang[$i]->harga * $barang[$i]->ttl));

                }
                $content .= '
                    <tr style = "vertical-align: middle;font-weight: bold">
                        <td style = "width: 3%;">&nbsp;</td>
                        <td style = "width: 5%;">&nbsp;</td>
                        <td style = "width: 10%;">&nbsp;</td>
                        <td style = "width: 20%;text-align: right"> &nbsp;</td>
                        <td style = "width: 20%;text-align: right"> Jumlah  </td>
                        <td style = "width: 11%;text-align: right"> ' . Terbilang::format_no_sign($sum[0]) . '</td>
                        <td style = "width: 8%;text-align: right"> ' . number_format($sum[1]) . '</td>
                        <td style = "width: 12%;">&nbsp;</td>
                        <td style = "width: 11%;text-align: right"> ' . Terbilang::format_no_sign($sum[2]) . '</td>
                    </tr> ';
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = '</table>';
        switch ($format) {
            case "0":
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor(PDF_AUTHOR);
                $pdf->SetTitle('Gudang DKK');
                $pdf->SetSubject('Laporan Pengeluaran Barang');

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
                    $pdf->AddPage('P', 'A4');
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
                $pdf->Output('lap_keluar_all' . date('d-m-Y') . ' . pdf', 'I');
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
                Excel::create('Lapkeluarall_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Pengeluaran', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

    }

    public function lap_stok()
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
        $jenis = Input::get('jns');
        $format = Input::get('format');
        switch ($jenis) {
            case "0":
                $barang = DB::table('pkm_inventori')->groupBy('id_barang')->get();
                $data = 'LAPORAN DATA STOCK BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . 's/d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "3">
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
                $s_awal1 = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tanggal', '<', $awal)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_awal2 = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.tanggal', '<', $awal)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_msk = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_klr = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $content = '';
                $sum = array(0, 0, 0, 0);
                for ($i = 0; $i < count($barang); $i++) {
                    $a = 0;
                    $a1 = 0;
                    $b = 0;
                    $c = 0;
                    for ($j = 0; $j < count($s_awal1); $j++) {
                        if ($s_awal1[$j]->id_barang == $barang[$i]->id_barang) {
                            $a = $s_awal1[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_awal2); $j++) {
                        if ($s_awal2[$j]->id_barang == $barang[$i]->id_barang) {
                            $a1 = $s_awal2[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_msk); $j++) {
                        if ($s_msk[$j]->id_barang == $barang[$i]->id_barang) {
                            $b = $s_msk[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_klr); $j++) {
                        if ($s_klr[$j]->id_barang == $barang[$i]->id_barang) {
                            $c = $s_klr[$j]->sum * (-1);
                            break;
                        }
                    }
                    $content .= '
                        <tr style="vertical-align: middle;text-align: right">
                            <td style="width: 10%;text-align: center"> ' . $barang[$i]->id_barang . '</td>
                            <td style="width: 38%;text-align: left"> ' . $barang[$i]->nama_barang . '</td>
                            <td style="width: 12%;text-align: center"> ' . $barang[$i]->nama_satuan . '</td>
                            <td style="width: 10%">' . number_format($a - $a1) . '</td>
                            <td style="width: 10%">' . number_format($b) . '</td>
                            <td style="width: 10%">' . number_format($c) . '</td>
                            <td style="width: 10%;">' . number_format($a - $a1 + $b + $c) . '</td>
                        </tr>';
                    $sum = array($sum[0] + $a - $a1, $sum[1] + $b, $sum[2] + $c, $sum[3] + ($a - $a1 + $b + $c));
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
                break;
            case "1":
                $sup = Input::get('p_jb');
                if ($sup == '') {
                    $op = '<>';
                } else {
                    $op = '=';
                }
                $barang = DB::table('pkm_inventori')->where('jenis', $op, $sup)->groupBy('id_barang')->orderBy('jenis', 'asc')->get();
                $data = 'DATA STOCK BARANG PERIODE ' . date('d-m-Y', strtotime($awal)) . 's/d ' . date('d-m-Y', strtotime($akhir));
                $header = '
                <table cellpadding = "3">
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
                $s_awal1 = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tanggal', '<', $awal)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_awal2 = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.tanggal', '<', $awal)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_msk = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_masuk.tanggal', '>=', $awal)
                    ->where('pkm_masuk.tanggal', '<=', $akhir)
                    ->select('pkm_masuk.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $s_klr = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('pkm_alokasi.tanggal', '>=', $awal)
                    ->where('pkm_alokasi.tanggal', '<=', $akhir)
                    ->select('pkm_alokasi.*', 'id_barang', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('id_barang')
                    ->get();
                $content = '';
                $cjenis = '';
                $m = 1;
                $n = 1;
                $sum = array(0, 0, 0, 0);
                for ($i = 0; $i < count($barang); $i++) {
                    if ($cjenis != $barang[$i]->jenis) {
                        if ($i != 0) {
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
                        }
                        $sum = array(0, 0, 0, 0);
                        $content .= '
                        <tr style = "vertical-align: middle">
                            <td style = "width: 3%;text-align: center;font-weight: bold"> ' . $n . ' </td>
                            <td colspan = "7" style = "font-weight: bold"> ' . $barang[$i]->jenis . ' </td>
                        </tr> ';
                        $cjenis = $barang[$i]->jenis;
                        $m = 1;
                        $n++;
                    }
                    $a = 0;
                    $a1 = 0;
                    $b = 0;
                    $c = 0;
                    for ($j = 0; $j < count($s_awal1); $j++) {
                        if ($s_awal1[$j]->id_barang == $barang[$i]->id_barang) {
                            $a = $s_awal1[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_awal2); $j++) {
                        if ($s_awal2[$j]->id_barang == $barang[$i]->id_barang) {
                            $a1 = $s_awal2[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_msk); $j++) {
                        if ($s_msk[$j]->id_barang == $barang[$i]->id_barang) {
                            $b = $s_msk[$j]->sum;
                            break;
                        }
                    }
                    for ($j = 0; $j < count($s_klr); $j++) {
                        if ($s_klr[$j]->id_barang == $barang[$i]->id_barang) {
                            $c = $s_klr[$j]->sum * (-1);
                            break;
                        }
                    }
                    $content .= '
                        <tr style="vertical-align: middle;text-align: right">
                            <td style="width: 10%;text-align: center"> ' . $barang[$i]->id_barang . '</td>
                            <td style="width: 38%;text-align: left"> ' . $barang[$i]->nama_barang . '</td>
                            <td style="width: 12%;text-align: center"> ' . $barang[$i]->nama_satuan . '</td>
                            <td style="width: 10%">' . number_format($a - $a1) . '</td>
                            <td style="width: 10%">' . number_format($b) . '</td>
                            <td style="width: 10%">' . number_format($c) . '</td>
                            <td style="width: 10%;">' . number_format($a - $a1 + $b + $c) . '</td>
                        </tr>';
                    $sum = array($sum[0] + ($a - $a1), $sum[1] + $b, $sum[2] + $c, $sum[3] + ($a - $a1 + $b + $c));
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
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
        $footer = "</table>";
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
                    $pdf->AddPage('P', 'A4');
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
                $pdf->Output('lap_stok_all' . date('d-m-Y') . ' . pdf', 'I');
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
                Excel::create('Lapstokall_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            default:
                echo "Eror Value!!";
        }

    }

    public function aset_stok()
    {
        set_time_limit(3000);
        $header = '
        <table cellpadding="2">
        <thead>
            <tr style="font-weight: bold;text-align: center">
                <th style="width: 5%">No</th>
                <th style="width: 10%">Kode Aset</th>
                <th style="width: 15%">Kode Barang<br>Rincian Objek</th>
                <th style="width: 15%">No Register</th>
                <th style="width: 30%;text-align: left">Nama Barang</th>
                <th style="width: 10%">Jumlah<br>Satuan</th>
                <th style="width: 15%">Harga Satuan<br>Harga Total</th>
            </tr>
            <tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>
        </thead>
        ';
        $footer = "</table>";
        $content = "";
        $aset = explode('-', Input::get('aset'));
        $data = DB::table('aset_data')->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))->where('id_aset', 'LIKE', $aset[0] . '%')->select('*', DB::raw('sum(jumlah) as sum'))->groupBy('id_aset')->orderBy('id_aset')->get();
        $i = 1;
        foreach ($data as $row) {
            $content .= '
                <tr style="text-align: center">
                    <td style="width: 5%">' . $i . '</td>
                    <td style="width: 10%">' . $row->id_aset . '</td>
                    <td style="width: 15%">' . $row->kode_bidang . '<br>' . $row->kode_perwali . '</td>
                    <td style="width: 15%">' . $row->no_register . '</td>
                    <td style="width: 30%;text-align: left">' . $row->nama . '</td>
                    <td style="width: 10%">' . $row->sum . '<br>' . $row->satuan . '</td>
                    <td style="width: 15%;text-align: right">' . number_format((int)$row->h_satuan) . '<br>' . number_format((int)$row->sum * $row->h_satuan) . '</td>
                </tr>
                ';
            $alk = DB::table('aset_data')->where('id_aset', $row->id_aset)->orderBy('id_ruangan')->get();
            $n = 0;
            $a = '';
            foreach ($alk as $baris) {
                if ($n == 0) {
                    $a = 'Alokasi';
                }
                if ($baris->id_ruangan == '') {
                    $tmpt = 'Belum Dialokasikan';
                } else {
                    $tmpt = $baris->nama_ruangan;
                }
                $content .= '
                <tr>
                    <td style="width: 5%"></td>
                    <td style="width: 10%;font-weight: bold">' . $a . '</td>
                    <td style="width: 5%;">' . ($n + 1) . '.</td>
                    <td style="width: 30%">' . $tmpt . '</td>
                    <td style="width: 10%;text-align: right">' . $baris->jumlah . '</td>
                    <td style="width: 10%">' . $baris->satuan . '</td>
                    <td style="width: 30%;text-align: right"></td>
                </tr>
                ';
                $n++;
            }
            $content .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
            $i++;
        }
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // CONTENT-------------------------------------------
        $pdf->AddPage('P', 'F4');
        $pdf->ln();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output('Data_Aset_' . $aset[1] . '#' . date('d-m-Y') . ' . pdf', 'I');
    }

    public function kendali_transfer()
    {
        $header = '<table>
            <tr>
                <td colspan="11">Kendali Transfer</td>
            </tr>
        </table>';
        $content = '
            <table>
                <thead>
                    <tr style="text-align: center;">
                        <td>KODE_LOKASI_ASAL</td>
                        <td>LOKASI_ASAL</td>
                        <td>KODE_LOKASI_TUJUAN</td>
                        <td>LOKASI_TUJUAN</td>
                        <td>NILAI_TRANSFER_KELUAR</td>
                        <td>SELISIH</td>
                        <td>KODE_LOKASI_PENERIMA</td>
                        <td>LOKASI_PENERIMA</td>
                        <td>KODE_LOKASI_ASAL_TRANSFER</td>
                        <td>LOKASI_ASAL_TRANSFER</td>
                        <td>NILAI_TRANSFER_TERIMA</td>
                    </tr>
                    <tr style="text-align: center;">
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6(5-11)</td>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                        <td>10</td>
                        <td>11</td>
                    </tr>
                </thead>
            </table>';
        $tabel = $header . $content;
        Excel::create('DAFTAR_KENDALI_TRANSFER' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Stok', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function penerimaan()
    {
        set_time_limit(6000);
        $awal = Input::get('awal');
        $akhir = Input::get('akhir');
        if ($awal == '') {
            $awal = date('Y-m-d');
        }
        if ($akhir == '') {
            $akhir = date('Y-m-d');
        }
        $option = Input::get('jenis');
        $format = Input::get('format');
        switch ($option) {
            case '1':
                $header = '
                    <table>
                        <tr><td colspan="2" style="font-weight: bold;">KOTA SURABAYA</td></tr>
                        <tr><td colspan="2" style="font-weight: bold;">DAFTAR RINCIAN MUASI TAMBAH</td></tr>
                        <tr><td colspan="2" style="font-weight: bold;">PERIODE ' . $awal . ' s/d ' . $akhir . '</td></tr>
                    </table>
                    <table>
                        <thead>
                            <tr style="text-align: center;font-weight: bold">
                                <td colspan="2" style="text-align: center;">LOKASI</td>
                                <td rowspan="2" style="text-align: center;">KDP / NON KDP</td>
                                <td colspan="3" style="text-align: center;">JURNAL SIMBADA</td>
                                <td colspan="13" style="text-align: center;">RINCIAN PENERIMAAN</td>
                                <td rowspan="2" style="text-align: center;">KODE KEPEMILIKAN</td>
                                <td rowspan="2" style="text-align: center;">KONDISI</td>
                                <td rowspan="2" style="text-align: center;">JENIS DATA</td>
                                <td colspan="5" style="text-align: center;">NILAI (Rp)</td>
                            </tr>
                            <tr style="text-align: center;font-weight: bold">
                                <td>NOMOR</td>
                                <td>LOKASI</td>
                                <td></td>
                                <td>JENIS PENERIMAAN</td>
                                <td>NOMOR</td>
                                <td>TANGGAL</td>
                                <td>NO REG INDUK</td>
                                <td>KODE BARANG</td>
                                <td>KODE RINCIAN OBJEK</td>
                                <td>NAMA BARANG</td>
                                <td>MERK/ALAMAT</td>
                                <td>TIPE</td>
                                <td>JUMLAH</td>
                                <td>SATUAN</td>
                                <td>TAHUN PENGADAAN</td>
                                <td>SUMBER DANA</td>
                                <td>KETERANGAN</td>
                                <td>UKURAN</td>
                                <td>NOMOR POLISI</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ASET TETAP</td>
                                <td>PENAMBAHAN NILAI</td>
                                <td>JASA</td>
                                <td>PAKAI HABIS</td>
                                <td>BANTUAN</td>
                            </tr>
                        </thead>';
                $footer = "</table>";
                $content = '';
                $data = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
                    ->where('id_puskesmas', sprintf("%04d", Auth::user()->id_puskesmas))
                    ->where('barang', 'aset')
                    //->where('id_inventori', '<', 13799)
                    ->where('tanggal', '>=', $awal)->where('tanggal', '<=', $akhir)
                    ->orderBy('tanggal', 'asc')
                    ->get();
                foreach ($data as $row) {
                    $dtl = DB::table('aset_data')->join('aset_mesin', 'aset_data.id_aset', '=', 'aset_mesin.id_aset')
                        ->where('id', $row->id_inventori)->first();
                    if (count($dtl) > 0) {
                        $content .= '
                        <tr>
                            <td></td>
                            <td>Kantor ' . $row->nama_puskesmas . '</td>
                            <td>NON KDP</td>
                            <td>' . $row->jenis . '</td>
                            <td></td>
                            <td>    ' . date('d-m-Y', strtotime($row->tanggal)) . '</td>
                            <td>' . $dtl->no_register . '</td>
                            <td>' . $dtl->kode_bidang . '</td>
                            <td>' . $dtl->kode_perwali . '</td>
                            <td>' . $dtl->nama . '</td>
                            <td>' . $dtl->merk . '</td>
                            <td>' . $dtl->tipe . '</td>
                            <td>' . $row->jumlah . '</td>
                            <td>' . $dtl->satuan . '</td>
                            <td>' . $dtl->t_pengadaan . '</td>
                            <td>' . $row->nama_sumber . '</td>
                            <td>' . $row->keterangan . '</td>
                            <td>' . $dtl->ukuran . '</td>
                            <td>' . $dtl->no_polisi . '</td>
                            <td>KodePem</td>
                            <td>Baik</td>
                            <td>Jenis Data</td>
                            <td>' . number_format($row->jumlah * $row->harga) . '</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        ';
                    }
                }
                $tabel = $header . $content . $footer;
                //echo count($data) . '<br>';
                //echo $tabel;
                Excel::create('Aset_Daftar_seluruh_Penerimaan_' . date('d-m-Y'), function ($excel) use ($tabel) {

                    $excel->sheet('Stok', function ($sheet) use ($tabel) {

                        $sheet->loadView('excel', array('tabel' => $tabel));
                    });
                })->export('xls');
                break;
            case '2':
                $header = '
                    <table>
                        <tr style="font-weight: bold;">
                            <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN PENGADAAN</td>
                            <td style="text-align: center;font-size: 75%">PENG 1.2</td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td colspan="10" style="text-align: center;font-size: larger">TANGGAL ' . $awal . ' s/d ' . $akhir . '</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="11">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>PROVINSI</td>
                            <td style="width: 3%">:</td>
                            <td>Jawa Timur</td>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>KAB / KOTA</td>
                            <td style="width: 3%">:</td>
                            <td>Surabaya</td>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>LOKASI</td>
                            <td style="width: 3%">:</td>
                            <td>' . Auth::user()->nama_puskesmas . '</td>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="11">&nbsp;</td>
                        </tr>
                    </table>
                    <table border="1" cellspacing="2">
                        <thead>
                        <tr style="font-weight: bold;text-align: center;">
                            <th style="width: 5%">No</th>
                            <th style="width: 10%">KODE BARANG & KODE RINCIAN OBJEK</th>
                            <th style="width: 10%">NO REGS INDUK</th>
                            <th style="width: 10%">NAMA BARANG</th>
                            <th style="width: 8%">MERK/TYPE</th>
                            <th style="width: 7%">JUMLAH/SATUAN</th>
                            <th style="width: 10%">NILAI (Rp)</th>
                            <th style="width: 10%">NILAI SPK/SP/KONTRAK (RP)</th>
                            <th style="width: 10%">SP2D/SPM/SPMU</th>
                            <th style="width: 10%">REK. BELANJA</th>
                            <th style="width: 10%">NILAI BELANJA</th>
                        </tr>
                        <tr style="font-weight: bold;text-align: center;">
                            <th style="width: 5%">1</th>
                            <th style="width: 10%">2</th>
                            <th style="width: 10%">3</th>
                            <th style="width: 10%">4</th>
                            <th style="width: 8%">5</th>
                            <th style="width: 7%">6</th>
                            <th style="width: 10%">7</th>
                            <th style="width: 10%">8</th>
                            <th style="width: 10%">9</th>
                            <th style="width: 10%">10</th>
                            <th style="width: 10%">11</th>
                       </tr>
                    </thead>';
                $footer = '</table>';
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
                    ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
                    ->where('barang', 'aset')->where('jenis', 'pengadaan')->where('pkm_masuk.id_puskesmas', sprintf("%04d", Auth::user()->id_puskesmas))
                    ->where('tanggal', '>=', $awal)->where('tanggal', '<=', $akhir)
                    ->orderBy('id_supplier', 'asc')->get();
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = 0;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nama_supplier) {
                        if ($m != '1') {
                            $content .= '
                        <tr style="font-weight: bold">
                            <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                            <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td colspan="3" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                        </tr>
                    ';
                        }
                        $content .= '
                        <tr style="font-weight: bold">
                            <td colspan="5" style="text-align: center;width: 43%">NO SPK/SP/DOK:&nbsp;&nbsp;&nbsp;</td>
                            <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;REKANAN:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_supplier . '</td>
                        </tr>
                    ';
                        $sum = 0;
                    }
                    $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
                    $content .= '
                        <tr style="text-align: center">
                            <td style="width: 5%">' . $m . '</td>
                            <td style="width: 10%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                            <td style="width: 10%">' . $barang[$i]->no_register . '</td>
                            <td style="width: 10%">' . $barang[$i]->nama . '</td>
                            <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                            <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                            <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                            <td style="width: 10%"></td>
                            <td style="width: 10%"></td>
                            <td style="width: 10%"></td>
                            <td style="width: 10%"></td>
                        </tr>
                        ';
                    $m++;
                    $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
                }
                $content .= '
                <tr style="font-weight: bold">
                    <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                    <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                    <td style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                    <td colspan="3" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                </tr>
                ';
                if ($format == 1) {
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor(PDF_AUTHOR);
                    $pdf->SetTitle('Gudang DKK');
                    $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                    $pdf->SetAutoPageBreak(TRUE, 8);

                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                    // CONTENT-------------------------------------------
                    $pdf->AddPage('L', 'F4');
                    $pdf->ln();
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
                    $pdf->Output('Pengadaan' . date('d-m-Y') . ' . pdf', 'I');
                } else {
                    $tabel = $header . $content . $footer;
                    Excel::create('Pengadaan' . date('d-m-Y'), function ($excel) use ($tabel) {

                        $excel->sheet('Stok', function ($sheet) use ($tabel) {

                            $sheet->loadView('excel', array('tabel' => $tabel));
                        });
                    })->export('xls');
                }
                break;
            case '3':
                $header = '
            <table>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN PENERIMAAN TRANSFER MASUK</td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="text-align: center;font-size: larger">TANGGAL ' . $awal . ' s/d ' . $akhir . '/td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
                <tr>
                    <td>PROVINSI</td>
                    <td style="width: 3%">:</td>
                    <td>Jawa Timur</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>KAB / KOTA</td>
                    <td style="width: 3%">:</td>
                    <td>Surabaya</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>LOKASI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="5">&nbsp;</td>
                    <td>Kepemilikan</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="2">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th rowspan="2" style="width: 5%">No</th>
                        <th rowspan="2" style="width: 7%">KODE BARANG<br>KODE RINCIAN OBJEK</th>
                        <th rowspan="2" style="width: 8%">NO REGS INDUK</th>
                        <th rowspan="2" style="width: 30%">NAMA BARANG</th>
                        <th rowspan="2" style="width: 8%">MERK<br>TYPE</th>
                        <th rowspan="2" style="width: 7%">JUMLAH<br>SATUAN</th>
                        <th rowspan="2" style="width: 10%">HARGA SATUAN<br>HARGA TOTAL</th>
                        <th colspan="3" style="width: 15%">KONDISI</th>
                        <th rowspan="2" style="width: 10%">KETERANGAN</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">B</th>
                        <th style="width: 5%">KB</th>
                        <th style="width: 5%">RB</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">1</th>
                        <th style="width: 7%">2</th>
                        <th style="width: 8%">3</th>
                        <th style="width: 30%">4</th>
                        <th style="width: 8%">5</th>
                        <th style="width: 7%">6</th>
                        <th style="width: 10%">7</th>
                        <th style="width: 5%">8</th>
                        <th style="width: 5%">9</th>
                        <th style="width: 5%">10</th>
                        <th style="width: 10%">11</th>
                    </tr>
                </thead>
        ';
                $footer = '</table>';
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
                    ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
                    ->where('barang', 'aset')->where('jenis', 'transfer masuk')->where('pkm_masuk.id_puskesmas', '0999')
                    ->where('tanggal', '>=', $awal)->where('tanggal', '<=', $akhir)
                    ->orderBy('id_supplier', 'asc')->get();
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = 0;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nama_supplier) {
                        /*if ($m != '1') {
                            $content .= '
                                <tr style="font-weight: bold">
                                    <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                                    <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                                    <td style="text-align: right;">&nbsp;&nbsp;</td>
                                    <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
                                </tr>
                            ';
                        }*/
                        $content .= '
                    <tr style="font-weight: bold">
                        <td colspan="5" style="text-align: center;width: 43%">ASAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_supplier . '</td>
                        <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;NO JURNAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->no_bukti . '</td>
                    </tr>
                ';
                    }
                    $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
                    $content .= '
            <tr style="text-align: center">
                <td style="width: 5%">' . $m . '</td>
                <td style="width: 7%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                <td style="width: 8%">' . $barang[$i]->no_register . '</td>
                <td style="width: 30%">' . $barang[$i]->nama . '</td>
                <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 10%"></td>
            </tr>
            ';
                    $m++;
                    $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
                }
                $content .= '
            <tr style="font-weight: bold">
                <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td style="text-align: right;">&nbsp;&nbsp;</td>
                <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
            </tr>
        ';
                if ($format == 1) {
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor(PDF_AUTHOR);
                    $pdf->SetTitle('Gudang DKK');
                    $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                    $pdf->SetAutoPageBreak(TRUE, 8);

                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                    // CONTENT-------------------------------------------
                    $pdf->AddPage('L', 'F4');
                    $pdf->ln();
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
                    $pdf->Output('TransferMasuk' . date('d-m-Y') . ' . pdf', 'I');
                } else {
                    $tabel = $header . $content . $footer;
                    Excel::create('TransferMasuk' . date('d-m-Y'), function ($excel) use ($tabel) {

                        $excel->sheet('TransferMasuk', function ($sheet) use ($tabel) {

                            $sheet->loadView('excel', array('tabel' => $tabel));
                        });
                    })->export('xls');
                }
                break;
            case '4':
                $header = '
            <table>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN INVENTARISASI</td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="text-align: center;font-size: larger">TANGGAL ' . $awal . ' s/d ' . $akhir . '</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
                <tr>
                    <td>PROVINSI</td>
                    <td style="width: 3%">:</td>
                    <td>Jawa Timur</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>KAB / KOTA</td>
                    <td style="width: 3%">:</td>
                    <td>Surabaya</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>LOKASI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="5">&nbsp;</td>
                    <td>Kepemilikan</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="2">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th rowspan="2" style="width: 5%">No</th>
                        <th rowspan="2" style="width: 7%">KODE BARANG<br>KODE RINCIAN OBJEK</th>
                        <th rowspan="2" style="width: 8%">NO REGS INDUK</th>
                        <th rowspan="2" style="width: 30%">NAMA BARANG</th>
                        <th rowspan="2" style="width: 8%">MERK<br>TYPE</th>
                        <th rowspan="2" style="width: 7%">JUMLAH<br>SATUAN</th>
                        <th rowspan="2" style="width: 10%">HARGA SATUAN<br>HARGA TOTAL</th>
                        <th colspan="3" style="width: 15%">KONDISI</th>
                        <th rowspan="2" style="width: 10%">KETERANGAN</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">B</th>
                        <th style="width: 5%">KB</th>
                        <th style="width: 5%">RB</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">1</th>
                        <th style="width: 7%">2</th>
                        <th style="width: 8%">3</th>
                        <th style="width: 30%">4</th>
                        <th style="width: 8%">5</th>
                        <th style="width: 7%">6</th>
                        <th style="width: 10%">7</th>
                        <th style="width: 5%">8</th>
                        <th style="width: 5%">9</th>
                        <th style="width: 5%">10</th>
                        <th style="width: 10%">11</th>
                    </tr>
                </thead>
        ';
                $footer = '</table>';
                $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
                    ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
                    ->where('barang', 'aset')->where('jenis', 'inventarisasi')->where('pkm_masuk.id_puskesmas', '0999')
                    ->where('tanggal', '>=', $awal)->where('tanggal', '<=', $akhir)
                    ->orderBy('id_unit', 'asc')->get();
                $content = '';
                $jenis = '';
                $n = 1;
                $sum = 0;
                $m = 1;
                for ($i = 0; $i < count($barang); $i++) {
                    if ($jenis != $barang[$i]->nama_unit) {
                        /*if ($m != '1') {
                            $content .= '
                                <tr style="font-weight: bold">
                                    <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                                    <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                                    <td style="text-align: right;">&nbsp;&nbsp;</td>
                                    <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
                                </tr>
                            ';
                        }*/
                        $content .= '
                    <tr style="font-weight: bold">
                        <td colspan="5" style="text-align: center;width: 43%">ASAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_unit . '</td>
                        <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;NO JURNAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->no_bukti . '</td>
                    </tr>
                ';
                    }
                    $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
                    $content .= '
            <tr style="text-align: center">
                <td style="width: 5%">' . $m . '</td>
                <td style="width: 7%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                <td style="width: 8%">' . $barang[$i]->no_register . '</td>
                <td style="width: 30%">' . $barang[$i]->nama . '</td>
                <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 10%"></td>
            </tr>
            ';
                    $m++;
                    $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
                }
                $content .= '
            <tr style="font-weight: bold">
                <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td style="text-align: right;">&nbsp;&nbsp;</td>
                <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
            </tr>
            ';
                if ($format == 1) {
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor(PDF_AUTHOR);
                    $pdf->SetTitle('Gudang DKK');
                    $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                    $pdf->SetAutoPageBreak(TRUE, 8);

                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                    // CONTENT-------------------------------------------
                    $pdf->AddPage('L', 'F4');
                    $pdf->ln();
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
                    $pdf->Output('Inventarisasi' . date('d-m-Y') . ' . pdf', 'I');
                } else {
                    $tabel = $header . $content . $footer;
                    Excel::create('Inventarisasi' . date('d-m-Y'), function ($excel) use ($tabel) {

                        $excel->sheet('Inventarisasi', function ($sheet) use ($tabel) {

                            $sheet->loadView('excel', array('tabel' => $tabel));
                        });
                    })->export('xls');
                }
                break;
        }

    }

    public function pengeluaranAll()
    {
        $header = '
            <table>
                <thead>
                    <tr style="text-align: center;">
                        <th rowspan="2" style="text-align: center;">JENIS\nPENGELUARAN</th>
                        <th colspan="2" style="text-align: center;">LOKASI</th>
                        <th colspan="2" style="text-align: center;">JURNAL</th>
                        <th colspan="2" style="text-align: center;">BA SERAH TERIMA</th>
                        <th rowspan="2" style="text-align: center;">PENERIMA</th>
                        <th colspan="6" style="text-align: center;">DATA BARANG</th>
                        <th colspan="2" style="text-align: center;">PENGELUARAN</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>NOMOR</th>
                        <th>NAMA</th>
                        <th>NOMOR</th>
                        <th>TANGGAL</th>
                        <th>NOMOR</th>
                        <th>TANGGAL</th>
                        <th></th>
                        <th>NO REGS</th>
                        <th>KODE BARANG</th>
                        <th>KODE RINC OBJEK</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>TIPE</th>
                        <th>JUMLAH</th>
                        <th>NILAI (Rp)</th>
                    </tr>
                </thead>';
        $footer = "</table>";
        $content = '';
        $data = DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')
            ->where('id_puskesmas', Auth::user()->id_puskesmas)
            ->where('tanggal', '>=', '')->where('tanggal', '<=', '')
            ->orderBy('tanggal', 'asc')
            ->get();
        $content .= '<tr>
                <td>Transfer Keluar</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>';
        foreach ($data as $row) {
            $dtl = DB::table('aset_data')->join('aset_mesin', 'aset_data.id_aset', '=', 'aset_mesin.id_aset')
                ->where('id', $row->id_inventori)->first();
            if ($row->tujuan == 'intern') {
                $penerima = Auth::user()->nama_puskesmas;
            } else {
                $penerima = $row->nama_unit;
            }
            $content .= '
                <tr>
                    <td></td>
                    <td>Nomor lokasi</td>
                    <td>' . Auth::user()->nama_puskesmas . '</td>
                    <td>' . $row->nomor . '</td>
                    <td>' . $row->tanggal . '</td>
                    <td></td>
                    <td>' . $row->tanggal . '</td>
                    <td>' . $penerima . '</td>
                    <td>' . $dtl->no_reg . '</td>
                    <td>' . $dtl->kdoe_bidang . '</td>
                    <td>' . $dtl->kdoe_perwali . '</td>
                    <td>' . $dtl->nama . '</td>
                    <td>' . $dtl->merk . '</td>
                    <td>' . $dtl->tipe . '</td>
                    <td>' . $row->jumlah . '</td>
                    <td>' . $row->jumlah * $dtl->h_satuan . '</td>
                </tr>
                ';
        }
        $tabel = $header . $content . $footer;
        Excel::create('Daftar_seluruh_pengeluaran' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Keluar', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function penerimaanAll()
    {

        $header = '
            <table>
                <thead>
                    <tr style="text-align: center;">
                        <td colspan="2" style="text-align: center;">LOKASI</td>
                        <td rowspan="2" style="text-align: center;">KDP / NON KDP</td>
                        <td colspan="3" style="text-align: center;">JURNAL SIMBADA</td>
                        <td colspan="13" style="text-align: center;">RINCIAN PENERIMAAN</td>
                        <td rowspan="2" style="text-align: center;">KODE KEPEMILIKAN</td>
                        <td rowspan="2" style="text-align: center;">KONDISI</td>
                        <td rowspan="2" style="text-align: center;">JENIS DATA</td>
                        <td colspan="5" style="text-align: center;">NILAI (Rp)</td>
                    </tr>
                    <tr style="text-align: center;">
                        <td>NOMOR</td>
                        <td>LOKASI</td>
                        <td></td>
                        <td>JENIS PENERIMAAN</td>
                        <td>NOMOR</td>
                        <td>TANGGAL</td>
                        <td>NO REG INDUK</td>
                        <td>KODE BARANG</td>
                        <td>KODE RINCIAN OBJEK</td>
                        <td>NAMA BARANG</td>
                        <td>MERK/ALAMAT</td>
                        <td>TIPE</td>
                        <td>JUMLAH</td>
                        <td>SATUAN</td>
                        <td>TAHUN PENGADAAN</td>
                        <td>SUMBER DANA</td>
                        <td>KETERANGAN</td>
                        <td>UKURAN</td>
                        <td>NOMOR POLISI</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ASET TETAP</td>
                        <td>PENAMBAHAN NILAI</td>
                        <td>JASA</td>
                        <td>PAKAI HABIS</td>
                        <td>BANTUAN</td>
                    </tr>
                </thead>';
        $footer = "</table>";
        $content = '';
        $data = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
            ->where('id_puskesmas', Auth::user()->id_puskesmas)
            ->where('barang', 'aset')
            ->where('tanggal', '>=', '')->where('tanggal', '<=', '')
            ->orderBy('tanggal', 'asc')
            ->get();
        foreach ($data as $row) {
            $dtl = DB::table('aset_data')->join('aset_mesin', 'aset_data.id_aset', '=', 'aset_mesin.id_aset')
                ->where('id', $row->id_inventori)->first();
            $content .= '
                <tr>
                    <td></td>
                    <td>Kantor ' . $row->nama_puskesmas . '</td>
                    <td>NON KDP</td>
                    <td>' . $row->jenis . '</td>
                    <td></td>
                    <td>' . date('d-m-Y', strtotime($row->tanggal)) . '</td>
                    <td>' . $dtl->no_register . '</td>
                    <td>' . $dtl->kode_bidang . '</td>
                    <td>' . $dtl->kode_perwali . '</td>
                    <td>' . $dtl->nama . '</td>
                    <td>' . $dtl->merk . '</td>
                    <td>' . $dtl->tipe . '</td>
                    <td>' . $row->jumlah . '</td>
                    <td>' . $dtl->satuan . '</td>
                    <td>' . $dtl->t_pengadaan . '</td>
                    <td>' . $row->nama_sumber . '</td>
                    <td>' . $row->keterangan . '</td>
                    <td>' . $dtl->ukuran . '</td>
                    <td>' . $dtl->no_polisi . '</td>
                    <td>KodePem</td>
                    <td>Baik</td>
                    <td>Jenis Data</td>
                    <td>' . number_format($row->jumlah * $row->harga) . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                ';
        }
        $tabel = $header . $content . $footer;
        Excel::create('Aset_Daftar_seluruh_Penerimaan_' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Stok', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function pengadaan()
    {
        $header = '
            <table>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN PENGADAAN</td>
                    <td style="text-align: center;font-size: 75%">PENG 1.2</td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="text-align: center;font-size: larger">TANGGAL</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
                <tr>
                    <td>PROVINSI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>KAB / KOTA</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>LOKASI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="2">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">No</th>
                        <th style="width: 10%">KODE BARANG & KODE RINCIAN OBJEK</th>
                        <th style="width: 10%">NO REGS INDUK</th>
                        <th style="width: 10%">NAMA BARANG</th>
                        <th style="width: 8%">MERK/TYPE</th>
                        <th style="width: 7%">JUMLAH/SATUAN</th>
                        <th style="width: 10%">NILAI (Rp)</th>
                        <th style="width: 10%">NILAI SPK/SP/KONTRAK (RP)</th>
                        <th style="width: 10%">SP2D/SPM/SPMU</th>
                        <th style="width: 10%">REK. BELANJA</th>
                        <th style="width: 10%">NILAI BELANJA</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">1</th>
                        <th style="width: 10%">2</th>
                        <th style="width: 10%">3</th>
                        <th style="width: 10%">4</th>
                        <th style="width: 8%">5</th>
                        <th style="width: 7%">6</th>
                        <th style="width: 10%">7</th>
                        <th style="width: 10%">8</th>
                        <th style="width: 10%">9</th>
                        <th style="width: 10%">10</th>
                        <th style="width: 10%">11</th>
                    </tr>
                </thead>
        ';
        $footer = '</table>';
        $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
            ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
            ->where('barang', 'aset')->where('jenis', 'pengadaan')->where('pkm_masuk.id_puskesmas', '0999')
            //->where('tanggal', '>=', '')->where('tanggal', '<=', '')
            ->orderBy('id_supplier', 'asc')->get();
        $content = '';
        $jenis = '';
        $n = 1;
        $sum = 0;
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            if ($jenis != $barang[$i]->nama_supplier) {
                if ($m != '1') {
                    $content .= '
                        <tr style="font-weight: bold">
                            <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                            <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td colspan="3" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                        </tr>
                    ';
                }
                $content .= '
                    <tr style="font-weight: bold">
                        <td colspan="5" style="text-align: center;width: 43%">NO SPK/SP/DOK:&nbsp;&nbsp;&nbsp;</td>
                        <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;REKANAN:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_supplier . '</td>
                    </tr>
                ';
                $sum = 0;
            }
            $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
            $content .= '
            <tr style="text-align: center">
                <td style="width: 5%">' . $m . '</td>
                <td style="width: 10%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                <td style="width: 10%">' . $barang[$i]->no_register . '</td>
                <td style="width: 10%">' . $barang[$i]->nama . '</td>
                <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                <td style="width: 10%"></td>
                <td style="width: 10%"></td>
                <td style="width: 10%"></td>
                <td style="width: 10%"></td>
            </tr>
            ';
            $m++;
            $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
        }
        $content .= '
            <tr style="font-weight: bold">
                <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td colspan="3" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
            </tr>
        ';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // CONTENT-------------------------------------------
        $pdf->AddPage('L', 'F4');
        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output('laporan_penyesuaian' . date('d-m-Y') . ' . pdf', 'I');
        /*$tabel=$header . $content . $footer;
        Excel::create('a' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Stok', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');*/
    }

    public function transfer_masuk()
    {
        $header = '
            <table>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN PENERIMAAN TRANSFER MASUK</td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="text-align: center;font-size: larger">TANGGAL</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
                <tr>
                    <td>PROVINSI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>KAB / KOTA</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>LOKASI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="5">&nbsp;</td>
                    <td>Kepemilikan</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="2">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th rowspan="2" style="width: 5%">No</th>
                        <th rowspan="2" style="width: 7%">KODE BARANG<br>KODE RINCIAN OBJEK</th>
                        <th rowspan="2" style="width: 8%">NO REGS INDUK</th>
                        <th rowspan="2" style="width: 30%">NAMA BARANG</th>
                        <th rowspan="2" style="width: 8%">MERK<br>TYPE</th>
                        <th rowspan="2" style="width: 7%">JUMLAH<br>SATUAN</th>
                        <th rowspan="2" style="width: 10%">HARGA SATUAN<br>HARGA TOTAL</th>
                        <th colspan="3" style="width: 15%">KONDISI</th>
                        <th rowspan="2" style="width: 10%">KETERANGAN</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">B</th>
                        <th style="width: 5%">KB</th>
                        <th style="width: 5%">RB</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">1</th>
                        <th style="width: 7%">2</th>
                        <th style="width: 8%">3</th>
                        <th style="width: 30%">4</th>
                        <th style="width: 8%">5</th>
                        <th style="width: 7%">6</th>
                        <th style="width: 10%">7</th>
                        <th style="width: 5%">8</th>
                        <th style="width: 5%">9</th>
                        <th style="width: 5%">10</th>
                        <th style="width: 10%">11</th>
                    </tr>
                </thead>
        ';
        $footer = '</table>';
        $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
            ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
            ->where('barang', 'aset')->where('jenis', 'transfer masuk')->where('pkm_masuk.id_puskesmas', '0999')
            //->where('tanggal', '>=', '')->where('tanggal', '<=', '')
            ->orderBy('id_supplier', 'asc')->get();
        $content = '';
        $jenis = '';
        $n = 1;
        $sum = 0;
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            if ($jenis != $barang[$i]->nama_supplier) {
                /*if ($m != '1') {
                    $content .= '
                        <tr style="font-weight: bold">
                            <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                            <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td style="text-align: right;">&nbsp;&nbsp;</td>
                            <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
                        </tr>
                    ';
                }*/
                $content .= '
                    <tr style="font-weight: bold">
                        <td colspan="5" style="text-align: center;width: 43%">ASAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_supplier . '</td>
                        <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;NO JURNAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->no_bukti . '</td>
                    </tr>
                ';
            }
            $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
            $content .= '
            <tr style="text-align: center">
                <td style="width: 5%">' . $m . '</td>
                <td style="width: 7%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                <td style="width: 8%">' . $barang[$i]->no_register . '</td>
                <td style="width: 30%">' . $barang[$i]->nama . '</td>
                <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 10%"></td>
            </tr>
            ';
            $m++;
            $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
        }
        $content .= '
            <tr style="font-weight: bold">
                <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td style="text-align: right;">&nbsp;&nbsp;</td>
                <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
            </tr>
        ';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // CONTENT-------------------------------------------
        $pdf->AddPage('L', 'F4');
        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output('laporan_penyesuaian' . date('d-m-Y') . ' . pdf', 'I');
        /*$tabel=$header . $content . $footer;
        Excel::create('a' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Stok', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');*/
    }

    public function inventarisasi()
    {
        $header = '
            <table>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="text-align: center;font-size: larger;">LAPORAN INVENTARISASI</td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="text-align: center;font-size: larger">TANGGAL</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
                <tr>
                    <td>PROVINSI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>KAB / KOTA</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
                <tr>
                    <td>LOKASI</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td colspan="5">&nbsp;</td>
                    <td>Kepemilikan</td>
                    <td style="width: 3%">:</td>
                    <td>--------</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="2">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th rowspan="2" style="width: 5%">No</th>
                        <th rowspan="2" style="width: 7%">KODE BARANG<br>KODE RINCIAN OBJEK</th>
                        <th rowspan="2" style="width: 8%">NO REGS INDUK</th>
                        <th rowspan="2" style="width: 30%">NAMA BARANG</th>
                        <th rowspan="2" style="width: 8%">MERK<br>TYPE</th>
                        <th rowspan="2" style="width: 7%">JUMLAH<br>SATUAN</th>
                        <th rowspan="2" style="width: 10%">HARGA SATUAN<br>HARGA TOTAL</th>
                        <th colspan="3" style="width: 15%">KONDISI</th>
                        <th rowspan="2" style="width: 10%">KETERANGAN</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">B</th>
                        <th style="width: 5%">KB</th>
                        <th style="width: 5%">RB</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 5%">1</th>
                        <th style="width: 7%">2</th>
                        <th style="width: 8%">3</th>
                        <th style="width: 30%">4</th>
                        <th style="width: 8%">5</th>
                        <th style="width: 7%">6</th>
                        <th style="width: 10%">7</th>
                        <th style="width: 5%">8</th>
                        <th style="width: 5%">9</th>
                        <th style="width: 5%">10</th>
                        <th style="width: 10%">11</th>
                    </tr>
                </thead>
        ';
        $footer = '</table>';
        $barang = DB::table('pkm_masuk')->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')
            ->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')
            ->where('barang', 'aset')->where('jenis', 'inventarisasi')->where('pkm_masuk.id_puskesmas', '0999')
            //->where('tanggal', '>=', '')->where('tanggal', '<=', '')
            ->orderBy('id_unit', 'asc')->get();
        $content = '';
        $jenis = '';
        $n = 1;
        $sum = 0;
        $m = 1;
        for ($i = 0; $i < count($barang); $i++) {
            if ($jenis != $barang[$i]->nama_unit) {
                /*if ($m != '1') {
                    $content .= '
                        <tr style="font-weight: bold">
                            <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                            <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                            <td style="text-align: right;">&nbsp;&nbsp;</td>
                            <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
                        </tr>
                    ';
                }*/
                $content .= '
                    <tr style="font-weight: bold">
                        <td colspan="5" style="text-align: center;width: 43%">ASAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->nama_unit . '</td>
                        <td colspan="6" style="text-align: left;width: 57%">&nbsp;&nbsp;NO JURNAL:&nbsp;&nbsp;&nbsp;' . $barang[$i]->no_bukti . '</td>
                    </tr>
                ';
            }
            $detail = DB::table('aset_mesin')->where('id_aset', $barang[$i]->id_aset)->first();
            $content .= '
            <tr style="text-align: center">
                <td style="width: 5%">' . $m . '</td>
                <td style="width: 7%;">' . $barang[$i]->kode_bidang . '<br>' . $barang[$i]->kode_perwali . '</td>
                <td style="width: 8%">' . $barang[$i]->no_register . '</td>
                <td style="width: 30%">' . $barang[$i]->nama . '</td>
                <td style="width: 8%">' . $detail->merk . '<br>' . $detail->tipe . '</td>
                <td style="width: 7%">' . $barang[$i]->jumlah . '<br>' . $barang[$i]->satuan . '</td>
                <td style="width: 10%;text-align: right">' . number_format($barang[$i]->jumlah * $barang[$i]->h_satuan) . '&nbsp;&nbsp;</td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 5%"></td>
                <td style="width: 10%"></td>
            </tr>
            ';
            $m++;
            $sum += ($barang[$i]->jumlah * $barang[$i]->h_satuan);
        }
        $content .= '
            <tr style="font-weight: bold">
                <td colspan="5" style="text-align: right;">SUB TOTAL&nbsp;&nbsp;</td>
                <td colspan="2" style="text-align: right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                <td style="text-align: right;">&nbsp;&nbsp;</td>
                <td colspan="3" style="text-align: right;">&nbsp;&nbsp;</td>
            </tr>
        ';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // CONTENT-------------------------------------------
        $pdf->AddPage('L', 'F4');
        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer, true, false, false, false, '');
        $pdf->Output('laporan_penyesuaian' . date('d-m-Y') . ' . pdf', 'I');
        /*$tabel=$header . $content . $footer;
        Excel::create('a' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('Stok', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');*/
    }

    public function aset_kib()
    {
        set_time_limit(6000);
        $temp = explode('-', Input::get('aset'));
        $aset = $temp[0];
        $top = '
        <table>
            <tr>
                <td colspan="15" style="text-align: center"><strong>KARTU INVENTARIS BARANG</strong></td>
            </tr>
            <tr>
                <td colspan="15" style="text-align: center"><strong>' . strtoupper($temp[1]) . '</strong></td>
            </tr>
            <tr>
                <td style="width: 15%;">SKPD</td>
                <td style="width: 5%;">:</td>
                <td>DINAS KESEHATAN KOTA SURABAYA</td>
            </tr>
            <tr>
                <td style="width: 15%;">LOKASI</td>
                <td style="width: 5%;">:</td>
                <td>--</td>
            </tr>
            <tr>
                <td style="width: 15%;">KODE LOKASI</td>
                <td style="width: 5%;">:</td>
                <td>--</td>
            </tr>
        </table>
        ';
        switch ($aset) {
            case 'tanah':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th rowspan="3">No Urut</th>
                        <th rowspan="3">Jenis/Nama Barang</th>
                        <th rowspan="2" colspan="2">Nomor</th>
                        <th rowspan="3">Luas</th>
                        <th rowspan="3">Tahun Pengadaan</th>
                        <th rowspan="3">Letak/Alamat</th>
                        <th colspan="3">Status Tanah</th>
                        <th rowspan="3">Penggunaan</th>
                        <th rowspan="3">Asal Usul</th>
                        <th rowspan="3">Jumlah</th>
                        <th rowspan="3">Satuan</th>
                        <th rowspan="3">Harga</th>
                        <th rowspan="3">Ket</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th rowspan="2">Hak</th>
                        <th colspan="2">Sertifikat</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>Kode Barang Permen 17</th>
                        <th>Register</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_tanah', 'aset_data.id_aset', '=', 'aset_tanah.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'A%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('desc_bidang')
                    ->get();
                $jenis = "";
                $n = 1;
                $jum = 0;
                foreach ($data as $row) {
                    if ($row->desc_bidang != $jenis) {
                        $content .= '
                            <tr>
                                <td colspan="16">' . $row->desc_bidang . '</td>
                            </tr>';
                        $jenis = $row->desc_bidang;
                        $n = 1;
                    }
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td>' . $row->luas . '</td>
                            <td>' . $row->tahun . '</td>
                            <td>' . $row->alamat . '</td>
                            <td>' . $row->status . '</td>
                            <td>' . $row->tgl_sertifikat . '</td>
                            <td>' . $row->no_sertifikat . '</td>
                            <td>' . $row->fungsi . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                    $jum += $row->sum;
                }
                $content .= '
                    <tr>
                        <td colspan="12" style="text-align: center">GRAND TOTAL</td>
                        <td style="text-align: center">' . $jum . '</td>
                        <td></td><td></td><td></td>
                    </tr>
                    ';
                break;
            case 'mesin':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>No</th>
                        <th>Kode Barang Permen 17</th>
                        <th>Kode Barang Permen 13</th>
                        <th>Deskripsi</th>
                        <th>No Register</th>
                        <th>Nama/JEnis Barang</th>
                        <th>Merk</th>
                        <th>Type</th>
                        <th>Ukuran/CC</th>
                        <th>Tahun Perolehan</th>
                        <th>Nomor Rangka</th>
                        <th>Nomor Mesin</th>
                        <th>Nomor Polisi</th>
                        <th>Nomor BPKB</th>
                        <th>Asal Usul</th>
                        <th>Jumlah Barang APBD</th>
                        <th>Satuan Barang APBD</th>
                        <th>Harga (Rp) APBD</th>
                        <th>Kondisi Barang APBD</th>
                        <th>Jumlah Barang Hibah</th>
                        <th>Satuan Hibah</th>
                        <th>Harga (Rp) Hibah</th>
                        <th>Kondisi Barang Hibah</th>
                        <th>Jumlah Barang Swadaya</th>
                        <th>Satuan Barang Swadaya</th>
                        <th>Harga (Rp) Swadaya</th>
                        <th>Kondisi Barang Swadaya</th>
                        <th>Ket</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_mesin', 'aset_data.id_aset', '=', 'aset_mesin.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'B%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('kode_perwali')
                    ->get();
                $n = 1;
                foreach ($data as $row) {
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->kode_perwali . '</td>
                            <td>' . $row->desc_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->merk . '</td>
                            <td>' . $row->tipe . '</td>
                            <td>' . $row->ukuran . '</td>
                            <td>' . $row->t_pengadaan . '</td>
                            <td>' . $row->no_rangka . '</td>
                            <td>' . $row->no_mesin . '</td>
                            <td>' . $row->no_polisi . '</td>
                            <td>' . $row->no_bpkb . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                }
                break;
            case 'bangunan':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;vertical-align: middle">
                        <th rowspan="2" style="text-align: center;">No</th>
                        <th rowspan="2" style="text-align: center;">Jenis/Nama Barang</th>
                        <th colspan="4" style="text-align: center;">Nomor</th>
                        <th rowspan="2" style="text-align: center;">Kondisi Bangunan</th>
                        <th colspan="2" style="text-align: center;">Konstruksi</th>
                        <th rowspan="2" style="text-align: center;">Luas Lantai</th>
                        <th rowspan="2" style="text-align: center;">Tahun Pengadaan</th>
                        <th rowspan="2" style="text-align: center;">Letak Lokasi Alamat</th>
                        <th colspan="2" style="text-align: center;">Dokumen</th>
                        <th colspan="3" style="text-align: center;">Tanah</th>
                        <th rowspan="2" style="text-align: center;">Asasl Usul</th>
                        <th rowspan="2" style="text-align: center;">Jumlah</th>
                        <th rowspan="2" style="text-align: center;">Satuan</th>
                        <th rowspan="2" style="text-align: center;">Harga</th>
                        <th rowspan="2" style="text-align: center;">Ket</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>Permen 17</th>
                        <th>Permen 13</th>
                        <th>Deskripsi</th>
                        <th>Register</th>
                        <th>7</th>
                        <th>Bertingkat</th>
                        <th>Beton</th>
                        <th>7</th>
                        <th>7</th>
                        <th>7</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Luas</th>
                        <th>Status Tanah</th>
                        <th>Nomor Register</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_bangunan', 'aset_data.id_aset', '=', 'aset_bangunan.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'C%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('desc_bidang')
                    ->get();
                $jenis = "";
                $n = 1;
                $jum = 0;
                foreach ($data as $row) {
                    if ($row->desc_bidang != $jenis) {
                        $content .= '
                            <tr>
                                <td colspan="16">' . $row->desc_bidang . '</td>
                            </tr>';
                        $jenis = $row->desc_bidang;
                        $n = 1;
                    }
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->kode_perwali . '</td>
                            <td>' . $row->desc_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->jml_lantai . '</td>
                            <td>' . $row->j_bahan . '</td>
                            <td>' . $row->l_lantai . '</td>
                            <td>' . $row->tahun . '</td>
                            <td>' . $row->alamat . '</td>
                            <td></td>
                            <td>' . $row->no_dok . '</td>
                            <td>' . $row->l_tanah . '</td>
                            <td>' . $row->s_tanah . '</td>
                            <td>' . $row->no_reg_tanah . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                    $jum += $row->sum;
                }
                break;
            case 'jalan':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;vertical-align: middle">
                        <th rowspan="2" style="text-align: center;">No</th>
                        <th rowspan="2" style="text-align: center;">Jenis/Nama Barang</th>
                        <th colspan="4" style="text-align: center;">Nomor</th>
                        <th rowspan="2" style="text-align: center;">Konstruksi</th>
                        <th rowspan="2" style="text-align: center;">Tahun Pengadaan</th>
                        <th rowspan="2" style="text-align: center;">Panjang</th>
                        <th rowspan="2" style="text-align: center;">Lebar</th>
                        <th rowspan="2" style="text-align: center;">Luas</th>
                        <th rowspan="2" style="text-align: center;">Letak Lokasi Alamat</th>
                        <th colspan="2" style="text-align: center;">Dokumen Gedung</th>
                        <th rowspan="2" style="text-align: center;">Status Tanah</th>
                        <th rowspan="2" style="text-align: center;">Nomor Kode Tanah</th>
                        <th rowspan="2" style="text-align: center;">Asal Usul</th>
                        <th rowspan="2" style="text-align: center;">Jumlah</th>
                        <th rowspan="2" style="text-align: center;">Satuan</th>
                        <th rowspan="2" style="text-align: center;">Harga</th>
                        <th rowspan="2" style="text-align: center;">Kondisi</th>
                        <th rowspan="2" style="text-align: center;">Ket</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>Permen 17</th>
                        <th>Permen 13</th>
                        <th>Deskripsi</th>
                        <th>Register</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                        <th>21</th>
                        <th>22</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_jalan', 'aset_data.id_aset', '=', 'aset_jalan.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'D%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('kode_perwali')
                    ->get();
                $n = 1;
                $jum = 0;
                foreach ($data as $row) {
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->kode_perwali . '</td>
                            <td>' . $row->desc_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td>' . $row->j_bahan . '</td>
                            <td>' . $row->tahun . '</td>
                            <td>' . $row->panjang . '</td>
                            <td>' . $row->lebar . '</td>
                            <td>' . $row->luas . '</td>
                            <td>' . $row->alamat . '</td>
                            <td></td>
                            <td>' . $row->no_dok . '</td>
                            <td>' . $row->s_tanah . '</td>
                            <td>' . $row->no_reg_tanah . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                    $jum += $row->sum;
                }
                break;
            case 'tetaplain':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;vertical-align: middle">
                        <th rowspan="2" style="text-align: center;">No</th>
                        <th rowspan="2" style="text-align: center;">Jenis/Nama Barang</th>
                        <th colspan="4" style="text-align: center;">Nomor</th>
                        <th colspan="2" style="text-align: center;">Buku/Perpustakaan</th>
                        <th colspan="3" style="text-align: center;">Barang Kesenian/Kebudayaan</th>
                        <th colspan="2" style="text-align: center;">Hewan/Ternak dan Tumbuhan</th>
                        <th rowspan="2" style="text-align: center;">Tahun Pengadaan</th>
                        <th rowspan="2" style="text-align: center;">Asal Usul</th>
                        <th rowspan="2" style="text-align: center;">Jumlah</th>
                        <th rowspan="2" style="text-align: center;">Satuan</th>
                        <th rowspan="2" style="text-align: center;">Harga</th>
                        <th rowspan="2" style="text-align: center;">Kondisi</th>
                        <th rowspan="2" style="text-align: center;">Ket</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>Permen 17</th>
                        <th>Permen 13</th>
                        <th>Deskripsi</th>
                        <th>Register</th>
                        <th>Judul/Pencipta</th>
                        <th>Spesifikasi</th>
                        <th>Asal Daerah</th>
                        <th>Pencipta</th>
                        <th>Bahan</th>
                        <th>Jenis</th>
                        <th>Ukuran</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_tetaplain', 'aset_data.id_aset', '=', 'aset_tetaplain.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'E%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('kode_perwali')
                    ->get();
                $n = 1;
                $jum = 0;
                foreach ($data as $row) {
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->kode_perwali . '</td>
                            <td>' . $row->desc_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td>' . $row->judul . '/' . $row->pengarang . '</td>
                            <td></td>
                            <td>' . $row->daerah . '</td>
                            <td>' . $row->pencipta . '</td>
                            <td>' . $row->bahan . '</td>
                            <td></td>
                            <td>' . $row->ukuran . '</td>
                            <td>' . $row->tahun . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                    $jum += $row->sum;
                }
                break;
            case 'lain':
                $header = '
                <table>
                <thead>
                    <tr style="font-weight: bold;vertical-align: middle">
                        <th rowspan="2" style="text-align: center;">No</th>
                        <th colspan="2" style="text-align: center;">Nomor</th>
                        <th rowspan="2" style="text-align: center;">Jenis/Nama Barang</th>
                        <th rowspan="2" style="text-align: center;">Merk</th>
                        <th rowspan="2" style="text-align: center;">Tipe</th>
                        <th rowspan="2" style="text-align: center;">Tahun Pengadaan</th>
                        <th rowspan="2" style="text-align: center;">Kondisi</th>
                        <th rowspan="2" style="text-align: center;">No Seri</th>
                        <th rowspan="2" style="text-align: center;">Asal Usul</th>
                        <th rowspan="2" style="text-align: center;">Jumlah</th>
                        <th rowspan="2" style="text-align: center;">Satuan</th>
                        <th rowspan="2" style="text-align: center;">Harga</th>
                        <th rowspan="2" style="text-align: center;">Ket</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>Permen 17</th>
                        <th>Register</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;vertical-align: middle">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                    </tr>
                </thead>';
                $footer = "</table>";
                $content = "";
                $data = DB::table('aset_data')->join('aset_lain', 'aset_data.id_aset', '=', 'aset_lain.id_aset')
                    ->where('id_puskesmas', sprintf('%03d', Auth::user()->id_puskesmas))
                    ->where('valid','1')
                    ->where('aset_data.id_aset', 'LIKE', 'G%')
                    ->select('*', DB::raw('sum(jumlah) as sum'))
                    ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('kode_perwali')
                    ->get();
                $n = 1;
                $jum = 0;
                foreach ($data as $row) {
                    $content .= '
                        <tr style="text-align: center">
                            <td>' . $n . '</td>
                            <td>' . $row->kode_bidang . '</td>
                            <td>' . $row->no_register . '</td>
                            <td style="text-align: left">' . $row->nama . '</td>
                            <td>' . $row->merk . '</td>
                            <td>' . $row->tipe . '</td>
                            <td>' . $row->tahun . '</td>
                            <td>' . $row->kondisi . '</td>
                            <td>' . $row->no_seri . '</td>
                            <td>' . $row->asal . '</td>
                            <td>' . $row->sum . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->h_satuan . '</td>
                            <td>' . $row->keterangan . '</td>
                        </tr>
                    ';
                    $n++;
                    $jum += $row->sum;
                }
                break;
        }
        $bot = '
            <table>
                <tr>
                    <td colspan="8" style="text-align: center"></td>
                    <td colspan="8" style="text-align: center">Surabaya,................</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center">Mengetahui,</td>
                    <td colspan="8" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center">a.n Kepala Dinas</td>
                    <td colspan="8" style="text-align: center">Pengurus Barang</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center">Sekretaris</td>
                    <td colspan="8" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center"><br><br><br><br></td>
                    <td colspan="8" style="text-align: center"><br><br><br><br></td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center">Nani Sukristina, S.KM</td>
                    <td colspan="8" style="text-align: center">Nanang Hariyanto</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center">NIP</td>
                    <td colspan="8" style="text-align: center">NIP</td>
                </tr>
            </table>
        ';
        $tabel = $top . $header . $content . $footer . $bot;
        Excel::create('KIB-' . $aset . '_' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('KIB', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function aset_kir()
    {
        $unit = explode('-', Input::get('unit'));
        $top = '
        <table>
            <tr>
                <td colspan="16" style="text-align: center"><strong>KARTU INVENTARIS RUANGAN</strong></td>
            </tr>
            <tr>
                <td colspan="16" style="text-align: center"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Propinsi</td>
                <td style="width: 5%;">:</td>
                <td style="width: 30%;">Jawa Timur</td>
                <td colspan="7" style="width: 20%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 5%;"></td>
                <td colspan="3" style="width: 13%;"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Kota</td>
                <td style="width: 5%;">:</td>
                <td style="width: 30%;">Surabaya</td>
                <td colspan="7" style="width: 20%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 5%;"></td>
                <td colspan="3" style="width: 13%;"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Unit</td>
                <td style="width: 5%;">:</td>
                <td style="width: 30%;">DINAS KESEHATAN KOTA SURABAYA</td>
                <td colspan="7" style="width: 20%;"></td>
                <td style="width: 12%;">No Kode Lokasi</td>
                <td style="width: 5%;">:</td>
                <td colspan="3" style="width: 13%;"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Satuan Kerja</td>
                <td style="width: 5%;">:</td>
                <td style="width: 30%;">DINAS KESEHATAN KOTA SURABAYA</td>
                <td colspan="7" style="width: 20%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 5%;"></td>
                <td colspan="3" style="width: 13%;"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Ruangan</td>
                <td style="width: 5%;">:</td>
                <td style="width: 30%;">' . $unit[1] . '</td>
                <td colspan="7" style="width: 20%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 5%;"></td>
                <td colspan="3" style="width: 13%;"></td>
            </tr>
        </table>
        ';
        $bot = '
            <table>
                <tr>
                    <td colspan="5" style="text-align: center"></td>
                    <td colspan="5" style="text-align: center"></td>
                    <td colspan="6" style="text-align: center">Surabaya,................</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center">Mengetahui,</td>
                    <td colspan="5" style="text-align: center"></td>
                    <td colspan="6" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center">Sekretaris</td>
                    <td colspan="5" style="text-align: center">Penanggung Jawab Ruangan</td>
                    <td colspan="6" style="text-align: center">Pengurus Barang</td>
                </tr>
                <tr>
                    <td colspan="16" style="text-align: center"><br><br><br><br><br></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center">Nanik Sukristina, S.KM</td>
                    <td colspan="5" style="text-align: center">Kani</td>
                    <td colspan="6" style="text-align: center">Tri Kartika Sari S.KM</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center">NIP</td>
                    <td colspan="5" style="text-align: center">NIP</td>
                    <td colspan="6" style="text-align: center">NIP</td>
                </tr>
            </table>
        ';
        $header = '
        <table>
        <thead>
            <tr style="font-weight: bold;">
                <th rowspan="2" style="width: 5%;text-align: center">No Urut</th>
                <th rowspan="2" style="width: 20%;text-align: center">Jenis Barang/Nama Barang</th>
                <th rowspan="2" style="width: 10%;text-align: center">Merk</th>
                <th rowspan="2" style="width: 10%;text-align: center">Tipe</th>
                <th rowspan="2" style="width: 10%;text-align: center">No Seri Pabrik</th>
                <th rowspan="2" style="width: 10%;text-align: center">Ukuran</th>
                <th rowspan="2" style="width: 10%;text-align: center">Bahan</th>
                <th rowspan="2" style="width: 10%;text-align: center">Tahun</th>
                <th rowspan="2" style="width: 10%;text-align: center">Kode Barang</th>
                <th rowspan="2" style="width: 10%;text-align: center">No Reg Simbada</th>
                <th rowspan="2" style="width: 10%;text-align: center">Jumlah Barang</th>
                <th rowspan="2" style="width: 10%;text-align: center">Harga Beli</th>
                <th colspan="3" style="width: 15%;text-align: center">Kondisi</th>
                <th rowspan="2" style="width: 10%;text-align: center">Keterangan</th>
            </tr>
            <tr style="text-align: center">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Baik</th>
                <th>Kurang Baik</th>
                <th>Rusak Berat</th>
            </tr>
        </thead>
        ';
        $content = '';
        $footer = '</table>';
        $data = DB::table('aset_data')->join('aset_mesin', 'aset_data.id_aset', '=', 'aset_mesin.id_aset')
            ->where('aset_data.id_aset', 'LIKE', 'B' . sprintf('%03d', Auth::user()->id_puskesmas) . '%')
            ->where('valid','1')
            ->where('id_ruangan', $unit[0])
            ->groupBy('aset_data.id_aset')->orderBy('aset_data.id_aset')->orderBy('desc_bidang')
            ->get();
        $n = 0;
        foreach ($data as $row) {
            $content .= '
                <tr>
                    <td style="width: 5%">' . $n . '</td>
                    <td style="width: 20%">' . $row->nama . '</td>
                    <td style="width: 10%">' . $row->merk . '</td>
                    <td style="width: 10%">' . $row->tipe . '</td>
                    <td style="width: 10%"></td>
                    <td style="width: 10%">' . $row->ukuran . '</td>
                    <td style="width: 10%">' . $row->b_warna . '</td>
                    <td style="width: 10%">' . $row->t_pengadaan . '</td>
                    <td style="width: 10%">' . $row->kode_bidang . '</td>
                    <td style="width: 10%">' . $row->no_register . '</td>
                    <td style="width: 10%">' . sprintf('%03d', $row->jumlah) . '</td>
                    <td style="width: 10%">' . number_format((int)$row->h_satuan) . '</td>
                    <td style="width: 5%">' . sprintf('%03d', $row->jumlah) . '</td>
                    <td style="width: 5%"></td>
                    <td style="width: 5%"></td>
                    <td style="width: 10%">' . $row->keterangan . '</td>
                </tr>
            ';
            $n++;
        }
        $tabel = $top . $header . $content . $footer . $bot;
        Excel::create('KIR_' . date('d-m-Y'), function ($excel) use ($tabel) {

            $excel->sheet('KIR', function ($sheet) use ($tabel) {

                $sheet->loadView('excel', array('tabel' => $tabel));
            });
        })->export('xls');
    }

    public function bend29()
    {
        $id=Input::get('nomor');
        if (substr($id, 0, 3) == 'ALO') {
            $data = DB::table('pkm_alokasi')->where('nomor', $id)->first();
            $dtl = DB::table('pkm_alokasidtl')->join('aset_data', 'pkm_alokasidtl.id_inventori', '=', 'aset_data.id')
                ->where('nomor', $id)->where('tipe', 'aset')->get();
        } else {
            $data = DB::table('aset_keluar')->where('nomor', $id)->first();
            $dtl = DB::table('aset_keluardtl')->join('aset_data', 'aset_keluardtl.id_inventori', '=', 'aset_data.id')
                ->where('nomor', $id)->select('aset_data.*')->get();
        }
        $ka1=DB::table('pkm_pegawai')->where('id_unit', $data->id_puskesmas)->where('jabatan',1)->first();
        $ka2=DB::table('pkm_pegawai')->where('id_unit',$data->id_unit)->where('jabatan',1)->first();
        $pb1=DB::table('pkm_pegawai')->where('id',Input::get('pihak1'))->first();
        $pb2=DB::table('pkm_pegawai')->where('id',Input::get('pihak2'))->first();
        $header = '
            <table>
                <tr style="font-weight: bold">
                    <td colspan="7" style="text-align: left"><br><br><br><br><br><br><br><br><br></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="4" style="text-align: left">PEMERINTAH KOTA SURABAYA</td>
                    <td colspan="3" style="text-align: right">BEND. 29</td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="4" style="text-align: left">&nbsp;</td>
                    <td colspan="3" style="text-align: right">NOMOR JURNAL : ' . $id . '</td>
                </tr>
                <tr style="font-size: large;font-weight: bold">
                    <td colspan="7" style="text-align: center">BUKTI PENYERAHAN BARANG DARI DAERAH/UNIT: ' . $data->nama_puskesmas . '</td>
                </tr>
                <tr style="font-size: large;font-weight: bold">
                    <td colspan="7" style="text-align: center">KEPADA DAERAH:UNIT : ' . $data->nama_unit . '</td>
                </tr>
                <tr style="font-size: large;font-weight: bold">
                    <td colspan="7" style="text-align: center">NO BERITA ACARA SERAH TERIMA: 028/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.24/436.6.3/2014</td>
                </tr>
                <tr style="font-size: large;font-weight: bold">
                    <td colspan="7" style="text-align: center">&nbsp;</td>
                </tr>
            </table>
            <table border="1" cellspacing="3">
                <thead>
                    <tr style="font-weight: bold;text-align: center;">
                        <th rowspan="2" style="width: 10%">NO</th>
                        <th rowspan="2" style="width: 10%">TANGGAL</th>
                        <th rowspan="2" style="width: 30%">Nama & Spesifikasi Barang <br> (Merk, Tipe, Kode, Kode Neraca, No Reg Induk)</th>
                        <th rowspan="2" style="width: 10%">SATUAN</th>
                        <th colspan="2" style="width: 20%">JUMLAH</th>
                        <th rowspan="2" style="width: 20%">NILAI (Rp)</th>
                    </tr>
                    <tr style="font-weight: bold;text-align: center;">
                        <th style="width: 10%">ANGKA</th>
                        <th style="width: 10%">HURUF</th>
                    </tr>
                </thead>
        ';
        $footer = '</table>';
        $content = '';
        for ($i = 0; $i < count($dtl); $i++) {
            $jml = $dtl[$i]->jumlah * $dtl[$i]->h_satuan;
            $content .= '
                <tr style="text-align: center;">
                    <td style="width: 10%">' . ($i + 1) . '</td>
                    <td style="width: 10%">' . $data->tanggal . '</td>
                    <td style="width: 30%;text-align: left;">&nbsp;' . $dtl[$i]->nama . '<br>&nbsp;' . $dtl[$i]->kode_bidang . '<br>&nbsp;' . $dtl[$i]->kode_perwali . '<br>&nbsp;' . $dtl[$i]->no_register . '</td>
                    <td style="width: 10%">' . $dtl[$i]->satuan . '</td>
                    <td style="width: 10%">' . $dtl[$i]->jumlah . '</td>
                    <td style="width: 10%">' . Terbilang::rupiah($dtl[$i]->jumlah) . '</td>
                    <td style="width: 20%;text-align: right;">' . number_format($jml + ($jml * $dtl[$i]->ppn / 100)) . '</td>
                </tr>
            ';
        }
        //$peg1 = DB::table('pkm_pegawai')->where('id_unit', $data->id_unit)->where('jabatan', 2)->first();
        if (Auth::user()->id_puskesmas == '0999') {
            $tt = '<tr>
                    <td style="width: 20%"></td>
                    <td style="width: 60%">
                        <table cellpadding="5">
                            <tr><td colspan="3">&nbsp;</td></tr>
                            <tr>
                                <td style="text-align: right">Mengetahui,</td>
                                <td style="width: 2%"></td>
                                <td></td>
                            </tr>
                            <tr><td colspan="3">&nbsp;</td></tr>
                            <tr><td colspan="3">&nbsp;</td></tr>
                            <tr><td colspan="3">&nbsp;</td></tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td style="width: 2%">:</td>
                                <td>............................</td>
                            </tr>
                            <tr>
                                <td>NAMA</td>
                                <td style="width: 2%">:</td>
                                <td>NANIK SUKRISTINA, SKM</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td style="width: 2%">:</td>
                                <td>19700117 199403 2 008</td>
                            </tr>
                            <tr>
                                <td>PANGKAT</td>
                                <td style="width: 2%">:</td>
                                <td>PEMBINA / IV A</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 20%"></td>
                </tr>';
        } else {
            $tt = '<tr>
                    <td colspan="3" style="width: 48%">
                        <table cellpadding="5">

                            <tr>
                                <td colspan="3">Mengetahui,</td>
                            </tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td style="width: 2%">:</td>
                                <td>............................</td>
                            </tr>
                            <tr>
                                <td>NAMA</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka2->nama_pegawai . '</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka2->nip . '</td>
                            </tr>
                            <tr>
                                <td>PANGKAT</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka2->pangkat . '</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 5%">&nbsp;</td>
                    <td colspan="3" style="width: 47%">
                        <table cellpadding="5">
                            <tr>
                                <td colspan="3">Yang Menyerahkan,</td>
                            </tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td style="width: 2%">:</td>
                                <td>............................</td>
                            </tr>
                            <tr>
                                <td>NAMA</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka1->nama_pegawai . '</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka1->nip . '</td>
                            </tr>
                            <tr>
                                <td>PANGKAT</td>
                                <td style="width: 2%">:</td>
                                <td>' . $ka1->pangkat . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>';
        }
        $ttd = '
            <table>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr>
                    <td colspan="3" style="width: 48%">
                        <table cellpadding="5">
                            <tr>
                                <td>DAERAH</td>
                                <td style="width: 2%">:</td>
                                <td style="width: 90%">KOTA SURABAYA</td>
                            </tr>
                            <tr>
                                <td>UNIT</td>
                                <td style="width: 2%">:</td>
                                <td>' . $data->nama_puskesmas . '</td>
                            </tr>
                            <tr>
                                <td>TANGGAL</td>
                                <td style="width: 2%">:</td>
                                <td>' . date("d/m/Y") . '</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">Yang Menerima,</td>
                            </tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td style="width: 2%">:</td>
                                <td>............................</td>
                            </tr>
                            <tr>
                                <td>NAMA</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb2->nama_pegawai . '</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb2->nip . '</td>
                            </tr>
                            <tr>
                                <td>PANGKAT</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb2->pangkat . '</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 5%">&nbsp;</td>
                    <td colspan="3" style="width: 47%">
                        <table cellpadding="5">
                            <tr>
                                <td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <td>Dibuat di</td>
                                <td style="width: 2%">:</td>
                                <td>SURABAYA</td>
                            </tr>
                            <tr>
                                <td>TANGGAL</td>
                                <td style="width: 2%">:</td>
                                <td>' . date("d/m/Y") . '</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">Yang Menyerahkan,</td>
                            </tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td style="width: 2%">:</td>
                                <td>............................</td>
                            </tr>
                            <tr>
                                <td>NAMA</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb1->nama_pegawai . '</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb1->nip . '</td>
                            </tr>
                            <tr>
                                <td>PANGKAT</td>
                                <td style="width: 2%">:</td>
                                <td>' . $pb1->pangkat . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                ' . $tt . '
            </table>
        ';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Gudang DKK');
        $pdf->SetSubject('Laporan Penerimaan Barang Pengadaan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, 8);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // CONTENT-------------------------------------------
        $pdf->AddPage('P', 'A4');
        /*$pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, "PEMERINTAH KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, "DINAS KESEHATAN KOTA SURABAYA", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Write(0, "JL. JEMURSARI NO 197 Tlp (031)8439473, 8439372, Fax. (031)8494965", '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Write(0, , '', 0, 'C', true, 0, false, false, 0);*/

        $pdf->ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTML($header . $content . $footer . $ttd, true, false, false, false, '');
        $pdf->Output('ben29' . date('d-m-Y') . ' . pdf', 'I');
    }

    public function wkhtml()
    {
        $pdf = WKPDF::make();
        $pdf->addPage('<html><head></head><body><b>Hello World</b></body></html>');
        $pdf->send();
        //return ;
    }
}
