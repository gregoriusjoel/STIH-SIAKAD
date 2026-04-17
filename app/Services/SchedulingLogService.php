<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * SchedulingLogService
 * 
 * Structured logging for scheduling operations
 * Logs to 'scheduling' channel in JSON format
 * 
 * Usage:
 *   $logService->info('Room selected', ['mata_kuliah_id' => 1, 'room' => 'R101'])
 *   $logService->warning('Fallback applied', [...])
 *   $logService->getStats() - get operation statistics
 */
class SchedulingLogService
{
    protected string $channel = 'scheduling';
    protected array $stats = [
        'operations' => 0,
        'successes' => 0,
        'warnings' => 0,
        'errors' => 0,
        'fallbacks' => 0,
    ];
    protected int $statsInterval = 10;

    public function __construct()
    {
        $this->channel = config('scheduling.logging.channel', 'scheduling');
        $this->statsInterval = config('scheduling.logging.stats_interval', 10);
    }

    /**
     * Log info message
     * 
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
        $this->stats['successes']++;
    }

    /**
     * Log warning message (usually for fallback)
     * 
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
        $this->stats['warnings']++;
        
        if (strpos($message, 'fallback') !== false) {
            $this->stats['fallbacks']++;
        }
    }

    /**
     * Log error message
     * 
     * @param string $message
     * @param array $context
     * @param \Throwable|null $exception
     * @return void
     */
    public function error(string $message, array $context = [], ?\Throwable $exception = null): void
    {
        if ($exception) {
            $context['exception'] = [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        $this->log('error', $message, $context);
        $this->stats['errors']++;
    }

    /**
     * Log conflict detected
     * 
     * @param string $conflictType
     * @param array $details
     * @return void
     */
    public function logConflict(string $conflictType, array $details = []): void
    {
        $this->warning("Conflict detected: {$conflictType}", [
            'conflict_type' => $conflictType,
            'details' => $details,
        ]);
    }

    /**
     * Log scheduling statistics
     * 
     * @param array $stats
     * @return void
     */
    public function logStatistics(array $stats): void
    {
        $this->info('Scheduling statistics', array_merge($this->stats, $stats));
    }

    /**
     * Log batch operation start
     * 
     * @param string $batchName
     * @param int $totalItems
     * @return void
     */
    public function startBatch(string $batchName, int $totalItems): void
    {
        $this->info("Batch operation started: {$batchName}", [
            'batch_name' => $batchName,
            'total_items' => $totalItems,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log batch operation completion
     * 
     * @param string $batchName
     * @param array $results
     * @return void
     */
    public function endBatch(string $batchName, array $results): void
    {
        $duration = $results['duration'] ?? 'unknown';
        $summary = [
            'successful' => $results['successful'] ?? 0,
            'failed' => $results['failed'] ?? 0,
            'skipped' => $results['skipped'] ?? 0,
        ];

        $this->info("Batch operation completed: {$batchName}", [
            'batch_name' => $batchName,
            'duration' => $duration,
            'summary' => $summary,
            'details' => $results,
        ]);
    }

    /**
     * Increment operation counter
     * 
     * @return void
     */
    public function incrementOperation(): void
    {
        $this->stats['operations']++;

        if ($this->stats['operations'] % $this->statsInterval === 0) {
            $this->logStatistics([]);
        }
    }

    /**
     * Get current statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        return array_merge($this->stats, [
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Reset statistics
     * 
     * @return void
     */
    public function resetStats(): void
    {
        $this->stats = [
            'operations' => 0,
            'successes' => 0,
            'warnings' => 0,
            'errors' => 0,
            'fallbacks' => 0,
        ];
    }

    /**
     * Internal log method with structured data
     * 
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    private function log(string $level, string $message, array $context = []): void
    {
        $useStructured = config('scheduling.logging.structured', true);

        if ($useStructured) {
            // Add scheduling context
            $context = array_merge([
                'service' => 'SchedulingLogService',
                'timestamp' => now()->toIso8601String(),
            ], $context);

            Log::channel($this->channel)->log($level, $message, $context);
        } else {
            // Simple logging
            Log::channel($this->channel)->log($level, $message);
        }
    }

    /**
     * Log room selection decision
     * 
     * @param array $decision
     * @return void
     */
    public function logRoomSelection(array $decision): void
    {
        $this->info('Room selection completed', [
            'mata_kuliah_id' => $decision['mata_kuliah_id'] ?? null,
            'selected_room' => $decision['selected_room'] ?? null,
            'algorithm' => $decision['algorithm'] ?? null,
            'candidates_count' => $decision['candidates_count'] ?? null,
            'was_fallback' => $decision['was_fallback'] ?? false,
        ]);
    }

    /**
     * Log scheduling proposal created
     * 
     * @param array $proposal
     * @return void
     */
    public function logProposalCreated(array $proposal): void
    {
        $this->info('Jadwal proposal created', [
            'proposal_id' => $proposal['id'] ?? null,
            'mata_kuliah_id' => $proposal['mata_kuliah_id'] ?? null,
            'dosen_id' => $proposal['dosen_id'] ?? null,
            'ruangan' => $proposal['ruangan'] ?? null,
            'hari' => $proposal['hari'] ?? null,
            'jam_mulai' => $proposal['jam_mulai'] ?? null,
            'jam_selesai' => $proposal['jam_selesai'] ?? null,
            'status' => $proposal['status'] ?? null,
        ]);
    }

    /**
     * Log scheduling operation failure
     * 
     * @param string $reason
     * @param array $context
     * @return void
     */
    public function logSchedulingFailed(string $reason, array $context = []): void
    {
        $this->error("Scheduling failed: {$reason}", $context);
    }
}
