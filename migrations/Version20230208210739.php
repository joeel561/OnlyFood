<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208210739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weekly_plan (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, user_id INT NOT NULL, weekday VARCHAR(255) NOT NULL, meal VARCHAR(255) NOT NULL, week_day_sort INT NOT NULL, meal_sort INT NOT NULL, INDEX IDX_3643E74D59D8A214 (recipe_id), INDEX IDX_3643E74DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weekly_plan ADD CONSTRAINT FK_3643E74D59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE weekly_plan ADD CONSTRAINT FK_3643E74DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE weekly_plan');
    }
}
