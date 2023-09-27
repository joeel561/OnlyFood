<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220203618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_list (id INT AUTO_INCREMENT NOT NULL, ingredients_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_3DC1A4593EC4DCE (ingredients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A4593EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE user ADD shopping_list_id INT DEFAULT NULL, CHANGE enabled enabled TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64923245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64923245BF9 ON user (shopping_list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64923245BF9');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP INDEX IDX_8D93D64923245BF9 ON user');
        $this->addSql('ALTER TABLE user DROP shopping_list_id, CHANGE enabled enabled TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
