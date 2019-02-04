<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204191639 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sales_order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_code VARCHAR(16) NOT NULL, product_name VARCHAR(255) NOT NULL, product_brand VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, subtotal NUMERIC(13, 2) NOT NULL, INDEX IDX_5DD6A8658D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_order (id INT AUTO_INCREMENT NOT NULL, customer_email VARCHAR(255) NOT NULL, submitted DATETIME NOT NULL, total NUMERIC(13, 2) NOT NULL, currency VARCHAR(3) NOT NULL, status VARCHAR(16) NOT NULL, INDEX customer_email_idx (customer_email), INDEX submitted_idx (submitted), INDEX total_idx (currency, total), INDEX status_idx (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price_discount (id INT AUTO_INCREMENT NOT NULL, price_id INT NOT NULL, rule VARCHAR(16) NOT NULL, value NUMERIC(13, 2) NOT NULL, UNIQUE INDEX UNIQ_D03FF135D614C7E7 (price_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_relation (parent_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_A9392DDE727ACA70 (parent_id), INDEX IDX_A9392DDE4584665A (product_id), PRIMARY KEY(parent_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, currency VARCHAR(3) NOT NULL, base_price NUMERIC(13, 2) NOT NULL, UNIQUE INDEX UNIQ_6B9459854584665A (product_id), INDEX base_price_idx (base_price), UNIQUE INDEX product_currency_price (currency, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(16) NOT NULL, name VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, INDEX name_idx (name), INDEX brand_idx (brand), UNIQUE INDEX product_code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sales_order_item ADD CONSTRAINT FK_5DD6A8658D9F6D38 FOREIGN KEY (order_id) REFERENCES sales_order (id)');
        $this->addSql('ALTER TABLE product_price_discount ADD CONSTRAINT FK_D03FF135D614C7E7 FOREIGN KEY (price_id) REFERENCES product_price (id)');
        $this->addSql('ALTER TABLE product_relation ADD CONSTRAINT FK_A9392DDE727ACA70 FOREIGN KEY (parent_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_relation ADD CONSTRAINT FK_A9392DDE4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_price ADD CONSTRAINT FK_6B9459854584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sales_order_item DROP FOREIGN KEY FK_5DD6A8658D9F6D38');
        $this->addSql('ALTER TABLE product_price_discount DROP FOREIGN KEY FK_D03FF135D614C7E7');
        $this->addSql('ALTER TABLE product_relation DROP FOREIGN KEY FK_A9392DDE727ACA70');
        $this->addSql('ALTER TABLE product_relation DROP FOREIGN KEY FK_A9392DDE4584665A');
        $this->addSql('ALTER TABLE product_price DROP FOREIGN KEY FK_6B9459854584665A');
        $this->addSql('DROP TABLE sales_order_item');
        $this->addSql('DROP TABLE sales_order');
        $this->addSql('DROP TABLE product_price_discount');
        $this->addSql('DROP TABLE product_relation');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('DROP TABLE product');
    }
}
