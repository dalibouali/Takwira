<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127204456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complex (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(200) NOT NULL, localisation JSON DEFAULT NULL, ville VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, complex_id INT NOT NULL, tarif DOUBLE PRECISION NOT NULL, unite DOUBLE PRECISION NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C87653B1E4695F7C (complex_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1E4695F7C FOREIGN KEY (complex_id) REFERENCES complex (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1E4695F7C');
        $this->addSql('DROP TABLE complex');
        $this->addSql('DROP TABLE terrain');
    }
}
