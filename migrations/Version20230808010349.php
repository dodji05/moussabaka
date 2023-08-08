<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808010349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, mom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, numero_candidat VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_6AB5B471BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_candidats (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_questions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jury (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, numero_jury VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, jury_id INT DEFAULT NULL, question_id INT DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_11BA68C8D0EB82 (candidat_id), INDEX IDX_11BA68CE560103C (jury_id), INDEX IDX_11BA68C1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, libelle_question VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, INDEX IDX_B6F7494EBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B471BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_candidats (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CE560103C FOREIGN KEY (jury_id) REFERENCES jury (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_questions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B471BCF5E72D');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C8D0EB82');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CE560103C');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C1E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBCF5E72D');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE categorie_candidats');
        $this->addSql('DROP TABLE categorie_questions');
        $this->addSql('DROP TABLE jury');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
