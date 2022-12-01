<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124172521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weekly_plan (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, weekday VARCHAR(255) NOT NULL, meal VARCHAR(255) NOT NULL, INDEX IDX_3643E74DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weekly_plan_recipe (weekly_plan_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_C735ECD3E0E9AE94 (weekly_plan_id), INDEX IDX_C735ECD359D8A214 (recipe_id), PRIMARY KEY(weekly_plan_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weekly_plan ADD CONSTRAINT FK_3643E74DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE weekly_plan_recipe ADD CONSTRAINT FK_C735ECD3E0E9AE94 FOREIGN KEY (weekly_plan_id) REFERENCES weekly_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weekly_plan_recipe ADD CONSTRAINT FK_C735ECD359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weekly_plan_recipe DROP FOREIGN KEY FK_C735ECD3E0E9AE94');
        $this->addSql('DROP TABLE weekly_plan');
        $this->addSql('DROP TABLE weekly_plan_recipe');
    }
}
