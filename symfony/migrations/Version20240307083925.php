<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307083925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webinar_registrant (webinar_id INT NOT NULL, registrant_id INT NOT NULL, INDEX IDX_7036CDC6A391D86E (webinar_id), INDEX IDX_7036CDC63304A716 (registrant_id), PRIMARY KEY(webinar_id, registrant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE webinar_panelist (webinar_id INT NOT NULL, panelist_id INT NOT NULL, INDEX IDX_D3172A5DA391D86E (webinar_id), INDEX IDX_D3172A5D7E8B14D (panelist_id), PRIMARY KEY(webinar_id, panelist_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE webinar_registrant ADD CONSTRAINT FK_7036CDC6A391D86E FOREIGN KEY (webinar_id) REFERENCES webinar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_registrant ADD CONSTRAINT FK_7036CDC63304A716 FOREIGN KEY (registrant_id) REFERENCES registrant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_panelist ADD CONSTRAINT FK_D3172A5DA391D86E FOREIGN KEY (webinar_id) REFERENCES webinar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_panelist ADD CONSTRAINT FK_D3172A5D7E8B14D FOREIGN KEY (panelist_id) REFERENCES panelist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar_registrant DROP FOREIGN KEY FK_7036CDC6A391D86E');
        $this->addSql('ALTER TABLE webinar_registrant DROP FOREIGN KEY FK_7036CDC63304A716');
        $this->addSql('ALTER TABLE webinar_panelist DROP FOREIGN KEY FK_D3172A5DA391D86E');
        $this->addSql('ALTER TABLE webinar_panelist DROP FOREIGN KEY FK_D3172A5D7E8B14D');
        $this->addSql('DROP TABLE webinar_registrant');
        $this->addSql('DROP TABLE webinar_panelist');
    }
}
