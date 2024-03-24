<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112110554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendrier (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, description LONGTEXT NOT NULL, all_day TINYINT(1) NOT NULL, couleur VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, niveau VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_calendrier (classe_id INT NOT NULL, calendrier_id INT NOT NULL, INDEX IDX_174EDD928F5EA509 (classe_id), INDEX IDX_174EDD92FF52FC51 (calendrier_id), PRIMARY KEY(classe_id, calendrier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_alt (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, etudiant_retenu_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(2000) NOT NULL, type VARCHAR(50) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_2A84DB37A4AEAFEA (entreprise_id), UNIQUE INDEX UNIQ_2A84DB37CD215B24 (etudiant_retenu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_alt_utilisateur (stage_alt_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_F75FC07FCF7C6DE2 (stage_alt_id), INDEX IDX_F75FC07FFB88E14F (utilisateur_id), PRIMARY KEY(stage_alt_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ter (id INT AUTO_INCREMENT NOT NULL, createur_id INT DEFAULT NULL, etudiant_id INT DEFAULT NULL, nom_sujet VARCHAR(200) NOT NULL, description VARCHAR(2000) NOT NULL, INDEX IDX_A38966C73A201E5 (createur_id), UNIQUE INDEX UNIQ_A38966CDDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ter_utilisateur (ter_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_B15183812895627C (ter_id), INDEX IDX_B1518381FB88E14F (utilisateur_id), PRIMARY KEY(ter_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(30) DEFAULT NULL, adresse VARCHAR(50) NOT NULL, code_postal VARCHAR(5) NOT NULL, ville VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, sexe VARCHAR(1) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_calendrier (utilisateur_id INT NOT NULL, calendrier_id INT NOT NULL, INDEX IDX_80CDFEAFFB88E14F (utilisateur_id), INDEX IDX_80CDFEAFFF52FC51 (calendrier_id), PRIMARY KEY(utilisateur_id, calendrier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_classe (utilisateur_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_EE2A851DFB88E14F (utilisateur_id), INDEX IDX_EE2A851D8F5EA509 (classe_id), PRIMARY KEY(utilisateur_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classe_calendrier ADD CONSTRAINT FK_174EDD928F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_calendrier ADD CONSTRAINT FK_174EDD92FF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_alt ADD CONSTRAINT FK_2A84DB37A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE stage_alt ADD CONSTRAINT FK_2A84DB37CD215B24 FOREIGN KEY (etudiant_retenu_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE stage_alt_utilisateur ADD CONSTRAINT FK_F75FC07FCF7C6DE2 FOREIGN KEY (stage_alt_id) REFERENCES stage_alt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_alt_utilisateur ADD CONSTRAINT FK_F75FC07FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ter ADD CONSTRAINT FK_A38966C73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ter ADD CONSTRAINT FK_A38966CDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ter_utilisateur ADD CONSTRAINT FK_B15183812895627C FOREIGN KEY (ter_id) REFERENCES ter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ter_utilisateur ADD CONSTRAINT FK_B1518381FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_calendrier ADD CONSTRAINT FK_80CDFEAFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_calendrier ADD CONSTRAINT FK_80CDFEAFFF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_classe ADD CONSTRAINT FK_EE2A851DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_classe ADD CONSTRAINT FK_EE2A851D8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_calendrier DROP FOREIGN KEY FK_174EDD928F5EA509');
        $this->addSql('ALTER TABLE classe_calendrier DROP FOREIGN KEY FK_174EDD92FF52FC51');
        $this->addSql('ALTER TABLE stage_alt DROP FOREIGN KEY FK_2A84DB37A4AEAFEA');
        $this->addSql('ALTER TABLE stage_alt DROP FOREIGN KEY FK_2A84DB37CD215B24');
        $this->addSql('ALTER TABLE stage_alt_utilisateur DROP FOREIGN KEY FK_F75FC07FCF7C6DE2');
        $this->addSql('ALTER TABLE stage_alt_utilisateur DROP FOREIGN KEY FK_F75FC07FFB88E14F');
        $this->addSql('ALTER TABLE ter DROP FOREIGN KEY FK_A38966C73A201E5');
        $this->addSql('ALTER TABLE ter DROP FOREIGN KEY FK_A38966CDDEAB1A3');
        $this->addSql('ALTER TABLE ter_utilisateur DROP FOREIGN KEY FK_B15183812895627C');
        $this->addSql('ALTER TABLE ter_utilisateur DROP FOREIGN KEY FK_B1518381FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_calendrier DROP FOREIGN KEY FK_80CDFEAFFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_calendrier DROP FOREIGN KEY FK_80CDFEAFFF52FC51');
        $this->addSql('ALTER TABLE utilisateur_classe DROP FOREIGN KEY FK_EE2A851DFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_classe DROP FOREIGN KEY FK_EE2A851D8F5EA509');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE classe_calendrier');
        $this->addSql('DROP TABLE stage_alt');
        $this->addSql('DROP TABLE stage_alt_utilisateur');
        $this->addSql('DROP TABLE ter');
        $this->addSql('DROP TABLE ter_utilisateur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_calendrier');
        $this->addSql('DROP TABLE utilisateur_classe');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
