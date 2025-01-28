<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128135514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FD06F647A76ED395 ON activity_log (user_id)');
        $this->addSql('ALTER TABLE fb ADD travelbook_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fb ADD CONSTRAINT FK_D1C2DEAACD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('CREATE INDEX IDX_D1C2DEAACD3BEE3C ON fb (travelbook_id)');
        $this->addSql('ALTER TABLE photos ADD travelbook_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9CD3BEE3C ON photos (travelbook_id)');
        $this->addSql('ALTER TABLE places ADD travelbook_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE places ADD CONSTRAINT FK_FEAF6C55CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('CREATE INDEX IDX_FEAF6C55CD3BEE3C ON places (travelbook_id)');
        $this->addSql('ALTER TABLE souvenirs ADD travelbook_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE souvenirs ADD CONSTRAINT FK_5CEFFBA9CD3BEE3C FOREIGN KEY (travelbook_id) REFERENCES travelbook (id)');
        $this->addSql('CREATE INDEX IDX_5CEFFBA9CD3BEE3C ON souvenirs (travelbook_id)');
        $this->addSql('ALTER TABLE travelbook ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE travelbook ADD CONSTRAINT FK_BFDE9B7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BFDE9B7BA76ED395 ON travelbook (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395');
        $this->addSql('DROP INDEX IDX_FD06F647A76ED395 ON activity_log');
        $this->addSql('ALTER TABLE activity_log DROP user_id');
        $this->addSql('ALTER TABLE places DROP FOREIGN KEY FK_FEAF6C55CD3BEE3C');
        $this->addSql('DROP INDEX IDX_FEAF6C55CD3BEE3C ON places');
        $this->addSql('ALTER TABLE places DROP travelbook_id');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9CD3BEE3C');
        $this->addSql('DROP INDEX IDX_876E0D9CD3BEE3C ON photos');
        $this->addSql('ALTER TABLE photos DROP travelbook_id');
        $this->addSql('ALTER TABLE travelbook DROP FOREIGN KEY FK_BFDE9B7BA76ED395');
        $this->addSql('DROP INDEX IDX_BFDE9B7BA76ED395 ON travelbook');
        $this->addSql('ALTER TABLE travelbook DROP user_id');
        $this->addSql('ALTER TABLE fb DROP FOREIGN KEY FK_D1C2DEAACD3BEE3C');
        $this->addSql('DROP INDEX IDX_D1C2DEAACD3BEE3C ON fb');
        $this->addSql('ALTER TABLE fb DROP travelbook_id');
        $this->addSql('ALTER TABLE souvenirs DROP FOREIGN KEY FK_5CEFFBA9CD3BEE3C');
        $this->addSql('DROP INDEX IDX_5CEFFBA9CD3BEE3C ON souvenirs');
        $this->addSql('ALTER TABLE souvenirs DROP travelbook_id');
    }
}
