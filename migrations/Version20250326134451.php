<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326134451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1C1DBD63');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1C1DBD63 FOREIGN KEY (content_uuid) REFERENCES content (uuid) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1C1DBD63');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1C1DBD63 FOREIGN KEY (content_uuid) REFERENCES content (uuid) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
