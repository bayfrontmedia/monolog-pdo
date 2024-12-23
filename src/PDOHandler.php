<?php

namespace Bayfront\MonologPDO;

use Bayfront\ArrayHelpers\Arr;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use PDO;

class PDOHandler extends AbstractProcessingHandler
{

    private PDO $pdo;
    private string $table_name;

    public function __construct(PDO $pdo, string $table_name = 'logs', int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->pdo = $pdo;
        $this->table_name = $table_name;
        parent::__construct($level, $bubble);
    }

    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void
    {

        $arr = $record->toArray();

        /** @noinspection SqlNoDataSourceInspection */
        $sql = "INSERT INTO $this->table_name (channel, level, levelName, message, context, extra, createdAt) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $record->channel,
            Arr::get($arr, 'level'),
            Arr::get($arr, 'level_name'),
            $record->message,
            empty($record->context) ? null : json_encode($record->context),
            empty($record->extra) ? null : json_encode($record->extra),
            $record->datetime->format('Y-m-d H:i:s')
        ]);

    }

    /**
     * Create database table for handler.
     *
     * @return void
     */
    public function up(): void
    {

        /** @noinspection SqlNoDataSourceInspection */
        $query = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS $this->table_name (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                `channel` VARCHAR(255), 
                `level` INT(3),
                `levelName` VARCHAR(10),
                `message` TEXT,
                `context` JSON,
                `extra` JSON,
                `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $query->execute();

    }

    /**
     * Remove database table for handler.
     *
     * @return void
     */
    public function down(): void
    {
        /** @noinspection SqlNoDataSourceInspection */
        $query = $this->pdo->prepare("DROP TABLE IF EXISTS $this->table_name");
        $query->execute();
    }

}