<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307085208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_registrant (module_id INT NOT NULL, registrant_id INT NOT NULL, INDEX IDX_F3F6715DAFC2B591 (module_id), INDEX IDX_F3F6715D3304A716 (registrant_id), PRIMARY KEY(module_id, registrant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_registrant ADD CONSTRAINT FK_F3F6715DAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_registrant ADD CONSTRAINT FK_F3F6715D3304A716 FOREIGN KEY (registrant_id) REFERENCES registrant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_registrant DROP FOREIGN KEY FK_F3F6715DAFC2B591');
        $this->addSql('ALTER TABLE module_registrant DROP FOREIGN KEY FK_F3F6715D3304A716');
        $this->addSql('DROP TABLE module_registrant');
    }
}
