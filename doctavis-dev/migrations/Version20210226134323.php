<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226134323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accessibility_criterion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE additional_criterion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE care (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE importance_criterion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner (id INT AUTO_INCREMENT NOT NULL, profession_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, sex VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, experience INT NOT NULL, sector VARCHAR(255) NOT NULL, consultation_type VARCHAR(255) NOT NULL, INDEX IDX_17323CBCFDEF8996 (profession_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_speciality (practitioner_id INT NOT NULL, speciality_id INT NOT NULL, INDEX IDX_C17B0E621121EA2C (practitioner_id), INDEX IDX_C17B0E623B5A08D7 (speciality_id), PRIMARY KEY(practitioner_id, speciality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_equipment (practitioner_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_A7FADAAF1121EA2C (practitioner_id), INDEX IDX_A7FADAAF517FE9FE (equipment_id), PRIMARY KEY(practitioner_id, equipment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_accessibility_criterion (practitioner_id INT NOT NULL, accessibility_criterion_id INT NOT NULL, INDEX IDX_8418E2D11121EA2C (practitioner_id), INDEX IDX_8418E2D17F46A813 (accessibility_criterion_id), PRIMARY KEY(practitioner_id, accessibility_criterion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_formation (practitioner_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_34822E931121EA2C (practitioner_id), INDEX IDX_34822E935200282E (formation_id), PRIMARY KEY(practitioner_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_temperament (practitioner_id INT NOT NULL, temperament_id INT NOT NULL, INDEX IDX_AA30EE5B1121EA2C (practitioner_id), INDEX IDX_AA30EE5B65A6C68A (temperament_id), PRIMARY KEY(practitioner_id, temperament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_additional_criterion (practitioner_id INT NOT NULL, additional_criterion_id INT NOT NULL, INDEX IDX_D02E76151121EA2C (practitioner_id), INDEX IDX_D02E7615EB9A2CFA (additional_criterion_id), PRIMARY KEY(practitioner_id, additional_criterion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_care (id INT AUTO_INCREMENT NOT NULL, practitioner_id INT DEFAULT NULL, care_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_7190CF3A1121EA2C (practitioner_id), INDEX IDX_7190CF3AF270FD45 (care_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_importance_criterion (id INT AUTO_INCREMENT NOT NULL, criterion_id INT DEFAULT NULL, practitioner_id INT DEFAULT NULL, note INT NOT NULL, INDEX IDX_A699705197766307 (criterion_id), INDEX IDX_A69970511121EA2C (practitioner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profession (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speciality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temperament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE practitioner ADD CONSTRAINT FK_17323CBCFDEF8996 FOREIGN KEY (profession_id) REFERENCES profession (id)');
        $this->addSql('ALTER TABLE practitioner_speciality ADD CONSTRAINT FK_C17B0E621121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_speciality ADD CONSTRAINT FK_C17B0E623B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_equipment ADD CONSTRAINT FK_A7FADAAF1121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_equipment ADD CONSTRAINT FK_A7FADAAF517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_accessibility_criterion ADD CONSTRAINT FK_8418E2D11121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_accessibility_criterion ADD CONSTRAINT FK_8418E2D17F46A813 FOREIGN KEY (accessibility_criterion_id) REFERENCES accessibility_criterion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_formation ADD CONSTRAINT FK_34822E931121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_formation ADD CONSTRAINT FK_34822E935200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_temperament ADD CONSTRAINT FK_AA30EE5B1121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_temperament ADD CONSTRAINT FK_AA30EE5B65A6C68A FOREIGN KEY (temperament_id) REFERENCES temperament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_additional_criterion ADD CONSTRAINT FK_D02E76151121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_additional_criterion ADD CONSTRAINT FK_D02E7615EB9A2CFA FOREIGN KEY (additional_criterion_id) REFERENCES additional_criterion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_care ADD CONSTRAINT FK_7190CF3A1121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id)');
        $this->addSql('ALTER TABLE practitioner_care ADD CONSTRAINT FK_7190CF3AF270FD45 FOREIGN KEY (care_id) REFERENCES care (id)');
        $this->addSql('ALTER TABLE practitioner_importance_criterion ADD CONSTRAINT FK_A699705197766307 FOREIGN KEY (criterion_id) REFERENCES importance_criterion (id)');
        $this->addSql('ALTER TABLE practitioner_importance_criterion ADD CONSTRAINT FK_A69970511121EA2C FOREIGN KEY (practitioner_id) REFERENCES practitioner (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE practitioner_accessibility_criterion DROP FOREIGN KEY FK_8418E2D17F46A813');
        $this->addSql('ALTER TABLE practitioner_additional_criterion DROP FOREIGN KEY FK_D02E7615EB9A2CFA');
        $this->addSql('ALTER TABLE practitioner_care DROP FOREIGN KEY FK_7190CF3AF270FD45');
        $this->addSql('ALTER TABLE practitioner_equipment DROP FOREIGN KEY FK_A7FADAAF517FE9FE');
        $this->addSql('ALTER TABLE practitioner_formation DROP FOREIGN KEY FK_34822E935200282E');
        $this->addSql('ALTER TABLE practitioner_importance_criterion DROP FOREIGN KEY FK_A699705197766307');
        $this->addSql('ALTER TABLE practitioner_speciality DROP FOREIGN KEY FK_C17B0E621121EA2C');
        $this->addSql('ALTER TABLE practitioner_equipment DROP FOREIGN KEY FK_A7FADAAF1121EA2C');
        $this->addSql('ALTER TABLE practitioner_accessibility_criterion DROP FOREIGN KEY FK_8418E2D11121EA2C');
        $this->addSql('ALTER TABLE practitioner_formation DROP FOREIGN KEY FK_34822E931121EA2C');
        $this->addSql('ALTER TABLE practitioner_temperament DROP FOREIGN KEY FK_AA30EE5B1121EA2C');
        $this->addSql('ALTER TABLE practitioner_additional_criterion DROP FOREIGN KEY FK_D02E76151121EA2C');
        $this->addSql('ALTER TABLE practitioner_care DROP FOREIGN KEY FK_7190CF3A1121EA2C');
        $this->addSql('ALTER TABLE practitioner_importance_criterion DROP FOREIGN KEY FK_A69970511121EA2C');
        $this->addSql('ALTER TABLE practitioner DROP FOREIGN KEY FK_17323CBCFDEF8996');
        $this->addSql('ALTER TABLE practitioner_speciality DROP FOREIGN KEY FK_C17B0E623B5A08D7');
        $this->addSql('ALTER TABLE practitioner_temperament DROP FOREIGN KEY FK_AA30EE5B65A6C68A');
        $this->addSql('DROP TABLE accessibility_criterion');
        $this->addSql('DROP TABLE additional_criterion');
        $this->addSql('DROP TABLE care');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE importance_criterion');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE practitioner');
        $this->addSql('DROP TABLE practitioner_speciality');
        $this->addSql('DROP TABLE practitioner_equipment');
        $this->addSql('DROP TABLE practitioner_accessibility_criterion');
        $this->addSql('DROP TABLE practitioner_formation');
        $this->addSql('DROP TABLE practitioner_temperament');
        $this->addSql('DROP TABLE practitioner_additional_criterion');
        $this->addSql('DROP TABLE practitioner_care');
        $this->addSql('DROP TABLE practitioner_importance_criterion');
        $this->addSql('DROP TABLE profession');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE temperament');
        $this->addSql('DROP TABLE user');
    }
}
