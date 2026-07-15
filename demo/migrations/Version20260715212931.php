<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260715212931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(160) NOT NULL, type VARCHAR(40) NOT NULL, status VARCHAR(20) NOT NULL, current_stage VARCHAR(40) DEFAULT NULL, created_at DATETIME NOT NULL, finished_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE work_order_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, stage VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, work_order_id INTEGER NOT NULL, CONSTRAINT FK_A7F19E99582AE764 FOREIGN KEY (work_order_id) REFERENCES work_order (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A7F19E99582AE764 ON work_order_event (work_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE work_order');
        $this->addSql('DROP TABLE work_order_event');
    }
}
