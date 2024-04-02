<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307084621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar DROP panelist1_name, DROP panelist1_email, DROP panelist2_name, DROP panelist2_email, DROP panelist3_name, DROP panelist3_email');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar ADD panelist1_name VARCHAR(255) DEFAULT NULL, ADD panelist1_email VARCHAR(255) DEFAULT NULL, ADD panelist2_name VARCHAR(255) DEFAULT NULL, ADD panelist2_email VARCHAR(255) DEFAULT NULL, ADD panelist3_name VARCHAR(255) DEFAULT NULL, ADD panelist3_email VARCHAR(255) DEFAULT NULL');
    }
}
