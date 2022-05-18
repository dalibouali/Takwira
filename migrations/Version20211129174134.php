<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129174134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipes (id INT AUTO_INCREMENT NOT NULL, capitaine_id INT NOT NULL, pseudo VARCHAR(100) NOT NULL, INDEX IDX_76F7625A2A10D79E (capitaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes_user (equipes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FAD4F595737800BA (equipes_id), INDEX IDX_FAD4F595A76ED395 (user_id), PRIMARY KEY(equipes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipes ADD CONSTRAINT FK_76F7625A2A10D79E FOREIGN KEY (capitaine_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE equipes_user ADD CONSTRAINT FK_FAD4F595737800BA FOREIGN KEY (equipes_id) REFERENCES equipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_user ADD CONSTRAINT FK_FAD4F595A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipes_user DROP FOREIGN KEY FK_FAD4F595737800BA');
        $this->addSql('DROP TABLE equipes');
        $this->addSql('DROP TABLE equipes_user');
    }
}
