<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107131949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE estados (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE imagen_producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, producto_id INTEGER DEFAULT NULL, nombre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6C871277645698E ON imagen_producto (producto_id)');
        $this->addSql('CREATE TABLE pedido (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, estado_id INTEGER NOT NULL, fecha DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_C4EC16CEA76ED395 ON pedido (user_id)');
        $this->addSql('CREATE INDEX IDX_C4EC16CE9F5A440B ON pedido (estado_id)');
        $this->addSql('CREATE TABLE pedido_producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cod_ped_id INTEGER NOT NULL, cod_prod_id INTEGER NOT NULL, unidades INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_DD333C2AC0827E5 ON pedido_producto (cod_ped_id)');
        $this->addSql('CREATE INDEX IDX_DD333C29370CFE1 ON pedido_producto (cod_prod_id)');
        $this->addSql('CREATE TABLE producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, precio NUMERIC(10, 2) NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A7BB06153397707A ON producto (categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE estados');
        $this->addSql('DROP TABLE imagen_producto');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('DROP TABLE pedido_producto');
        $this->addSql('DROP TABLE producto');
    }
}
