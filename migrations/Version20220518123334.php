<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518123334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO category (name) VALUES (\'Non classé\')');
        $this->addSql('ALTER TABLE post ADD categorized_by_id INT DEFAULT NULL');
        $this->addSql('UPDATE post SET categorized_by_id = 1');
        $this->addSql('ALTER TABLE post CHANGE categorized_by_id categorized_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D4B7FB604 FOREIGN KEY (categorized_by_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D4B7FB604 ON post (categorized_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D4B7FB604');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_5A8A6C8D4B7FB604 ON post');
        $this->addSql('ALTER TABLE post DROP categorized_by_id');
    }
}
