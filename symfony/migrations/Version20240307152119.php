<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307152119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD abbreviation VARCHAR(255) NOT NULL, ADD description VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE webinar DROP reminder_subject');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar ADD reminder_subject VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE module DROP abbreviation, DROP description');
    }
}
