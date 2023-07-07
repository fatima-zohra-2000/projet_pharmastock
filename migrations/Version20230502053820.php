<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502053820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, fournisseur_id_id INT NOT NULL, taille_achat_id INT NOT NULL, num_achat INT NOT NULL, tva INT NOT NULL, prix DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_26A9845662122BA2 (fournisseur_id_id), INDEX IDX_26A98456B543685A (taille_achat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, taille_commande_id INT DEFAULT NULL, num_commande INT NOT NULL, prix DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_6EEAA67DA01F529C (taille_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(1000) DEFAULT NULL, telephone INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, image LONGTEXT NOT NULL, ordonnance TINYINT(1) NOT NULL, INDEX IDX_29A5EC278A3C7387 (categorie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id_id INT NOT NULL, fournisseur_id_id INT NOT NULL, quantite INT NOT NULL, UNIQUE INDEX UNIQ_4B3656604FD8F9C3 (produit_id_id), INDEX IDX_4B36566062122BA2 (fournisseur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_achat (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_achat_produit (taille_achat_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_98FFE1DCB543685A (taille_achat_id), INDEX IDX_98FFE1DCF347EFB (produit_id), PRIMARY KEY(taille_achat_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_commande_produit (taille_commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_332FD5A2A01F529C (taille_commande_id), INDEX IDX_332FD5A2F347EFB (produit_id), PRIMARY KEY(taille_commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845662122BA2 FOREIGN KEY (fournisseur_id_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456B543685A FOREIGN KEY (taille_achat_id) REFERENCES taille_achat (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA01F529C FOREIGN KEY (taille_commande_id) REFERENCES taille_commande (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656604FD8F9C3 FOREIGN KEY (produit_id_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B36566062122BA2 FOREIGN KEY (fournisseur_id_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE taille_achat_produit ADD CONSTRAINT FK_98FFE1DCB543685A FOREIGN KEY (taille_achat_id) REFERENCES taille_achat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taille_achat_produit ADD CONSTRAINT FK_98FFE1DCF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taille_commande_produit ADD CONSTRAINT FK_332FD5A2A01F529C FOREIGN KEY (taille_commande_id) REFERENCES taille_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taille_commande_produit ADD CONSTRAINT FK_332FD5A2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845662122BA2');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456B543685A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA01F529C');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278A3C7387');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604FD8F9C3');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B36566062122BA2');
        $this->addSql('ALTER TABLE taille_achat_produit DROP FOREIGN KEY FK_98FFE1DCB543685A');
        $this->addSql('ALTER TABLE taille_achat_produit DROP FOREIGN KEY FK_98FFE1DCF347EFB');
        $this->addSql('ALTER TABLE taille_commande_produit DROP FOREIGN KEY FK_332FD5A2A01F529C');
        $this->addSql('ALTER TABLE taille_commande_produit DROP FOREIGN KEY FK_332FD5A2F347EFB');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE taille_achat');
        $this->addSql('DROP TABLE taille_achat_produit');
        $this->addSql('DROP TABLE taille_commande');
        $this->addSql('DROP TABLE taille_commande_produit');
    }
}
