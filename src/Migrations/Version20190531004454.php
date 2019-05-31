<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531004454 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote ADD author_id INT NOT NULL, DROP author, DROP vote');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A108564F675F31B ON vote (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564F675F31B');
        $this->addSql('DROP INDEX UNIQ_5A108564F675F31B ON vote');
        $this->addSql('ALTER TABLE vote ADD author VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD vote TINYINT(1) NOT NULL, DROP author_id');
    }
}
