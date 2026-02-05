<?php

namespace App\Services\Exports;

use App\Models\WeeklyReports;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Style\Cell;
use ZipArchive;
use DateTime;

class WeeklyReportsExportService
{
    public function exportCertifiedReports($reports)
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        \PhpOffice\PhpWord\Settings::setDefaultPaper('Letter');

        $zip = new ZipArchive();
        $zipFileName = 'weekly_reports.zip';
        $zipPath = storage_path("app/temp/{$zipFileName}");

        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $php = new PhpWord();
        $section = $php->addSection();


        $reports = WeeklyReports::where('status', 'certified')->with('user')->get();
        foreach ($reports as $report) {

            $php = new PhpWord();
            $php->setDefaultFontName('Tahoma');
            $php->setDefaultFontSize(11);
            $paragraphStyle = array(
                'lineHeight' => 1.5,
                'spaceBefore' => 0, 
                'spaceAfter'  => 0, 
            );

            $section = $php->addSection();
            $textRun = $section->addTextRun();

            $titleStyle = ['alignment' => 'center', 'size' => 12];
            $sectionTitleStyle = ['name' => 'Tahoma'];
            $labelStyle = ['color' => '#092b56', 'bold' => true];

            // Title
            $section->addText('OJT WEEKLY REPORT', $titleStyle, ['align' => 'center']);
            $section->addTextBreak(2);

            // Header

            $tableStyle = [
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
            ];
            $table = $section->addTable($tableStyle);
            $week_start = new DateTime($report->week_start);
            $week_end = new DateTime($report->week_end);

            $formatted_week_start = $week_start->format('F d');
            $formatted_week_end = $week_end->format('F d Y');


            $table->addRow();

            $table->addCell(4500)->addText(
                "Week of: {$formatted_week_start} to {$formatted_week_end}",
                ['name' => 'Tahoma', 'size' => 11, 'underline' => 'single'],
                ['alignment' => 'left']
            );

            $table->addCell(4500)->addText(
                "Name: {$report->user->name}",
                ['name' => 'Tahoma', 'size' => 11, 'underline' => 'single'],
                ['alignment' => 'right']
            );
            $section->addTextBreak(2);

            $entries = $report->entries;

            if (is_string($entries)) {
                $entries = json_decode($entries, true) ?? [];
            }

            // WEEK FOCUS 
            $section->addText('1. WEEK FOCUS', $sectionTitleStyle,$paragraphStyle);
            $section->addText('     What was your main focus this week?', null , $paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Answer:', $labelStyle,$paragraphStyle);
            $section->addText("     {$entries['week_focus']}", null, $paragraphStyle);
            $section->addTextBreak(2);


            // TOPICS & CONCEPTS LEARNED 
            $section->addText('2. TOPICS & CONCEPTS LEARNED', $sectionTitleStyle,$paragraphStyle);
            $section->addText("     List the topics, tools, or concepts you worked on this week.", null ,$paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Topics:', $labelStyle,$paragraphStyle);
            foreach ($entries['topics_learned'] as $topic) {
                $section->addText(
                    "       - ".htmlspecialchars($topic, ENT_QUOTES | ENT_XML1, 'UTF-8'), null, $paragraphStyle
                );
            }
            $section->addTextBreak(2);

            // OUTPUTS & LINKS;
            $section->addText('3. OUTPUTS & LINKS', $sectionTitleStyle,$paragraphStyle);
            foreach ($entries['outputs_links'] as $link) {
                if (isset($link['url'])) {
                    $section->addLink(
                        $link['url'],
                        "     URL: ".$link['url']
                    );
                    $section->addText("     Description: ".$link['description'],null,$paragraphStyle);
                }
                $section->addTextBreak();
            }
            $section->addTextBreak(2);

            // WHAT YOU BUILT OR DESIGNED
            $section->addText('4. WHAT YOU BUILT OR DESIGNED', $sectionTitleStyle,$paragraphStyle);
            $section->addText('     Describe what you created and what problem it was meant to solve.', $sectionTitleStyle,$paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Answer:', $labelStyle);
            $section->addText("     ".$entries['what_built'],null,$paragraphStyle);
            $section->addTextBreak(2);


            // DECISIONS & REASONING
            $section->addText('5. DECISIONS & REASONING', $sectionTitleStyle,$paragraphStyle);
            $section->addText('     Explain at least two decisions you made this week.', $sectionTitleStyle,$paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Decision 1:', $labelStyle,$paragraphStyle);
            $section->addText("     ".$entries['decisions_reasoning']['decision_1'] ?? '',null,$paragraphStyle);
            $section->addText('Decision 2:', $labelStyle);
            $section->addText("     ".$entries['decisions_reasoning']['decision_2'] ?? '',null,$paragraphStyle);
            $section->addTextBreak(2);

            // CHALLENGES & BLOCKERS
            $section->addText('6. CHALLENGES & BLOCKERS ', $sectionTitleStyle,$paragraphStyle);
            $section->addText('     What was difficult or confusing? What slowed you down?', $sectionTitleStyle,$paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Answer:', $labelStyle,$paragraphStyle);
            $section->addText("     ".$entries['challenges_blockers'],null,$paragraphStyle);
            $section->addTextBreak(2);

            // WHAT YOU BUILT OR DESIGNED
            $section->addText('7. WHAT YOU’D IMPROVE NEXT TIME', $sectionTitleStyle,$paragraphStyle);
            $section->addText('     If you had more time, what would you improve or change?', $sectionTitleStyle,$paragraphStyle);
            $section->addTextBreak(1);
            $section->addText('Decision 1:', $labelStyle);
            $section->addText("     ".$entries['improve_next_time']['improvement_1'] ?? '',null,$paragraphStyle);
            $section->addText('Decision 2:', $labelStyle);
            $section->addText("     ".$entries['improve_next_time']['improvement_2'] ?? '',null,$paragraphStyle);
            $section->addTextBreak(2);

            // Save
            $safeName = preg_replace('/[^A-Za-z0-9 _-]/', '', $report->user->name);
            $fileName = "Weekly_Report_{$safeName}.docx";
            $tempPath = storage_path("app/temp/{$fileName}");

            Storage::makeDirectory('temp');
            $writer = IOFactory::createWriter($php, 'Word2007');
            $writer->save($tempPath);

            clearstatcache(true, $tempPath);


            if (!file_exists($tempPath) || filesize($tempPath) < 1000) {
                throw new \Exception('DOCX file was not written correctly.');
            }

            usleep(200000);

            $zip->addFile($tempPath, $fileName);

        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}