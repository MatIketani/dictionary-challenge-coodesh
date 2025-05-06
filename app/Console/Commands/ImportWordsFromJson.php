<?php

namespace App\Console\Commands;

// Import required dependencies for database operations and command handling
use App\Repositories\Words\WordsRepository;
use Illuminate\Console\Command;
use Storage;

/**
 * Command to import words from a JSON dictionary file into the database
 */
class ImportWordsFromJson extends Command
{
    /**
     * Command name: php artisan app:import-words-from-json
     */
    protected $signature = 'app:import-words-from-json';

    /**
     * Command purpose description
     */
    protected $description = 'Import the words from the JSON on GitHub.';

    /**
     * Main execution method
     * Handles the process of reading JSON and importing to database
     */
    public function handle(WordsRepository $wordsRepository)
    {
        // Load and decode the JSON dictionary file
        $fileContent = Storage::disk('local')->get('words_dictionary.json');

        $fullWords = json_decode($fileContent, true);
        
        $onlyWords = array_keys($fullWords);

        $this->info('Importing words...');

        // Process words in chunks of 1000 for better memory management
        $wordsChunks = array_chunk($onlyWords, 1000);

        $progressBar = $this->output->createProgressBar(count($wordsChunks));

        foreach ($wordsChunks as $wordsChunk) {
            // Prepare word data with timestamps for database insertion
            $wordsData = array_map(fn ($word) => ['word' => $word, 'created_at' => now(), 'updated_at' => now()], $wordsChunk);

            // Perform bulk insert of the current chunk
            $wordsRepository->bulkInsert($wordsData);

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info('Words imported successfully.');
    }
}
