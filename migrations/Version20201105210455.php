<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201105210455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE user_id user_id INT NOT NULL, CHANGE amount amount VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `signal` ADD date DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project CHANGE user_id user_id INT DEFAULT NULL, CHANGE amount amount VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `signal` DROP date');
    }
}
