<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220204652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A4593EC4DCE');
        $this->addSql('DROP INDEX IDX_3DC1A4593EC4DCE ON shopping_list');
        $this->addSql('ALTER TABLE shopping_list ADD ingredients LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP ingredients_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list ADD ingredients_id INT DEFAULT NULL, DROP ingredients');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A4593EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3DC1A4593EC4DCE ON shopping_list (ingredients_id)');
    }
}
