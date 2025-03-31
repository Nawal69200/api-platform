<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329220240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organizer ADD user_id INT NOT NULL, DROP is_user');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D47173A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99D47173A76ED395 ON organizer (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organizer DROP FOREIGN KEY FK_99D47173A76ED395');
        $this->addSql('DROP INDEX UNIQ_99D47173A76ED395 ON organizer');
        $this->addSql('ALTER TABLE organizer ADD is_user TINYINT(1) NOT NULL, DROP user_id');
    }
}
