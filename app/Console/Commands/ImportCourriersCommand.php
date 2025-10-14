<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CourrierImportService;
use Illuminate\Support\Facades\Log;

class ImportCourriersCommand extends Command
{
    protected $signature = 'courriers:import {file : Path to the Excel file} {--force : Force import even with errors}';
    protected $description = 'Import courriers from Excel file';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        // Check if file exists
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        // Check file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
            $this->error("Invalid file format. Please use .xlsx, .xls, or .csv files.");
            return 1;
        }

        $this->info("╔═══════════════════════════════════════════════╗");
        $this->info("║        Courrier Import Starting               ║");
        $this->info("╚═══════════════════════════════════════════════╝");
        $this->line("");
        $this->info("File: {$filePath}");
        $this->line("");
        
        // Show progress bar
        $this->output->write("Processing");
        
        try {
            $importService = new CourrierImportService();
            $result = $importService->importCourriersFromExcel($filePath);
            
            $this->line("");
            $this->line("");
            $this->info("╔═══════════════════════════════════════════════╗");
            $this->info("║           Import Completed!                   ║");
            $this->info("╚═══════════════════════════════════════════════╝");
            $this->line("");
            
            // Display results
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Successfully Imported', $this->colorCount($result['imported'], 'success')],
                    ['Skipped/Errors', $this->colorCount($result['skipped'], $result['skipped'] > 0 ? 'warning' : 'info')],
                    ['Total Processed', $result['imported'] + $result['skipped']]
                ]
            );
            
            // Display errors if any
            if ($result['skipped'] > 0 && !empty($result['errors'])) {
                $this->line("");
                $this->warn("⚠ Errors encountered during import:");
                $this->line("");
                
                // Show first 10 errors
                $errorsToShow = array_slice($result['errors'], 0, 10);
                foreach ($errorsToShow as $error) {
                    $this->line("  • " . $error);
                }
                
                if (count($result['errors']) > 10) {
                    $remaining = count($result['errors']) - 10;
                    $this->line("");
                    $this->comment("  ... and {$remaining} more errors. Check the log file for details.");
                }
                
                $this->line("");
                $this->comment("Check the log file for full error details: storage/logs/laravel.log");
            }
            
            // Success message
            if ($result['imported'] > 0) {
                $this->line("");
                $this->info("✓ {$result['imported']} courrier(s) have been successfully imported!");
            }
            
            if ($result['skipped'] === 0) {
                $this->line("");
                $this->info("✓ All rows processed without errors!");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->line("");
            $this->line("");
            $this->error("╔═══════════════════════════════════════════════╗");
            $this->error("║           Import Failed!                      ║");
            $this->error("╚═══════════════════════════════════════════════╝");
            $this->line("");
            $this->error("Error: " . $e->getMessage());
            $this->line("");
            $this->comment("Stack trace has been logged to: storage/logs/laravel.log");
            
            Log::error("Courrier import command failed", [
                'file' => $filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
    
    private function colorCount($count, $type = 'info')
    {
        switch ($type) {
            case 'success':
                return "<fg=green>{$count}</>";
            case 'warning':
                return "<fg=yellow>{$count}</>";
            case 'error':
                return "<fg=red>{$count}</>";
            default:
                return $count;
        }
    }
}