<?php

namespace App\Services;

use App\Models\Courrier;
use App\Models\Expediteur;
use App\Models\Entite;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CourrierImportService
{
    public function importCourriersFromExcel($filePath)
    {
        // ✅ Force the Excel calendar system BEFORE loading the file
        Date::setExcelCalendar(Date::CALENDAR_WINDOWS_1900);

        // ✅ Load the Excel file
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $importedCount = 0;
        $skippedCount = 0;

        // Skip header rows (first 3)
        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];

            // Skip empty rows
            if (empty($row[1]) || $row[1] === "N°BUREAU D'ORDRE") {
                continue;
            }

            try {
                DB::transaction(function () use ($row, &$importedCount) {
                    $referenceBo = $this->cleanNumber($row[1]);
                    $dateArrivee = $this->parseDate($row[2]);
                    $numeroCourrier = $row[3];
                    $dateCourrier = $this->parseDate($row[4]);
                    $expediteur = $row[5];
                    $objet = $row[6];
                    $destination = $row[7];
                    $observation = $row[9];
                    $courrierScanne = $row[10];

                    $expediteurId = $this->getOrCreateExpediteur($expediteur);
                    $entiteId = $this->getOrCreateEntite($destination);

                    Courrier::create([
                        'reference_bo' => $referenceBo,
                        'reference_arrive' => $numeroCourrier,
                        'type_courrier' => 'arrive',
                        'objet' => $objet,
                        'date_reception' => $dateCourrier,
                        'date_enregistrement' => $dateArrivee,
                        'fichier_scan' => $courrierScanne,
                        'id_expediteur' => $expediteurId,
                        'entite_id' => $entiteId,
                        'statut' => 'arriver',
                        'priorite' => 'normale',
                        'Nbr_piece' => 1,
                    ]);

                    $importedCount++;
                });
            } catch (\Exception $e) {
                $skippedCount++;
                \Log::error("Failed to import courrier row {$i}: " . $e->getMessage());
                continue;
            }
        }

        return [
            'imported' => $importedCount,
            'skipped' => $skippedCount
        ];
    }

    private function cleanNumber($value)
    {
        if (empty($value)) {
            return null;
        }
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return !empty($cleaned) ? (int)$cleaned : null;
    }

   private function parseDate($excelDate)
    {
        if (empty($excelDate)) {
            return null;
        }

        try {
            // If already a DateTime
            if ($excelDate instanceof \DateTime) {
                return $excelDate->format('Y-m-d');
            }

            // If numeric (Excel serial)
            if (is_numeric($excelDate)) {
                // Try Windows 1900 first
                Date::setExcelCalendar(Date::CALENDAR_WINDOWS_1900);
                $dateObj = Date::excelToDateTimeObject($excelDate);
                
                // If year is unreasonable (too far in future), try Mac 1904
                if ($dateObj->format('Y') > (date('Y') + 1)) {
                    Date::setExcelCalendar(Date::CALENDAR_MAC_1904);
                    $dateObj = Date::excelToDateTimeObject($excelDate);
                }
                
                return $dateObj->format('Y-m-d');
            }

            // If string, parse normally
            $datePart = explode(' ', trim($excelDate))[0];
            $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $datePart);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }

            $timestamp = strtotime($excelDate);
            return $timestamp ? date('Y-m-d', $timestamp) : null;

        } catch (\Exception $e) {
            \Log::warning("Failed to parse date: {$excelDate}");
            return null;
        }
    }

    private function getOrCreateExpediteur($name)
    {
        if (empty($name)) {
            return null;
        }

        $normalizedName = trim(mb_strtolower($name));
        $expediteur = Expediteur::whereRaw('LOWER(TRIM(nom)) = ?', [$normalizedName])->first();

        if (!$expediteur) {
            $expediteur = Expediteur::create([
                'nom' => ucwords($normalizedName),
                'type' => $this->determineExpediteurType($name),
                'adresse' => null,
                'telephone' => null,
                'email' => null,
            ]);
        }

        return $expediteur->id;
    }

    private function getOrCreateEntite($name)
    {
        if (empty($name)) {
            return null;
        }

        $entite = Entite::where('nom', $name)->first();

        if (!$entite) {
            $entite = Entite::create([
                'nom' => $name,
                'description' => $name,
                'type' => 'interne',
            ]);
        }

        return $entite->id;
    }

    private function determineExpediteurType($name)
    {
        $name = strtolower($name);
        return $name === 'مواطن' ? 'citoyen' : 'administration';
    }
}
