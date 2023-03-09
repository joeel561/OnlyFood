<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220210825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A459A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3DC1A459A76ED395 ON shopping_list (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64923245BF9');
        $this->addSql('DROP INDEX IDX_8D93D64923245BF9 ON user');
        $this->addSql('ALTER TABLE user DROP shopping_list_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A459A76ED395');
        $this->addSql('DROP INDEX IDX_3DC1A459A76ED395 ON shopping_list');
        $this->addSql('ALTER TABLE shopping_list DROP user_id');
        $this->addSql('ALTER TABLE user ADD shopping_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64923245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D64923245BF9 ON user (shopping_list_id)');
    }
}
