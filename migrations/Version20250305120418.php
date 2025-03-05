<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305120418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, login DATETIME NOT NULL, duration_of_connection INT DEFAULT NULL, logout DATETIME DEFAULT NULL, INDEX IDX_FD06F647A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(20) DEFAULT \'unread\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fb (id INT AUTO_INCREMENT NOT NULL, travelbook_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, visit_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D1C2DEAACD3BEE3C (travelbook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, travelbook_id INT DEFAULT NULL, img_url VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_876E0D9CD3BEE3C (travelbook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE places (id INT AUTO_INCREMENT NOT NULL, travelbook_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, visit_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FEAF6C55CD3BEE3C (travelbook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE souvenirs (id INT AUTO_INCREMENT NOT NULL, travelbook_id INT DEFAULT NULL, what VARCHAR(255) NOT NULL, for_who VARCHAR(255) NOT NULL, INDEX IDX_5CEFFBA9CD3BEE3C (travelbook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travelbook (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, img_couverture VARCHAR(255) DEFAULT NULL, departure_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comeback_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', flight_number VARCHAR(28) DEFAULT NULL, accommodation VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BFDE9B7BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(128) NOT NULL, last_name VARCHAR(255) NOT NULL, pseudo VARCHAR(128) NOT NULL, account_status VARCHAR(20) NOT NULL, api_token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_connection_time INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_IDENTIFIER_PSEUDO (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fb ADD CONSTRAINT FK_D1C2DEAACD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('ALTER TABLE places ADD CONSTRAINT FK_FEAF6C55CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('ALTER TABLE souvenirs ADD CONSTRAINT FK_5CEFFBA9CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('ALTER TABLE travelbook ADD CONSTRAINT FK_BFDE9B7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395');
        $this->addSql('ALTER TABLE fb DROP FOREIGN KEY FK_D1C2DEAACD3BEE3C');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9CD3BEE3C');
        $this->addSql('ALTER TABLE places DROP FOREIGN KEY FK_FEAF6C55CD3BEE3C');
        $this->addSql('ALTER TABLE souvenirs DROP FOREIGN KEY FK_5CEFFBA9CD3BEE3C');
        $this->addSql('ALTER TABLE travelbook DROP FOREIGN KEY FK_BFDE9B7BA76ED395');
        $this->addSql('DROP TABLE activity_log');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE fb');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE places');
        $this->addSql('DROP TABLE souvenirs');
        $this->addSql('DROP TABLE travelbook');
        $this->addSql('DROP TABLE user');
    }
}
