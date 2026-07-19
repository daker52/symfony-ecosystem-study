<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260719100226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_order_event ADD COLUMN via_broker VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE work_order_event ADD COLUMN via_exchange VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE work_order_event ADD COLUMN via_queue VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE work_order_event ADD COLUMN via_routing_key VARCHAR(80) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__work_order_event AS SELECT id, stage, message, created_at, work_order_id FROM work_order_event');
        $this->addSql('DROP TABLE work_order_event');
        $this->addSql('CREATE TABLE work_order_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, stage VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, work_order_id INTEGER NOT NULL, CONSTRAINT FK_A7F19E99582AE764 FOREIGN KEY (work_order_id) REFERENCES work_order (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO work_order_event (id, stage, message, created_at, work_order_id) SELECT id, stage, message, created_at, work_order_id FROM __temp__work_order_event');
        $this->addSql('DROP TABLE __temp__work_order_event');
        $this->addSql('CREATE INDEX IDX_A7F19E99582AE764 ON work_order_event (work_order_id)');
    }
}
