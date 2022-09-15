<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811180057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient_quantity_ingredients (ingredient_quantity_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_64DE7E66C6F88465 (ingredient_quantity_id), INDEX IDX_64DE7E663EC4DCE (ingredients_id), PRIMARY KEY(ingredient_quantity_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_quantity_ingredients ADD CONSTRAINT FK_64DE7E66C6F88465 FOREIGN KEY (ingredient_quantity_id) REFERENCES ingredient_quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_quantity_ingredients ADD CONSTRAINT FK_64DE7E663EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ingredient_quantity_recipe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient_quantity_recipe (ingredient_quantity_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_4DE1F8CA59D8A214 (recipe_id), INDEX IDX_4DE1F8CAC6F88465 (ingredient_quantity_id), PRIMARY KEY(ingredient_quantity_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ingredient_quantity_recipe ADD CONSTRAINT FK_4DE1F8CA59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_quantity_recipe ADD CONSTRAINT FK_4DE1F8CAC6F88465 FOREIGN KEY (ingredient_quantity_id) REFERENCES ingredient_quantity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE ingredient_quantity_ingredients');
    }
}
