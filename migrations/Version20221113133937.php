<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113133937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, chips INTEGER NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, title, isbn, author, img FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (book_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO books (book_id, title, isbn, author, img) SELECT id, title, isbn, author, img FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, value FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (prod_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value INTEGER NOT NULL)');
        $this->addSql('INSERT INTO product (prod_id, name, value) SELECT id, name, value FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT book_id, title, isbn, author, img FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO books (id, title, isbn, author, img) SELECT book_id, title, isbn, author, img FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT prod_id, name, value FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value INTEGER NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, value) SELECT prod_id, name, value FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }
}
