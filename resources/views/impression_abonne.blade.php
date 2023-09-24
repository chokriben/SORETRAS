<?php
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Set font
$a = 99999999999999999999;
// Add a page
foreach ($abonnes as $abonne) {
    $year = date('Y', strtotime($abonne->updated_at));

    $date_deb = $year . '-09-15';
    $date_mid = $year + 1 . '-06-30';
    $date_fin = $year + 1 . '-12-31';
    $result = DB::select("select count(*) position from abonnes where (etat='paye' or etat='Imprimer') and id<=(select id from abonnes where code='$abonne->code') ORDER BY `abonnes`.`id` ASC");
    if ($a != $result[0]->position) {
        if ($result[0]->position == 0) {
            $result[0]->position = 1;
        }
        $a = $result[0]->position;

        $data = DB::table('lignes')
            ->select('cod')
            ->where('id', $abonne->ligne_id)
            ->get();

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('dejavusans', 'B', 10);

        // Write year
        $pdf->SetXY(8, 2);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(10, $year + 1 . '/ ' . $year);

        // Write line cod
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(13, 8);
        $pdf->Write(10, $data[0]->cod);

        // Add an image
        $imagePath = public_path($abonne->src); // Specify the path to your image
        $pdf->Image($imagePath, 5, 18, 30, 30); // Parameters: image path, x, y, width, height (0 for auto height)

        // Write abonne code
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetXY(10, 45);
        $pdf->Write(10, $abonne->code);
        $pdf->setRTL(true);

        // Write type abonne
        /*
    $pdf->SetFont('aealarabiya', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(125, 8);
    if ($abonne->type_abonne == null) {
        $pdf->Write(10, 'إشتراك مدرسي حضري');
    } else {
        $pdf->Write(10, 'إشتراك إقليمي');
    }*/

        // Write aboone periode
        $pdf->SetFont('aealarabiya', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(125, 32);
        $pdf->Write(10, $abonne->type_paiment);
        // Write type abonne
        $pdf->SetFont('aealarabiya', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(125, 15);
        $pdf->Write(10, $abonne->prenom);

        // Write type abonne
        $pdf->SetFont('aealarabiya', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(125, 20);
        $pdf->Write(10, $abonne->adresse);

        // Write type abonne
        $pdf->SetFont('aealarabiya', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(125, 25);
        $etab = DB::table('etablissement_translations')
            ->select('labelle')
            ->where('etablissement_id', $abonne->Etablissement_id)
            ->where('locale', 'ar')
            ->get();
        if (isset($etab[0]) && isset($etab[0]->labelle)) {
            $pdf->Write(10, $etab[0]->labelle, '', false, '', true);
        }
        // Write type abonne
        $pdf->SetFont('aealarabiya', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(124, 8);

        $pdf->Write(10, ' إشتراك مدرسي  صالح إلى');

        $pdf->setRTL(false);

        // Write Arabic text
        $pdf->SetFont('dejavusans', 'B', 8);
        $pdf->SetXY(34, 8);

        if ($abonne->type_periode == 'an') {
            $pdf->Write(10, $date_mid);
        } else {
            $pdf->Write(10, $date_fin);
        }

        $stations = DB::table('ligne_station')
            ->select('*')
            ->where('ligne_id', $abonne->ligne_id)
            ->where('gare_id', $abonne->type_zone)
            ->get();
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetXY(36, 30);

        $pdf->Write(10, 'N° ' . $result[0]->position);
        foreach ($stations as $station) {
            $st1 = DB::table('station_translations')
                ->select('name')
                ->where('station_id', $station->station_dep_id)
                ->where('locale', 'fr')
                ->get();
            $st2 = DB::table('station_translations')
                ->select('name')
                ->where('station_id', $station->station_fin_id)
                ->where('locale', 'fr')
                ->get();
            $pdf->setRTL(true);

            $pdf->setRTL(false);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('aealarabiya', 'B', 8);
            $pdf->SetXY(48, 38);
            $pdf->Write(10, ' من ' . $st1[0]->name . ' إلى  ' . $st2[0]->name . '');
            $pdf->setRTL(false);
        }
        if ($abonne->etat == 'paye') {
            DB::table('abonnes')
                ->where('id', $abonne->id)
                ->update(['etat' => 'En cours liv', 'date_imprim' => now()]);
        }
        if ($abonne->etat == 'paye ch') {
            DB::table('abonnes')
                ->where('id', $abonne->id)
                ->update(['etat' => 'En cours liv ch', 'date_imprim' => now()]);
        }
    } else {
        $a = 99999999999999999999;
    }
}

// Clear any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Output the PDF
$pdf->Output();

?>
