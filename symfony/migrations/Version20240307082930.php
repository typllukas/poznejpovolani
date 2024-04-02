<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307082930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webinar_module (webinar_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_A00D7A39A391D86E (webinar_id), INDEX IDX_A00D7A39AFC2B591 (module_id), PRIMARY KEY(webinar_id, module_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE webinar_module ADD CONSTRAINT FK_A00D7A39A391D86E FOREIGN KEY (webinar_id) REFERENCES webinar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_module ADD CONSTRAINT FK_A00D7A39AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar_module DROP FOREIGN KEY FK_A00D7A39A391D86E');
        $this->addSql('ALTER TABLE webinar_module DROP FOREIGN KEY FK_A00D7A39AFC2B591');
        $this->addSql('DROP TABLE webinar_module');
    }
}
