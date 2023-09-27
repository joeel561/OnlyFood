<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811162343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient_quantity (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_quantity_ingredients (ingredient_quantity_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_64DE7E66C6F88465 (ingredient_quantity_id), INDEX IDX_64DE7E663EC4DCE (ingredients_id), PRIMARY KEY(ingredient_quantity_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_quantity_ingredients ADD CONSTRAINT FK_64DE7E66C6F88465 FOREIGN KEY (ingredient_quantity_id) REFERENCES ingredient_quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_quantity_ingredients ADD CONSTRAINT FK_64DE7E663EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe CHANGE portion portion INT DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_quantity_ingredients DROP FOREIGN KEY FK_64DE7E66C6F88465');
        $this->addSql('DROP TABLE ingredient_quantity');
        $this->addSql('DROP TABLE ingredient_quantity_ingredients');
        $this->addSql('ALTER TABLE recipe CHANGE portion portion INT DEFAULT NULL');
    }
}
