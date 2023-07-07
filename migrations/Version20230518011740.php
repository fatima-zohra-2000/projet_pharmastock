<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518011740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT DEFAULT NULL, num_achat INT NOT NULL, tva DOUBLE PRECISION NOT NULL, mantant_tva DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_26A98456670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, num_commande INT NOT NULL, total DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, mantant_tva DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_achat (id INT AUTO_INCREMENT NOT NULL, achat_id INT NOT NULL, produit_id INT DEFAULT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_19FF77C9FE95D117 (achat_id), INDEX IDX_19FF77C9F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, produit_id INT DEFAULT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_253C1F3082EA2E54 (commande_id), INDEX IDX_253C1F30F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE taille_achat ADD CONSTRAINT FK_19FF77C9FE95D117 FOREIGN KEY (achat_id) REFERENCES achat (id)');
        $this->addSql('ALTER TABLE taille_achat ADD CONSTRAINT FK_19FF77C9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE taille_commande ADD CONSTRAINT FK_253C1F3082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE taille_commande ADD CONSTRAINT FK_253C1F30F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456670C757F');
        $this->addSql('ALTER TABLE taille_achat DROP FOREIGN KEY FK_19FF77C9FE95D117');
        $this->addSql('ALTER TABLE taille_achat DROP FOREIGN KEY FK_19FF77C9F347EFB');
        $this->addSql('ALTER TABLE taille_commande DROP FOREIGN KEY FK_253C1F3082EA2E54');
        $this->addSql('ALTER TABLE taille_commande DROP FOREIGN KEY FK_253C1F30F347EFB');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE taille_achat');
        $this->addSql('DROP TABLE taille_commande');
    }
}
