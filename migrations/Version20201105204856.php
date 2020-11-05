<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201105204856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `signal` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, resume VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, pic VARCHAR(255) NOT NULL, picalt VARCHAR(255) NOT NULL, INDEX IDX_740C95F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE user_id user_id INT NOT NULL, CHANGE amount amount VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `signal`');
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project CHANGE user_id user_id INT DEFAULT NULL, CHANGE amount amount VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
