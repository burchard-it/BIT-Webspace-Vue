<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206153137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Accounts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created DATETIME NOT NULL, modified DATETIME DEFAULT NULL, vnr INTEGER DEFAULT 0 NOT NULL, username VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, enabled BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33BEFCFAF85E0677 ON Accounts (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Accounts');
    }
}
